<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\AcceptanceWebBot;

use Tests\Support\AcceptanceWebBot;

class StatCest
{
    public function _before(AcceptanceWebBot $I)
    {
    }

    /**
     * @param AcceptanceWebBot $I
     * @link https://github.com/nukeviet/nukeviet/issues/2823
     *
     * @group stat
     */
    public function testOnlineBotUpdate(AcceptanceWebBot $I)
    {
        $I->wantTo('Check for updates on the number of online users for the BOT');
        $I->amOnUrl($I->getDomain() . '/');
        $I->amOnUrl($I->getDomain() . '/vi/news/');
        $I->see('Máy chủ tìm kiếm');
        $I->see('Khách viếng thăm');
    }
}
