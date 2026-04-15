<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Shared;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

abstract class BaseUapi implements UiApi
{
    protected UapiResult $result;
    protected \PDO $db;
    protected Tables $tables;
    protected $cache;
    protected string $module_name;
    protected array $config;

    public function setResultHander(UapiResult $result)
    {
        $this->result = $result;
    }

    protected function bootstrap(): void
    {
        global $db, $nv_Cache, $module_config, $site_mods;

        $this->db = $db;
        $this->cache = $nv_Cache;
        $this->module_name = Uapi::getModuleName();
        $this->tables = new Tables(NV_PREFIXLANG, $site_mods[$this->module_name]['module_data']);
        $this->config = $module_config[$this->module_name];
    }
}
