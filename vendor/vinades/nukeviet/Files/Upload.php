<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 18/1/2011, 1:11
 */

namespace NukeViet\Files;

use COM;
use finfo;
use Exception;

if (!defined('NV_MIME_INI_FILE')) {
    define('NV_MIME_INI_FILE', NV_ROOTDIR . '/includes/ini/mime.ini');
}

class Upload
{
    private $config = array(
        'allowed_files' => array(),
        'upload_checking_mode' => 'strong',
        'maxsize' => 0,
        'maxwidth' => 0,
        'maxheight' => 0,
        'magic_path' => ''
    );
    private $lang = array(
        'error_uploadNameEmpty' => 'Upload failed: UserFile Name is empty',
        'error_uploadSizeEmpty' => 'Upload failed: UserFile Size is empty',
        'error_upload_ini_size' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        'error_upload_form_size' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        'error_upload_partial' => 'The uploaded file was only partially uploaded',
        'error_upload_no_file' => 'No file was uploaded',
        'error_upload_no_tmp_dir' => 'Missing a temporary folder',
        'error_upload_cant_write' => 'Failed to write file to disk',
        'error_upload_extension' => 'File upload stopped by extension',
        'error_upload_unknown' => 'Unknown upload error',
        'error_upload_type_not_allowed' => 'Files of this type are not allowed',
        'error_upload_mime_not_recognize' => 'System does not recognize the mime type of uploaded file',
        'error_upload_max_user_size' => 'The file exceeds the maximum size allowed. Maximum size is %s',
        'error_upload_not_image' => 'The file is not a known image format',
        'error_upload_image_failed' => 'Image Content is failed',
        'error_upload_image_width' => 'The image is not allowed because the width is greater than the maximum of %d pixels',
        'error_upload_image_height' => 'The image is not allowed because the height is greater than the maximum of %d pixels',
        'error_upload_forbidden' => 'Upload forbidden',
        'error_upload_writable' => 'Directory %s is not writable',
        'error_upload_urlfile' => 'The URL is not valid and cannot be loaded',
        'error_upload_url_notfound' => 'The url was not found'
    );

    private $file_extension = '';
    private $urlfile_extension = '';
    private $file_mime = '';
    private $file_size = 0;
    private $urlfile_mime = '';
    private $temp_file = '';
    private $url_info = array();
    private $is_img = false;
    private $img_info = array();
    private $disable_functions = array();
    private $disable_classes = array();
    private $user_agent;

    private $chunk_filename = '';
    private $chunk_current = 0;
    private $chunk_total = 0;
    private $chunk_tmpdir = '';
    private $chunk_prefix = '';
    private $chunk_resource = null;

