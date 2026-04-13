<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\modules\Content\Unit;

use NukeViet\Module\Content\Cat\CatEntity;
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatService;
use Tests\Support\UnitTester;

class CatServiceTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;
    private $repo;
    private $service;

    protected function _before()
    {
        if (!defined('NV_MAINFILE')) {
            define('NV_MAINFILE', true);
        }

        $this->repo = $this->makeEmpty(CatRepository::class);
        $this->service = new CatService($this->repo);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testGetDetailSuccess()
    {
        $id = 10;
        $mockEntity = new CatEntity();
        $mockEntity->catid = $id;
        $mockEntity->title = 'Test Category';

        $this->repo = $this->makeEmpty(CatRepository::class, [
            'findById' => function ($catid) use ($mockEntity) {
                return $catid === 10 ? $mockEntity : null;
            }
        ]);
        $service = new CatService($this->repo);

        $result = $service->getDetail($id);
        $this->assertInstanceOf(CatEntity::class, $result);
        $this->assertEquals($id, $result->catid);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testGetDetailNotFound()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(404);

        $this->repo = $this->makeEmpty(CatRepository::class, [
            'findById' => function ($catid) {
                return null;
            }
        ]);
        $service = new CatService($this->repo);
        $service->getDetail(999);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testGetSelectList()
    {
        $cat1 = new CatEntity();
        $cat1->catid = 1;
        $cat1->title = 'Cat 1';
        $cat2 = new CatEntity();
        $cat2->catid = 2;
        $cat2->title = 'Cat 2';

        $this->repo = $this->makeEmpty(CatRepository::class, [
            'getAll' => function () use ($cat1, $cat2) {
                return [$cat1, $cat2];
            }
        ]);
        $service = new CatService($this->repo);

        $result = $service->getSelectList();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Cat 1', $result[1]);
        $this->assertEquals('Cat 2', $result[2]);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testSaveCat()
    {
        $data = ['title' => 'New Cat'];
        $id = 0;
        $module = 'content';

        $this->repo = $this->makeEmpty(CatRepository::class, [
            'save' => function ($data, $catid) {
                return 55; // New ID
            },
            'invalidateCache' => function () {
                // Check if called
                return true;
            }
        ]);
        
        // Note: nv_apply_hook is a global function. In Unit tests, we might need to mock it if possible, 
        // or just let it run if it doesn't have side effects that crash the test.
        // NukeViet 5 usually has functions.php loaded.
        
        $service = new CatService($this->repo);
        $result = $service->saveCat($data, $id, $module);
        
        $this->assertEquals(55, $result);
    }
}
