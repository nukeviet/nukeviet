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
 * Kiểm thử chức năng thêm tài khoản mới tại admin/users/user_add.
 * Bao gồm: validate tên đăng nhập, email, mật khẩu và thêm thành công với đầy đủ các trường.
 */
class UsersUserAddCest
{
    private string $testUsername;
    private string $testEmail;
    private string $testFirstName;
    private string $testLastName;

    /** Danh sách tên để chọn ngẫu nhiên mỗi lần chạy */
    private const PROFILES = [
        ['last' => 'Nguyễn', 'first' => 'Văn An',      'slug' => 'nguyenvanan'],
        ['last' => 'Trần',   'first' => 'Thị Bình',     'slug' => 'tranthiminh'],
        ['last' => 'Lê',     'first' => 'Văn Cường',    'slug' => 'levancuong'],
        ['last' => 'Phạm',   'first' => 'Thị Dung',     'slug' => 'phamthidung'],
        ['last' => 'Hoàng',  'first' => 'Minh Đức',     'slug' => 'hoangminhduc'],
        ['last' => 'Vũ',     'first' => 'Thị Hà',       'slug' => 'vuthiha'],
        ['last' => 'Đặng',   'first' => 'Văn Hùng',     'slug' => 'dangvanhung'],
        ['last' => 'Bùi',    'first' => 'Thị Lan',      'slug' => 'buithilan'],
        ['last' => 'Đỗ',     'first' => 'Quang Minh',   'slug' => 'doquangminh'],
        ['last' => 'Hồ',     'first' => 'Thị Ngọc',     'slug' => 'hothingoc'],
        ['last' => 'Phan',   'first' => 'Văn Phong',    'slug' => 'phanvanphong'],
        ['last' => 'Võ',     'first' => 'Thị Quyên',    'slug' => 'vothiquyen'],
        ['last' => 'Đinh',   'first' => 'Văn Sơn',      'slug' => 'dinhvanson'],
        ['last' => 'Trịnh',  'first' => 'Thị Tâm',      'slug' => 'trinhtitam'],
        ['last' => 'Ngô',    'first' => 'Quốc Tuấn',    'slug' => 'ngoquoctuan'],
        ['last' => 'Dương',  'first' => 'Thị Uyên',     'slug' => 'duongthiuyen'],
        ['last' => 'Lý',     'first' => 'Văn Vinh',     'slug' => 'lyvanvinh'],
        ['last' => 'Cao',    'first' => 'Thị Xuân',     'slug' => 'caothixuan'],
        ['last' => 'Tô',     'first' => 'Minh Yên',     'slug' => 'tominhyen'],
        ['last' => 'Mai',    'first' => 'Thị Ánh',      'slug' => 'maithianh'],
        ['last' => 'Lưu',    'first' => 'Văn Bảo',      'slug' => 'luuvanbao'],
        ['last' => 'Tạ',     'first' => 'Thị Chi',      'slug' => 'tathichi'],
        ['last' => 'Trương', 'first' => 'Văn Dũng',     'slug' => 'truongvandung'],
        ['last' => 'Từ',     'first' => 'Thị Em',       'slug' => 'tutiem'],
        ['last' => 'Châu',   'first' => 'Văn Phúc',     'slug' => 'chauvanphuc'],
        ['last' => 'La',     'first' => 'Thị Giang',    'slug' => 'lathigiang'],
        ['last' => 'Hà',     'first' => 'Văn Hiếu',     'slug' => 'havanhieu'],
        ['last' => 'Kiều',   'first' => 'Thị Hoa',      'slug' => 'kieuthihoa'],
        ['last' => 'Ông',    'first' => 'Chí Kiên',     'slug' => 'ongchikien'],
        ['last' => 'Mạc',    'first' => 'Thị Liên',     'slug' => 'macthilien'],
        ['last' => 'Nghiêm', 'first' => 'Văn Long',     'slug' => 'nghiemvanlong'],
        ['last' => 'Quách',  'first' => 'Thị My',       'slug' => 'quachthimy'],
        ['last' => 'Thái',   'first' => 'Văn Nam',      'slug' => 'thaivannam'],
        ['last' => 'Đoàn',   'first' => 'Thị Oanh',     'slug' => 'doanthioanh'],
        ['last' => 'Vương',  'first' => 'Văn Quân',     'slug' => 'vuongvanquan'],
        ['last' => 'Liêu',   'first' => 'Thị Ráng',     'slug' => 'lieuthirang'],
        ['last' => 'Đào',    'first' => 'Văn Sang',     'slug' => 'daovansang'],
        ['last' => 'Cù',     'first' => 'Thị Thảo',     'slug' => 'cuthithao'],
        ['last' => 'Bạch',   'first' => 'Văn Thịnh',    'slug' => 'bachvanthinh'],
        ['last' => 'Hứa',    'first' => 'Thị Thu',      'slug' => 'huathithu'],
        ['last' => 'Tống',   'first' => 'Văn Tiến',     'slug' => 'tongvantien'],
        ['last' => 'Nông',   'first' => 'Thị Trà',      'slug' => 'nongtitra'],
        ['last' => 'Giáp',   'first' => 'Văn Trung',    'slug' => 'giapvantrung'],
        ['last' => 'Đồng',   'first' => 'Thị Vân',      'slug' => 'dongthivan'],
        ['last' => 'Khổng',  'first' => 'Văn Việt',     'slug' => 'khongvanviet'],
        ['last' => 'Sầm',    'first' => 'Thị Yến',      'slug' => 'samthiyen'],
        ['last' => 'Vy',     'first' => 'Công Anh',     'slug' => 'vyconganh'],
        ['last' => 'Chu',    'first' => 'Thị Bích',     'slug' => 'chuthibich'],
        ['last' => 'Lã',     'first' => 'Văn Cảnh',     'slug' => 'lavancanh'],
        ['last' => 'Mã',     'first' => 'Thị Diễm',     'slug' => 'mathidiem'],
    ];