    /**
     * upload::__construct()
     *
     * @param mixed $allowed_filetypes
     * @param mixed $forbid_extensions
     * @param mixed $forbid_mimes
     * @param integer $maxsize
     * @param integer $maxwidth
     * @param integer $maxheight
     * @param string $magic_path
     * @return
     */
    public function __construct($allowed_filetypes = array('any'), $forbid_extensions = array('php'), $forbid_mimes = array(), $maxsize = 0, $maxwidth = 0, $maxheight = 0, $magic_path = '')
    {
        if (!is_array($allowed_filetypes)) {
            $allowed_filetypes = array(
                $allowed_filetypes
            );
        }
        if (!empty($allowed_filetypes) and in_array('any', $allowed_filetypes)) {
            $allowed_filetypes = array(
                'any'
            );
        }
        if (!is_array($forbid_extensions)) {
            $forbid_extensions = array(
                $forbid_extensions
            );
        }
        if (!is_array($forbid_mimes)) {
            $forbid_mimes = array(
                $forbid_mimes
            );
        }

        $this->config['allowed_files'] = $this->get_ini($allowed_filetypes, $forbid_extensions, $forbid_mimes);
        $this->config['maxsize'] = floatval($maxsize);
        $this->config['maxwidth'] = intval($maxwidth);
        $this->config['maxheight'] = intval($maxheight);
        $this->config['upload_checking_mode'] = defined('UPLOAD_CHECKING_MODE') ? UPLOAD_CHECKING_MODE : 'strong';
        $this->config['magic_path'] = $magic_path;

        $disable_functions = (ini_get('disable_functions') != '' and ini_get('disable_functions') != false) ? array_map('trim', preg_split('/[\s,]+/', ini_get('disable_functions'))) : array();
        if (extension_loaded('suhosin')) {
            $disable_functions = array_merge($disable_functions, array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
        }
        $this->disable_functions = $disable_functions;

        $this->disable_classes = (ini_get('disable_classes') != '' and ini_get('disable_classes') != false) ? array_map('trim', preg_split("/[\s,]+/", ini_get('disable_classes'))) : array();

        $userAgents = array(
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
            'Mozilla/4.8 [en] (Windows NT 6.0; U)',
            'Opera/9.25 (Windows NT 6.0; U; en)'
        );
        srand((float) microtime() * 10000000);
        $rand = array_rand($userAgents);
        $this->user_agent = $userAgents[$rand];

        if (function_exists('set_time_limit') and !in_array('set_time_limit', $this->disable_functions)) {
            set_time_limit(120);
        }

        if (function_exists('ini_set') and !in_array('ini_set', $this->disable_functions)) {
            ini_set('default_socket_timeout', 120);
            ini_set('user_agent', $this->user_agent);
        }
    }

    public function setLanguage($lang_upload)
    {
        if (isset($lang_upload['error_uploadNameEmpty'])) {
            $this->lang['error_uploadNameEmpty'] = $lang_upload['error_uploadNameEmpty'];
        }
        if (isset($lang_upload['error_uploadSizeEmpty'])) {
            $this->lang['error_uploadSizeEmpty'] = $lang_upload['error_uploadSizeEmpty'];
        }
        if (isset($lang_upload['error_upload_ini_size'])) {
            $this->lang['error_upload_ini_size'] = $lang_upload['error_upload_ini_size'];
        }
        if (isset($lang_upload['error_upload_form_size'])) {
            $this->lang['error_upload_form_size'] = $lang_upload['error_upload_form_size'];
        }
        if (isset($lang_upload['error_upload_partial'])) {
            $this->lang['error_upload_partial'] = $lang_upload['error_upload_partial'];
        }
        if (isset($lang_upload['error_upload_no_file'])) {
            $this->lang['error_upload_no_file'] = $lang_upload['error_upload_no_file'];
        }
        if (isset($lang_upload['error_upload_no_tmp_dir'])) {
            $this->lang['error_upload_no_tmp_dir'] = $lang_upload['error_upload_no_tmp_dir'];
        }
        if (isset($lang_upload['error_upload_cant_write'])) {
            $this->lang['error_upload_cant_write'] = $lang_upload['error_upload_cant_write'];
        }
        if (isset($lang_upload['error_upload_extension'])) {
            $this->lang['error_upload_extension'] = $lang_upload['error_upload_extension'];
        }
        if (isset($lang_upload['error_upload_unknown'])) {
            $this->lang['error_upload_unknown'] = $lang_upload['error_upload_unknown'];
        }
        if (isset($lang_upload['error_upload_type_not_allowed'])) {
            $this->lang['error_upload_type_not_allowed'] = $lang_upload['error_upload_type_not_allowed'];
        }
        if (isset($lang_upload['error_upload_mime_not_recognize'])) {
            $this->lang['error_upload_mime_not_recognize'] = $lang_upload['error_upload_mime_not_recognize'];
        }
        if (isset($lang_upload['error_upload_max_user_size'])) {
            $this->lang['error_upload_max_user_size'] = $lang_upload['error_upload_max_user_size'];
        }
        if (isset($lang_upload['error_upload_not_image'])) {
            $this->lang['error_upload_not_image'] = $lang_upload['error_upload_not_image'];
        }
        if (isset($lang_upload['error_upload_image_failed'])) {
            $this->lang['error_upload_image_failed'] = $lang_upload['error_upload_image_failed'];
        }
        if (isset($lang_upload['error_upload_image_width'])) {
            $this->lang['error_upload_image_width'] = $lang_upload['error_upload_image_width'];
        }
        if (isset($lang_upload['error_upload_image_height'])) {
            $this->lang['error_upload_image_height'] = $lang_upload['error_upload_image_height'];
        }
        if (isset($lang_upload['error_upload_forbidden'])) {
            $this->lang['error_upload_forbidden'] = $lang_upload['error_upload_forbidden'];
        }
        if (isset($lang_upload['error_upload_writable'])) {
            $this->lang['error_upload_writable'] = $lang_upload['error_upload_writable'];
        }
        if (isset($lang_upload['error_upload_urlfile'])) {
            $this->lang['error_upload_urlfile'] = $lang_upload['error_upload_urlfile'];
        }
        if (isset($lang_upload['error_upload_url_notfound'])) {
            $this->lang['error_upload_url_notfound'] = $lang_upload['error_upload_url_notfound'];
        }
    }

    /**
     * upload::func_exists()
     *
     * @param mixed $funcName
     * @return
     */
    private function func_exists($funcName)
    {
        return (function_exists($funcName) and !in_array($funcName, $this->disable_functions));
    }

    /**
     * upload::cl_exists()
     *
     * @param mixed $clName
     * @return
     */
    private function cl_exists($clName)
    {
        return (class_exists($clName, false) and !in_array($clName, $this->disable_classes));
    }

    /**
     * upload::getextension()
     *
     * @param mixed $filename
     * @return
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
     * upload::get_ini()
     *
     * @param mixed $allowed_filetypes
     * @param mixed $forbid_extensions
     * @param mixed $forbid_mimes
     * @return
     */
    private function get_ini($allowed_filetypes, $forbid_extensions, $forbid_mimes)
    {
        $ini = $all_ini = array();

        if (file_exists(NV_MIME_INI_FILE)) {
            $data = file(NV_MIME_INI_FILE);
            $section = '';
            foreach ($data as $line) {
                $line = trim($line);
                if (empty($line) or preg_match('/^;/', $line)) {
                    continue;
                }

                if (preg_match('/^\[(.*?)\]$/', $line, $match)) {
                    $section = $match[1];
                    continue;
                }

                if (!strpos($line, '=')) {
                    continue;
                }

                list ($key, $value) = explode('=', $line);
                $key = trim($key);
                $value = trim($value);
                $value = str_replace(array(
                    '"',
                    "'"
                ), array(
                    '',
                    ''
                ), $value);

                if (preg_match('/^(.*?)\[\]$/', $key, $match)) {
                    $all_ini[$section][$match[1]][] = $value;
                } else {
                    $all_ini[$section][$key][] = $value;
                }
            }

            foreach ($all_ini as $section => $line) {
                if ($allowed_filetypes == array(
                    'any'
                ) or in_array($section, $allowed_filetypes)) {
                    $ini = array_merge($ini, $line);
                }
            }

            if (!empty($forbid_extensions)) {
                foreach ($forbid_extensions as $extension) {
                    unset($ini[$extension]);
                }
            }

            if (!empty($forbid_mimes)) {
                $new_ini = array();
                foreach ($ini as $key => $i) {
                    $new = array();
                    $new[$key] = array();
                    foreach ($i as $i2) {
                        if (!in_array($i2, $forbid_mimes)) {
                            $new[$key][] = $i2;
                        }
                    }
                    if (!empty($new[$key])) {
                        $new_ini = array_merge($new_ini, $new);
                    }
                }
                $ini = $new_ini;
            }
        }

        return $ini;
    }

    /**
     * upload::get_mime_from_iniFile()
     *
     * @return
     */
    private function get_mime_from_iniFile()
    {
        return $this->config['allowed_files'][$this->file_extension][0];
    }

    /**
     * upload::get_mime_from_userFile()
     *
     * @param mixed $userfile
     * @return
     */
    private function get_mime_from_userFile($userfile)
    {
        return preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($userfile['type']));
    }

    /**
     * upload::get_mime_finfo()
     *
     * @param mixed $userfile
     * @return
     */
    private function get_mime_finfo($userfile)
    {
        $mime = '';
        if ($this->func_exists('finfo_open')) {
            if (empty($this->config['magic_path'])) {
                $finfo = finfo_open(FILEINFO_MIME);
            } elseif ($this->config['magic_path'] != 'auto') {
                $finfo = finfo_open(FILEINFO_MIME, $this->config['magic_path']);
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
                $mime = finfo_file($finfo, realpath($userfile['tmp_name']));
                finfo_close($finfo);
                $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
            }
        }

        if (empty($mime) or $mime == 'application/octet-stream') {
            if ($this->cl_exists('finfo')) {
                $finfo = new finfo(FILEINFO_MIME);
                if ($finfo) {
                    $mime = $finfo->file(realpath($userfile['tmp_name']));
                    $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
                }
            }
        }

        return $mime;
    }

