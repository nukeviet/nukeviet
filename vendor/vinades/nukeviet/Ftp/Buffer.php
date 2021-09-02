<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
class Buffer
{
    public $position = 0;

    public $varname;

    /**
     * stream_open()
     *
     * @param string $path
     * @param mixed  $mode
     * @param mixed  $options
     * @param mixed  $opened_path
     * @return true
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url['host'];
        $this->position = 0;

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
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
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
        if (!isset($GLOBALS[$this->varname])) {
            $GLOBALS[$this->varname] = '';
        }

        $left = substr($GLOBALS[$this->varname], 0, $this->position);
        $right = substr($GLOBALS[$this->varname], $this->position + strlen($data));
        $GLOBALS[$this->varname] = $left . $data . $right;
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
        return $this->position >= strlen($GLOBALS[$this->varname]);
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
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($GLOBALS[$this->varname]) and $offset >= 0) {
                    $this->position = $offset;

                    return true;
                }

                    return false;
                break;
            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;

                    return true;
                }

                    return false;
                break;
            case SEEK_END:
                if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
                    $this->position = strlen($GLOBALS[$this->varname]) + $offset;

                    return true;
                }

                    return false;
                break;
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
            $varname = $url['host'];

            if (!isset($GLOBALS[$varname])) {
                $GLOBALS[$varname] = '';
            }

            return true;
        }

        return false;
    }
}
