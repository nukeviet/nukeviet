<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

use NukeViet\Site;

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
    const DISPLAY_ERROR_LIST_DEFAULT = E_ALL;
    const LOG_ERROR_LIST_DEFAULT = E_ALL | E_STRICT;
    const SEND_ERROR_LIST_DEFAULT = E_USER_ERROR;
    const ERROR_LOG_PATH_DEFAULT = 'data/logs/error_logs';
    const LOG_FILE_NAME_DEFAULT = 'error_log'; //Tên file log lỗi
    const LOG_NOTICE_FILE_NAME_DEFAULT = 'notice_log'; //tên file log cảnh báo
    const LOG_FILE_EXT_DEFAULT = 'log'; //đuôi file log
    const LOG_DELIMITER = '-------------------'; //dấu phân cách

    public $cfg;
    private $cl;
    private $errno = false;
    private $errstr = false;
    private $errfile = false;
    private $errline = false;
    private $errid = false;
    private static $errortype = [
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
        E_STRICT => 'Strict Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated Notice',
        E_USER_DEPRECATED => 'User-deprecated Notice'
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
    public static $unreported_errors = [ // md5($this->errfile . $this->errline . $this->errno)
        '28fbebcb00a83556d3ada1cc54e6f06e', // md5('/includes/ini.php' . '360' . '2')
        'a6fbadb31af3e7035cf25831dd9865ab', // md5('/vendor/vinades/nukeviet/Files/Upload.php' . '1045' . '2')
    ];

    /**
     * __construct()
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->cfg = [
            'log_errors_list' => self::parse_error_num((int) ($config['log_errors_list'] ?? self::LOG_ERROR_LIST_DEFAULT)),
            'display_errors_list' => self::parse_error_num((int) ($config['display_errors_list'] ?? self::DISPLAY_ERROR_LIST_DEFAULT)),
            'send_errors_list' => self::parse_error_num((int) ($config['send_errors_list'] ?? self::SEND_ERROR_LIST_DEFAULT)),
            'error_send_mail' => !empty($config['error_send_email']) ? (string) $config['error_send_email'] : '',
            'error_set_logs' => isset($config['error_set_logs']) ? (bool) $config['error_set_logs'] : true,
            'error_log_filename' => (isset($config['error_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['error_log_filename'])) ? $config['error_log_filename'] : self::LOG_FILE_NAME_DEFAULT,
            'notice_log_filename' => (isset($config['notice_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['notice_log_filename'])) ? $config['notice_log_filename'] : self::LOG_NOTICE_FILE_NAME_DEFAULT,
            'error_log_fileext' => (isset($config['error_log_fileext']) and preg_match('/[a-z]+/i', $config['error_log_fileext'])) ? $config['error_log_fileext'] : self::LOG_FILE_EXT_DEFAULT
        ];
        $this->cfg = array_merge($this->cfg, self::get_error_log_path((string) ($config['error_log_path'] ?? self::ERROR_LOG_PATH_DEFAULT)));

        $this->cl = [
            'day' => gmdate('Y-m-d', NV_CURRENTTIME), // Prefix của file log, Lấy cố định GMT, không theo múi giờ
            'error_date' => date('r', NV_CURRENTTIME), // Thời gian xảy ra lỗi, Lấy theo múi giờ của client (tùy cấu hình)
            'month' => gmdate('Y-m', NV_CURRENTTIME), // Prefix theo tháng log 256, Lấy cố định GMT, không theo múi giờ,
            'ip' => Ips::$remote_ip,
            'request' => substr(Site::getEnv(['UNENCODED_URL', 'REQUEST_URI']), 0, 500),
            'useragent' => trim(substr(Site::getEnv('HTTP_USER_AGENT'), 0, 500)),
            'server_name' => preg_replace('/(\:[0-9]+)$/', '', preg_replace('/^[a-z]+\:\/\//i', '', trim(Site::getEnv(['HTTP_HOST', 'SERVER_NAME', 'Host'])))),
            'method' => strtoupper(Site::getEnv(['REQUEST_METHOD', 'Method']))
        ];

        set_error_handler([&$this, 'error_handler']);
        register_shutdown_function([&$this, 'shutdown']);
    }

    /**
     * get_error_log_path()
     *
     * @param string $path
     * @return string
     */
    private static function get_error_log_path($path)
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
            @file_put_contents($log_path . '/index.html', '');
            @file_put_contents($log_path . '/tmp/index.html', '');
            @file_put_contents($log_path . '/errors256/index.html', '');
            @file_put_contents($log_path . '/old/index.html', '');
        }

        return [
            'error_log_path' => $log_path,
            'error_log_tmp' => is_dir($log_path . '/tmp') ? $log_path . '/tmp' : false,
            'error_log_256' => is_dir($log_path . '/errors256') ? $log_path . '/errors256' : false
        ];
    }

    /**
     * parse_error_num()
     *
     * @param int $num
     * @return array
     */
    private static function parse_error_num($num)
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
                $result[$n] = self::$errortype[$n];
            }
            $n *= 2;
            $num >>= 1;
        }

        return $result;
    }

    /**
     * format_str()
     *
     * @param mixed $str
     * @return string|string[]|null
     */
    private static function format_str($str)
    {
        $str = str_replace('\\', '/', $str);
        $str = preg_replace('/\/{2,}/', '/', $str);
        $str = str_replace(NV_ROOTDIR, '', $str);

        return str_replace(['[', ']'], ['&lbrack;', '&rbrack;'], $str);
    }

    /**
     * _log_content()
     *
     * @return string
     */
    private function _log_content()
    {
        $errstr = [];
        if (stripos($this->errstr, 'stack trace') !== false) {
            $errstr = explode("\n", $this->errstr);
            $this->errstr = trim(array_shift($errstr));
            array_shift($errstr);
        }

        $content = [];
        $content['time'] = $this->cl['error_date'];
        $content['server'] = $this->cl['server_name'];
        $content['ip'] = $this->cl['ip'];
        $content['errno'] = $this->errno . ' (' . self::$errortype[$this->errno] . ')';
        $content['errstr'] = $this->errstr;
        if (!empty($this->errfile)) {
            $content['file'] = $this->errfile;
        }
        if (!empty($this->errline)) {
            $content['line'] = $this->errline;
        }
        if (!empty($this->cl['request'])) {
            $content['request'] = $this->cl['request'];
        }
        if (!empty($this->cl['method'])) {
            $content['method'] = $this->cl['method'];
        }
        if (!empty($this->cl['useragent'])) {
            $content['agent'] = $this->cl['useragent'];
        }
        if (!empty($errstr)) {
            $content['backtrace'] = array_map('trim', $errstr);
        }

        return $content;
    }

    /**
     * info_die()
     *
     * @return never
     */
    private function info_die()
    {
        $error_code = md5($this->errid . '-' . $this->cl['month'] . '-' . $this->cl['ip']);
        $error_file = $this->cfg['error_log_256'] . '/' . $this->cl['month'] . '_' . $error_code . '.' . $this->cfg['error_log_fileext'];

        if ($this->cfg['error_set_logs'] and !file_exists($error_file)) {
            $content = json_encode($this->_log_content(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            file_put_contents($error_file, $content, FILE_APPEND);
        }

        if (!empty($this->cfg['error_send_mail'])) {
            $strEncodedEmail = '';
            $strlen = strlen($this->cfg['error_send_mail']);
            for ($i = 0; $i < $strlen; ++$i) {
                $strEncodedEmail .= '&#' . ord(substr($this->cfg['error_send_mail'], $i)) . ';';
            }
            $email = '<a href="mailto:' . $strEncodedEmail . '">contact</a>';
        } else {
            $email = 'contact';
        }

        $contents = file_get_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl/error.tpl');
        $contents = str_replace('[PAGE_TITLE]', self::$errortype[$this->errno], $contents);
        $contents = str_replace('[ERRSTR]', nl2br($this->errstr), $contents);
        $contents = str_replace('[CODE]', $error_code, $contents);
        $contents = str_replace('[EMAIL]', $email, $contents);

        header('Content-Type: text/html; charset=utf-8');
        if (defined('NV_ADMIN') or !defined('NV_ANTI_IFRAME') or NV_ANTI_IFRAME != 0) {
            header('X-Frame-Options: SAMEORIGIN');
        }
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        exit($contents);
    }

    /**
     * _log()
     */
    private function _log()
    {
        $trace = [];
        if (NV_DEBUG) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (isset($backtrace[3])) {
                $trace_total = sizeof($backtrace);
                $stt = 0;
                for ($i = $trace_total - 1; $i >= 3; --$i) {
                    if (!empty($backtrace[$i]['file'])) {
                        ++$stt;
                        $_trace = '#' . $stt . ' ' . str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $backtrace[$i]['file']));
                        if (!empty($backtrace[$i]['line'])) {
                            $_trace .= '(' . $backtrace[$i]['line'] . ')';
                        }
                        $trace[] = $_trace;
                    }
                }
            }
        }

        $content = $this->_log_content();
        if (!empty($trace)) {
            $content['backtrace'] = $trace;
        }

        $content = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $content .= "\n";

        $error_log_file = in_array($this->errno, [E_WARNING, E_NOTICE, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED], true) ? $this->cfg['notice_log_filename'] : $this->cfg['error_log_filename'];
        $error_log_file = $this->cfg['error_log_path'] . '/' . $this->cl['day'] . '_' . $error_log_file . '_' . $this->errid . '.' . $this->cfg['error_log_fileext'];
        error_log($content, 3, $error_log_file);
    }

    /**
     * _send()
     */
    private function _send()
    {
        $content = json_encode($this->_log_content(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        $content .= self::LOG_DELIMITER . "\n";
        $error_log_file = $this->cfg['error_log_path'] . '/sendmail.' . $this->cfg['error_log_fileext'];
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
            $info = nl2br($this->errstr);
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
     * @desc Hàm ghi log ra file
     */
    private function log_control()
    {
        $log_file = $this->cfg['error_log_tmp'] . '/' . $this->cl['day'] . '_' . $this->errid . '.' . $this->cfg['error_log_fileext'];
        if ($this->cfg['error_set_logs'] and !file_exists($log_file)) {
            file_put_contents($log_file, '', LOCK_EX);

            if (!empty($this->cfg['log_errors_list']) and isset($this->cfg['log_errors_list'][$this->errno])) {
                $this->_log();
            }

            if (!empty($this->cfg['send_errors_list']) and isset($this->cfg['send_errors_list'][$this->errno])) {
                $this->_send();
            }
        }
        if (NV_DEBUG and !empty($this->cfg['display_errors_list']) and isset($this->cfg['display_errors_list'][$this->errno])) {
            $this->_display();
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
        $this->errstr = self::format_str($errstr);
        !empty($errfile) && $this->errfile = self::format_str($errfile);
        !empty($errline) && $this->errline = $errline;
        $this->errid = md5(($this->errfile ?: '') . ($this->errline ?: '') . $this->errno);

        if (!in_array($this->errid, self::$unreported_errors, true)) {
            $this->log_control();

            if ($this->errno == 256) {
                $this->info_die();
            }
        }
    }

    /**
     * shutdown()
     */
    public function shutdown()
    {
        $error = error_get_last();

        if (!empty($error) and $error['type'] === E_ERROR | E_PARSE) {
            $file = substr(str_replace('\\', '/', preg_replace(['/\\\\/', "/\/{2,}/"], '/', $error['file'])), strlen(NV_ROOTDIR . '/'));
            $finded_track = false;

            $this->errno = $error['type'];
            $error['type'] .= ' (' . self::$errortype[$error['type']] . ')';
            $error['message'] = self::format_str($error['message']);
            $error['file'] = self::format_str($error['file']);
            $this->errstr = $error['message'];
            $this->errfile = $error['file'];
            $this->errline = $error['line'];
            $this->errid = md5(($this->errfile ?: '') . ($this->errline ?: '') . $this->errno);

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
                    exit('An error occurred while loading the page:<br /><pre><code>' . print_r($error, true) . '</code></pre>');
                }

                if (!empty($this->cfg['error_send_mail'])) {
                    $strEncodedEmail = '';
                    $strlen = strlen($this->cfg['error_send_mail']);
                    for ($i = 0; $i < $strlen; ++$i) {
                        $strEncodedEmail .= '&#' . ord(substr($this->cfg['error_send_mail'], $i)) . ';';
                    }
                    $email = '<a href="mailto:' . $strEncodedEmail . '">let us know</a>';
                } else {
                    $email = 'let us know';
                }
                exit('An error occurred while loading the page: ' . self::$errortype[$this->errno] . '(' . $this->errno . ').<br/>Please ' . $email . ' about this!');
            }
        }
    }
}
