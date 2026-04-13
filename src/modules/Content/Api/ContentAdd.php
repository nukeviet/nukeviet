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
use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentValidator;
use NukeViet\Module\Content\Content\ContentService;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * ContentAdd API - Thêm bài viết mới qua API
 * Cho phép các ứng dụng/site khác kết nối và đẩy nội dung bài viết
 */
class ContentAdd implements IApi
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
        global $db, $nv_Cache, $nv_Request, $nv_Lang, $admin_info;

        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];

        $repo = new ContentRepository(
            $db,
            NV_PREFIXLANG . '_' . $module_data,
            $nv_Cache,
            $module_name
        );

        $service = new ContentService($repo);
        $moduleConfig = $repo->getConfig();

        // Parse request qua Service
        $data = $service->collectRequestData($nv_Request);

        // Chuẩn hóa dữ liệu qua Service
        $data = $service->prepareSaveData($data, $moduleConfig);

        // Validate
        try {
            $validator = new ContentValidator($repo);
            $validator->validateSave($data);
        } catch (\InvalidArgumentException $e) {
            $this->result->setCode(ApiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getModule($e->getMessage()));
            return $this->result->getResult();
        }

        // Lưu qua Service
        $savedId = $service->saveContent(
            $data, 
            0, 
            $module_name, 
            $moduleConfig, 
            $admin_info['admin_id'] ?? 0
        );

        // Lấy entity vừa tạo để trả về
        $entity = $repo->findById($savedId);

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