    // -------------------------------------------------------------------------
    // Helper methods
    // -------------------------------------------------------------------------

    private function userAddUrl(AcceptanceTester $I): string
    {
        return $I->getDomain() . '/admin/index.php?language=vi&nv=users&op=user_add';
    }

    /**
     * Click phần tử qua JavaScript để tránh bị sticky navbar chặn.
     */
    private function jsClick(AcceptanceTester $I, string $selector): void
    {
        $escaped = addslashes($selector);
        $I->executeJS("
            var el = document.querySelector('{$escaped}');
            if (el) { el.scrollIntoView({block: 'center', inline: 'nearest'}); el.click(); }
        ");
    }

    // -------------------------------------------------------------------------
    // Lifecycle
    // -------------------------------------------------------------------------

    public function _before(AcceptanceTester $I): void
    {
        $profile = self::PROFILES[array_rand(self::PROFILES)];
        $suffix  = rand(100, 999);

        $this->testUsername  = $profile['slug'] . '_' . $suffix;
        $this->testEmail     = $profile['slug'] . '_' . $suffix . '@example.vn';
        $this->testFirstName = $profile['first'];
        $this->testLastName  = $profile['last'];

        $I->login();
    }

    // -------------------------------------------------------------------------
    // Validate: tên đăng nhập
    // -------------------------------------------------------------------------

    /**
     * Gửi form không có tên đăng nhập — phải báo lỗi tại ô username.
     *
     * @group users
     * @group users-user-add
     * @group all
     */
    public function validateEmptyUsername(AcceptanceTester $I): void
    {
        $I->wantTo('Kiểm tra lỗi khi để trống tên đăng nhập');
        $I->amOnUrl($this->userAddUrl($I));
        $I->waitForElement('[name="username"]', 10);

        // Bỏ trống username, điền đủ các trường bắt buộc còn lại
        $I->fillField(['name' => 'email'], $this->testEmail);
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD']);

        $this->jsClick($I, '[type="submit"]');

        // Ô username phải có class is-invalid
        $I->waitForElement('[name="username"].is-invalid', 5);
    }

    /**
     * Gửi form với tên đăng nhập đã tồn tại trong CSDL — phải báo lỗi trùng.
     *
     * @group users
     * @group users-user-add
     * @group all
     */
    public function validateUsernameAlreadyExists(AcceptanceTester $I): void
    {
        $I->wantTo('Kiểm tra lỗi khi tên đăng nhập đã được sử dụng');
        $I->amOnUrl($this->userAddUrl($I));
        $I->waitForElement('[name="username"]', 10);

        // Dùng tên đăng nhập của admin đang đăng nhập — chắc chắn tồn tại
        $I->fillField(['name' => 'username'], $_ENV['NV_USERNAME']);
        $I->fillField(['name' => 'email'], $this->testEmail);
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD']);

        $this->jsClick($I, '[type="submit"]');

        $I->waitForElement('[name="username"].is-invalid', 5);
        $I->see('Tên người dùng này đã được tài khoản khác sử dụng');
    }

    // -------------------------------------------------------------------------
    // Validate: email
    // -------------------------------------------------------------------------

    /**
     * Gửi form với email sai định dạng — phải báo lỗi tại ô email.
     *
     * @group users
     * @group users-user-add
     * @group all
     */
    public function validateInvalidEmail(AcceptanceTester $I): void
    {
        $I->wantTo('Kiểm tra lỗi khi nhập email không hợp lệ');
        $I->amOnUrl($this->userAddUrl($I));
        $I->waitForElement('[name="username"]', 10);

        $I->fillField(['name' => 'username'], $this->testUsername);
        $I->fillField(['name' => 'email'], 'day-khong-phai-email');
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD']);

        $this->jsClick($I, '[type="submit"]');

        // Ô email phải có class is-invalid
        $I->waitForElement('[name="email"].is-invalid', 5);
    }

    /**
     * Gửi form với email đã tồn tại trong CSDL — phải báo lỗi trùng.
     *
     * @group users
     * @group users-user-add
     * @group all
     */
    public function validateEmailAlreadyExists(AcceptanceTester $I): void
    {
        $I->wantTo('Kiểm tra lỗi khi email đã được tài khoản khác sử dụng');
        $I->amOnUrl($this->userAddUrl($I));
        $I->waitForElement('[name="username"]', 10);

        // Lấy email của admin đang đăng nhập từ CSDL — chắc chắn tồn tại
        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $adminEmail = $I->grabFromDatabase($prefix . '_users', 'email', ['username' => $_ENV['NV_USERNAME']]);

        $I->fillField(['name' => 'username'], $this->testUsername);
        $I->fillField(['name' => 'email'], $adminEmail);
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD']);

        $this->jsClick($I, '[type="submit"]');

        $I->waitForElement('[name="email"].is-invalid', 5);
        $I->see('Email đã được tài khoản khác sử dụng');
    }

    // -------------------------------------------------------------------------
    // Validate: mật khẩu
    // -------------------------------------------------------------------------

    /**
     * Gửi form với hai mật khẩu không khớp nhau — phải báo lỗi.
     *
     * @group users
     * @group users-user-add
     * @group all
     */
    public function validatePasswordMismatch(AcceptanceTester $I): void
    {
        $I->wantTo('Kiểm tra lỗi khi hai mật khẩu nhập vào không giống nhau');
        $I->amOnUrl($this->userAddUrl($I));
        $I->waitForElement('[name="username"]', 10);

        $I->fillField(['name' => 'username'], $this->testUsername);
        $I->fillField(['name' => 'email'], $this->testEmail);
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD'] . '_sai');

        $this->jsClick($I, '[type="submit"]');

        $I->waitForElement('[name="password1"].is-invalid', 5);
        $I->see('Hai mật khẩu nhập vào không giống nhau');
    }

    // -------------------------------------------------------------------------
    // Custom fields: query DB → điền động theo field_type
    // -------------------------------------------------------------------------

    /**
     * Lấy tất cả custom fields từ DB và điền vào form.
     */
    private function fillCustomFields(AcceptanceTester $I): void
    {
        $prefix   = $I->getDbConfig('prefix') ?? 'nv5';
        $dbConfig = $I->getDbConfig();

        $host = $dbConfig['dbhost'] ?? $dbConfig['host'] ?? '127.0.0.1';
        $name = $dbConfig['dbname'] ?? $dbConfig['name'] ?? $_ENV['DB_NAME'] ?? '';
        $user = $dbConfig['dbuser'] ?? $dbConfig['user'] ?? $_ENV['DB_UNAME'] ?? 'root';
        $pass = $dbConfig['dbpassword'] ?? $dbConfig['dbpass'] ?? $dbConfig['pass'] ?? $_ENV['DB_UPASS'] ?? '';

        try {
            $pdo = new \PDO(
                "mysql:host={$host};dbname={$name};charset=utf8",
                $user,
                $pass,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\Throwable $e) {
            $I->comment('Bỏ qua custom fields: không kết nối DB — ' . $e->getMessage());
            return;
        }

        $stmt   = $pdo->query("SELECT field, field_type, field_choices FROM `{$prefix}_users_field` WHERE is_system=0 ORDER BY weight");
        $fields = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($fields as $row) {
            $this->fillOneCustomField($I, $row['field'], $row['field_type'], $row['field_choices']);
        }
    }

    /**
     * Điền một custom field dựa theo field_type.
     * Bỏ qua: editor (CKEditor), file (cần modal upload).
     */
    private function fillOneCustomField(AcceptanceTester $I, string $field, string $type, string $rawChoices): void
    {
        $choices = !empty($rawChoices) ? @unserialize($rawChoices) : [];
        if (!is_array($choices)) {
            $choices = [];
        }
        $firstKey = (string) (array_key_first($choices) ?? '');

        $nameSingle = "[name=\"custom_fields[{$field}]\"]";
        $nameArray  = "[name=\"custom_fields[{$field}][]\"]";

        switch ($type) {
            case 'number':
                if ($I->tryToSeeElement($nameSingle)) {
                    $I->fillField($nameSingle, '50');
                }
                break;

            case 'textbox':
                if ($I->tryToSeeElement($nameSingle)) {
                    $I->fillField($nameSingle, "Giá trị {$field}");
                }
                break;

            case 'date':
                if ($I->tryToSeeElement($nameSingle)) {
                    $I->fillField($nameSingle, '01/06/2023');
                }
                break;

            case 'textarea':
                if ($I->tryToSeeElement($nameSingle)) {
                    $I->fillField($nameSingle, "Mô tả cho trường {$field}.");
                }
                break;

            case 'select':
                if ($firstKey !== '' && $I->tryToSeeElement($nameSingle)) {
                    $I->selectOption($nameSingle, $firstKey);
                }
                break;

            case 'radio':
                if ($firstKey !== '') {
                    $selector = "{$nameSingle}[value=\"{$firstKey}\"]";
                    if ($I->tryToSeeElement($selector)) {
                        $this->jsClick($I, $selector);
                    }
                }
                break;

            case 'checkbox':
                if ($firstKey !== '') {
                    $selector = "{$nameArray}[value=\"{$firstKey}\"]";
                    if ($I->tryToSeeElement($selector)) {
                        $this->jsClick($I, $selector);
                    }
                }
                break;

            case 'multiselect':
                if (!empty($choices) && $I->tryToSeeElement($nameArray)) {
                    $I->selectOption($nameArray, array_slice(array_keys($choices), 0, 2));
                }
                break;

            case 'editor':
            case 'file':
                // Bỏ qua: editor cần CKEditor, file cần modal upload
                break;
        }
    }

    // -------------------------------------------------------------------------
    // Thêm tài khoản thành công với đầy đủ các trường
    // -------------------------------------------------------------------------

    /**
     * Nhập đầy đủ các trường trong form và thêm tài khoản thành công.
     * Sau khi thêm: kiểm tra redirect về danh sách, thấy email trong bảng, và xác nhận trong CSDL.
     *
     * @group users
     * @group users-user-add
     * @group all
     */
    public function addUserWithAllFields(AcceptanceTester $I): void
    {
        $I->wantTo('Thêm tài khoản mới với đầy đủ thông tin — tên, email, mật khẩu, thông tin cá nhân');
        $I->amOnUrl($this->userAddUrl($I));
        $I->waitForElement('[name="username"]', 10);

        // --- Thông tin tài khoản ---
        $I->fillField(['name' => 'username'], $this->testUsername);
        $I->fillField(['name' => 'email'], $this->testEmail);
        $I->fillField(['name' => 'password1'], $_ENV['NV_PASSWORD']);
        $I->fillField(['name' => 'password2'], $_ENV['NV_PASSWORD']);

        // Yêu cầu đổi mật khẩu lần đăng nhập tiếp theo: không yêu cầu
        $I->selectOption('[name="pass_reset_request"]', '0');

        // --- Thông tin cá nhân (các trường hệ thống) ---
        // Tên (first_name) và Họ (last_name) — hiển thị tùy cấu hình name_show
        if ($I->tryToSeeElement('[name="first_name"]')) {
            $I->fillField(['name' => 'first_name'], $this->testFirstName);
        }
        if ($I->tryToSeeElement('[name="last_name"]')) {
            $I->fillField(['name' => 'last_name'], $this->testLastName);
        }

        // Giới tính: Nam
        if ($I->tryToSeeElement('[name="gender"]')) {
            $I->selectOption('[name="gender"]', 'm');
        }

        // Ngày sinh: 15/03/1992
        if ($I->tryToSeeElement('[name="birthday"]')) {
            $I->fillField(['name' => 'birthday'], '15/03/1992');
        }

        // Chữ ký
        if ($I->tryToSeeElement('[name="sig"]')) {
            $I->fillField(['name' => 'sig'], 'Thành viên NukeViet CMS — mã nguồn mở Việt Nam');
        }

        // Câu hỏi và câu trả lời bảo mật
        if ($I->tryToSeeElement('[name="question"]')) {
            $I->fillField(['name' => 'question'], 'Tên trường tiểu học đầu tiên của bạn?');
        }
        if ($I->tryToSeeElement('[name="answer"]')) {
            $I->fillField(['name' => 'answer'], 'Trường Tiểu học Nguyễn Trãi');
        }

        // --- Trường tùy biến (custom fields) — điền động theo DB ---
        $this->fillCustomFields($I);

        // --- Tùy chọn tài khoản ---
        // Hiển thị email: bật
        $this->jsClick($I, '#view_mail_field');

        // Là thành viên chính thức: mặc định đã checked — giữ nguyên
        // (is_official = 1 → nhóm 4, cho phép chọn thêm nhóm tùy chỉnh)

        // Email đã xác minh: mặc định đã checked — giữ nguyên

        // Không gửi email thông báo (tránh gửi mail khi test)
        // (adduser_email mặc định không checked — giữ nguyên)

        // --- Submit ---
        $this->jsClick($I, '[type="submit"]');

        // Sau khi thêm thành công phải redirect về trang danh sách tài khoản
        $I->waitForText('Danh sách tài khoản', 10);
        $I->see($this->testEmail);

        // Xác nhận tài khoản đã được ghi vào CSDL
        $prefix = $I->getDbConfig('prefix') ?? 'nv5';
        $I->seeInDatabase($prefix . '_users', [
            'username' => $this->testUsername,
            'email'    => $this->testEmail,
        ]);
    }
}
