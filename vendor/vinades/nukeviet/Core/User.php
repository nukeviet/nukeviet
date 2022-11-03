<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * NukeViet\Core\User
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class User
{
    public function __construct()
    {
    }

    /**
     * set_userlogin_hash()
     *
     * @param array $data
     * @param bool  $remember
     */
    public static function set_userlogin_hash($data, $remember = false)
    {
        global $nv_Request, $global_config;

        $live_cookie_time = $remember ? NV_LIVE_COOKIE_TIME : 0;
        $data['loghash_time'] = NV_CURRENTTIME;
        $nvloginhash_name = substr($data['checknum'], 5, 8);
        $nvloginhash_name_encrypt = 'l' . substr(md5($nvloginhash_name . $global_config['sitekey']), 8, 10);
        $nv_Request->set_Cookie('lghm', $nvloginhash_name, $live_cookie_time);
        $nv_Request->set_Cookie($nvloginhash_name_encrypt, json_encode($data), $live_cookie_time);
    }

    /**
     * unset_userlogin_hash()
     */
    public static function unset_userlogin_hash()
    {
        global $nv_Request, $global_config;

        $nvloginhash_name = $nv_Request->get_title('lghm', 'cookie', '');
        if (!empty($nvloginhash_name)) {
            $nvloginhash_name_encrypt = 'l' . substr(md5($nvloginhash_name . $global_config['sitekey']), 8, 10);
            $nv_Request->unset_request($nvloginhash_name_encrypt, 'cookie');
            $nv_Request->unset_request('lghm', 'cookie');
        }
    }

    /**
     * get_userlogin_hash()
     * 
     * @return array 
     */
    public static function get_userlogin_hash()
    {
        global $nv_Request, $global_config;

        if (!$nv_Request->isset_request('lghm', 'cookie')) {
            return [];
        }

        $nvloginhash_name = $nv_Request->get_title('lghm', 'cookie', '');
        if (empty($nvloginhash_name)) {
            $nv_Request->unset_request('lghm', 'cookie');

            return [];
        }

        $nvloginhash_name_encrypt = 'l' . substr(md5($nvloginhash_name . $global_config['sitekey']), 8, 10);
        if (!$nv_Request->isset_request($nvloginhash_name_encrypt, 'cookie')) {
            print_r($nvloginhash_name_encrypt);
            exit;
            $nv_Request->unset_request('lghm', 'cookie');

            return [];
        }

        $user = $nv_Request->get_string($nvloginhash_name_encrypt, 'cookie', '');
        $user = json_decode($user, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $nv_Request->unset_request('lghm', 'cookie');
            $nv_Request->unset_request($nvloginhash_name_encrypt, 'cookie');

            return [];
        }

        $user['loginhash'] = $nvloginhash_name;

        return $user;
    }
}
