<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace Tests\modules\Content\Unit;

use NukeViet\Module\Content\Content\ContentEntity;
use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentService;
use Tests\Support\UnitTester;

class ContentServiceTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;
    private $repo;
    private $service;

    protected function _before()
    {
        if (!defined('NV_MAINFILE')) {
            define('NV_MAINFILE', true);
        }

        // Mock Repository
        $this->repo = $this->makeEmpty(ContentRepository::class, [
            'findById' => function ($id) {
                if ($id == 123) {
                    return ContentEntity::fromArray([
                        'id' => 123,
                        'title' => 'Original Title',
                        'alias' => 'original-alias',
                        'status' => 1
                    ]);
                }
                return null;
            },
            'save' => function($data, $id) {
                return $id > 0 ? $id : 999;
            }
        ]);
        $this->service = new ContentService($this->repo);
    }

    /**
     * Test lấy chi tiết thành công
     */
    public function testGetDetailSuccess()
    {
        $entity = $this->service->getDetail(123);
        $this->assertInstanceOf(ContentEntity::class, $entity);
        $this->assertEquals('Original Title', $entity->title);
    }

    /**
     * Test logic sao chép (Duplication logic)
     */
    public function testDuplicateContentData()
    {
        $source = $this->service->getDetail(123);
        $copyData = $this->service->duplicateContentData($source);

        $this->assertEquals(0, $copyData['id']);
        $this->assertEquals('Original Title (Copy)', $copyData['title']);
        $this->assertEquals('', $copyData['alias']);
        $this->assertEquals(0, $copyData['status']);
    }

    /**
     * Test trích xuất Request Data (Config)
     */
    public function testCollectConfigData()
    {
        // Giả lập nv_Request
        $requestMock = $this->makeEmpty(\NukeViet\Core\Request::class, [
            'get_int' => function($key) { return $key === 'viewtype' ? 1 : 0; },
            'get_string' => function($key) { return $key === 'facebookapi' ? 'FB-API' : ''; },
            'get_page' => function() { return 20; },
            'get_typed_array' => function() { return []; }
        ]);

        $config = $this->service->collectConfigData($requestMock);
        $this->assertEquals(1, $config['viewtype']);
        $this->assertEquals('FB-API', $config['facebookapi']);
    }
}
