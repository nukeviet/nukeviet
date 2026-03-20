<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

/**
 * Kiểm thử thêm 10 trường dữ liệu tùy biến (mỗi loại 1 trường) tại /admin/vi/users/fields/.
 * Nếu trường đã tồn tại sẽ xóa trước rồi tạo lại.
 */
class UsersFieldsCest
{
    /** Tên các trường tùy biến (1 trường cho mỗi loại dữ liệu) */
    private const TEST_FIELDS = [
        'score',
        'join_date',
        'nickname',
        'bio',
        'profile_detail',
        'occupation',
        'marital_status',
        'newsletter',
        'interests',
        'cv_file',
    ];

    public function _before(AcceptanceTester $I): void
    {
    }

    // -------------------------------------------------------------------------
    // Helper methods
    // -------------------------------------------------------------------------

    private function fieldsUrl(AcceptanceTester $I): string
    {
        return $I->getDomain() . '/admin/vi/users/fields/';
    }

    /**
     * Click an element using JavaScript to bypass sticky headers and overlays.
     */
    private function jsClick(AcceptanceTester $I, string $selector): void
    {
        $escaped = addslashes($selector);
        $I->executeJS("
            var el = document.querySelector('{$escaped}');
            if (el) { el.scrollIntoView({block:'center', inline:'nearest'}); el.click(); }
        ");
    }

    /**
     * Xóa trường tùy biến qua giao diện admin nếu tồn tại trong CSDL.
     */
    private function deleteFieldIfExists(AcceptanceTester $I, string $fieldName): void
    {
        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $table = $prefix . '_users_field';

        try {
            $fid = $I->grabFromDatabase($table, 'fid', ['field' => $fieldName]);
        } catch (\Throwable $e) {
            return;
        }

        if (!$fid) {
            return;
        }

        $I->amOnUrl($this->fieldsUrl($I));
        $I->waitForElement('[data-action="delete"][data-fid="' . $fid . '"]', 15);

        $this->jsClick($I, '[data-action="delete"][data-fid="' . $fid . '"]');

        // Hệ thống dùng nukeviet.confirm (cr-alert), không phải Bootstrap modal
        $I->waitForElement('.cr-alert', 5);
        $I->executeJS("document.querySelector('[id$=\"-confirm\"]').click();");
        $I->waitForElementNotVisible('.cr-alert', 5);

        // Chờ AJAX delete hoàn tất: list reload → nút xóa biến mất
        $I->waitForElementNotVisible('[data-action="delete"][data-fid="' . $fid . '"]', 10);
        $I->dontSeeInDatabase($table, ['field' => $fieldName]);
    }

    /**
     * Điều hướng đến trang thêm trường mới và chờ form sẵn sàng.
     */
    private function goToAddForm(AcceptanceTester $I): void
    {
        $I->amOnUrl($this->fieldsUrl($I));
        $I->waitForElement('#field_id', 10);
    }

    /**
     * Điền thông tin cơ bản và chọn loại trường qua JS click.
     */
    private function fillBasicInfo(AcceptanceTester $I, string $fieldId, string $title, string $fieldType): void
    {
        $I->fillField('#field_id', $fieldId);
        $I->fillField('#field_title', $title);
        $I->executeJS("
            var el = document.getElementById('f_{$fieldType}');
            if (el) { el.scrollIntoView({block:'center'}); el.click(); }
        ");
    }

    /**
     * Gửi form bằng JS và chờ redirect về trang thêm mới.
     */
    private function submitAndWaitForRedirect(AcceptanceTester $I): void
    {
        $I->executeJS("document.querySelector('button[type=\"submit\"]').click();");
        $I->waitForElement('#field_id', 15);
    }

    // -------------------------------------------------------------------------
    // Step 1: Xóa tất cả trường test nếu đã tồn tại
    // -------------------------------------------------------------------------

    /**
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function deleteExistingTestFields(AcceptanceTester $I): void
    {
        $I->wantTo('Xóa các trường tùy biến test nếu đã tồn tại');
        $I->login();

        foreach (self::TEST_FIELDS as $fieldName) {
            $this->deleteFieldIfExists($I, $fieldName);
        }
    }

    // -------------------------------------------------------------------------
    // Step 2: Thêm 10 trường – mỗi phương thức 1 loại
    // -------------------------------------------------------------------------

    /**
     * Loại 1: Number – trường số nguyên
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addNumberField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Number');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'score', 'Điểm thành viên', 'number');
        $I->waitForElementVisible('#numberfields', 5);

        $I->executeJS("document.getElementById('number_type_1').click();");
        $I->fillField('#default_value_number', '0');
        $I->fillField('#min_number_length', '0');
        $I->fillField('#max_number_length', '100');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'score', 'field_type' => 'number']);
    }

    /**
     * Loại 2: Date – trường ngày tháng
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addDateField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Date');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'join_date', 'Ngày tham gia', 'date');
        $I->waitForElementVisible('#datefields', 5);

        $I->executeJS("document.getElementById('current_date_0').click();");

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'join_date', 'field_type' => 'date']);
    }

    /**
     * Loại 3: Textbox – ô nhập văn bản 1 dòng
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addTextboxField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Textbox');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'nickname', 'Biệt danh', 'textbox');
        $I->waitForElementVisible('#textfields', 5);

        $I->executeJS("document.getElementById('m_none').click();");
        $I->fillField('#min_length', '0');
        $I->fillField('#max_length_input', '100');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'nickname', 'field_type' => 'textbox']);
    }

    /**
     * Loại 4: Textarea – vùng nhập văn bản nhiều dòng
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addTextareaField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Textarea');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'bio', 'Tiểu sử', 'textarea');
        $I->waitForElementVisible('#textfields', 5);

        $I->executeJS("document.getElementById('m_none').click();");
        $I->fillField('#min_length', '0');
        $I->fillField('#max_length_input', '500');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'bio', 'field_type' => 'textarea']);
    }

    /**
     * Loại 5: Editor – trình soạn thảo HTML
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addEditorField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Editor');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'profile_detail', 'Hồ sơ chi tiết', 'editor');
        $I->waitForElementVisible('#editorfields', 5);

        $I->fillField('#editor_width', '100%');
        $I->fillField('#editor_height', '300px');

        $I->waitForElementVisible('#textfields', 5);
        $I->fillField('#min_length', '0');
        $I->fillField('#max_length_input', '65536');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'profile_detail', 'field_type' => 'editor']);
    }

    /**
     * Loại 6: Select – dropdown chọn một giá trị
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addSelectField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Select');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'occupation', 'Nghề nghiệp', 'select');
        $I->waitForElementVisible('#choicetypes', 5);

        $I->selectOption('[name="choicetypes"]', 'field_choicetypes_text');
        $I->waitForElementVisible('#choiceitems', 5);

        $I->executeJS("document.getElementById('add_field_choice').scrollIntoView({block:'center'}); document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[1]"]', 5);
        $I->fillField('input[name="field_choice[1]"]', 'engineer');
        $I->fillField('input[name="field_choice_text[1]"]', 'Kỹ sư');

        $I->executeJS("document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[2]"]', 5);
        $I->fillField('input[name="field_choice[2]"]', 'teacher');
        $I->fillField('input[name="field_choice_text[2]"]', 'Giáo viên');

        $I->executeJS("document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[3]"]', 5);
        $I->fillField('input[name="field_choice[3]"]', 'other');
        $I->fillField('input[name="field_choice_text[3]"]', 'Khác');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'occupation', 'field_type' => 'select']);
    }

    /**
     * Loại 7: Radio – nút radio chọn một giá trị
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addRadioField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Radio');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'marital_status', 'Tình trạng hôn nhân', 'radio');
        $I->waitForElementVisible('#choicetypes', 5);

        $I->selectOption('[name="choicetypes"]', 'field_choicetypes_text');
        $I->waitForElementVisible('#choiceitems', 5);

        $I->executeJS("document.getElementById('add_field_choice').scrollIntoView({block:'center'}); document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[1]"]', 5);
        $I->fillField('input[name="field_choice[1]"]', 'single');
        $I->fillField('input[name="field_choice_text[1]"]', 'Độc thân');

        $I->executeJS("document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[2]"]', 5);
        $I->fillField('input[name="field_choice[2]"]', 'married');
        $I->fillField('input[name="field_choice_text[2]"]', 'Đã kết hôn');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'marital_status', 'field_type' => 'radio']);
    }

    /**
     * Loại 8: Checkbox – hộp kiểm chọn một/nhiều giá trị
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addCheckboxField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Checkbox');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'newsletter', 'Đăng ký nhận bản tin', 'checkbox');
        $I->waitForElementVisible('#choicetypes', 5);

        $I->selectOption('[name="choicetypes"]', 'field_choicetypes_text');
        $I->waitForElementVisible('#choiceitems', 5);

        $I->executeJS("document.getElementById('add_field_choice').scrollIntoView({block:'center'}); document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[1]"]', 5);
        $I->fillField('input[name="field_choice[1]"]', 'promo');
        $I->fillField('input[name="field_choice_text[1]"]', 'Khuyến mãi');

        $I->executeJS("document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[2]"]', 5);
        $I->fillField('input[name="field_choice[2]"]', 'news');
        $I->fillField('input[name="field_choice_text[2]"]', 'Tin tức');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'newsletter', 'field_type' => 'checkbox']);
    }

    /**
     * Loại 9: Multiselect – dropdown chọn nhiều giá trị
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addMultiselectField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại Multiselect');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'interests', 'Sở thích', 'multiselect');
        $I->waitForElementVisible('#choicetypes', 5);

        $I->selectOption('[name="choicetypes"]', 'field_choicetypes_text');
        $I->waitForElementVisible('#choiceitems', 5);

        $I->executeJS("document.getElementById('add_field_choice').scrollIntoView({block:'center'}); document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[1]"]', 5);
        $I->fillField('input[name="field_choice[1]"]', 'reading');
        $I->fillField('input[name="field_choice_text[1]"]', 'Đọc sách');

        $I->executeJS("document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[2]"]', 5);
        $I->fillField('input[name="field_choice[2]"]', 'travel');
        $I->fillField('input[name="field_choice_text[2]"]', 'Du lịch');

        $I->executeJS("document.getElementById('add_field_choice').click();");
        $I->waitForElement('input[name="field_choice[3]"]', 5);
        $I->fillField('input[name="field_choice[3]"]', 'sports');
        $I->fillField('input[name="field_choice_text[3]"]', 'Thể thao');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'interests', 'field_type' => 'multiselect']);
    }

    /**
     * Loại 10: File – trường tải lên tệp
     *
     * @param AcceptanceTester $I
     *
     * @group users
     * @group users-fields
     * @group all
     */
    public function addFileField(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm trường tùy biến loại File');
        $I->login();
        $this->goToAddForm($I);

        $this->fillBasicInfo($I, 'cv_file', 'Hồ sơ / CV', 'file');
        $I->waitForElementVisible('#filefields', 5);

        // Checkbox filetype[] là d-none → dùng JS để check trực tiếp
        $I->executeJS("
            var cb = document.querySelector('input[name=\"filetype[]\"][value=\"documents\"]');
            if (cb) {
                cb.checked = true;
                cb.dispatchEvent(new Event('change', { bubbles: true }));
            }
        ");

        // Chọn MIME type application/pdf
        $I->executeJS("
            var mime = document.querySelector('input[name=\"mime[]\"][value=\"application/pdf\"]');
            if (mime) { mime.checked = true; mime.dispatchEvent(new Event('change', { bubbles: true })); }
        ");

        // Chọn option đầu tiên (kích thước tối đa cho phép của server)
        $I->executeJS("var sel = document.querySelector('[name=\"file_max_size\"]'); sel.selectedIndex = 0; sel.dispatchEvent(new Event('change',{bubbles:true}));");
        $I->selectOption('[name="maxnum"]', '1');

        $this->submitAndWaitForRedirect($I);

        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users_field', ['field' => 'cv_file', 'field_type' => 'file']);
    }
}
