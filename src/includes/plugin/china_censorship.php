<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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

nv_add_hook($module_name, 'modify_global_config', $priority, function (): void {
    global $global_config, $client_info;

    if ($client_info['country'] == 'CN' and defined('NV_SYSTEM')) {
        $global_config['recaptcha_sitekey'] = '';
        $global_config['recaptcha_secretkey'] = '';
        $global_config['recaptcha_ver'] = 0;
        $global_config['turnstile_sitekey'] = '';
        $global_config['turnstile_secretkey'] = '';
        $global_config['googleAnalyticsID'] = '';
        $global_config['googleAnalytics4ID'] = '';
        $global_config['google_tag_manager'] = '';
        $global_config['searchEngineUniqueID'] = '';
        $global_config['google_client_id'] = '';
        $global_config['google_client_secret'] = '';
        $global_config['facebook_client_id'] = '';
        $global_config['facebook_client_secret'] = '';
    }
});
