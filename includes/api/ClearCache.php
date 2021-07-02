<?php

/**
 * @Project NUKEVIET 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @createdate Jun 20, 2010 8:59:32 PM
 */

namespace NukeViet\Api;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class ClearCache implements IApi
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
        return 'webtools';
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $db, $nv_Cache;
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