    /**
     * upload::get_mime_exec()
     *
     * @param mixed $userfile
     * @return
     */
    private function get_mime_exec($userfile)
    {
        $mime = '';

        if (substr(PHP_OS, 0, 3) != 'WIN') {
            if ($this->func_exists('system')) {
                ob_start();
                system('file -i -b ' . escapeshellarg($userfile['tmp_name']));
                $m = ob_get_clean();
                $m = trim($m);
                if (!empty($m)) {
                    $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $m);
                }
            } elseif ($this->func_exists('exec')) {
                $m = @exec('file -bi ' . escapeshellarg($userfile['tmp_name']));
                $m = trim($m);
                if (!empty($m)) {
                    $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $m);
                }
            }
        }

        return $mime;
    }

    /**
     * upload::get_mime_content_type()
     *
     * @param mixed $userfile
     * @return
     */
    private function get_mime_content_type($userfile)
    {
        $mime = '';

        if ($this->func_exists('mime_content_type')) {
            $mime = mime_content_type($userfile['tmp_name']);
            $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
        }

        return $mime;
    }

    /**
     * upload::get_mime_image()
     *
     * @param mixed $userfile
     * @return
     */
    private function get_mime_image($userfile)
    {
        $mime = '';
        $img_exts = array(
            IMAGETYPE_GIF => 'gif',
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_SWF => 'swf',
            IMAGETYPE_PSD => 'psd',
            IMAGETYPE_BMP => 'bmp',
            IMAGETYPE_TIFF_II => 'tiff',
            IMAGETYPE_TIFF_MM => 'tiff'
        );
        if (in_array($this->file_extension, $img_exts)) {
            if (($img_info = @getimagesize($userfile['tmp_name'])) !== false) {
                $this->img_info = $img_info;

                if (array_key_exists('mime', $this->img_info) and !empty($this->img_info['mime'])) {
                    $mime = trim($this->img_info['mime']);
                    $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $mime);
                }

                if (empty($mime) and isset($this->img_info[2])) {
                    $mime = image_type_to_mime_type($this->img_info[2]);
                }
                if (isset($img_exts[$this->img_info[2]])) {
                    $this->file_extension = $img_exts[$this->img_info[2]];
                }
            }
        }

        return $mime;
    }

    /**
     * upload::check_mime_from_ext()
     *
     * @param mixed $mime
     * @return
     */
    private function check_mime_from_ext($mime)
    {
        if (!empty($mime) and !in_array($mime, $this->config['allowed_files'][$this->file_extension])) {
            $mime = '';
        }
        return $mime;
    }

    /**
     * upload::mime_ign()
     *
     * @param mixed $mime
     * @return
     */
    private function mime_ign($mime)
    {
        if (preg_match('/^application\/(?:x-)?zip(?:-compressed)?$/is', $mime)) {
            if ($this->file_extension == 'docx') {
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            } elseif ($this->file_extension == 'dotx') {
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
            } elseif ($this->file_extension == 'potx') {
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.template';
            } elseif ($this->file_extension == 'ppsx') {
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
            } elseif ($this->file_extension == 'pptx') {
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            } elseif ($this->file_extension == 'xlsx') {
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            } elseif ($this->file_extension == 'xltx') {
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
            } elseif ($this->file_extension == 'docm') {
                $mime = 'application/vnd.ms-word.document.macroEnabled.12';
            } elseif ($this->file_extension == 'dotm') {
                $mime = 'application/vnd.ms-word.template.macroEnabled.12';
            } elseif ($this->file_extension == 'potm') {
                $mime = 'application/vnd.ms-powerpoint.template.macroEnabled.12';
            } elseif ($this->file_extension == 'ppam') {
                $mime = 'application/vnd.ms-powerpoint.addin.macroEnabled.12';
            } elseif ($this->file_extension == 'ppsm') {
                $mime = 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12';
            } elseif ($this->file_extension == 'pptm') {
                $mime = 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
            } elseif ($this->file_extension == 'xlam') {
                $mime = 'application/vnd.ms-excel.addin.macroEnabled.12';
            } elseif ($this->file_extension == 'xlsb') {
                $mime = 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
            } elseif ($this->file_extension == 'xlsm') {
                $mime = 'application/vnd.ms-excel.sheet.macroEnabled.12';
            } elseif ($this->file_extension == 'xltm') {
                $mime = 'application/vnd.ms-excel.template.macroEnabled.12';
            }
        } elseif ($mime == 'application/vnd.ms-office') {
            if ($this->file_extension == 'doc') {
                $mime = 'application/msword';
            } elseif ($this->file_extension == 'xls') {
                $mime = 'application/excel';
            } elseif ($this->file_extension == 'ppt') {
                $mime = 'application/vnd.ms-powerpoint';
            } elseif ($this->file_extension == 'pps') {
                $mime = 'application/vnd.ms-powerpoint';
            }
        }
        return $mime;
    }

    /**
     * upload::get_mime_type()
     *
     * @param mixed $userfile
     * @return
     */
    private function get_mime_type($userfile)
    {
        if ($this->config['upload_checking_mode'] != 'strong' and $this->config['upload_checking_mode'] != 'mild' and $this->config['upload_checking_mode'] != 'lite') {
            if (($mime = $this->get_mime_finfo($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_exec($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_content_type($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_image($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_from_userFile($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_from_iniFile()) != '') {
                return $this->mime_ign($mime);
            }
            return '';
        }

        if ($this->config['upload_checking_mode'] != 'strong' and $this->config['upload_checking_mode'] != 'mild') {
            if (($mime = $this->get_mime_finfo($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_exec($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_content_type($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_image($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if (($mime = $this->get_mime_from_userFile($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            return '';
        }

        if ($this->config['upload_checking_mode'] != 'strong') {
            if ($this->check_mime_from_ext($mime = $this->get_mime_finfo($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if ($this->check_mime_from_ext($mime = $this->get_mime_exec($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if ($this->check_mime_from_ext($mime = $this->get_mime_content_type($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if ($this->check_mime_from_ext($mime = $this->get_mime_image($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            if ($this->check_mime_from_ext($mime = $this->get_mime_from_userFile($userfile)) != '') {
                return $this->mime_ign($mime);
            }
            return '';
        }

        if ($this->check_mime_from_ext($mime = $this->get_mime_finfo($userfile)) != '') {
            return $this->mime_ign($mime);
        }
        if ($this->check_mime_from_ext($mime = $this->get_mime_exec($userfile)) != '') {
            return $this->mime_ign($mime);
        }
        if ($this->check_mime_from_ext($mime = $this->get_mime_content_type($userfile)) != '') {
            return $this->mime_ign($mime);
        }
        if ($this->check_mime_from_ext($mime = $this->get_mime_image($userfile)) != '') {
            return $this->mime_ign($mime);
        }
        return '';
    }

    /**
     * upload::verify_image()
     *
     * @param mixed $file
     * @return
     */
    private function verify_image($file)
    {
        $file = preg_replace('/\0/uis', '', $file);
        $txt = file_get_contents($file);
        if ($txt === false) {
            return false;
        }

        //if( preg_match( "#&\#x([0-9a-f]+);#i", $txt ) ) return false;
        //elseif( preg_match( '#&\#([0-9]+);#i', $txt ) ) return false;
        //else
        if (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) {
            return false;
        } elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) {
            return false;
        } elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) {
            return false;
        } elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) {
            return false;
        } elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) {
            return false;
        } elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) {
            return false;
        } elseif (preg_match("#<\?php(.*)\?>#ms", $txt)) {
            return false;
        }
        return true;
    }

    /**
     * Upload::check_tmpfile()
     *
     * @param mixed $userfile
     * @param mixed $no_check_size
     * @return
     */
    private function check_tmpfile($userfile, $no_check_size)
    {
        if (empty($userfile)) {
            return $this->lang['error_upload_no_file'];
        }
        if (!isset($userfile['name']) or empty($userfile['name'])) {
            return $this->lang['error_uploadNameEmpty'];
        }
        if (!isset($userfile['size']) or empty($userfile['size'])) {
            return _ERROR_UPLOAD_SIZEEMPTY;
        }
        if (!isset($userfile['tmp_name']) or empty($userfile['tmp_name']) or !file_exists($userfile['tmp_name'])) {
            return _ERROR_UPLOAD_SIZEEMPTY;
        }
        if (!isset($userfile['error']) or $userfile['error'] != UPLOAD_ERR_OK) {
            switch ($userfile['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $er = $this->lang['error_upload_ini_size'];
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $er = $this->lang['error_upload_form_size'];
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $er = $this->lang['error_upload_partial'];
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $er = $this->lang['error_upload_no_file'];
                    ;
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $er = $this->lang['error_upload_no_tmp_dir'];
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $er = $this->lang['error_upload_cant_write'];
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $er = $this->lang['error_upload_extension'];
                    break;
                default:
                    $er = $this->lang['error_upload_unknown'];
            }
            return $er;
        }

        $extension = $this->getextension($userfile['name']);
        if (empty($extension) or !isset($this->config['allowed_files'][$extension])) {
            return $this->lang['error_upload_type_not_allowed'];
        }

        $this->file_extension = $extension;
        $this->file_mime = $this->get_mime_type($userfile);
        $this->file_size = $userfile['size'];

        /**
         * Chú ý:
         * - Nếu upload chunk sau khi hoàn thành tất cả các chunk cần kiểm tra lại mime của file trước khi rename, move, copy file qua đích đến
         * - Khi upload chunk sẽ chỉ kiểm tra BLOB đầu tiên mang thông tin của file đó
         */
        if (empty($this->file_mime) and ($this->chunk_total <= 1 or $this->chunk_current <= 0)) {
            return $this->lang['error_upload_mime_not_recognize'];
        }

        /**
         * Nếu upload nhiều phần không kiểm tra dung lượng BLOB hoặc tất cả các BLOB
         * Nguyên nhân tổng số BLOB cộng lại có thể lớn hơn dung lượng cho phép
         * tuy nhiên BLOB cuối cùng lại có thể có dung lượng rất nhỏ và chưa vượt mức cho phép
         * Sau khi upload tất cả BLOB lên sẽ check sau, tuy nhiên tốt nhất là nên sử dụng các công cụ
         * để kiểm tra trực tiếp từ trình duyệt.
         */
        if (!empty($this->config['maxsize']) and $this->file_size > $this->config['maxsize'] and $this->chunk_total <= 1) {
            if (!($no_check_size and preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime))) {
                return sprintf($this->lang['error_upload_max_user_size'], nv_convertfromBytes($this->config['maxsize']));
            }
        }

        if (preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime) or preg_match('#application\/[x\-]*(shockwave\-flash)#', $this->file_mime)) {
            $this->is_img = true;
            if (empty($this->img_info)) {
                $this->img_info = @getimagesize($userfile['tmp_name']);
            }

            if (empty($this->img_info) or !isset($this->img_info[0]) or empty($this->img_info[0]) or !isset($this->img_info[1]) or empty($this->img_info[1])) {
                return $this->lnag['error_upload_not_image'];
            }

            if (!$this->verify_image($userfile['tmp_name'])) {
                return $this->lang['error_upload_image_failed'];
            }

            if (!($no_check_size and preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime))) {
                if (!empty($this->config['maxwidth']) and $this->img_info[0] > $this->config['maxwidth']) {
                    return sprintf($this->lang['error_upload_image_width'], $this->config['maxwidth']);
                }

                if (!empty($this->config['maxheight']) and $this->img_info[1] > $this->config['maxheight']) {
                    return sprintf($this->lang['error_upload_image_height'], $this->config['maxheight']);
                }
            }
        }

        return '';
    }

    private function check_chunktmp($file_tmp, $no_check_size)
    {
        if (!file_exists($file_tmp)) {
            return $this->lang['error_upload_cant_write'];
        }

        $userfile = array();
        $userfile['tmp_name'] = $file_tmp;
        $userfile['type'] = 'type';

        $this->file_mime = $this->get_mime_type($userfile);

        if (empty($this->file_mime)) {
            return $this->lang['error_upload_mime_not_recognize'];
        }

        $this->file_size = $this->filesize($userfile['tmp_name']);

        if (!empty($this->config['maxsize']) and $this->file_size > $this->config['maxsize']) {
            if (!($no_check_size and preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime))) {
                return sprintf($this->lang['error_upload_max_user_size'], nv_convertfromBytes($this->config['maxsize']));
            }
        }

        if (preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime) or preg_match('#application\/[x\-]*(shockwave\-flash)#', $this->file_mime)) {
            $this->is_img = true;
            if (empty($this->img_info)) {
                $this->img_info = @getimagesize($userfile['tmp_name']);
            }

            if (empty($this->img_info) or !isset($this->img_info[0]) or empty($this->img_info[0]) or !isset($this->img_info[1]) or empty($this->img_info[1])) {
                return $this->lnag['error_upload_not_image'];
            }

            if (!$this->verify_image($userfile['tmp_name'])) {
                return $this->lang['error_upload_image_failed'];
            }

            if (!($no_check_size and preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime))) {
                if (!empty($this->config['maxwidth']) and $this->img_info[0] > $this->config['maxwidth']) {
                    return sprintf($this->lang['error_upload_image_width'], $this->config['maxwidth']);
                }

                if (!empty($this->config['maxheight']) and $this->img_info[1] > $this->config['maxheight']) {
                    return sprintf($this->lang['error_upload_image_height'], $this->config['maxheight']);
                }
            }
        }

        return '';
    }

    /**
     * upload::check_save_path()
     *
     * @param mixed $savepath
     * @return
     */
    private function check_save_path($savepath)
    {
        if (empty($savepath) or !is_dir($savepath)) {
            return $this->lang['error_upload_forbidden'];
        }

        if (!is_writable($savepath)) {
            @chmod($savepath, 0755);
            if (!is_writable($savepath)) {
                return sprintf($this->lang['error_upload_writable'], $savepath);
            }
        }
        return '';
    }

    /**
     * upload::string_to_filename()
     *
     * @param mixed $word
     * @return
     */
    private function string_to_filename($word)
    {
        if (defined('NV_LANG_DATA') and file_exists(NV_ROOTDIR . '/includes/utf8/lookup_' . NV_LANG_DATA . '.php')) {
            include NV_ROOTDIR . '/includes/utf8/lookup_' . NV_LANG_DATA . '.php';
            $word = strtr($word, $utf8_lookup_lang);
        }

        if (file_exists(NV_ROOTDIR . '/includes/utf8/lookup.php')) {
            $utf8_lookup = false;
            include NV_ROOTDIR . '/includes/utf8/lookup.php';
            $word = strtr($word, $utf8_lookup['romanize']);
        }

        $word = rawurldecode($word);
        $word = preg_replace('/[^a-z0-9\.\-\_ ]/i', '', $word);
        $word = preg_replace('/^\W+|\W+$/', '', $word);
        $word = preg_replace('/[ ]+/', '-', $word);
        return strtolower(preg_replace('/\W-/', '', $word));
    }

    /**
     * Upload::save_file()
     *
     * @param mixed $userfile
     * @param mixed $savepath
     * @param bool $replace_if_exists
     * @param bool $no_check_size
     * @return
     */
    public function save_file($userfile, $savepath, $replace_if_exists = true, $no_check_size = false)
    {
        $this->headersNoCache();
        $this->file_extension = '';
        $this->file_mime = '';
        $this->file_size = 0;
        $this->is_img = false;
        $this->img_info = array();

        if ($this->chunk_total > 1) {
            $userfile['name'] = $this->chunk_filename;
        }

        $return = array();
        $return['error'] = $this->check_tmpfile($userfile, $no_check_size);
        if (!empty($return['error'])) {
            return $return;
        }

        $savepath = str_replace("\\", "/", realpath($savepath));
        $return['error'] = $this->check_save_path($savepath);
        if (!empty($return['error'])) {
            return $return;
        }

        if ($this->chunk_total > 1) {
            $return['error'] = $this->check_save_path($this->chunk_tmpdir);
            if (!empty($return['error'])) {
                return $return;
            }
        }

        unset($f);
        preg_match('/^(.*)\.[a-zA-Z0-9]+$/', $userfile['name'], $f);
        $fn = $this->string_to_filename($f[1]);
        $filename = $fn . '.' . $this->file_extension;
        if (!preg_match('/\/$/', $savepath)) {
            $savepath = $savepath . '/';
        }

        if (empty($replace_if_exists)) {
            $filename2 = $filename;
            $i = 1;
            while (file_exists($savepath . $filename2)) {
                $filename2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $filename);
                ++$i;
            }
            $filename = $filename2;
        }

        if ($this->chunk_total > 1) {
            $chunkComplete = false;
            if (!preg_match('/\/$/', $this->chunk_tmpdir)) {
                $this->chunk_tmpdir = $this->chunk_tmpdir . '/';
            }

            $file_tmp = $this->chunk_tmpdir . $this->chunk_prefix . md5($userfile['name'] . $this->chunk_total);

            if ($this->chunk_current == 0 and file_exists($file_tmp)) {
                @unlink($file_tmp);
            }

            try {
                $this->lockChunkFile($file_tmp);
                if (!$out = @fopen($file_tmp, 'ab')) {
                    throw new Exception($this->lang['error_upload_cant_write']);
                }
                if (!$in = @fopen($userfile['tmp_name'], "rb")) {
                    throw new Exception($this->lang['error_upload_no_file']);
                }

                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }

                @fclose($in);
                fflush($out);
                @fclose($out);

                if ($this->chunk_total <= ($this->chunk_current + 1)) {
                    $error = $this->check_chunktmp($file_tmp, $no_check_size);
                    if ($error != '') {
                        throw new Exception($error);
                    }

                    $chunkComplete = true;

                    if (!@rename($file_tmp, $savepath . $filename)) {
                        @copy($file_tmp, $savepath . $filename);
                    }

                    if (!file_exists($savepath . $filename)) {
                        throw new Exception($this->lang['error_upload_cant_write']);
                    }

                    if (file_exists($file_tmp)) {
                        @unlink($file_tmp);
                    }
                }

                $this->unlockChunkFile($file_tmp);
            } catch (Exception $e) {
                $this->unlockChunkFile($file_tmp);
                @unlink($file_tmp);
                $return['error'] = $e->getMessage();
                return $return;
            }
        } else {
            $chunkComplete = true;
            if (!@copy($userfile['tmp_name'], $savepath . $filename)) {
                @move_uploaded_file($userfile['tmp_name'], $savepath . $filename);
            }

            if (!file_exists($savepath . $filename)) {
                $return['error'] = $this->lang['error_upload_cant_write'];
                return $return;
            }
        }

        $return['complete'] = $chunkComplete;

        if ($chunkComplete) {
            if (substr(PHP_OS, 0, 3) != 'WIN') {
                $oldumask = umask(0);
                chmod($savepath . $filename, 0777);
                umask($oldumask);
            }

            $return['name'] = $savepath . $filename;
            $return['basename'] = $filename;
            $return['ext'] = $this->file_extension;
            $return['mime'] = $this->file_mime;
            $return['size'] = $this->file_size;
            $return['is_img'] = $this->is_img;
            if ($this->is_img) {
                $return['img_info'] = $this->img_info;
            }
        }

        return $return;
    }

    /**
     * upload::url_get_info()
     *
     * @param mixed $url
     * @return
     */
    private function url_get_info($url)
    {
        //URL: http://username:password@www.example.com:80/dir/page.php?foo=bar&foo2=bar2#bookmark
        $url_info = @parse_url($url);

        //[host] => www.example.com
        if (!isset($url_info['host'])) {
            return false;
        }

        //[port] => :80
        $url_info['port'] = isset($url_info['port']) ? $url_info['port'] : 80;

        //[login] => username:password@
        $url_info['login'] = '';
        if (isset($url_info['user'])) {
            $url_info['login'] = $url_info['user'];
            if (isset($url_info['pass'])) {
                $url_info['login'] .= ':' . $url_info['pass'];
            }
            $url_info['login'] .= '@';
        }

        //[path] => /dir/page.php
        if (isset($url_info['path'])) {
            if (substr($url_info['path'], 0, 1) != '/') {
                $url_info['path'] = '/' . $url_info['path'];
            }
            $path_array = explode('/', $url_info['path']);
            $path_array = array_map('rawurlencode', $path_array);
            $url_info['path'] = implode('/', $path_array);
        } else {
            $url_info['path'] = '/';
        }

        //[query] => ?foo=bar&foo2=bar2
        $url_info['query'] = (isset($url_info['query']) and !empty($url_info['query'])) ? '?' . $url_info['query'] : '';

        //[fragment] => bookmark
        $url_info['fragment'] = isset($url_info['fragment']) ? $url_info['fragment'] : '';

        //[file] => page.php
        $url_info['file'] = explode('/', $url_info['path']);
        $url_info['file'] = array_pop($url_info['file']);

        //[dir] => /dir
        $url_info['dir'] = substr($url_info['path'], 0, strrpos($url_info['path'], '/'));

        //[base] => http://www.example.com/dir
        $url_info['base'] = $url_info['scheme'] . '://' . $url_info['host'] . $url_info['dir'];

        //[uri] => http://username:password@www.example.com:80/dir/page.php?#bookmark
        $url_info['uri'] = $url_info['scheme'] . '://' . $url_info['login'] . $url_info['host'];
        if ($url_info['port'] != 80) {
            $url_info['uri'] .= ':' . $url_info['port'];
        }
        $url_info['uri'] .= $url_info['path'] . $url_info['query'];

        if ($url_info['fragment'] != '') {
            $url_info['uri'] .= '#' . $url_info['fragment'];
        }

        return $url_info;
    }

    /**
     * upload::check_url()
     *
     * @param integer $is_200
     * @return
     */
    private function check_url($is_200 = 0)
    {
        $allow_url_fopen = (ini_get('allow_url_fopen') == '1' or strtolower(ini_get('allow_url_fopen')) == 'on') ? 1 : 0;
        if (function_exists('get_headers') and !in_array('get_headers', $this->disable_functions) and $allow_url_fopen == 1) {
            $res = get_headers($this->url_info['uri']);
        } elseif (function_exists('curl_init') and !in_array('curl_init', $this->disable_functions) and function_exists('curl_exec') and !in_array('curl_exec', $this->disable_functions)) {
            $url_info = @parse_url($this->url_info['uri']);
            $port = isset($url_info['port']) ? intval($url_info['port']) : 80;

            $userAgents = array(
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
                'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
                'Mozilla/4.8 [en] (Windows NT 6.0; U)',
                'Opera/9.25 (Windows NT 6.0; U; en)'
            );
            $open_basedir = (ini_get('open_basedir') == '1' or strtolower(ini_get('open_basedir')) == 'on') ? 1 : 0;

            srand((float) microtime() * 10000000);
            $rand = array_rand($userAgents);
            $agent = $userAgents[$rand];

            $curl = curl_init($this->url_info['uri']);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_NOBODY, true);

            curl_setopt($curl, CURLOPT_PORT, $port);

            if ($open_basedir) {
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            }

            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);

            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                return false;
            } else {
                $res = explode("\n", $response);
            }
        } elseif (function_exists('fsockopen') and !in_array('fsockopen', $this->disable_functions) and function_exists('fgets') and !in_array('fgets', $this->disable_functions)) {
            $res = array();
            $url_info = parse_url($this->url_info['uri']);
            $port = isset($url_info['port']) ? intval($url_info['port']) : 80;
            $fp = fsockopen($url_info['host'], $port, $errno, $errstr, 15);
            if ($fp) {
                $path = !empty($url_info['path']) ? $url_info['path'] : '/';
                $path .= !empty($url_info['query']) ? '?' . $url_info['query'] : '';

                fputs($fp, "HEAD " . $path . " HTTP/1.0\r\n");
                fputs($fp, "Host: " . $url_info['host'] . ":" . $port . "\r\n");
                fputs($fp, "Connection: close\r\n\r\n");

                while (!feof($fp)) {
                    if ($header = trim(fgets($fp, 1024))) {
                        $res[] = $header;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        if (!$res) {
            return false;
        }
        if (preg_match("/(200)/", $res[0])) {
            $ContentType = '';
            foreach ($res as $k => $v) {
                if (preg_match("/content-type:\s(.*?)$/is", $v, $matches)) {
                    $ContentType = trim($matches[1]);
                }
            }
            if (!empty($ContentType)) {
                if (!is_array($ContentType)) {
                    $ContentType = array(
                        $ContentType
                    );
                }
                foreach ($ContentType as $Ctype) {
                    $Ctype = trim($Ctype);
                    if (!empty($Ctype)) {
                        $this->urlfile_mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', $Ctype);
                        break;
                    }
                }
            }
            return true;
        }
        if ($is_200 > 5) {
            return false;
        }
        if (preg_match('/(301)|(302)|(303)/', $res[0])) {
            foreach ($res as $k => $v) {
                if (preg_match("/location:\s(.*?)$/is", $v, $matches)) {
                    ++$is_200;
                    $location = trim($matches[1]);
                    if (substr($location, 0, 1) == '/') {
                        $location = $this->url_info['scheme'] . '://' . $this->url_info['host'] . $location;
                    }
                    $this->url_info = $this->url_get_info($location);
                    if (empty($this->url_info) or !isset($this->url_info['scheme'])) {
                        return false;
                    }
                    return $this->check_url($is_200);
                }
            }
        }
        return false;
    }

    /**
     * upload::check_allow_methods()
     *
     * @return
     */
    private function check_allow_methods()
    {
        $allow_methods = array();
        if (extension_loaded('curl') and !preg_grep('/^curl\_/', $this->disable_functions)) {
            $allow_methods[] = 'curl';
        }

        if (ini_get('allow_url_fopen') == '1' or strtolower(ini_get('allow_url_fopen')) == 'on') {
            if ($this->func_exists('fopen')) {
                $allow_methods[] = 'fopen';
            }

            if ($this->func_exists('file_get_contents')) {
                $allow_methods[] = 'file_get_contents';
            }

            if ($this->func_exists('file')) {
                $allow_methods[] = 'file';
            }
        }

        return $allow_methods;
    }

    /**
     * upload::check_mime()
     *
     * @param mixed $mime
     * @return
     */
    private function check_mime($mime)
    {
        $return = false;

        foreach ($this->config['allowed_files'] as $ext => $mimes) {
            if (in_array($mime, $mimes)) {
                $this->urlfile_extension = $ext;
                $return = true;
                break;
            }
        }

        return $return;
    }

    /**
     * upload::curl_Download()
     *
     * @return
     */
    private function curl_Download()
    {
        $options = array(
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_COOKIEFILE => '',
            CURLOPT_FOLLOWLOCATION => true
        );

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url_info['uri']);
        curl_setopt_array($curlHandle, $options);
        if (($fp = fopen($this->temp_file, 'wb')) === false) {
            curl_close($curlHandle);
            return false;
        }

        curl_setopt($curlHandle, CURLOPT_FILE, $fp);
        curl_setopt($curlHandle, CURLOPT_BINARYTRANSFER, true);

        if (curl_exec($curlHandle) === false) {
            fclose($fp);
            curl_close($curlHandle);
            return false;
        }
        fclose($fp);
        curl_close($curlHandle);
        return true;
    }

    /**
     * upload::fopen_Download()
     *
     * @return
     */
    private function fopen_Download()
    {
        if (($fp = fopen($this->url_info['uri'], 'rb')) === false) {
            return false;
        }
        if (($fp2 = fopen($this->temp_file, 'wb')) === false) {
            fclose($fp);
            return false;
        }

        while (!feof($fp)) {
            if (fwrite($fp2, fread($fp, 1024)) === false) {
                fclose($fp2);
                fclose($fp);
                return false;
            }
        }

        fclose($fp2);
        fclose($fp);
        return true;
    }

    /**
     * upload::file_get_contents_Download()
     *
     * @return
     */
    private function file_get_contents_Download()
    {
        $content = file_get_contents($this->url_info['uri']);
        if ($content === false) {
            return false;
        }
        return @file_put_contents($this->temp_file, $content);
    }

    /**
     * upload::file_Download()
     *
     * @return
     */
    private function file_Download()
    {
        $lines = @file($this->url_info['uri']);
        if ($lines === false) {
            return false;
        }
        if (($fp = fopen($this->temp_file, 'wb')) === false) {
            return false;
        }

        foreach ($lines as $line) {
            if (fwrite($fp, $line) === false) {
                fclose($fp);
                return false;
            }
        }

        fclose($fp);
        return true;
    }

    /**
     * upload::save_urlfile()
     *
     * @param mixed $urlfile
     * @param mixed $savepath
     * @param bool $replace_if_exists
     * @return
     */
    public function save_urlfile($urlfile, $savepath, $replace_if_exists = true, $no_check_size = false)
    {
        $this->headersNoCache();
        $this->file_extension = '';
        $this->file_mime = '';
        $this->urlfile_mime = '';
        $this->urlfile_extension = '';
        $this->is_img = false;
        $this->img_info = array();

        $return = array();
        $return['error'] = '';

        $this->url_info = $this->url_get_info($urlfile);
        if (empty($this->url_info) or !isset($this->url_info['scheme'])) {
            $return['error'] = $this->lang['error_upload_urlfile'];
            return $return;
        }

        if ($this->check_url() === false) {
            $return['error'] = $this->lang['error_upload_url_notfound'];
            return $return;
        }

        if (empty($this->urlfile_mime)) {
            $return['error'] = $this->lang['error_upload_mime_not_recognize'];
            return $return;
        }

        if (!$this->check_mime($this->urlfile_mime)) {
            $return['error'] = $this->lang['error_upload_type_not_allowed'] . ' (' . $this->urlfile_mime . ')';
            return $return;
        }

        if (isset($this->url_info['file'])) {
            $urlfile_extension = $this->getextension($this->url_info['file']);
            if (!empty($urlfile_extension) and isset($this->config['allowed_files'][$urlfile_extension])) {
                if (in_array($this->urlfile_mime, $this->config['allowed_files'][$urlfile_extension])) {
                    $this->urlfile_extension = $urlfile_extension;
                }
            }
        }

        $allow_methods = $this->check_allow_methods();
        if (!$this->func_exists('fopen')) {
            $allow_methods = array(
                'file_get_contents'
            );
        }

        $this->temp_file = str_replace("\\", "/", tempnam(NV_ROOTDIR . '/' . NV_TEMP_DIR, NV_TEMPNAM_PREFIX));

        $result = false;
        foreach ($allow_methods as $method) {
            $result = call_user_func(array(
                &$this,
                $method . '_Download'
            ));
            if ($result === true) {
                break;
            }
        }

        if ($result === false) {
            @unlink($this->temp_file);
            $return['error'] = $this->lang['error_upload_no_file'];
            return $return;
        }

        $return['size'] = $this->filesize($this->temp_file);

        $this->file_extension = $this->urlfile_extension;
        $this->file_mime = $this->get_mime_type(array(
            'type' => $this->urlfile_mime,
            'tmp_name' => $this->temp_file
        ));
        if (empty($this->file_mime)) {
            @unlink($this->temp_file);
            $return['error'] = $this->lang['error_upload_mime_not_recognize'];
            return $return;
        }

        if (!empty($this->config['maxsize']) and $return['size'] > $this->config['maxsize']) {
            if (!($no_check_size and preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime))) {
                @unlink($this->temp_file);
                $return['error'] = sprintf($this->lang['error_upload_max_user_size'], $this->config['maxsize']);
                return $return;
            }
        }

        if (preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime) or preg_match('#application\/[x\-]*(shockwave\-flash)#', $this->file_mime)) {
            $this->is_img = true;
            if (empty($this->img_info)) {
                $this->img_info = @getimagesize($this->temp_file);
            }

            if (empty($this->img_info) or !isset($this->img_info[0]) or empty($this->img_info[0]) or !isset($this->img_info[1]) or empty($this->img_info[1])) {
                @unlink($this->temp_file);
                $return['error'] = $this->lang['error_upload_not_image'];
                return $return;
            }

            if (!$this->verify_image($this->temp_file)) {
                @unlink($this->temp_file);
                $return['error'] = $this->lang['error_upload_image_failed'];
                return $return;
            }

            if (!($no_check_size and preg_match('#image\/[x\-]*([a-z]+)#', $this->file_mime))) {
                if (!empty($this->config['maxwidth']) and $this->img_info[0] > $this->config['maxwidth']) {
                    @unlink($this->temp_file);
                    $return['error'] = sprintf($this->lang['error_upload_image_width'], $this->config['maxwidth']);
                    return $return;
                }

                if (!empty($this->config['maxheight']) and $this->img_info[1] > $this->config['maxheight']) {
                    @unlink($this->temp_file);
                    $return['error'] = sprintf($this->lang['error_upload_image_height'], $this->config['maxheight']);
                    return $return;
                }
            }
        }

        $savepath = str_replace("\\", "/", realpath($savepath));
        $return['error'] = $this->check_save_path($savepath);
        if (!empty($return['error'])) {
            @unlink($this->temp_file);
            return $return;
        }

        unset($f);
        if (isset($this->url_info['file']) and preg_match("/^(.*)\.[a-zA-Z0-9]+$/", $this->url_info['file'], $f)) {
            $fn = $this->string_to_filename($f[1]);
            $filename = $fn . "." . $this->file_extension;
        } else {
            $filename = time() . "." . $this->file_extension;
        }

        if (!preg_match('/\/$/', $savepath)) {
            $savepath = $savepath . "/";
        }
        if (empty($replace_if_exists)) {
            $filename2 = $filename;
            $i = 1;
            while (file_exists($savepath . $filename2)) {
                $filename2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $filename);
                ++$i;
            }
            $filename = $filename2;
        }

        if (!@copy($this->temp_file, $savepath . $filename)) {
            @move_uploaded_file($this->temp_file, $savepath . $filename);
        }

        if (!file_exists($savepath . $filename)) {
            @unlink($this->temp_file);
            $return['error'] = $this->lang['error_upload_cant_write'];
            return $return;
        }

        @unlink($this->temp_file);

        if (substr(PHP_OS, 0, 3) != 'WIN') {
            $oldumask = umask(0);
            chmod($savepath . $filename, 0777);
            umask($oldumask);
        }

        $return['complete'] = true;
        $return['name'] = $savepath . $filename;
        $return['basename'] = $filename;
        $return['ext'] = $this->file_extension;
        $return['mime'] = $this->file_mime;
        $return['is_img'] = $this->is_img;
        if ($this->is_img) {
            $return['img_info'] = $this->img_info;
        }
        return $return;
    }

    /**
     * Upload::headersNoCache()
     *
     * @return void
     */
    private function headersNoCache()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    /**
     * Upload::setChunkOption()
     *
     * @param mixed $chunk_upload
     * @return
     */
    public function setChunkOption($chunk_upload)
    {
        if (is_array($chunk_upload)) {
            if (!empty($chunk_upload['name'])) {
                $this->chunk_filename = $chunk_upload['name'];
            }
            if (isset($chunk_upload['chunk'])) {
                $this->chunk_current = $chunk_upload['chunk'];
            }
            if (!empty($chunk_upload['chunks'])) {
                $this->chunk_total = $chunk_upload['chunks'];
            }
            if (!empty($chunk_upload['tmpdir'])) {
                $this->chunk_tmpdir = $chunk_upload['tmpdir'];
            }
            if (!empty($chunk_upload['chunk_prefix'])) {
                $this->chunk_prefix = $chunk_upload['chunk_prefix'];
            }
        }
        return $this;
    }

    /**
     * PHPs filesize() fails to measure files larger than 2gb
     * @see http://stackoverflow.com/a/5502328/189673
     *
     * @param string $file Path to the file to measure
     * @return int
     */
    protected function filesize($file)
    {
        if (!file_exists($file)) {
            return false;
        }
        static $iswin;
        if (!isset($iswin)) {
            $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
        }
        static $exec_works;
        if (!isset($exec_works)) {
            $exec_works = ($this->func_exists('exec') and !ini_get('safe_mode') and @exec('echo EXEC') == 'EXEC');
        }
        // Try a shell command
        if ($exec_works) {
            $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : "stat -c%s \"$file\"";
            @exec($cmd, $output);
            if (is_array($output) and is_numeric($size = trim(implode("\n", $output)))) {
                return $size;
            }
        }
        // Try the Windows COM interface
        if ($iswin and $this->cl_exists("COM")) {
            try {
                $fsobj = new COM('Scripting.FileSystemObject');
                $filecal = $fsobj->GetFile(realpath($file));
                $size = $filecal->Size;
            } catch (Exception $e) {
                $size = null;
            }
            if (ctype_digit($size)) {
                return $size;
            }
        }
        // If everything else fails
        return @filesize($file);
    }

    /**
     * Upload::lockChunkFile()
     *
     * @param mixed $file
     * @return void
     */
    private function lockChunkFile($file)
    {
        if (is_resource($this->chunk_resource)) {
            fclose($this->chunk_resource);
        }
        $this->chunk_resource = fopen($file . '.lock', 'w');
        flock($this->chunk_resource, LOCK_EX);
    }

    /**
     * Upload::unlockChunkFile()
     *
     * @param mixed $file
     * @return void
     */
    private function unlockChunkFile($file)
    {
        fclose($this->chunk_resource);
        @unlink($file . '.lock');
    }
}