<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 9:10
 */

namespace NukeViet\Http;

class Encoding
{

    /**
     * Encoding::compress()
     *
     * @param mixed $raw
     * @param integer $level
     * @param mixed $supports
     * @return
     */
    public static function compress($raw, $level = 9)
    {
        return gzdeflate($raw, $level);
    }

    /**
     * Encoding::decompress()
     *
     * @param mixed $compressed
     * @param mixed $length
     * @return
     */
    public static function decompress($compressed)
    {
        if (empty($compressed)) {
            return $compressed;
        }

        if (($decompressed = Encoding::compatible_gzinflate($compressed)) !== false) {
            return $decompressed;
        }

        if (($decompressed = @gzuncompress($compressed)) !== false) {
            return $decompressed;
        }

        if (function_exists('gzdecode')) {
            $decompressed = @gzdecode($compressed);

            if ($decompressed !== false) {
                return $decompressed;
            }
        }

        if (($decompressed = @gzinflate($compressed)) !== false) {
            return $decompressed;
        }

        return $compressed;
    }

    /**
     * Encoding::compatible_gzinflate()
     *
     * @param mixed $gzData
     * @return
     */
    public static function compatible_gzinflate($gzData)
    {
        // Compressed data might contain a full header, if so strip it for gzinflate()
        if (substr($gzData, 0, 3) == "\x1f\x8b\x08") {
            $i = 10;
            $flg = ord(substr($gzData, 3, 1));
            if ($flg > 0) {
                if ($flg & 4) {
                    list ($xlen) = unpack('v', substr($gzData, $i, 2));
                    $i = $i + 2 + $xlen;
                }

                if ($flg & 8) {
                    $i = strpos($gzData, "\0", $i) + 1;
                }

                if ($flg & 16) {
                    $i = strpos($gzData, "\0", $i) + 1;
                }

                if ($flg & 2) {
                    $i = $i + 2;
                }
            }

            $decompressed = @gzinflate(substr($gzData, $i, -8));

            if ($decompressed !== false) {
                return $decompressed;
            }
        }

        // Compressed data from java.util.zip.Deflater amongst others.
        $decompressed = @gzinflate(substr($gzData, 2));

        if ($decompressed !== false) {
            return $decompressed;
        }

        return false;
    }

    /**
     * Encoding::accept_encoding()
     *
     * @param mixed $url
     * @param mixed $args
     * @return
     */
    public static function accept_encoding($url, $args)
    {
        $type = array();
        $compression_enabled = Encoding::is_available();

        if (!$args['decompress']) {
            // decompression specifically disabled
            $compression_enabled = false;
        } elseif ($args['stream']) {
            // disable when streaming to file
            $compression_enabled = false;
        } elseif (isset($args['limit_response_size'])) {
            // If only partial content is being requested, we won't be able to decompress it
            $compression_enabled = false;
        }

        if ($compression_enabled) {
            if (function_exists('gzinflate')) {
                $type[] = 'deflate;q=1.0';
            }

            if (function_exists('gzuncompress')) {
                $type[] = 'compress;q=0.5';
            }

            if (function_exists('gzdecode')) {
                $type[] = 'gzip;q=0.5';
            }
        }

        return implode(', ', $type);
    }

    /**
     * Encoding::content_encoding()
     *
     * @return
     */
    public static function content_encoding()
    {
        return 'deflate';
    }

    /**
     * Encoding::should_decode()
     *
     * @param mixed $headers
     * @return
     */
    public static function should_decode($headers)
    {
        if (is_array($headers)) {
            if (array_key_exists('content-encoding', $headers) and !empty($headers['content-encoding'])) {
                return true;
            }
        } elseif (is_string($headers)) {
            return (stripos($headers, 'content-encoding:') !== false);
        }

        return false;
    }

    /**
     * Encoding::is_available()
     *
     * @return
     */
    public static function is_available()
    {
        return (function_exists('gzuncompress') or function_exists('gzdeflate') or function_exists('gzinflate'));
    }
}