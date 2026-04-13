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

use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatValidator;
use Tests\Support\UnitTester;

class CatValidatorTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;
    private $repo;
    private $validator;

    protected function _before()
    {
        // Define NV_MAINFILE to bypass basic security check in source files
        if (!defined('NV_MAINFILE')) {
            define('NV_MAINFILE', true);
        }

        // Mock Repository
        $this->repo = $this->makeEmpty(CatRepository::class, [
            'isAliasExists' => function ($alias, $excludeId) {
                return $alias === 'existing-cat-alias';
            }
        ]);
        $this->validator = new CatValidator($this->repo);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testValidateSaveSuccess()
    {
        $data = [
            'title' => 'New Category',
            'alias' => 'new-cat-alias'
        ];
        $this->validator->validateSave($data);
        $this->assertTrue(true);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testValidateSaveEmptyTitle()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cat_empty_title');
        $this->expectExceptionCode(1);

        $data = [
            'title' => '',
            'alias' => 'some-alias'
        ];
        $this->validator->validateSave($data);
    }

    /**
     * @group content
     * @group content-cat
     */
    public function testValidateSaveDuplicateAlias()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('erroralias');
        $this->expectExceptionCode(2);

        $data = [
            'title' => 'Sample Title',
            'alias' => 'existing-cat-alias'
        ];
        $this->validator->validateSave($data, 0);
    }
}
