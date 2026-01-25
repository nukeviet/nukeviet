<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

class NotificationTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Kiểm tra lưu thông báo vào CSDL
     *
     * @group install
     * @group all
     */
    public function testInsertNotification()
    {
        $check = nv_insert_notification('settings', 'server_config_file_changed', ['file' => 'index.php'], 0, 0, 0, 1, 1);
        $this->assertGreaterThan(0, $check);

        $check = nv_insert_notification('users', 'remove_2step_request', [
            'title' => 'webmaster',
            'uid' => 1
        ], 1, 0, 0, 1, 1, 1);
        $this->assertGreaterThan(0, $check);

        // Test thông báo module news
        $notis_news = [
            [[
                'title' => 'Ra mắt công ty mã nguồn mở đầu tiên tại Việt Nam',
                'hometext' => 'Mã nguồn mở NukeViet vốn đã quá quen thuộc với cộng đồng CNTT Việt Nam trong mấy năm qua'
            ], 1],
            [[
                'title' => 'Mã nguồn mở NukeViet giành giải ba Nhân tài đất Việt 2011',
                'hometext' => 'Không có giải nhất và giải nhì, sản phẩm Mã nguồn mở NukeViet của VINADES.,JSC là một trong ba sản phẩm đã đoạt giải ba Nhân tài đất Việt 2011 - Lĩnh vực Công nghệ thông tin (Sản phẩm đã ứng dụng rộng rãi).'
            ], 6],
            [[
                'title' => 'NukeViet được ưu tiên mua sắm, sử dụng trong cơ quan, tổ chức nhà nước',
                'hometext' => 'Ngày 5/12/2014, Bộ trưởng Bộ TT&TT Nguyễn Bắc Son đã ký ban hành Thông tư 20/2014/TT-BTTTT (Thông tư 20) quy định về các sản phẩm phần mềm nguồn mở (PMNM) được ưu tiên mua sắm, sử dụng trong cơ quan, tổ chức nhà nước. NukeViet (phiên bản 3.4.02 trở lên) là phần mềm được nằm trong danh sách này.'
            ], 7],
            [[
                'title' => 'Công ty VINADES tuyển dụng nhân viên kinh doanh',
                'hometext' => 'Công ty cổ phần phát triển nguồn mở Việt Nam là đơn vị chủ quản của phần mềm mã nguồn mở NukeViet - một mã nguồn được tin dùng trong cơ quan nhà nước, đặc biệt là ngành giáo dục. Chúng tôi cần tuyển 05 nhân viên kinh doanh cho lĩnh vực này.'
            ], 8],
        ];
        foreach ($notis_news as $noti) {
            $check = nv_insert_notification('news', 'post_queue', $noti[0], $noti[1], 0, 1);
            $this->assertGreaterThan(0, $check);
        }
    }
}
