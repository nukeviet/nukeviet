<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\content\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use PDO;

/**
 * ConfigRepositoryTrait — Trait dùng chung cho các Repository cần quản lý cấu hình module.
 *
 * Yêu cầu class sử dụng phải có các property: $db (PDO), $table (string), $cache, $module_name (string).
 * Bảng config có dạng: {$table}_config (config_name, config_value).
 */
trait ConfigRepositoryTrait
{
    /**
     * Đọc cấu hình module từ DB (có cache)
     */
    public function getConfig(): array
    {
        $sql = 'SELECT config_name, config_value FROM ' . $this->table . '_config';
        $list = $this->cache->db($sql, '', $this->module_name);
        $config = [];
        foreach ($list as $values) {
            $config[$values['config_name']] = $values['config_value'];
        }
        return $config;
    }

    /**
     * Lưu cấu hình module
     */
    public function saveConfig(array $config): void
    {
        $sth = $this->db->prepare('UPDATE ' . $this->table . '_config SET config_value = :config_value WHERE config_name = :config_name');
        foreach ($config as $config_name => $config_value) {
            $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }
    }
}
