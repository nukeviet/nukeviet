<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 19:53
 */

namespace NukeViet\Ftp;

class Buffer
{
    public $position = 0;

    public $varname = null;

    /**
     * @param mixed $path
     * @param mixed $mode
     * @param mixed $options
     * @param mixed $opened_path
     * @return
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url["host"];
        $this->position = 0;

        return true;
    }

    /**
     * @param mixed $count
     * @return
     */
    public function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);

        return $ret;
    }

    /**
     *
     * @param mixed $data
     * @return
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
     *
     * @return
     */
    public function stream_tell()
    {
        return $this->position;
    }

    /**
     *
     * @return
     */
    public function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    /**
     *
     * @param mixed $offset
     * @param mixed $whence
     * @return
     */
    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($GLOBALS[$this->varname]) and $offset >= 0) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }

                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }

                break;

            case SEEK_END:
                if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
                    $this->position = strlen($GLOBALS[$this->varname]) + $offset;
                    return true;
                } else {
                    return false;
                }

                break;

            default:
                return false;
        }
    }

    /**
     *
     * @param mixed $path
     * @param mixed $option
     * @param mixed $var
     * @return
     */
    public function stream_metadata($path, $option, $var)
    {
        if ($option == STREAM_META_TOUCH) {
            $url = parse_url($path);
            $varname = $url["host"];

            if (!isset($GLOBALS[$varname])) {
                $GLOBALS[$varname] = '';
            }

            return true;
        }
        return false;
    }
}