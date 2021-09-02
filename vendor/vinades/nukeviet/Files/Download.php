<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Files;

use finfo;

if (!defined('NV_MIME_INI_FILE')) {
    define('NV_MIME_INI_FILE', NV_ROOTDIR . '/includes/ini/mime.ini');
}

/**
 * NukeViet\Files\Download
 *
 * @package NukeViet\Files
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Download
{
    private $properties = [
        'path' => '',
        'name' => '',
        'extension' => '',
        'type' => '',
        'size' => '',
        'mtime' => 0,
        'resume' => '',
        'max_speed' => '',
        'directory' => ''
    ];
    private $disable_functions = [];
    private $disable_classes = [];
    private $magic_path;

    /**
     * __construct()
     *
     * @param string $path
     * @param string $name
     * @param bool   $resume
     * @param int    $max_speed
     */
    public function __construct($path, $directory, $name = '', $resume = false, $max_speed = 0, $magic_path = '')
    {
        $directory = $this->real_dir($directory);
        if (empty($directory) or !is_dir($directory)) {
            $directory = NV_UPLOADS_REAL_DIR;
        }
        $disable_functions = (ini_get('disable_functions') != '' and ini_get('disable_functions') != false) ? array_map('trim', preg_split("/[\s,]+/", ini_get('disable_functions'))) : [];
        if (extension_loaded('suhosin')) {
            $disable_functions = array_merge($disable_functions, array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
        }
        $this->disable_functions = $disable_functions;

        $this->disable_classes = (ini_get('disable_classes') != '' and ini_get('disable_classes') != false) ? array_map('trim', preg_split("/[\s,]+/", ini_get('disable_classes'))) : [];

        $path = $this->real_path($path, $directory);
        $extension = $this->getextension($path);
        $this->properties = [
            'path' => $path,
            'name' => ($name == '') ? substr(strrchr('/' . $path, '/'), 1) : $name,
            'extension' => $extension,
            'type' => '',
            'size' => (int) (sprintf('%u', filesize($path))),
            'mtime' => ($mtime = filemtime($path)) > 0 ? $mtime : time(),
            'resume' => $resume,
            'max_speed' => $max_speed,
            'directory' => $directory
        ];
        $this->properties['type'] = $this->my_mime_content_type($path);
        $this->magic_path = $magic_path;
    }

    /**
     * real_dir()
     *
     * @param string $dir
     * @return false|string
     */
    public function real_dir($dir)
    {
        if (empty($dir)) {
            return false;
        }
        $dir = realpath($dir);
        if (empty($dir)) {
            return false;
        }
        $dir = str_replace('\\', '/', $dir);
        $dir = rtrim($dir, '\\/');
        if (!preg_match('/^(' . nv_preg_quote(NV_ROOTDIR) . ")(\/[\S]+)/", $dir)) {
            return false;
        }

        return $dir;
    }

    /**
     * real_path()
     *
     * @param string $path
     * @param string $dir
     * @return false|string
     */
    public function real_path($path, $dir)
    {
        if (empty($path) or !is_readable($path) or !is_file($path)) {
            return false;
        }

        $realpath = realpath($path);

        if (empty($realpath)) {
            return false;
        }

        $realpath = str_replace('\\', '/', $realpath);
        $realpath = rtrim($realpath, '\\/');

        if (!preg_match('/^(' . nv_preg_quote($dir) . ")(\/[\S]+)/", $realpath)) {
            return false;
        }

        return $realpath;
    }

    /**
     * func_exists()
     *
     * @param string $funcName
     * @return bool
     */
    private function func_exists($funcName)
    {
        return function_exists($funcName) and !in_array($funcName, $this->disable_functions, true);
    }

    /**
     * cl_exists()
     *
     * @param string $clName
     * @return bool
     */
    private function cl_exists($clName)
    {
        return class_exists($clName, false) and !in_array($clName, $this->disable_classes, true);
    }

    /**
     * getextension()
     *
     * @param string $filename
     * @return string
     */
    private function getextension($filename)
    {
        if (strpos($filename, '.') === false) {
            return '';
        }
        $filename = basename(strtolower($filename));
        $filename = explode('.', $filename);

        return array_pop($filename);
    }

    /**
     * my_mime_content_type()
     *
     * @param string $path
     * @return string
     */
    private function my_mime_content_type($path)
    {
        $mime = '';

        if ($this->func_exists('finfo_open')) {
            if (empty($this->magic_path)) {
                $finfo = finfo_open(FILEINFO_MIME);
            } elseif ($this->magic_path != 'auto') {
                $finfo = finfo_open(FILEINFO_MIME, $this->magic_path);
            } else {
                if (($magic = getenv('MAGIC')) !== false) {
                    $finfo = finfo_open(FILEINFO_MIME, $magic);
                } else {
                    if (substr(PHP_OS, 0, 3) == 'WIN') {
                        $path = realpath(ini_get('extension_dir') . '/../') . 'extras/magic';
                        $finfo = finfo_open(FILEINFO_MIME, $path);
                    } else {
                        $finfo = finfo_open(FILEINFO_MIME, '/usr/share/file/magic');
                    }
                }
            }

            if (is_resource($finfo)) {
                $mime = finfo_file($finfo, realpath($path));
                finfo_close($finfo);
                $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
            }
        }

        if (empty($mime) or $mime == 'application/octet-stream') {
            if ($this->cl_exists('finfo')) {
                $finfo = new finfo(FILEINFO_MIME);
                if ($finfo) {
                    $mime = $finfo->file(realpath($path));
                    $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
                }
            }
        }

        if (empty($mime) or $mime == 'application/octet-stream') {
            if (substr(PHP_OS, 0, 3) != 'WIN') {
                if ($this->func_exists('system')) {
                    ob_start();
                    system('file -i -b ' . escapeshellarg($path));
                    $m = ob_get_clean();
                    $m = trim($m);
                    if (!empty($m)) {
                        $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $m);
                    }
                } elseif ($this->func_exists('exec')) {
                    $m = @exec('file -bi ' . escapeshellarg($path));
                    $m = trim($m);
                    if (!empty($m)) {
                        $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $m);
                    }
                }
            }
        }

        if (empty($mime) or $mime == 'application/octet-stream') {
            if ($this->func_exists('mime_content_type')) {
                $mime = mime_content_type($path);
                $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
            }
        }

        if (empty($mime) or $mime == 'application/octet-stream') {
            $img_exts = ['png', 'gif', 'jpg', 'bmp', 'tiff', 'swf', 'psd'];
            if (in_array($this->properties['extension'], $img_exts, true)) {
                if (($img_info = @getimagesize($path)) !== false) {
                    if (array_key_exists('mime', $img_info) and !empty($img_info['mime'])) {
                        $mime = trim($img_info['mime']);
                        $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $mime);
                    }

                    if (empty($mime) and isset($img_info[2])) {
                        $mime = image_type_to_mime_type($img_info[2]);
                    }
                }
            }
        }

        if (empty($mime) or $mime == 'application/octet-stream') {
            $mime_types = nv_parse_ini_file(NV_MIME_INI_FILE);

            if (array_key_exists($this->properties['extension'], $mime_types)) {
                if (is_string($mime_types[$this->properties['extension']])) {
                    return $mime_types[$this->properties['extension']];
                }
                $mime = $mime_types[$this->properties['extension']][0];
            }
        }

        if (preg_match('/^application\/(?:x-)?zip(?:-compressed)?$/is', $mime)) {
            if ($this->properties['extension'] == 'docx') {
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            } elseif ($this->properties['extension'] == 'dotx') {
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
            } elseif ($this->properties['extension'] == 'potx') {
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.template';
            } elseif ($this->properties['extension'] == 'ppsx') {
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
            } elseif ($this->properties['extension'] == 'pptx') {
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            } elseif ($this->properties['extension'] == 'xlsx') {
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            } elseif ($this->properties['extension'] == 'xltx') {
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
            } elseif ($this->properties['extension'] == 'docm') {
                $mime = 'application/vnd.ms-word.document.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'dotm') {
                $mime = 'application/vnd.ms-word.template.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'potm') {
                $mime = 'application/vnd.ms-powerpoint.template.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'ppam') {
                $mime = 'application/vnd.ms-powerpoint.addin.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'ppsm') {
                $mime = 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'pptm') {
                $mime = 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'xlam') {
                $mime = 'application/vnd.ms-excel.addin.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'xlsb') {
                $mime = 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'xlsm') {
                $mime = 'application/vnd.ms-excel.sheet.macroEnabled.12';
            } elseif ($this->properties['extension'] == 'xltm') {
                $mime = 'application/vnd.ms-excel.template.macroEnabled.12';
            }
        }

        return !empty($mime) ? $mime : 'application/force-download';
    }

    /**
     * nv_getenv()
     *
     * @param string $key
     * @return string
     */
    private function nv_getenv($key)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        if (@getenv($key)) {
            return @getenv($key);
        }
        if (function_exists('apache_getenv') and apache_getenv($key, true)) {
            return apache_getenv($key, true);
        }

        return '';
    }

    /**
     * get_property()
     *
     * @param string $property
     * @return string|null
     */
    public function get_property($property)
    {
        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        return null;
    }

    /**
     * set_property()
     *
     * @param string $property
     * @param string $value
     * @return bool
     */
    public function set_property($property, $value)
    {
        if (array_key_exists($property, $this->properties)) {
            $this->properties[$property] = $value;

            return true;
        }

        return false;
    }

    /**
     * download_file()
     *
     * @return never
     */
    public function download_file($attachment = 1)
    {
        if (!$this->properties['path']) {
            exit('Nothing to download!');
        }

        $seek_start = 0;
        $seek_end = -1;
        $data_section = false;

        if (($http_range = nv_getenv('HTTP_RANGE')) != '') {
            $seek_range = substr($http_range, 6);

            $range = explode('-', $seek_range);

            if (!empty($range[0])) {
                $seek_start = (int) ($range[0]);
            }

            if (isset($range[1]) and !empty($range[1])) {
                $seek_end = (int) ($range[1]);
            }

            if (!$this->properties['resume']) {
                $seek_start = 0;
            } else {
                $data_section = true;
            }
        }

        if (@ob_get_length()) {
            @ob_end_clean();
        }
        $old_status = ignore_user_abort(true);

        if (function_exists('set_time_limit') and !in_array('set_time_limit', $this->disable_functions, true)) {
            set_time_limit(0);
        }

        if ($seek_start > ($this->properties['size'] - 1)) {
            $seek_start = 0;
        }

        $res = fopen($this->properties['path'], 'rb');

        if (!$res) {
            exit('File error');
        }

        if ($seek_start) {
            fseek($res, $seek_start);
        }
        if ($seek_end < $seek_start) {
            $seek_end = $this->properties['size'] - 1;
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control:');
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $this->properties['type']);
        if ($attachment) {
            if (strstr($this->nv_getenv('HTTP_USER_AGENT'), 'MSIE') != false) {
                header('Content-Disposition: attachment; filename="' . urlencode($this->properties['name']) . '";');
            } else {
                header('Content-Disposition: attachment; filename="' . $this->properties['name'] . '";');
            }
        }
        header('Last-Modified: ' . date('D, d M Y H:i:s \G\M\T', $this->properties['mtime']));

        if ($data_section and $this->properties['resume']) {
            http_response_code(206);
            header('Accept-Ranges: bytes');
            header('Content-Range: bytes ' . $seek_start . '-' . $seek_end . '/' . $this->properties['size']);
            header('Content-Length: ' . ($seek_end - $seek_start + 1));
        } else {
            header('Content-Length: ' . $this->properties['size']);
        }

        if (function_exists('usleep') and !in_array('usleep', $this->disable_functions, true) and ($speed = $this->properties['max_speed']) > 0) {
            $sleep_time = (8 / $speed) * 1e6;
        } else {
            $sleep_time = 0;
        }

        while (!(connection_aborted() or connection_status() == 1) and !feof($res)) {
            echo fread($res, 1024 * 8);
            flush();
            if ($sleep_time > 0) {
                usleep($sleep_time);
            }
        }
        fclose($res);

        ignore_user_abort($old_status);
        if (function_exists('set_time_limit') and !in_array('set_time_limit', $this->disable_functions, true)) {
            set_time_limit(ini_get('max_execution_time'));
        }
        exit(0);
    }
}
