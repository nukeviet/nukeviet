<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

use NukeViet\Http\HttpException;
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
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Error
{
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
    private $errtrace = [];
    private static $errortype = [
        2048 => 'Strict Notice', // Backward compatible with PHP versions below 8.4 (E_STRICT removed in PHP 8.4)
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
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated Notice',
        E_USER_DEPRECATED => 'User-deprecated Notice'
    ];

    /**
     * @var array Các vấn đề lỗi log lại nhưng không cần hiển thị cảnh báo lên
     */
    private $error_excluded = [
        "/^ftp\_login\(\)/i",
        "/^gzinflate\(\)\: data error/i"
    ];

    /**
     * @var array Các trường hợp lỗi cố định có chủ đích, không báo cũng như không log lại
     */
    private $unreported_errors = [
        // Load config server không cần kiểm tra và bỏ qua lỗi
        '/includes/ini.php' => [[
            'message' => '/include\_once.*config\_ini.*No such file or directory/i'
        ], [
            'message' => '/include_once.*Failed opening.*config_ini\..*\.php/i'
        ]],
        // Cảnh báo DB không tồn tại khi cài đặt
        '/includes/vendor/vinades/nukeviet/Core/Database.php' => [[
            'message' => '/SQLSTATE.*HY000.*Unknown database/i',
            'request' => '/\/install\/index\.php.*step\=5/i'
        ]],
        // Cảnh báo sRGB khi xử lý ảnh
        '/includes/vendor/vinades/nukeviet/Files/Image.php' => [[
            'message' => '/known[\s]*incorrect[\s]*sRGB[\s]*profile/i'
        ]]
    ];

    /**
     * __construct()
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->cfg = [
            'log_errors_list' => self::parse_error_num((int) ($config['log_errors_list'] ?? (version_compare(PHP_VERSION, '8.4.0', '<') ? (E_ALL | E_STRICT) : E_ALL))),
            'display_errors_list' => self::parse_error_num((int) ($config['display_errors_list'] ?? E_ALL)),
            'send_errors_list' => self::parse_error_num((int) ($config['send_errors_list'] ?? E_USER_ERROR)),
            'error_send_mail' => !empty($config['error_send_email']) ? (string) $config['error_send_email'] : '',
            'error_set_logs' => isset($config['error_set_logs']) ? (bool) $config['error_set_logs'] : true,
            'error_separate_file' => isset($config['error_separate_file']) ? (bool) $config['error_separate_file'] : false,
            'error_log_filename' => (isset($config['error_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['error_log_filename'])) ? $config['error_log_filename'] : self::LOG_FILE_NAME_DEFAULT,
            'notice_log_filename' => (isset($config['notice_log_filename']) and preg_match('/[a-z0-9\_]+/i', $config['notice_log_filename'])) ? $config['notice_log_filename'] : self::LOG_NOTICE_FILE_NAME_DEFAULT,
            'error_log_fileext' => (isset($config['error_log_fileext']) and preg_match('/[a-z]+/i', $config['error_log_fileext'])) ? $config['error_log_fileext'] : self::LOG_FILE_EXT_DEFAULT
        ];
        $this->cfg = array_merge($this->cfg, self::get_error_log_path((string) ($config['error_log_path'] ?? self::ERROR_LOG_PATH_DEFAULT)));

        $this->cl = [
            'day' => gmdate('Y-m-d', NV_CURRENTTIME), // Prefix của file log, Lấy cố định GMT, không theo múi giờ
            'error_date' => date('r', NV_CURRENTTIME), // Thời gian xảy ra lỗi, Lấy theo múi giờ của client (tùy cấu hình)
            'month' => gmdate('Y-m', NV_CURRENTTIME), // Prefix theo tháng log 256, Lấy cố định GMT, không theo múi giờ,
            'ip' => Ips::$remote_ip, // IP thật của khách
            'remote_addr' => Ips::$remote_addr, // IP kết nối trực tiếp, không giả mạo được bằng header
            'request' => substr(Site::getEnv(['UNENCODED_URL', 'REQUEST_URI']), 0, 500),
            'useragent' => trim(substr(Site::getEnv('HTTP_USER_AGENT'), 0, 500)),
            'server_name' => preg_replace('/(\:[0-9]+)$/', '', preg_replace('/^[a-z]+\:\/\//i', '', trim(Site::getEnv(['HTTP_HOST', 'SERVER_NAME', 'Host'])))),
            'method' => strtoupper(Site::getEnv(['REQUEST_METHOD', 'Method']))
        ];

        set_error_handler([&$this, 'error_handler']);
        set_exception_handler([&$this, 'exception_handler']);
        register_shutdown_function([&$this, 'shutdown']);
    }

    /**
     * get_error_log_path()
     *
     * @param string $path
     * @return array
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
        if (version_compare(PHP_VERSION, '8.4.0', '<')) {
            if ($num > E_ALL + E_STRICT) {
                $num = E_ALL + E_STRICT;
            }
        } else {
            if ($num > E_ALL) {
                $num = E_ALL;
            }
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
     * Chuẩn hóa \ thành / cắt bỏ path tuyệt đối của server
     *
     * @param mixed $str
     * @return string|string[]|null
     */
    private static function format_str($str)
    {
        $str = str_replace('\\', '/', $str);
        $str = preg_replace('/\/{2,}/', '/', $str);

        return str_replace(NV_ROOTDIR, '', $str);
    }

    /**
     * format_trace()
     *
     * @param string $trace
     * @return array
     */
    private static function format_trace($trace)
    {
        $trace = explode("\n", self::format_str($trace));
        $trace = array_map('trim', $trace);

        return array_values(array_filter($trace));
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

        // Ghi thêm IP kết nối trực tiếp khi nó khác IP khách
        if ($this->cl['remote_addr'] !== $this->cl['ip']) {
            $content['remote_addr'] = $this->cl['remote_addr'];
        }

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
        } elseif (!empty($this->errtrace)) {
            $content['backtrace'] = $this->errtrace;
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
            $content = json_encode($this->_log_content(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            file_put_contents($error_file, $content, FILE_APPEND);
        }

        if (!empty($this->cfg['error_send_mail'])) {
            $strEncodedEmail = '';
            $strlen = strlen($this->cfg['error_send_mail']);
            for ($i = 0; $i < $strlen; ++$i) {
                $strEncodedEmail .= '&#' . ord($this->cfg['error_send_mail'][$i]) . ';';
            }
            $email = '<a href="mailto:' . $strEncodedEmail . '">contact</a>';
        } else {
            $email = 'contact';
        }

        $contents = file_get_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl/error.tpl');
        $contents = strtr($contents, [
            '[PAGE_TITLE]' => self::$errortype[$this->errno],
            '[ERRSTR]' => nl2br(htmlspecialchars($this->errstr, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')),
            '[CODE]' => $error_code,
            '[EMAIL]' => $email
        ]);

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
                $trace_total = count($backtrace);
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

        $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $content .= "\n";
        if (!$this->cfg['error_separate_file']) {
            $content .= self::LOG_DELIMITER . "\n";
        }

        $error_log_file = in_array($this->errno, [E_WARNING, E_NOTICE, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED], true) ? $this->cfg['notice_log_filename'] : $this->cfg['error_log_filename'];
        $error_log_file = $this->cfg['error_log_path'] . '/' . $this->cl['day'] . '_' . $error_log_file;
        if ($this->cfg['error_separate_file']) {
            $error_log_file .= '_' . $this->errid;
        }
        $error_log_file .= '.' . $this->cfg['error_log_fileext'];
        error_log($content, 3, $error_log_file);
    }

    /**
     * _send()
     */
    private function _send()
    {
        $content = json_encode($this->_log_content(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
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
            $info = nl2br(htmlspecialchars($this->errstr, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
            if ($this->errno != E_USER_ERROR and $this->errno != E_USER_WARNING and $this->errno != E_USER_NOTICE) {
                if (!empty($this->errfile)) {
                    $info .= ' in file ' . htmlspecialchars($this->errfile, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
        $this->errtrace = [];
        $this->errid = md5(($this->errfile ?: '') . ($this->errline ?: '') . $this->errno);

        if ($this->errfile and isset($this->unreported_errors[$this->errfile])) {
            foreach ($this->unreported_errors[$this->errfile] as $regex) {
                if (preg_match($regex['message'], $this->errstr) and (!isset($regex['request']) or preg_match($regex['request'], $this->cl['request'] ?? ''))) {
                    return;
                }
            }
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

        if (!empty($error) and $error['type'] === E_ERROR | E_PARSE) {
            http_response_code(500);

            $this->errno = $error['type'];
            $error['type'] .= ' (' . self::$errortype[$error['type']] . ')';
            $error['message'] = self::format_str($error['message']);
            $error['file'] = self::format_str($error['file']);
            $this->errstr = $error['message'];
            $this->errfile = $error['file'];
            $this->errline = $error['line'];
            $this->errtrace = [];
            $this->errid = md5(($this->errfile ?: '') . ($this->errline ?: '') . $this->errno);

            $this->log_control();

            if (NV_DEBUG) {
                exit('An error occurred while loading the page:<br /><pre><code>' . htmlspecialchars(print_r($error, true), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code></pre>');
            }

            $this->displayErrorPage();
        }
    }

    /**
     * exception_handler()
     *
     * Xử lý các exception chưa được bắt, đặc biệt là HttpException để giữ lại HTTP status code
     *
     * @param \Throwable $exception
     */
    public function exception_handler($exception)
    {
        // Thiết lập mã HTTP status dựa trên loại exception
        if ($exception instanceof HttpException) {
            http_response_code($exception->getHttpCode());
            $this->errno = 256; // Sử dụng 256 để kích hoạt hành vi info_die()
        } else {
            http_response_code(500);
            $this->errno = E_ERROR;
        }

        $this->errstr = self::format_str($exception->getMessage());
        $this->errfile = self::format_str($exception->getFile());
        $this->errline = $exception->getLine();
        $this->errtrace = self::format_trace($exception->getTraceAsString());
        $this->errid = md5(($this->errfile ?: '') . ($this->errline ?: '') . $this->errno);

        $this->log_control();

        if ($this->errno == 256) {
            $this->info_die();
        }

        if (NV_DEBUG) {
            exit('An error occurred while loading the page:<br /><pre><code>' . htmlspecialchars(print_r([
                'type' => get_class($exception),
                'message' => self::format_str($exception->getMessage()),
                'file' => self::format_str($exception->getFile()),
                'line' => $exception->getLine(),
                'trace' => self::format_str($exception->getTraceAsString())
            ], true), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</code></pre>');
        }

        $this->displayErrorPage();
    }

    /**
     * displayErrorPage()
     *
     * Hiển thị trang lỗi với thông tin liên hệ
     */
    private function displayErrorPage()
    {
        if (!empty($this->cfg['error_send_mail'])) {
            $strEncodedEmail = '';
            $strlen = strlen($this->cfg['error_send_mail']);
            for ($i = 0; $i < $strlen; ++$i) {
                $strEncodedEmail .= '&#' . ord($this->cfg['error_send_mail'][$i]) . ';';
            }
            $email = '<a href="mailto:' . $strEncodedEmail . '">let us know</a>';
        } else {
            $email = 'let us know';
        }

        if (isset(self::$errortype[$this->errno])) {
            exit('An error occurred while loading the page: ' . self::$errortype[$this->errno] . '(' . $this->errno . ').<br/>Please ' . $email . ' about this!');
        } else {
            exit('An error occurred while loading the page.<br/>Please ' . $email . ' about this!');
        }
    }
}
