<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/10/2010 19:43
 */

namespace NukeViet\Core;

if (!defined('E_STRICT')) {
    define('E_STRICT', 2048); //khong sua
}
if (!defined('E_RECOVERABLE_ERROR')) {
    define('E_RECOVERABLE_ERROR', 4096); //khong sua
}
if (!defined('E_DEPRECATED')) {
    define('E_DEPRECATED', 8192); //khong sua
}
if (!defined('E_USER_DEPRECATED')) {
    define('E_USER_DEPRECATED', 16384); //khong sua
}
if (!defined('NV_DEBUG')) {
    define('NV_DEBUG', 0);
}

class Error
{
    const INCORRECT_IP = 'Incorrect IP address specified';
    const LOG_FILE_NAME_DEFAULT = 'error_log'; //ten file log
    const LOG_FILE_EXT_DEFAULT = 'log'; //duoi file log

    private $log_errors_list;
    private $display_errors_list;
    private $send_errors_list;
    private $error_send_mail;
    private $error_set_logs = false;
    private $error_log_path;
    private $error_log_tmp = false;
    private $error_log_filename;
    private $error_log_fileext;
    private $error_log_256;
    private $errno = false;
    private $errstr = false;
    private $errfile = false;
    private $errline = false;
    private $ip = false;
    private $server_name = false;
    private $useragent = false;
    private $request = false;
    private $day;
    private $month;
    private $error_date;
    private $errortype = array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable fatal error',
        E_DEPRECATED => 'Run-time notices',
        E_USER_DEPRECATED => 'User-generated warning message'
    );
    private $track_fatal_error = array(
        array(
            'file' => 'vendor/vinades/nukeviet/Cache/Redis.php',
            'pattern' => array(
                array('/[\'|"]Redis[\'|"] not found/i', 'PHP Redis Extension does not exists!')
            )
        ),
        array(
            'file' => 'vendor/vinades/nukeviet/Cache/Memcached.php',
            'pattern' => array(
                array('/[\'|"]Memcached[\'|"] not found/i', 'PHP Memcached Extension does not exists!')
            )
        )
    );
    private $error_excluded = array("/^ftp\_login\(\)/i", "/^gzinflate\(\)\: data error/i");

    /**
     * Error::__construct()
     *
     * @param mixed $config
     * @return
     */
    public function __construct($config)
    {
        $this->log_errors_list = $this->parse_error_num((int)$config['log_errors_list']);
        $this->display_errors_list = $this->parse_error_num((int)$config['display_errors_list']);
        $this->send_errors_list = $this->parse_error_num((int)$config['send_errors_list']);
        $this->error_log_path = $this->get_error_log_path((string )$config['error_log_path']);
        $this->error_send_mail = (string )$config['error_send_email'];
        $this->error_set_logs = $config['error_set_logs'];

        if (isset($config['error_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['error_log_filename'])) {
            $this->error_log_filename = $config['error_log_filename'];
        } else {
            $this->error_log_filename = Error::LOG_FILE_NAME_DEFAULT;
        }
        if (isset($config['error_log_fileext']) and preg_match('/[a-z]+/i', $config['error_log_fileext'])) {
            $this->error_log_fileext = $config['error_log_fileext'];
        } else {
            $this->error_log_fileext = Error::LOG_FILE_EXT_DEFAULT;
        }

        $this->day = date('d-m-Y', NV_CURRENTTIME);
        $this->error_date = date('r', NV_CURRENTTIME);
        $this->month = date('m-Y', NV_CURRENTTIME);

        $ip = $this->get_Env('REMOTE_ADDR');
        $this->ip = $ip;

        if (preg_match('#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#', $ip)) {
            $ip2long = ip2long($ip);
        } else {
            if (substr_count($ip, '::')) {
                $ip = str_replace('::', str_repeat(':0000', 8 - substr_count($ip, ':')) . ':', $ip);
            }
            $ip = explode(':', $ip);
            $r_ip = '';
            foreach ($ip as $v) {
                $r_ip .= str_pad(base_convert($v, 16, 2), 16, 0, STR_PAD_LEFT);
            }
            $ip2long = base_convert($r_ip, 2, 10);
        }
        if ($ip2long === -1 or $ip2long === false) {
            exit(Error::INCORRECT_IP);
        }

        $request = $this->get_request();
        if (!empty($request)) {
            $this->request = substr($request, 500);
        }

        $useragent = $this->get_Env('HTTP_USER_AGENT');
        if (!empty($useragent)) {
            $this->useragent = substr($useragent, 0, 500);
        }

        set_error_handler(array(&$this, 'error_handler'));
        register_shutdown_function(array(&$this, 'shutdown'));
    }

    /**
     * Error::get_Env()
     *
     * @param mixed $key
     * @return
     */
    private function get_Env($key)
    {
        if (!is_array($key)) {
            $key = array($key);
        }

        foreach ($key as $k) {
            if (isset($_SERVER[$k])) {
                return $_SERVER[$k];
            } elseif (isset($_ENV[$k])) {
                return $_ENV[$k];
            } elseif (@getenv($k)) {
                return @getenv($k);
            } elseif (function_exists('apache_getenv') and apache_getenv($k, true)) {
                return apache_getenv($k, true);
            }
        }
        return '';
    }

    /**
     * Error::get_error_log_path()
     *
     * @param mixed $path
     * @return
     */
    private function get_error_log_path($path)
    {
        $path = ltrim(rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), '/'), '/');
        if (is_dir(NV_ROOTDIR . '/' . $path)) {
            $log_path = NV_ROOTDIR . '/' . $path;
        } else {
            $log_path = NV_ROOTDIR;
            $e = explode('/', $path);
            $cp = '';
            foreach ($e as $p) {
                if (preg_match('#[^a-zA-Z0-9\_]#', $p)) {
                    $cp = '';
                    break;
                }
                if (!is_dir(NV_ROOTDIR . '/' . $cp . $p)) {
                    if (!@mkdir(NV_ROOTDIR . '/' . $cp . $p, 0777)) {
                        $cp = '';
                        break;
                    }
                }
                $cp .= $p . '/';
            }
            $log_path .= '/' . $path;
            @mkdir($log_path . '/tmp');
            @mkdir($log_path . '/errors256');
            @mkdir($log_path . '/old');
        }
        if (is_dir($log_path . '/tmp')) {
            $this->error_log_tmp = $log_path . '/tmp';
        }
        if (is_dir($log_path . '/errors256')) {
            $this->error_log_256 = $log_path . '/errors256';
        }
        return $log_path;
    }

    /**
     * Error::parse_error_num()
     *
     * @param mixed $num
     * @return
     */
    private function parse_error_num($num)
    {
        if ($num > E_ALL + E_STRICT) {
            $num = E_ALL + E_STRICT;
        }
        if ($num < 0) {
            $num = 0;
        }
        $result = array();
        $n = 1;
        while ($num > 0) {
            if ($num & 1 == 1) {
                $result[$n] = $this->errortype[$n];
            }
            $n *= 2;
            $num >>= 1;
        }

        return $result;
    }

    /**
     * Error::get_request()
     *
     * @return
     */
    public function get_request()
    {
        $request = array();
        if (sizeof($_GET)) {
            foreach ($_GET as $key => $value) {
                if (preg_match('/^[a-zA-Z0-9\_]+$/', $key) and !is_numeric($key)) {
                    $value = $this->fixQuery($key, $value);
                    if ($value !== false) {
                        $request[$key] = $value;
                    }
                }
            }
        }

        $request = !empty($request) ? '?' . http_build_query($request) : '';
        $request = $this->get_Env('PHP_SELF') . $request;

        return $request;
    }

    /**
     * Error::fixQuery()
     *
     * @param mixed $key
     * @param mixed $value
     * @return
     */
    private function fixQuery($key, $value)
    {
        if (preg_match('/^[a-zA-Z0-9\_]+$/', $key)) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $_value = $this->fixQuery($k, $v);
                    if ($_value !== false) {
                        $value[$k] = $_value;
                    }
                }
                return $value;
            }

            $value = strip_tags(stripslashes($value));
            $value = preg_replace("/[\'|\"|\t|\r|\n|\.\.\/]+/", "", $value);
            $value = str_replace(array("'", '"', "&"), array('&rsquo;', '&quot;', '&amp;'), $value);
            return $value;
        }

        return false;
    }

    /**
     * Error::info_die()
     *
     * @return void
     */
    private function info_die()
    {
        $error_code = md5($this->errno . (string )$this->errfile . (string )$this->errline . $this->ip);
        $error_code2 = md5($error_code);
        $error_file = $this->error_log_256 . '/' . $this->month . '__' . $error_code2 . '__' . $error_code . '.' . $this->error_log_fileext;

        if ($this->error_set_logs and !file_exists($error_file)) {
            $content = "TIME: " . $this->error_date . "\r\n";
            if (!empty($this->ip)) {
                $content .= "IP: " . $this->ip . "\r\n";
            }
            $content .= "INFO: " . $this->errortype[$this->errno] . "(" . $this->errno . "): " . $this->errstr . "\r\n";
            if (!empty($this->errfile)) {
                $content .= "FILE: " . $this->errfile . "\r\n";
            }
            if (!empty($this->errline)) {
                $content .= "LINE: " . $this->errline . "\r\n";
            }
            if (!empty($this->request)) {
                $content .= "REQUEST: " . $this->request . "\r\n";
            }
            if (!empty($this->useragent)) {
                $content .= "USER-AGENT: " . $this->useragent . "\r\n";
            }

            file_put_contents($error_file, $content, FILE_APPEND);
        }

        $strEncodedEmail = '';
        $strlen = strlen($this->error_send_mail);
        for ($i = 0; $i < $strlen; ++$i) {
            $strEncodedEmail .= "&#" . ord(substr($this->error_send_mail, $i)) . ";";
        }

        header('Content-Type: text/html; charset=utf-8');
        if (defined('NV_ADMIN') or !defined('NV_ANTI_IFRAME') or NV_ANTI_IFRAME != 0) {
            Header('X-Frame-Options: SAMEORIGIN');
        }
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');

        $_info = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
        $_info .= "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
        $_info .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
        $_info .= "<head>\n";
        $_info .= "	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n";
        $_info .= "	<meta http-equiv=\"expires\" content=\"0\" />\n";
        $_info .= "<title>" . $this->errortype[$this->errno] . "</title>\n";
        $_info .= "</head>\n\n";
        $_info .= "<body>\n";
        $_info .= "	<div style=\"width: 400px; margin-right: auto; margin-left: auto; margin-top: 20px; margin-bottom: 20px; color: #dd3e31; text-align: center;\"><span style=\"font-weight: bold;\">" . $this->errortype[$this->errno] . "</span><br />\n";
        $_info .= "	<span style=\"color: #1a264e;font-weight: bold;\">" . $this->errstr . "</span><br />\n";
        $_info .= "	<span style=\"color: #1a264e;\">(Code: " . $error_code2 . ")</span></div>\n";
        $_info .= "	<div style=\"width: 400px; margin-right: auto; margin-left: auto;text-align:center\">\n";
        $_info .= "	If you have any questions about this site,<br />please <a href=\"mailto:" . $strEncodedEmail . "\">contact</a> the site administrator for more information</div>\n";
        $_info .= "</body>\n";
        $_info .= "</html>";
        exit($_info);
    }

    /**
     * Error::_log()
     *
     * @return void
     */
    private function _log()
    {
        $content = '[' . $this->error_date . '] [' . $this->get_server_name() . ']';
        if (!empty($this->ip)) {
            $content .= ' [' . $this->ip . ']';
        }
        $content .= ' [' . $this->errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . ']';
        if (!empty($this->errfile)) {
            $content .= ' [FILE: ' . $this->errfile . ']';
        }
        if (!empty($this->errline)) {
            $content .= ' [LINE: ' . $this->errline . ']';
        }
        if (!empty($this->request)) {
            $content .= ' [REQUEST: ' . $this->request . ']';
        }
        $content .= "\r\n";
        $error_log_file = $this->error_log_path . '/' . $this->day . '_' . $this->error_log_filename . '.' . $this->error_log_fileext;
        error_log($content, 3, $error_log_file);
    }

    /**
     * Error::_send()
     *
     * @return void
     */
    private function _send()
    {
        $content = '[' . $this->error_date . ']';
        if (!empty($this->ip)) {
            $content .= ' [' . $this->ip . ']';
        }
        $content .= ' [' . $this->errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . ']';
        if (!empty($this->errfile)) {
            $content .= ' [FILE: ' . $this->errfile . ']';
        }
        if (!empty($this->errline)) {
            $content .= ' [LINE: ' . $this->errline . ']';
        }
        if (!empty($this->request)) {
            $content .= ' [REQUEST: ' . $this->request . ']';
        }
        if (!empty($this->useragent)) {
            $content .= ' [AGENT: ' . $this->useragent . ']';
        }
        $content .= "\r\n";
        $error_log_file = $this->error_log_path . '/sendmail.' . $this->error_log_fileext;
        error_log($content, 3, $error_log_file);
    }

    /**
     * Error::_display()
     *
     * @return void
     */
    private function _display()
    {
        global $error_info;

        $display = true;
        foreach ($this->error_excluded as $pattern) {
            if (preg_match($pattern, $this->errstr)) {
                $display = false;
                break;
            }
        }

        if ($display) {
            $info = $this->errstr;
            if ($this->errno != E_USER_ERROR and $this->errno != E_USER_WARNING and $this->errno != E_USER_NOTICE) {
                if (!empty($this->errfile)) {
                    $info .= ' in file ' . $this->errfile;
                }
                if (!empty($this->errline)) {
                    $info .= ' on line ' . $this->errline;
                }
            }

            $error_info[] = array('errno' => $this->errno, 'info' => $info);
        }
    }

    /**
     * Error::error_handler()
     *
     * @param mixed $errno
     * @param mixed $errstr
     * @param mixed $errfile
     * @param mixed $errline
     * @return
     */
    public function error_handler($errno, $errstr, $errfile, $errline)
    {
        $this->errno = $errno;
        $this->errstr = $errstr;

        if (!empty($errfile)) {
            $this->errfile = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $errfile));
        }
        if (!empty($errline)) {
            $this->errline = $errline;
        }

        $this->log_control();

        if ($this->errno == 256) {
            $this->info_die();
        }
    }

    /**
     * Error::shutdown()
     *
     * @return void
     */
    public function shutdown()
    {
        $error = error_get_last();

        if (!empty($error) and $error['type'] === E_ERROR) {
            $file = $this->get_fixed_path($error['file']);
            $finded_track = false;

            $this->errno = E_ERROR;
            $this->errstr = $error['message'];
            $this->errfile = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $error['file']));
            $this->errline = $error['line'];

            foreach ($this->track_fatal_error as $track_fatal) {
                if ($track_fatal['file'] == $file) {
                    foreach ($track_fatal['pattern'] as $patterns_message) {
                        if (preg_match($patterns_message[0], $error['message'])) {
                            $finded_track = true;
                            $this->errstr = $patterns_message[1];
                            break;
                        }
                    }
                }
                if ($finded_track) {
                    break;
                }
            }

            $this->log_control();

            // Only display some track fatal error!
            if ($finded_track) {
                $this->info_die();
            } else {
                if (NV_DEBUG) {
                    echo('Error on file ' . $this->errfile . ' line ' . $this->errline . ':<br /><pre><code>');
                    echo($error['message']);
                    die('</code></pre>');
                }
                die(chr(0));
            }
        }
    }

    /**
     * Error::fix_path()
     *
     * @param mixed $path
     * @return
     */
    private function fix_path($path)
    {
        return str_replace('\\', '/', preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path));
    }

    /**
     * Error::get_fixed_path()
     *
     * @param mixed $realpath
     * @return
     */
    private function get_fixed_path($realpath)
    {
        return substr($this->fix_path($realpath), strlen(NV_ROOTDIR . '/'));
    }

    /**
     * Error::log_control()
     *
     * @return void
     */
    private function log_control()
    {
        $track_errors = $this->day . '_' . md5($this->errno . (string )$this->errfile . (string )$this->errline . $this->ip);
        $track_errors = $this->error_log_tmp . '/' . $track_errors . '.' . $this->error_log_fileext;
        $log_is_displayed = file_exists($track_errors);

        if ($this->error_set_logs and !$log_is_displayed) {
            file_put_contents($track_errors, '', FILE_APPEND);

            if (!empty($this->log_errors_list) and isset($this->log_errors_list[$this->errno])) {
                $this->_log();
            }

            if (!empty($this->send_errors_list) and isset($this->send_errors_list[$this->errno])) {
                $this->_send();
            }

        }
        if (NV_DEBUG and !empty($this->display_errors_list) and isset($this->display_errors_list[$this->errno])) {
            $this->_display();
        }
    }

    /**
     * Error::get_server_name()
     *
     * @return void
     */
    private function get_server_name()
    {
        if ($this->server_name != false) {
            return $this->server_name;
        }
        $server_name = trim((isset($_SERVER['HTTP_HOST']) and !empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
        $server_name = preg_replace('/^[a-z]+\:\/\//i', '', $server_name);
        $server_name = preg_replace('/(\:[0-9]+)$/', '', $server_name);
        $this->server_name = $server_name;
        return $server_name;
    }
}