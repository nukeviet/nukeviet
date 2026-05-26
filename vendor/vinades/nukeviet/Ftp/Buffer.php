<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Ftp;

/**
 * NukeViet\Ftp\Buffer
 *
 * @package NukeViet\Ftp
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Buffer extends \stdClass
{
    public $position = 0;

    public $varname;

    /**
     * @var array<string, string> Lưu trữ các buffer thay vì sử dụng $GLOBALS
     */
    protected static $buffers = [];

    /**
     * stream_open()
     *
     * @param string $path
     * @param mixed  $mode
     * @param mixed  $options
     * @param mixed  $opened_path
     * @return bool
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        // Ngăn TypeError crash khi URL không hợp lệ hoặc thiếu host
        if (!is_array($url) || empty($url['host'])) {
            return false;
        }

        $this->varname = $url['host'];
        $this->position = 0;

        // Đảm bảo buffer được khởi tạo trước khi đọc/ghi
        if (!isset(self::$buffers[$this->varname])) {
            self::$buffers[$this->varname] = '';
        }

        return true;
    }

    /**
     * stream_read()
     *
     * @param int $count
     * @return string
     */
    public function stream_read($count)
    {
        $buffer = isset(self::$buffers[$this->varname]) ? self::$buffers[$this->varname] : '';
        $ret = substr($buffer, $this->position, $count);
        $this->position += strlen($ret);

        return $ret;
    }

    /**
     * stream_write()
     *
     * @param mixed $data
     * @return int
     */
    public function stream_write($data)
    {
        if (!isset(self::$buffers[$this->varname])) {
            self::$buffers[$this->varname] = '';
        }

        $left  = substr(self::$buffers[$this->varname], 0, $this->position);
        $right = substr(self::$buffers[$this->varname], $this->position + strlen($data));
        self::$buffers[$this->varname] = $left . $data . $right;
        $this->position += strlen($data);

        return strlen($data);
    }

    /**
     * stream_tell()
     *
     * @return int
     */
    public function stream_tell()
    {
        return $this->position;
    }

    /**
     * stream_eof()
     *
     * @return bool
     */
    public function stream_eof()
    {
        $buffer = isset(self::$buffers[$this->varname]) ? self::$buffers[$this->varname] : '';

        return $this->position >= strlen($buffer);
    }

    /**
     * stream_seek()
     *
     * @param int    $offset
     * @param string $whence
     * @return bool
     */
    public function stream_seek($offset, $whence)
    {
        $buffer = isset(self::$buffers[$this->varname]) ? self::$buffers[$this->varname] : '';
        $len = strlen($buffer);

        switch ($whence) {
            case SEEK_SET:
                if ($offset <= $len && $offset >= 0) {
                    $this->position = $offset;

                    return true;
                }

                return false;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;

                    return true;
                }

                return false;

            case SEEK_END:
                if ($len + $offset >= 0) {
                    $this->position = $len + $offset;

                    return true;
                }

                return false;

            default:
                return false;
        }
    }

    /**
     * stream_metadata()
     *
     * @param string $path
     * @param string $option
     * @param mixed  $var
     * @return bool
     */
    public function stream_metadata($path, $option, $var)
    {
        if ($option == STREAM_META_TOUCH) {
            $url = parse_url($path);
            // Ngăn TypeError crash khi URL không hợp lệ hoặc thiếu host
            if (!is_array($url) || empty($url['host'])) {
                return false;
            }

            $varname = $url['host'];

            if (!isset(self::$buffers[$varname])) {
                self::$buffers[$varname] = '';
            }

            return true;
        }

        return false;
    }

    /**
     * releaseBuffer()
     * Giải phóng bộ nhớ buffer sau khi sử dụng xong.
     * Được gọi bởi Ftp::read() để tránh rò rỉ bộ nhớ.
     *
     * @param string $key
     * @return void
     */
    public static function releaseBuffer($key)
    {
        unset(self::$buffers[$key]);
    }
}
