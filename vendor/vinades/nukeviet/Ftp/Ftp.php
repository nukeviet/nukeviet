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

// Ngon ngu
define('NV_FTP_ERR_CONNECT', isset($lang_global['ftp_err_connect']) ? $lang_global['ftp_err_connect'] : 'Error: Couldn\'t connect to FTP server');
define('NV_FTP_ERR_LOGIN', isset($lang_global['ftp_err_login']) ? $lang_global['ftp_err_login'] : 'Error: Couldn\'t login with this account');
define('NV_FTP_ERR_DISABLED_FTP', isset($lang_global['ftp_err_enable']) ? $lang_global['ftp_err_enable'] : 'Error: Your system unsuport FTP extension');
define('NV_FTP_ERR_PASSIVE_ON', isset($lang_global['ftp_err_passive_on']) ? $lang_global['ftp_err_passive_on'] : 'Error: Could\'n turn passive mode on');
define('NV_FTP_ERR_RAWLIST', isset($lang_global['ftp_err_rawlist']) ? $lang_global['ftp_err_rawlist'] : 'Error: Rawlist bad');
define('NV_FTP_ERR_LISTDETAIL_NOTRECONIZE', isset($lang_global['ftp_err_list_detail']) ? $lang_global['ftp_err_list_detail'] : 'Error: Notreconize type of OS');
define('NV_FTP_ERR_FGET', isset($lang_global['ftp_err_fget']) ? $lang_global['ftp_err_fget'] : 'Error get file');
define('NV_FTP_ERR_BUFFER_CLASS', isset($lang_global['ftp_err_NVbuffet']) ? $lang_global['ftp_err_NVbuffet'] : 'Error not exist NVbuffer class');

// FTP mode
if (!defined('FTP_AUTOASCII')) {
    define('FTP_AUTOASCII', -1);
}
if (!defined('FTP_BINARY')) {
    define('FTP_BINARY', 1);
}
if (!defined('FTP_ASCII')) {
    define('FTP_ASCII', 0);
}

