<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/* Hiện tại trong nhân của hệ thống tích hợp một số tiện ích của Google, Facebook.
 Trong khi đó Trung Quốc áp dụng chính sách "Cấm cửa" trên môi trường mạng internet.
 Tất cả các tiện ích online của Google và một số mạng xã hội khác đều bị chặn, Vì vậy những site sử dụng NukeViet đều bị "đơ" khi xem từ IP của TQ.
 */

nv_add_hook($module_name, 'modify_global_config', $priority, function ($vars) {
    $global_config = $vars[0];
    $client_info = $vars[1];

    if ($client_info['country'] == 'CN' and defined('NV_SYSTEM')) {
        // Không dùng reCAPTCHA nếu truy cập từ trung quốc. Khi đó dùng: Cool php captcha
        if ($global_config['captcha_type'] == 2) {
            $global_config['captcha_type'] = 1;
        }

        // Không load google Analytics khi truy cập từ trung quốc.
        $global_config['googleAnalyticsID'] = '';
    }

    return $global_config;
});
