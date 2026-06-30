<?php

declare(strict_types=1);

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Template;

use Smarty\Security;

/**
 * Chính sách bảo mật siết chặt dành riêng cho việc biên dịch nội dung email
 * (tiêu đề + thân email) được admin lưu trong CSDL.
 *
 * Nội dung email là DỮ LIỆU do người dùng (admin module emailtemplates) nhập,
 * nhưng lại bị compile như mã Smarty qua resource "string:". Vì vậy KHÔNG được
 * dùng chính sách mặc định của \Smarty\Security: khi các mảng allowlist để rỗng
 * thì Smarty hiểu là "cho phép tất cả" (vd static_classes rỗng => mọi static
 * method của mọi class đều gọi được => RCE).
 *
 * Class này CHẶN HẲN việc truy cập static class/method và truy cập hằng/super
 * global trong template, trong khi vẫn giữ nguyên các tính năng templating hợp lệ
 * mà email cần: biến {$var}, điều kiện {if}, vòng lặp {foreach}, modifier chuẩn...
 */
class NVSmartyMailSecurity extends Security
{
    /**
     * Không cho phép đọc hằng PHP tùy ý trong template ({$smarty.const.XXX}).
     * Các hằng cần thiết của hệ thống đã được NVSmarty assign sẵn thành biến.
     *
     * @var boolean
     */
    public $allow_constants = false;

    /**
     * Không cho phép truy cập super global ({$smarty.server...}, {$smarty.get...}).
     *
     * @var boolean
     */
    public $allow_super_globals = false;

    /**
     * Cấm thẳng các tag không cần thiết cho nội dung email
     * - eval        : biên dịch chuỗi tùy ý như template (compile động)
     * - include / extends : nạp/kế thừa template khác (LFI nếu lọt trusted dir)
     * - fetch       : đọc file/URL (LFI/SSRF)
     * - config_load : nạp file cấu hình
     * - setfilter   : đăng ký filter động
     * Các tag templating hợp lệ ({if}, {foreach}, {section}, modifier...) không bị ảnh hưởng.
     *
     * @var array
     */
    public $disabled_tags = ['eval', 'include', 'extends', 'fetch', 'config_load', 'setfilter'];

    /**
     * Chặn mọi truy cập static class trong template.
     *
     * @param string $class_name
     * @param object $compiler
     *
     * @return boolean
     */
    public function isTrustedStaticClass($class_name, $compiler)
    {
        $compiler->trigger_template_error("access to static class '{$class_name}' is not allowed in email templates");

        return false;
    }

    /**
     * Chặn mọi truy cập static method/property trong template.
     *
     * @param string $class_name
     * @param array $params
     * @param object $compiler
     *
     * @return boolean
     */
    public function isTrustedStaticClassAccess($class_name, $params, $compiler)
    {
        $compiler->trigger_template_error("access to static class '{$class_name}' is not allowed in email templates");

        return false;
    }
}
