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
//$blockers=array("ssss");

class UsersInfo implements IApi
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
     
		$timestamp = $nv_Request->get_int('timestamp', 'post', '');
		$http_authorization = explode(":",base64_decode($_SERVER["HTTP_" .  $timestamp]));
		$access_token = NV_CHECK_SESSION;
		$userid = $http_authorization[0];
        $check_access_token = $http_authorization[1];
		$data =array(
					'userid' => $row['userid'],
					'username' => $row['username'],
					'email' => $row['email'],
					'first_name' => $row['first_name'],
					'last_name' => $row['last_name'],
					'gender' => $row['gender'],
					'photo' => $row['photo'],
					'birthday' => $row['birthday'],
					'active' => $row['active'],
					'access_token' => $access_token
						
				);
		if(intval($userid) > 0){
			$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_info['module_data'] . " WHERE userid =" . intval($userid) . "";
			$row = $db->query($sql)->fetch();
			
				$data =array(
					'userid' => intval($row['userid']),
					'username' => $row['username'],
					'email' => $row['email'],
					'first_name' => $row['first_name'],
					'last_name' => $row['last_name'],
					'gender' => intval($row['gender']),
					'photo' => 'b',
					'birthday' => $row['birthday'],
					'active' => intval($row['active']),
					'access_token' => $access_token,
					'QuanTriHeThong' => 1
						
				);
				$this->result->set('message', $data);

		}else{
			$this->result->set('message', $http_authorization);
		}

        $this->result->setSuccess();
 
        return $this->result->getResult();
    }
}
