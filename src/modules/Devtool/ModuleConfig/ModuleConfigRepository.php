<?php
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\ModuleConfig;

use PDO;

/**
 * Class ModuleConfigRepository
 * @package NukeViet\Module\Devtool\ModuleConfig
 */
class ModuleConfigRepository
{
    private PDO $db;
    private string $dataPath;

    /**
     * ModuleConfigRepository constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->dataPath = NV_ROOTDIR . '/data/devtool/';
    }

    /**
     * Lấy danh sách các config_name của một module từ database
     * @param string $module
     * @param string $lang
     * @return array
     */
    public function getModuleConfigKeys(string $module, string $lang): array
    {
        $sql = "SELECT config_name FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang = :lang AND module = :module";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':lang', $lang);
        $stmt->bindParam(':module', $module);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Lưu metadata cấu hình vào file JSON
     * @param ModuleConfigEntity $entity
     * @return bool
     */
    public function saveMetadata(ModuleConfigEntity $entity): bool
    {
        $module = $entity->getModule();
        if (empty($module)) {
            return false;
        }
        $filename = $this->dataPath . 'config_' . $module . '.json';
        $content = json_encode($entity->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (!is_dir($this->dataPath)) {
            nv_mkdir($this->dataPath, 0755, true);
        }

        $result = file_put_contents($filename, $content);
        if ($result === false) {
            trigger_error('Failed to write file: ' . $filename, E_USER_WARNING);
            return false;
        }
        return true;
    }

    /**
     * Đọc metadata cấu hình từ file JSON
     * @param string $module
     * @return ModuleConfigEntity|null
     */
    public function loadMetadata(string $module): ?ModuleConfigEntity
    {
        $filename = $this->dataPath . 'config_' . $module . '.json';
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $data = json_decode($content, true);
            if (is_array($data)) {
                return ModuleConfigEntity::fromArray($data);
            }
        }
        return null;
    }
}