/**
 * NukeViet\Ftp\Ftp
 *
 * @package NukeViet\Ftp
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Ftp
{
    // Thong tin dang nhap
    private $host = 'localhost';

    private $port = 21;

    private $user = 'root';

    private $pass = '';

    // Du lieu FTP connect
    private $conn_id;

    // Cau hinh chung
    private $config = [
        'timeout' => 90, // Thoi gian het han ket noi
        'type' => FTP_BINARY, // Kieu
        'os' => 'UNIX' // He dieu hanh
    ];

    // Du lieu xuat ra, du lieu kiem tra
    public $error = '';

    public $logined = false;

    // Loai file xac nhan trong mode FTP_AUTOASCII
    private $AutoAscii = [
        'asp',
        'bat',
        'c',
        'cpp',
        'csv',
        'h',
        'htm',
        'html',
        'shtml',
        'ini',
        'inc',
        'log',
        'php',
        'php3',
        'pl',
        'perl',
        'sh',
        'sql',
        'txt',
        'xhtml',
        'xml'
    ];

    /**
     * __construct()
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param array  $config
     * @param int    $port
     * @return false|void
     */
    public function __construct($host = '', $user = 'root', $pass = '', $config = [], $port = 21)
    {
        // Kiem tra thu vien FTP hoat dong
        $disable_functions = (ini_get('disable_functions') != '' and ini_get('disable_functions') != false) ? array_map('trim', preg_split("/[\s,]+/", ini_get('disable_functions'))) : [];
        if (extension_loaded('suhosin')) {
            $disable_functions = array_merge($disable_functions, array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
        }

        if (!(extension_loaded('ftp') and (empty($disable_functions) or (!empty($disable_functions) and !preg_grep('/^ftp\_/', $disable_functions))))) {
            $this->error = NV_FTP_ERR_DISABLED_FTP;

            return false;
        }
        unset($disable_functions);

        if (!empty($host)) {
            $this->host = $host;
        }
        if (!empty($user)) {
            $this->user = $user;
        }
        if (!empty($pass)) {
            $this->pass = $pass;
        }
        if (!empty($port)) {
            $this->port = $port;
        }

        // Xac dinh thoi gian het han
        if (!empty($config['timeout']) and $config['timeout'] > 0) {
            $this->config['timeout'] = (int) ($config['timeout']);
        }

        // Xac dinh phuong thuc
        if (isset($config['type']) and in_array($config['type'], [
            FTP_BINARY,
            FTP_AUTOASCII,
            FTP_ASCII
        ], true)) {
            $this->config['type'] = $config['type'];
        }

        // Xac dinh he hieu hanh
        if (!empty($config['os']) and in_array($config['os'], [
            'WIN',
            'UNIX',
            'MAC'
        ], true)) {
            $this->config['os'] = $config['os'];
        } else {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $this->config['os'] = 'WIN';
            } elseif (strtoupper(substr(PHP_OS, 0, 3)) === 'MAC') {
                $this->config['os'] = 'MAC';
            } else {
                $this->config['os'] = 'UNIX';
            }
        }

        // Ket noi den FTP server
        if (!is_resource($this->conn_id)) {
            $this->conn_id = ftp_connect($this->host, $this->port);

            if ($this->conn_id === false) {
                $this->error = NV_FTP_ERR_CONNECT;

                return false;
            }

            ftp_set_option($this->conn_id, FTP_TIMEOUT_SEC, $this->config['timeout']);
        }

        // Dang nhap
        if (ftp_login($this->conn_id, $this->user, $this->pass) === false) {
            $this->error = NV_FTP_ERR_LOGIN;

            return false;
        }
        $this->logined = true;
    }

    /**
     * check_login()
     *
     * @return bool
     */
    private function check_login()
    {
        if (is_resource($this->conn_id) and $this->logined) {
            return true;
        }

        return false;
    }

    /**
     * detectFtpRoot()
     *
     * @param array  $list_valid
     * @param string $path_root
     * @param bool   $read_buffer
     * @param string $read_file
     * @return mixed
     */
    public function detectFtpRoot($list_valid = [], $path_root = '', $read_buffer = true, $read_file = 'index.php')
    {
        if (!$this->check_login()) {
            return false;
        }

        $cwd = $this->pwd();
        $cwd = rtrim($cwd, '/');

        $list_folder = $this->listDetail(null, 'folders');

        if (empty($list_folder)) {
            return false;
        }

        // Chi lay ten thu muc
        for ($i = 0, $n = sizeof($list_folder); $i < $n; ++$i) {
            $list_folder[$i] = $list_folder[$i]['name'];
        }

        if (!is_array($list_valid)) {
            $list_valid = [
                $list_valid
            ];
        }

        $paths = [];

        // Neu cac file kiem tra dat ngay thu muc dang tro den
        if (sizeof(array_diff($list_valid, $list_folder)) == 0) {
            $paths[] = $cwd . '/';
        }

        // Tim kiem cac thu muc khac tro den duong dan chi dinh
        $parts = explode('/', $path_root);
        $tmp = '';

        for ($i = sizeof($parts) - 1; $i >= 0; --$i) {
            $tmp = '/' . $parts[$i] . $tmp;

            if (in_array($parts[$i], $list_folder, true)) {
                $paths[] = $cwd . $tmp;
            }
        }

        if ($read_buffer === true) {
            $return_path = false;
            $check_value = file_get_contents($path_root . '/' . $read_file);

            foreach ($paths as $tmp) {
                $filePath = rtrim($tmp, '/') . '/' . $read_file;
                $buffer = null;

                $this->read($filePath, $buffer);

                if ($buffer == $check_value) {
                    $return_path = $tmp;
                    break;
                }
            }

            return $return_path;
        }

        return $paths[0];
    }

    /**
     * listDetail()
     *
     * @param mixed|null $path
     * @param string     $type
     * @param bool       $show_hidden
     * @return array|false
     */
    public function listDetail($path = null, $type = 'raw', $show_hidden = false)
    {
        if (!$this->check_login()) {
            return false;
        }

        // Bat passive mode
        if (ftp_pasv($this->conn_id, true) === false) {
            $this->error = NV_FTP_ERR_PASSIVE_ON;

            return false;
        }

        // Danh sach chi tiet thu muc
        $cmd_path = $show_hidden ? '-al ' . $path : $path;
        $list_detail = ftp_rawlist($this->conn_id, $cmd_path);

        if ($list_detail === false) {
            $this->error = NV_FTP_ERR_RAWLIST;

            return false;
        }

        $dir_list = [];

        if ($type == 'raw') {
            return $list_detail;
        }
        if (empty($list_detail[0])) {
            return $dir_list;
        }

        if (strtolower(substr($list_detail[0], 0, 6)) == 'total ') {
            array_shift($list_detail);
            if (!isset($list_detail[0]) or empty($list_detail[0])) {
                return $dir_list;
            }
        }

        // Xac dinh chuan dinh dang cua 3 he dieu hanh
        $regexps = [
            'UNIX' => '#([-dl][rwxstST-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{1,2}:[0-9]{2})|[0-9]{4}) (.+)#',
            'MAC' => '#([-dl][rwxstST-]+).* ?([0-9 ]*)?([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)#',
            'WIN' => '#([0-9]{2})-([0-9]{2})-([0-9]{2}) +([0-9]{2}):([0-9]{2})(AM|PM) +([0-9]+|<DIR>) +(.+)#'
        ];

        // Xac dinh he dieu hanh thich hop
        $osType = null;

        foreach ($regexps as $k => $v) {
            if (preg_match($v, $list_detail[0])) {
                $osType = $k;
                $regexp = $v;
                break;
            }
        }

        if (!$osType) {
            $this->error = NV_FTP_ERR_LISTDETAIL_NOTRECONIZE;

            return false;
        }

        if ($osType == 'UNIX') {
            foreach ($list_detail as $file) {
                $tmp_array = null;

                if (preg_match($regexp, $file, $regs)) {
                    $fType = (int) strpos('-dl', $regs[1][0]);

                    $tmp_array['type'] = $fType;
                    $tmp_array['rights'] = $regs[1];

                    $tmp_array['user'] = $regs[3];
                    $tmp_array['group'] = $regs[4];
                    $tmp_array['size'] = $regs[5];
                    $tmp_array['date'] = strtotime($regs[6]);
                    $tmp_array['time'] = $regs[7];
                    $tmp_array['name'] = $regs[9];
                }

                if ($type != 'all') {
                    if ($type == 'files' and $tmp_array['type'] == 1) {
                        continue;
                    }
                    if ($type == 'folders' and $tmp_array['type'] == 0) {
                        continue;
                    }
                }

                if (is_array($tmp_array) and $tmp_array['name'] != '.' and $tmp_array['name'] != '..') {
                    $dir_list[] = $tmp_array;
                }
            }
        } elseif ($osType == 'MAC') {
            foreach ($list_detail as $file) {
                $tmp_array = null;

                if (preg_match($regexp, $file, $regs)) {
                    $fType = (int) strpos('-dl', $regs[1][0]);

                    $tmp_array['type'] = $fType;
                    $tmp_array['rights'] = $regs[1];

                    $tmp_array['user'] = $regs[3];
                    $tmp_array['group'] = $regs[4];
                    $tmp_array['size'] = $regs[5];
                    $tmp_array['date'] = strtotime($regs[6]);
                    $tmp_array['time'] = $regs[7];
                    $tmp_array['name'] = $regs[9];
                }

                if ($type != 'all') {
                    if ($type == 'files' and $tmp_array['type'] == 1) {
                        continue;
                    }
                    if ($type == 'folders' and $tmp_array['type'] == 0) {
                        continue;
                    }
                }

                if (is_array($tmp_array) and $tmp_array['name'] != '.' and $tmp_array['name'] != '..') {
                    $dir_list[] = $tmp_array;
                }
            }
        } else {
            foreach ($list_detail as $file) {
                $tmp_array = null;

                if (preg_match($regexp, $file, $regs)) {
                    $fType = (int) ($regs[7] == '<DIR>');
                    $timestamp = strtotime("$regs[3]-$regs[1]-$regs[2] $regs[4]:$regs[5]$regs[6]");

                    $tmp_array['type'] = $fType;
                    $tmp_array['rights'] = '';

                    $tmp_array['user'] = '';
                    $tmp_array['group'] = '';
                    $tmp_array['size'] = (int) $regs[7];
                    $tmp_array['date'] = $timestamp;
                    $tmp_array['time'] = $timestamp;
                    $tmp_array['name'] = $regs[8];
                }

                if ($type != 'all') {
                    if ($type == 'files' and $tmp_array['type'] == 1) {
                        continue;
                    }
                    if ($type == 'folders' and $tmp_array['type'] == 0) {
                        continue;
                    }
                }

                if (is_array($tmp_array) and $tmp_array['name'] != '.' and $tmp_array['name'] != '..') {
                    $dir_list[] = $tmp_array;
                }
            }
        }

        return $dir_list;
    }

    /**
     * read()
     *
     * @param mixed $remote
     * @param mixed $buffer
     * @return bool
     */
    public function read($remote, &$buffer)
    {
        $mode = $this->DetectedMode($remote);

        // Bat passive mode on
        if (ftp_pasv($this->conn_id, true) === false) {
            $this->error = NV_FTP_ERR_PASSIVE_ON;

            return false;
        }

        if (!in_array('nvbuffer', stream_get_wrappers(), true)) {
            stream_wrapper_register('nvbuffer', 'NukeViet\Ftp\Buffer');
        }

        $tmp = fopen('nvbuffer://tmp', 'br+');
        if (ftp_fget($this->conn_id, $tmp, $remote, $mode) === false) {
            fclose($tmp);
            $this->error = NV_FTP_ERR_FGET;

            return false;
        }

        rewind($tmp);

        $buffer = '';
        while (!feof($tmp)) {
            $buffer .= fread($tmp, 8192);
        }

        fclose($tmp);

        return true;
    }

    /**
     * pwd()
     *
     * @return false|string
     */
    public function pwd()
    {
        if (!$this->check_login()) {
            return false;
        }

        return ftp_pwd($this->conn_id);
    }

    /**
     * mkdir()
     *
     * @param mixed $dir
     * @return false|string
     */
    public function mkdir($dir)
    {
        if (!$this->check_login()) {
            return false;
        }

        return ftp_mkdir($this->conn_id, $dir);
    }

    /**
     * unlink()
     *
     * @param mixed $file
     * @return bool
     */
    public function unlink($file)
    {
        if (!$this->check_login()) {
            return false;
        }

        return ftp_delete($this->conn_id, $file);
    }

    /**
     * rmdir()
     *
     * @param mixed $dir
     * @return bool
     */
    public function rmdir($dir)
    {
        if (!$this->check_login()) {
            return false;
        }

        return ftp_rmdir($this->conn_id, $dir);
    }

    /**
     * rename()
     *
     * @param mixed $old
     * @param mixed $new
     * @return bool
     */
    public function rename($old, $new)
    {
        if (!$this->check_login()) {
            return false;
        }

        return ftp_rename($this->conn_id, $old, $new);
    }

    /**
     * chdir()
     *
     * @param mixed $path
     * @return bool
     */
    public function chdir($path)
    {
        if (!$this->check_login()) {
            return false;
        }

        if (ftp_chdir($this->conn_id, $path) === false) {
            return false;
        }

        return true;
    }

    /**
     * close()
     *
     * @return false|void
     */
    public function close()
    {
        if (!is_resource($this->conn_id)) {
            return false;
        }

        ftp_close($this->conn_id);
    }

    /**
     * DetectedMode()
     *
     * @param mixed $fileName
     * @return int
     */
    protected function DetectedMode($fileName)
    {
        if ($this->config['type'] == FTP_AUTOASCII) {
            $dot = strrpos($fileName, '.') + 1;
            $ext = substr($fileName, $dot);

            if (in_array($ext, $this->AutoAscii, true)) {
                $mode = FTP_ASCII;
            } else {
                $mode = FTP_BINARY;
            }
        } elseif ($this->config['type'] == FTP_ASCII) {
            $mode = FTP_ASCII;
        } else {
            $mode = FTP_BINARY;
        }

        return $mode;
    }
}
