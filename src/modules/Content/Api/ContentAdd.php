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
        global $db, $nv_Cache, $nv_Request, $nv_Lang, $module_config;

        $module_name = Api::getModuleName();
        $admin_id = Api::getAdminId();
        $config = $module_config[$module_name];

        $contentRepo = new ContentRepository(
            $db,
            $config['table_row'],
            $nv_Cache,
            $module_name
        );


        $service = new ContentService($contentRepo);

        // Parse request qua Service
        $data = $service->collectRequestData($nv_Request);

        // Chuẩn hóa dữ liệu qua Service
        $data = $service->prepareSaveData($data, $config);

        // Validate
        try {
            $validator = new ContentValidator($contentRepo);
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
            $config,
            $admin_id
        );

        // Lấy entity vừa tạo để trả về
        $entity = $contentRepo->findById($savedId);

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
