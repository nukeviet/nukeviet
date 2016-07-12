<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/27/2010 0:30
 */

namespace NukeViet\Core;

/**
 * Blocker
 * 
 * @package NUKEVIET 4 CORE
 * @author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @version 4.0
 * @access public
 */
class Blocker
{
    const INCORRECT_TEMPRORARY_DIRECTORY = 'Incorrect temprorary directory specified';
    const INCORRECT_IP_ADDRESS = 'Incorrect IP address specified';

    public $is_flooded;
    public $flood_block_time;

    private $logs_path;
    private $ip_addr;
    private $flood_rules = array(
        10 => 10, // rule 1 - maximum 10 requests in 10 secs
        60 => 30, // rule 2 - maximum 30 requests in 60 secs
        300 => 50, // rule 3 - maximum 50 requests in 300 secs
        3600 => 200 // rule 4 - maximum 200 requests in 3600 secs
    );

    /**
     * Blocker::__construct()
     *
     * @param mixed $logs_path
     * @param mixed $rules
     * @param string $ip
     * @return void
     */
    public function __construct($logs_path, $rules = array(), $ip = '')
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
     * Blocker::trackFlood()
     * 
     * @param mixed $rules
     * @return void
     */
    public function trackFlood($rules = array())
    {
        if (!empty($rules)) {
            $this->flood_rules = $rules;
        }

        $info = array();
        $logfile = $this->logs_path . $this->ip_addr . '.' . NV_LOGS_EXT;
        if (file_exists($logfile)) {
            $info = unserialize(file_get_contents($logfile));
        }

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
            file_put_contents($logfile, serialize($info));
        }
    }
}
