<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace Tests\modules\Content\Unit;

use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentValidator;
use Tests\Support\UnitTester;

class ContentValidatorTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;
    private $repo;
    private $validator;

    protected function _before()
    {
        if (!defined('NV_MAINFILE')) {
            define('NV_MAINFILE', true);
        }

        // Mock Repository
        $this->repo = $this->makeEmpty(ContentRepository::class, [
            'isAliasExists' => function ($alias, $excludeId) {
                return $alias === 'existing-alias';
            }
        ]);
        $this->validator = new ContentValidator($this->repo);
    }

    /**
     * @group content
     */
    public function testValidateSaveSuccess()
    {
        $data = [
            'title' => 'Valid Content Title',
            'bodytext' => 'Some content here...',
            'alias' => 'new-alias'
        ];
        $this->validator->validateSave($data);
        $this->assertTrue(true);
    }

    /**
     * @group content
     */
    public function testValidateSaveEmptyTitle()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('empty_title');

        $data = [
            'title' => '',
            'bodytext' => 'Content...'
        ];
        $this->validator->validateSave($data);
    }

    /**
     * @group content
     */
    public function testValidateSaveEmptyBody()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('empty_bodytext');

        $data = [
            'title' => 'Title',
            'bodytext' => ''
        ];
        $this->validator->validateSave($data);
    }

    /**
     * @group content
     */
    public function testValidateSaveDuplicateAlias()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('erroralias');

        $data = [
            'title' => 'Title',
            'bodytext' => 'Content',
            'alias' => 'existing-alias'
        ];
        $this->validator->validateSave($data, 0);
    }
}
