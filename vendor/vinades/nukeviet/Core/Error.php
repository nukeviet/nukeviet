<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
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

/**
 * NukeViet\Core\Error
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
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
    private $errortype = [
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
    ];
    private $track_fatal_error = [
        [
            'file' => 'vendor/vinades/nukeviet/Cache/Redis.php',
            'pattern' => [
                ['/[\'|"]Redis[\'|"] not found/i', 'PHP Redis Extension does not exists!']
            ]
        ],
        [
            'file' => 'vendor/vinades/nukeviet/Cache/Memcached.php',
            'pattern' => [
                ['/[\'|"]Memcached[\'|"] not found/i', 'PHP Memcached Extension does not exists!']
            ]
        ]
    ];
    private $error_excluded = ["/^ftp\_login\(\)/i", "/^gzinflate\(\)\: data error/i"];

    /**
     * __construct()
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->log_errors_list = $this->parse_error_num((int) $config['log_errors_list']);
        $this->display_errors_list = $this->parse_error_num((int) $config['display_errors_list']);
        $this->send_errors_list = $this->parse_error_num((int) $config['send_errors_list']);
        $this->error_log_path = $this->get_error_log_path((string) $config['error_log_path']);
        $this->error_send_mail = (string) $config['error_send_email'];
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

        /*
         * Prefix của file log
         * Lấy cố định GMT, không theo múi giờ
         */
        $this->day = gmdate('d-m-Y', NV_CURRENTTIME);

        /*
         * Thời gian xảy ra lỗi
         * Lấy theo múi giờ của client (tùy cấu hình)
         */
        $this->error_date = date('r', NV_CURRENTTIME);

        /*
         * Prefix theo tháng log 256
         * Lấy cố định GMT, không theo múi giờ
         */
        $this->month = gmdate('m-Y', NV_CURRENTTIME);

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

        set_error_handler([&$this, 'error_handler']);
        register_shutdown_function([&$this, 'shutdown']);
    }

    /**
     * get_Env()
     *
     * @param mixed $key
     * @return string
     */
    private function get_Env($key)
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        foreach ($key as $k) {
            if (isset($_SERVER[$k])) {
                return $_SERVER[$k];
            }
            if (isset($_ENV[$k])) {
                return $_ENV[$k];
            }
            if (@getenv($k)) {
                return @getenv($k);
            }
            if (function_exists('apache_getenv') and apache_getenv($k, true)) {
                return apache_getenv($k, true);
            }
        }

        return '';
    }

    /**
     * get_error_log_path()
     *
     * @param string $path
     * @return string
     */
    private function get_error_log_path($path)
    {
        $path = ltrim(rtrim(preg_replace(['/\\\\/', "/\/{2,}/"], '/', $path), '/'), '/');
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
     * parse_error_num()
     *
     * @param int $num
     * @return array
     */
    private function parse_error_num($num)
    {
        if ($num > E_ALL + E_STRICT) {
            $num = E_ALL + E_STRICT;
        }
        if ($num < 0) {
            $num = 0;
        }
        $result = [];
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
     * get_request()
     *
     * @return string
     */
    public function get_request()
    {
        $request = [];
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
     * fixQuery()
     *
     * @param string $key
     * @param mixed  $value
     * @return array|false|string|null
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
            $value = preg_replace("/[\'|\"|\t|\r|\n|\.\.\/]+/", '', $value);

            return str_replace(["'", '"', '&'], ['&rsquo;', '&quot;', '&amp;'], $value);
        }

        return false;
    }

    /**
     * info_die()
     *
     * @return never
     */
    private function info_die()
    {
        $error_code = md5($this->errno . (string) $this->errfile . (string) $this->errline . $this->ip);
        $error_code2 = md5($error_code);
        $error_file = $this->error_log_256 . '/' . $this->month . '__' . $error_code2 . '__' . $error_code . '.' . $this->error_log_fileext;

        if ($this->error_set_logs and !file_exists($error_file)) {
            $content = 'TIME: ' . $this->error_date . "\n";
            if (!empty($this->ip)) {
                $content .= 'IP: ' . $this->ip . "\n";
            }
            $content .= 'INFO: ' . $this->errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . "\n";
            if (!empty($this->errfile)) {
                $content .= 'FILE: ' . $this->errfile . "\n";
            }
            if (!empty($this->errline)) {
                $content .= 'LINE: ' . $this->errline . "\n";
            }
            if (!empty($this->request)) {
                $content .= 'REQUEST: ' . $this->request . "\n";
            }
            if (!empty($this->useragent)) {
                $content .= 'USER-AGENT: ' . $this->useragent . "\n";
            }

            file_put_contents($error_file, $content, FILE_APPEND);
        }

        $strEncodedEmail = '';
        $strlen = strlen($this->error_send_mail);
        for ($i = 0; $i < $strlen; ++$i) {
            $strEncodedEmail .= '&#' . ord(substr($this->error_send_mail, $i)) . ';';
        }

        header('Content-Type: text/html; charset=utf-8');
        if (defined('NV_ADMIN') or !defined('NV_ANTI_IFRAME') or NV_ANTI_IFRAME != 0) {
            header('X-Frame-Options: SAMEORIGIN');
        }
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');

        $_info = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
        $_info .= "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
        $_info .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
        $_info .= "<head>\n";
        $_info .= "	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n";
        $_info .= "	<meta http-equiv=\"expires\" content=\"0\" />\n";
        $_info .= '<title>' . $this->errortype[$this->errno] . "</title>\n";
        $_info .= "</head>\n\n";
        $_info .= "<body>\n";
        $_info .= '	<div style="width: 400px; margin-right: auto; margin-left: auto; margin-top: 20px; margin-bottom: 20px; color: #dd3e31; text-align: center;"><span style="font-weight: bold;">' . $this->errortype[$this->errno] . "</span><br />\n";
        $_info .= '	<span style="color: #1a264e;font-weight: bold;">' . $this->errstr . "</span><br />\n";
        $_info .= '	<span style="color: #1a264e;">(Code: ' . $error_code2 . ")</span></div>\n";
        $_info .= "	<div style=\"width: 400px; margin-right: auto; margin-left: auto;text-align:center\">\n";
        $_info .= '	If you have any questions about this site,<br />please <a href="mailto:' . $strEncodedEmail . "\">contact</a> the site administrator for more information</div>\n";
        $_info .= "</body>\n";
        $_info .= '</html>';
        exit($_info);
    }

    /**
     * _log()
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

        if (NV_DEBUG) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (isset($backtrace[3])) {
                $content .= " [TRACE:]\n";
                $trace_total = sizeof($backtrace);
                $stt = 0;
                for ($i = $trace_total - 1; $i >= 3; --$i) {
                    ++$stt;
                    $content .= '#' . str_pad($stt, 2, ' ', STR_PAD_RIGHT) . ' LINE: ' . str_pad($backtrace[$i]['line'], 5, ' ', STR_PAD_RIGHT) . ' FILE: ' . str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $backtrace[$i]['file'])) . "\n";
                }
            }
        }

        $content .= "\n";
        $error_log_file = $this->error_log_path . '/' . $this->day . '_' . $this->error_log_filename . '.' . $this->error_log_fileext;
        error_log($content, 3, $error_log_file);
    }

    /**
     * _send()
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
        $content .= "\n";
        $error_log_file = $this->error_log_path . '/sendmail.' . $this->error_log_fileext;
        error_log($content, 3, $error_log_file);
    }

    /**
     * _display()
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

            $error_info[] = ['errno' => $this->errno, 'info' => $info];
        }
    }

    /**
     * error_handler()
     *
     * @param string $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
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
     * shutdown()
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
                    echo 'Error on file ' . $this->errfile . ' line ' . $this->errline . ':<br /><pre><code>';
                    echo $error['message'];
                    exit('</code></pre>');
                }
                exit(chr(0));
            }
        }
    }

    /**
     * fix_path()
     *
     * @param string $path
     * @return string
     */
    private function fix_path($path)
    {
        return str_replace('\\', '/', preg_replace(['/\\\\/', "/\/{2,}/"], '/', $path));
    }

    /**
     * get_fixed_path()
     *
     * @param string $realpath
     * @return string
     */
    private function get_fixed_path($realpath)
    {
        return substr($this->fix_path($realpath), strlen(NV_ROOTDIR . '/'));
    }

    /**
     * log_control()
     */
    private function log_control()
    {
        $track_errors = $this->day . '_' . md5($this->errno . (string) $this->errfile . (string) $this->errline . $this->ip);
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
     * get_server_name()
     *
     * @return string
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
