<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatValidator;
use NukeViet\Module\Content\Cat\CatService;
use NukeViet\Module\Content\Shared\Tables;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class CatAdd implements IApi
{
    private $result;

    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    public static function getCat()
    {
        return 'content';
    }

    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    public function execute()
    {
        global $db, $nv_Cache, $nv_Request, $nv_Lang, $module_config, $site_mods;

        $module_name = Api::getModuleName();
        $config = $module_config[$module_name];
        $tables = new Tables(NV_PREFIXLANG, $site_mods[$module_name]['module_data']);

        $repo = new CatRepository(
            $db,
            $tables,
            $nv_Cache,
            $module_name
        );

        $catService = new CatService($repo);

        // Parse request — trích xuất dữ liệu qua Service (DRY)
        $data = $catService->collectRequestData($nv_Request);

        // Chuẩn hóa dữ liệu (alias, keywords, image) qua Service — DRY
        $data = $catService->prepareSaveData($data, $config);

        // Validate
        try {
            $validator = new CatValidator($repo);
            $validator->validateSave($data);
        } catch (\InvalidArgumentException $e) {
            $this->result->setCode(ApiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getModule($e->getMessage()));
            return $this->result->getResult();
        }

        // Lưu qua Service (weight + timestamps tự động)
        $savedId = $catService->saveCat($data, 0, $module_name);

        // Lấy entity vừa tạo để trả về
        $entity = $repo->findById($savedId);

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
