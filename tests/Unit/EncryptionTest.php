<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

class EncryptionTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    private $siteKey = 'f70ec864dd62e6ddc024972ab39566d2';

    /**
     * @var \NukeViet\Core\Encryption
     */
    private $crypt;

    protected function _before()
    {
        $this->crypt = new \NukeViet\Core\Encryption($this->siteKey);
    }

    protected function _after()
    {
    }

    /**
     * @group install
     * @group all
     * @group cache
     */
    public function testEncryptionHash()
    {
        $string = 'testdata';
        $hashEqual = '5812f339cd1d74816b1f2c6e8486bc00f5d1713a';

        $this->assertEquals($hashEqual, $this->crypt->hash($string));
        $this->crypt->hash($string, true);
    }
}
