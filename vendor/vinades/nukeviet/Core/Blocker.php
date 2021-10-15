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

/**
 * NukeViet\Core\Blocker
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Blocker
{
    const INCORRECT_TEMPRORARY_DIRECTORY = 'Incorrect temprorary directory specified';
    const INCORRECT_IP_ADDRESS = 'Incorrect IP address specified';

    const LOGIN_RULE_NUMBER = 0;
    const LOGIN_RULE_TIMERANGE = 1;
    const LOGIN_RULE_END = 2;

    public $is_flooded;
    public $flood_block_time;
    public $login_block_end;

    private $logs_path;
    private $ip_addr;
    private $flood_rules = [
        10 => 10, // rule 1 - maximum 10 requests in 10 secs
        60 => 30, // rule 2 - maximum 30 requests in 60 secs
        300 => 50, // rule 3 - maximum 50 requests in 300 secs
        3600 => 200 // rule 4 - maximum 200 requests in 3600 secs
    ];
    private $login_rules = [5, 5, 1440];
    private $track_login = true;

    /**
     * __construct()
     *
     * @param string $logs_path
     * @param string $ip
     */
    public function __construct($logs_path, $ip = '')
    {
        if (!is_dir($logs_path)) {
            trigger_error(Blocker::INCORRECT_TEMPRORARY_DIRECTORY, E_USER_ERROR);
        }
        if (substr($logs_path, -1) != '/') {
            $logs_path .= '/';
        }

        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
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
        if ($ip2long == -1 or $ip2long === false) {
            trigger_error(Blocker::INCORRECT_IP_ADDRESS, E_USER_ERROR);
        }

        $this->logs_path = $logs_path;
        $this->ip_addr = $ip2long;
    }

    /**
     * trackFlood()
     *
     * @param array $rules
     */
    public function trackFlood($rules = [])
    {
        if (!empty($rules)) {
            $this->flood_rules = $rules;
        }

        $info = $this->_get_info();
        foreach ($this->flood_rules as $interval => $limit) {
            if (!isset($info['access'][$interval])) {
                $info['access'][$interval]['time'] = NV_CURRENTTIME;
                $info['access'][$interval]['count'] = 0;
            }

            ++$info['access'][$interval]['count'];

            if (NV_CURRENTTIME - $info['access'][$interval]['time'] > $interval) {
                $info['access'][$interval]['count'] = 1;
                $info['access'][$interval]['time'] = NV_CURRENTTIME;
            }

            if ($info['access'][$interval]['count'] > $limit) {
                $this->flood_block_time = 1 + (NV_CURRENTTIME - $info['access'][$interval]['time'] - $interval) * -1;
                $this->is_flooded = true;
            }
        }

        if (empty($this->is_flooded)) {
            $this->_save_info($info);
        }
    }

    /**
     * trackLogin()
     *
     * @param array $rules
     * @param int   $allowed
     */
    public function trackLogin($rules = [], $allowed = 1)
    {
        if (!empty($rules)) {
            $this->login_rules = $rules;
        }
        $this->track_login = $allowed ? true : false;
    }

    /**
     * is_blocklogin()
     *
     * @param string $loginname
     * @return bool
     */
    public function is_blocklogin($loginname)
    {
        if (!$this->track_login) {
            return false;
        }
        $blocked = false;

        if (!empty($loginname)) {
            $_loginname = md5($loginname);
            $info = $this->_get_info();

            if (isset($info['login'][$_loginname]) and $info['login'][$_loginname]['count'] >= $this->login_rules[Blocker::LOGIN_RULE_NUMBER]) {
                $this->login_block_end = $info['login'][$_loginname]['lasttime'] + ($this->login_rules[Blocker::LOGIN_RULE_END] * 60);
                if ($this->login_block_end > NV_CURRENTTIME) {
                    $blocked = true;
                }
            }
        }

        return $blocked;
    }

    /**
     * set_loginFailed()
     *
     * @param string $loginname
     * @param int    $time
     * @return true|void
     */
    public function set_loginFailed($loginname, $time = 0)
    {
        if (!$this->track_login) {
            return true;
        }
        if (empty($time)) {
            $time = time();
        }

        if (!empty($loginname)) {
            $loginname = md5($loginname);
            $info = $this->_get_info();

            if (!isset($info['login'][$loginname]) or ($time - $info['login'][$loginname]['starttime']) > ($this->login_rules[Blocker::LOGIN_RULE_TIMERANGE] * 60)) {
                $info['login'][$loginname] = [];
                $info['login'][$loginname]['count'] = 0;
                $info['login'][$loginname]['starttime'] = $time;
                $info['login'][$loginname]['lasttime'] = 0;
            }

            ++$info['login'][$loginname]['count'];
            $info['login'][$loginname]['lasttime'] = $time;

            $this->_save_info($info);
        }
    }

    /**
     * reset_trackLogin()
     *
     * @param string $loginname
     */
    public function reset_trackLogin($loginname)
    {
        if (!empty($loginname)) {
            $loginname = md5($loginname);
            $info = $this->_get_info();
            unset($info['login'][$loginname]);
            $this->_save_info($info);
        }
    }

    /**
     * resetTrackFlood()
     */
    public function resetTrackFlood()
    {
        $info = $this->_get_info();
        if (isset($info['access'])) {
            unset($info['access']);
        }
        $this->_save_info($info);
    }

    /**
     * _get_info()
     *
     * @return mixed
     */
    private function _get_info()
    {
        $info = [];
        $logfile = $this->_get_logfile();
        if (file_exists($logfile)) {
            $info = unserialize(file_get_contents($logfile));
        }

        return $info;
    }

    /**
     * _save_info()
     *
     * @param mixed $info
     * @return false|int
     */
    private function _save_info($info)
    {
        $logfile = $this->_get_logfile();

        return file_put_contents($logfile, serialize($info));
    }

    /**
     * _get_logfile()
     *
     * @return string
     */
    private function _get_logfile()
    {
        return $this->logs_path . $this->ip_addr . '.' . NV_LOGS_EXT;
    }
}
