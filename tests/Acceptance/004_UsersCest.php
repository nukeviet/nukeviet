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

class UsersCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group install
     * @group user-datadeletion
     * @group all
     */
    public function addUserInAdminPanel(AcceptanceTester $I)
    {
        $I->wantTo('Add one user account in admin area');
        $I->login();

        $I->amOnUrl($I->getDomain() . '/admin/index.php?language=vi&nv=users&op=user_add');
        $I->seeElement('[name="username"]');

        $I->fillField(['name' => 'username'], 'spadmin');
        $I->fillField(['name' => 'email'], 'spadmin@nukeviet.vn');
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'first_name'], 'Super Admin');
        $I->fillField(['name' => 'birthday'], '20/10/2000');
        $I->fillField(['name' => 'question'], 'NukeViet');
        $I->fillField(['name' => 'answer'], 'NukeViet CMS');

        $I->click('[type="submit"]');
        $I->waitForText('Danh sách tài khoản', 5);
        $I->see('spadmin@nukeviet.vn');
    }

    /**
     * @param AcceptanceTester $I
     *
     * @group user-datadeletion
     * @group user-datadeletion-only
     * @group all
     */
    public function configOauthFacebook(AcceptanceTester $I)
    {
        $I->wantTo('Configure OAuth for Facebook');
        $I->login();

        $I->amOnUrl($I->getDomain() . '/admin/vi/users/config/?oauth_config=facebook');
        $I->seeElement('[name="oauth_client_id"]');

        $I->fillField(['name' => 'oauth_client_id'], $_ENV['FACEBOOK_APP_ID']);
        $I->fillField(['name' => 'oauth_client_secret'], $_ENV['FACEBOOK_APP_SECRET']);

        // Lưu cấu hình
        $I->click('[type="submit"]');
        $I->waitForElementVisible('#site-toasts', 60);
        $I->wait(1);
        $I->see('Các thay đổi đã được ghi nhận');
    }
}
