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
    const LOG_FILE_NAME_DEFAULT = 'error_log'; //ten file log
    const LOG_NOTICE_FILE_NAME_DEFAULT = 'notice_log'; //ten file log
    const LOG_FILE_EXT_DEFAULT = 'log'; //duoi file log

    private $cfg;
    private $cl;
    private $errno = false;
    private $errstr = false;
    private $errfile = false;
    private $errline = false;
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
        $this->cfg = [
            'log_errors_list' => self::parse_error_num((int) (isset($config['log_errors_list']) ? $config['log_errors_list'] : self::LOG_ERROR_LIST_DEFAULT)),
            'display_errors_list' => self::parse_error_num((int) (isset($config['display_errors_list']) ? $config['display_errors_list'] : self::DISPLAY_ERROR_LIST_DEFAULT)),
            'send_errors_list' => self::parse_error_num((int) (isset($config['send_errors_list']) ? $config['send_errors_list'] : self::SEND_ERROR_LIST_DEFAULT)),
            'error_send_mail' => !empty($config['error_send_email']) ? (string) $config['error_send_email'] : '',
            'error_set_logs' => isset($config['error_set_logs']) ? (bool) $config['error_set_logs'] : true,
            'error_log_filename' => (isset($config['error_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['error_log_filename'])) ? $config['error_log_filename'] : self::LOG_FILE_NAME_DEFAULT,
            'notice_log_filename' => (isset($config['notice_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['notice_log_filename'])) ? $config['notice_log_filename'] : self::LOG_NOTICE_FILE_NAME_DEFAULT,
            'error_log_fileext' => (isset($config['error_log_fileext']) and preg_match('/[a-z]+/i', $config['error_log_fileext'])) ? $config['error_log_fileext'] : self::LOG_FILE_EXT_DEFAULT
        ];
        $this->cfg = array_merge($this->cfg, $this->get_error_log_path((string) (isset($config['error_log_path']) ? $config['error_log_path'] : self::ERROR_LOG_PATH_DEFAULT)));

        $this->cl = [
            'day' => gmdate('Y-m-d', NV_CURRENTTIME), // Prefix của file log, Lấy cố định GMT, không theo múi giờ
            'error_date' => date('r', NV_CURRENTTIME), // Thời gian xảy ra lỗi, Lấy theo múi giờ của client (tùy cấu hình)
            'month' => gmdate('Y-m', NV_CURRENTTIME), // Prefix theo tháng log 256, Lấy cố định GMT, không theo múi giờ,
            'ip' => Ips::$remote_ip,
            'request' => substr(Site::getEnv(['UNENCODED_URL', 'REQUEST_URI']), 0, 500),
            'useragent' => substr(Site::getEnv('HTTP_USER_AGENT'), 0, 500),
            'server_name' => preg_replace('/(\:[0-9]+)$/', '', preg_replace('/^[a-z]+\:\/\//i', '', trim(Site::getEnv(['HTTP_HOST', 'SERVER_NAME', 'Host']))))
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
     * info_die()
     *
     * @return never
     */
    private function info_die()
    {
        $error_code = md5($this->errno . (string) $this->errfile . (string) $this->errline . $this->cl['ip']);
        $error_code2 = md5($error_code);
        $error_file = $this->cfg['error_log_256'] . '/' . $this->cl['month'] . '__' . $error_code2 . '__' . $error_code . '.' . $this->cfg['error_log_fileext'];

        if ($this->cfg['error_set_logs'] and !file_exists($error_file)) {
            $content = 'TIME: ' . $this->cl['error_date'] . "\n";
            if (!empty($this->cl['ip'])) {
                $content .= 'IP: ' . $this->cl['ip'] . "\n";
            }
            $content .= 'INFO: ' . self::$errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . "\n";
            if (!empty($this->errfile)) {
                $content .= 'FILE: ' . $this->errfile . "\n";
            }
            if (!empty($this->errline)) {
                $content .= 'LINE: ' . $this->errline . "\n";
            }
            if (!empty($this->cl['request'])) {
                $content .= 'REQUEST: ' . $this->cl['request'] . "\n";
            }
            if (!empty($this->cl['useragent'])) {
                $content .= 'USER-AGENT: ' . $this->cl['useragent'] . "\n";
            }

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
        $contents = str_replace('[CODE]', $error_code2, $contents);
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
        $content = '[' . $this->cl['error_date'] . '] [' . $this->cl['server_name'] . ']';
        if (!empty($this->cl['ip'])) {
            $content .= ' [' . $this->cl['ip'] . ']';
        }
        $content .= ' [' . self::$errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . ']';
        if (!empty($this->errfile)) {
            $content .= ' [FILE: ' . $this->errfile . ']';
        }
        if (!empty($this->errline)) {
            $content .= ' [LINE: ' . $this->errline . ']';
        }
        if (!empty($this->cl['request'])) {
            $content .= ' [REQUEST: ' . $this->cl['request'] . ']';
        }

        if (NV_DEBUG) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (isset($backtrace[3])) {
                $content .= "\n";
                $trace_total = sizeof($backtrace);
                $stt = 0;
                for ($i = $trace_total - 1; $i >= 3; --$i) {
                    ++$stt;
                    $content .= '^^^ [TRACE#' . str_pad($stt, 2, '0', STR_PAD_LEFT) . '] [FILE: ' . str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $backtrace[$i]['file'])) . '] [LINE: ' . $backtrace[$i]['line'] . "]\n";
                }
            }
        }

        $content .= "\n";
        $error_log_file = $this->cfg['error_log_path'] . '/' . $this->cl['day'] . '_' . ($this->errno == E_NOTICE ? $this->cfg['notice_log_filename'] : $this->cfg['error_log_filename']) . '.' . $this->cfg['error_log_fileext'];
        error_log($content, 3, $error_log_file);
    }

    /**
     * _send()
     */
    private function _send()
    {
        $content = '[' . $this->cl['error_date'] . ']';
        if (!empty($this->cl['ip'])) {
            $content .= ' [' . $this->cl['ip'] . ']';
        }
        $content .= ' [' . self::$errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . ']';
        if (!empty($this->errfile)) {
            $content .= ' [FILE: ' . $this->errfile . ']';
        }
        if (!empty($this->errline)) {
            $content .= ' [LINE: ' . $this->errline . ']';
        }
        if (!empty($this->cl['request'])) {
            $content .= ' [REQUEST: ' . $this->cl['request'] . ']';
        }
        if (!empty($this->cl['useragent'])) {
            $content .= ' [AGENT: ' . $this->cl['useragent'] . ']';
        }
        $content .= "\n";
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
        $track_errors = $this->cl['day'] . '_' . md5($this->errno . (string) $this->errfile . (string) $this->errline);
        $track_errors = $this->cfg['error_log_tmp'] . '/' . $track_errors . '.' . $this->cfg['error_log_fileext'];
        $log_is_displayed = file_exists($track_errors);

        if ($this->cfg['error_set_logs'] and !$log_is_displayed) {
            file_put_contents($track_errors, '', FILE_APPEND);

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
        $this->errstr = str_replace(NV_ROOTDIR, '...', str_replace('\\', '/', $errstr));
        !empty($errfile) && $this->errfile = str_replace(NV_ROOTDIR, '...', str_replace('\\', '/', $errfile));
        !empty($errline) && $this->errline = $errline;

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
            $file = substr(str_replace('\\', '/', preg_replace(['/\\\\/', "/\/{2,}/"], '/', $error['file'])), strlen(NV_ROOTDIR . '/'));
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
}
