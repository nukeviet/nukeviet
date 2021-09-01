<?php
 
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jun 20, 2010 8:59:32 PM
 */
 
namespace NukeViet\Module\users\Api;
use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
 
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}
class Login implements IApi
{
    private $result;
 
    /**
     * @return number
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    } 
    /**
     * @return string
     */
    public static function getCat()
    {
        return 'users';
    }
    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }
    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $db, $nv_Cache, $global_config, $nv_Request,$lang_global,$lang_module, $db_config, $crypt;
		    $module_name = Api::getModuleName();
		    $module_info = Api::getModuleInfo();
        $username = $_SERVER["PHP_AUTH_USER"];
        $password = $_SERVER["PHP_AUTH_PW"];
		    $md5uname = md5($uname);
		    $nv_username = $username;
		    $nv_password =  $password;
        if (empty($nv_username)) {
          $this->result->setError($lang_global['username_empty']);
        }
        if (empty($nv_password)) {
          $this->result->setError($lang_global['password_empty']);
        } 

        if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
          $error = '';
          require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/login.php';
          if (!empty($error)) {
            $this->result->setError($error);
          }
        } else {
          $error1 = $lang_global['loginincorrect'];
          require_once NV_ROOTDIR . '/includes/functions.php';
          $check_email = nv_check_valid_email($nv_username, true);
          if ($check_email[0] == '') {
            // Email login
            $nv_username = $check_email[1];
            $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_info['module_data'] . ' WHERE email =' . $db->quote($nv_username);
            $login_email = true;
          } else {
            // Username login
            $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_info['module_data'] . " WHERE md5username ='" . nv_md5safe($nv_username) . "'";
            $login_email = false;
          }

          $row = $db->query($sql)->fetch();

          if (!empty($row)) {
            if ((($row['md5username'] == nv_md5safe($nv_username) and $login_email == false) or ($row['email'] == $nv_username and $login_email == true)) and $crypt->validate_password($nv_password, $row['password'])) {
              if (!$row['active']) {
                $error1 = $lang_module['login_no_active'];
              } else {
                if (empty($error1)) 
                  $nv_Request->unset_request('users_dismiss_captcha', 'session');
                }
              }
            }
          }

          if (!empty($error1)) {
            $this->result->setError($error1);

          } elseif (empty($row['active2step'])) {
            $_2step_require = in_array((int) $global_config['two_step_verification'], [
              2,
              3
            ], true);
            if (!$_2step_require) {
              $_2step_require = nv_user_groups($row['in_groups'], true);
              $_2step_require = $_2step_require[1];
            }
            if ($_2step_require) {
              $this->result->setError($lang_global['2teplogin_require']);
            }
          }
        } 
        $data =array(
          'userid' => $row['userid'],
          'username' => $row['username'],
          'email' => $row['email'],
          'first_name' => $row['first_name'],
          'last_name' => $row['last_name'],
          'gender' => $row['gender'],
          'photo' => $row['photo'],
          'birthday' => $row['birthday'],
          'active' => $row['active']
        );
        $this->result->set('data', $data);
        $this->result->setSuccess();
 
        return $this->result->getResult();
    }
}
