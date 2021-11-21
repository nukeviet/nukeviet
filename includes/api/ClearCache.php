<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @package NukeViet\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2010-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class ClearCache implements IApi
{
    private $result;

    /**
     * getAdminLev()
     *
     * @return int
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    /**
     * getCat()
     *
     * @return string
     */
    public static function getCat()
    {
        return 'webtools';
    }

    /**
     * setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * execute()
     *
     * @return mixed
     */
    public function execute()
    {
        global $db, $nv_Cache, $global_config;

        if ($dh = opendir(NV_ROOTDIR . '/' . NV_CACHEDIR)) {
            while (($modname = readdir($dh)) !== false) {
                if (preg_match($global_config['check_module'], $modname)) {
                    $cacheDir = NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname;
                    $files = $this->nv_clear_files($cacheDir, NV_CACHEDIR . '/' . $modname);
                }
            }
            closedir($dh);
        }
        $nv_Cache->delAll();

        $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . NV_CURRENTTIME . "' WHERE lang = 'sys' AND module = 'global' AND config_name = 'timestamp'");
        nv_save_file_config_global();

        $this->result->setSuccess();

        return $this->result->getResult();
    }

    /**
     * nv_clear_files()
     *
     * @param string $dir
     * @param string $base
     * @return array
     */
    private function nv_clear_files($dir, $base)
    {
        $dels = [];
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!preg_match("/^[\.]{1,2}([a-zA-Z0-9]*)$/", $file) and $file != 'index.html' and is_file($dir . '/' . $file)) {
                    if (unlink($dir . '/' . $file)) {
                        $dels[] = $base . '/' . $file;
                    }
                }
            }
            closedir($dh);
        }
        if (!file_exists($dir . '/index.html')) {
            file_put_contents($dir . '/index.html', '');
        }

        return $dels;
    }
}
