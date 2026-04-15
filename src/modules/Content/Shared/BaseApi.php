<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Shared;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

abstract class BaseApi implements IApi
{
    protected ApiResult $result;
    protected \PDO $db;
    protected Tables $tables;
    protected $cache;
    protected string $module_name;
    protected string $module_upload;
    protected array $config;

    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    protected function bootstrap(): void
    {
        global $db, $nv_Cache, $module_config;

        $this->db = $db;
        $this->cache = $nv_Cache;
        $this->module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $this->tables = new Tables(NV_PREFIXLANG, $module_info['module_data']);
        $this->module_upload = $module_info['module_upload'];
        $this->config = $module_config[$this->module_name];
    }
}
