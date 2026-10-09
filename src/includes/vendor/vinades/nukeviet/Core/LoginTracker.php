<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

declare(strict_types=1);

namespace NukeViet\Core;

use PDO;

/**
 * NukeViet\Core\LoginTracker
 *
 * Đếm số lần đăng nhập sai theo tài khoản, lưu trong CSDL và không phụ thuộc IP.
 * Khác với Blocker (đếm trong file log riêng của từng IP), đổi IP không làm bộ đếm về 0.
 *
 * Khi chạm ngưỡng, class chỉ báo "cần captcha" chứ không khóa tài khoản, tránh việc
 * kẻ tấn công cố tình nhập sai để khóa chủ tài khoản ra ngoài.
 *
 * Trạng thái của một khóa:
 * - Đang đếm: count < limit, các lần sai cách starttime quá $window thì đếm lại từ đầu
 * - Cần captcha: count >= limit và lần sai gần nhất chưa quá $banTime. Mỗi lần sai
 *   tiếp theo kéo dài thời gian cần captcha, không đếm lại khi hết $window
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class LoginTracker
{
    /**
     * Trần của count, bằng giới hạn kiểu smallint unsigned. Bị dò liên tục không làm tràn cột
     */
    public const MAX_COUNT = 65535;

    private PDO $db;
    private string $table;
    private int $limit;
    private int $window;
    private int $banTime;

    /**
     * @param PDO    $db
     * @param string $table   Bảng lưu bộ đếm, thường là NV_USERS_GLOBALTABLE . '_login_attempts'
     * @param int    $limit   Số lần sai để bắt đầu cần captcha (login_number_tracking), 0 là tắt
     * @param int    $window  Cửa sổ đếm tính bằng phút (login_time_tracking)
     * @param int    $banTime Thời gian còn cần captcha tính bằng phút (login_time_ban), 0 thì lấy bằng $window
     */
    public function __construct(PDO $db, string $table, int $limit, int $window, int $banTime)
    {
        $this->db = $db;
        $this->table = $table;
        $this->limit = max(0, $limit);
        $this->window = max(1, $window) * 60;
        $this->banTime = ($banTime > 0 ? $banTime * 60 : $this->window);
    }

    /**
     * Xác định khóa đếm
     *
     * Tài khoản tồn tại thì đếm theo userid để username và email dùng chung một bộ đếm.
     * Tên không tồn tại vẫn được đếm, để phản hồi giống hệt tài khoản thật và không lộ
     * tài khoản nào tồn tại.
     *
     * @param int|null $userid    ID tài khoản, null hoặc 0 nếu không tìm thấy
     * @param string   $loginname Tên đăng nhập người dùng gửi lên
     * @return string
     */
    public static function getKey(?int $userid, string $loginname): string
    {
        if (!empty($userid)) {
            return 'u' . $userid;
        }

        return 'n' . md5(mb_strtolower(trim($loginname), 'UTF-8'));
    }

    /**
     * Khóa đếm riêng cho một bước xác thực khác của tài khoản, không trùng được với khóa của getKey()
     *
     * @param string $scope  tfa: mã xác thực 2 bước, pwd: xác nhận lại mật khẩu khi đã đăng nhập
     * @param int    $userid
     * @return string
     */
    public static function getScopeKey(string $scope, int $userid): string
    {
        return $scope . ':' . $userid;
    }

    /**
     * Bộ đếm có đang bật không
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->limit > 0;
    }

    /**
     * Thời gian còn cần captcha tính bằng giây, đã xử lý trường hợp login_time_ban = 0
     *
     * @return int
     */
    public function getBanTime(): int
    {
        return $this->banTime;
    }

    /**
     * Ghi nhận một lần đăng nhập sai
     *
     * Không đọc rồi ghi mà dùng hai câu lệnh tự nguyên tử, các request song song từ
     * nhiều IP không làm mất lượt đếm:
     * 1. Đặt lại bản ghi đã hết hiệu lực về 0
     * 2. Thêm mới hoặc tăng count
     *
     * @param string $key
     * @param int    $time Thời điểm sai, mặc định NV_CURRENTTIME
     */
    public function fail(string $key, int $time = 0): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        $time = $this->time($time);
        $windowStart = $time - $this->window;
        $banStart = $time - $this->banTime;

        $sth = $this->db->prepare('UPDATE ' . $this->table . ' SET
            count = 0, starttime = :time
        WHERE keyname = :keyname AND (
            (count < :limit1 AND starttime < :window_start) OR
            (count >= :limit2 AND lasttime <= :ban_start)
        )');
        $sth->bindValue(':time', $time, PDO::PARAM_INT);
        $sth->bindValue(':keyname', $key, PDO::PARAM_STR);
        $sth->bindValue(':limit1', $this->limit, PDO::PARAM_INT);
        $sth->bindValue(':window_start', $windowStart, PDO::PARAM_INT);
        $sth->bindValue(':limit2', $this->limit, PDO::PARAM_INT);
        $sth->bindValue(':ban_start', $banStart, PDO::PARAM_INT);
        $sth->execute();

        $sth = $this->db->prepare('INSERT INTO ' . $this->table . ' (
            keyname, count, starttime, lasttime
        ) VALUES (
            :keyname, 1, :starttime, :lasttime
        ) ON DUPLICATE KEY UPDATE
            count = LEAST(count + 1, ' . self::MAX_COUNT . '),
            lasttime = :lasttime_update
        ');
        $sth->bindValue(':keyname', $key, PDO::PARAM_STR);
        $sth->bindValue(':starttime', $time, PDO::PARAM_INT);
        $sth->bindValue(':lasttime', $time, PDO::PARAM_INT);
        $sth->bindValue(':lasttime_update', $time, PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * Đưa khóa vào trạng thái cần captcha ngay, dùng khi bước xác thực sau (mã 2 bước) bị dò
     * thì buộc bước mật khẩu phải có captcha. Không hạ count nếu đang cao hơn ngưỡng
     *
     * @param string $key
     * @param int    $time Mặc định NV_CURRENTTIME
     */
    public function forceRequired(string $key, int $time = 0): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        $time = $this->time($time);

        $sth = $this->db->prepare('INSERT INTO ' . $this->table . ' (
            keyname, count, starttime, lasttime
        ) VALUES (
            :keyname, :count, :starttime, :lasttime
        ) ON DUPLICATE KEY UPDATE
            count = GREATEST(count, :count_update),
            lasttime = :lasttime_update
        ');
        $sth->bindValue(':keyname', $key, PDO::PARAM_STR);
        $sth->bindValue(':count', $this->limit, PDO::PARAM_INT);
        $sth->bindValue(':starttime', $time, PDO::PARAM_INT);
        $sth->bindValue(':lasttime', $time, PDO::PARAM_INT);
        $sth->bindValue(':count_update', $this->limit, PDO::PARAM_INT);
        $sth->bindValue(':lasttime_update', $time, PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * Khóa này có đang cần captcha không
     *
     * @param string $key
     * @param int    $time Thời điểm kiểm tra, mặc định NV_CURRENTTIME
     * @return bool
     */
    public function isRequired(string $key, int $time = 0): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $time = $this->time($time);

        $sth = $this->db->prepare('SELECT COUNT(*) FROM ' . $this->table . '
            WHERE keyname = :keyname AND count >= :limit AND lasttime > :ban_start');
        $sth->bindValue(':keyname', $key, PDO::PARAM_STR);
        $sth->bindValue(':limit', $this->limit, PDO::PARAM_INT);
        $sth->bindValue(':ban_start', $time - $this->banTime, PDO::PARAM_INT);
        $sth->execute();

        return (bool) $sth->fetchColumn();
    }

    /**
     * Xóa bộ đếm của khóa, gọi khi đăng nhập thành công
     *
     * @param string $key
     */
    public function reset(string $key): void
    {
        $sth = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE keyname = :keyname');
        $sth->bindValue(':keyname', $key, PDO::PARAM_STR);
        $sth->execute();
    }

    /**
     * Xóa các bản ghi đã hết hiệu lực ở cả hai trạng thái
     *
     * @param int $time Thời điểm tính, mặc định NV_CURRENTTIME
     * @return int Số bản ghi đã xóa
     */
    public function cleanup(int $time = 0): int
    {
        $time = $this->time($time);

        $sth = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE lasttime <= :expired');
        $sth->bindValue(':expired', $time - max($this->window, $this->banTime), PDO::PARAM_INT);
        $sth->execute();

        return $sth->rowCount();
    }

    /**
     * @param int $time
     * @return int
     */
    private function time(int $time): int
    {
        return $time > 0 ? $time : NV_CURRENTTIME;
    }
}
