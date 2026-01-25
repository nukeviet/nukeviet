<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

/**
 *
 */
class StatCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     * @link https://github.com/nukeviet/nukeviet/issues/2823
     *
     * @group stat
     */
    public function testOnlineHumanUpdate(AcceptanceTester $I)
    {
        $I->wantTo('Check for updates on the number of online users for people');
        $I->amOnUrl($I->getDomain() . '/');
        $I->amOnUrl($I->getDomain() . '/vi/news/');
        $I->see('Đang truy cập');
    }
}
