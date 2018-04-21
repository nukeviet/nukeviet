<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 29 Jan 2018 07:32:56 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/* Hiện tại trong nhân của hệ thống tích hợp một số tiện ích của Google, Facebook.
 Trong khi đó Trung Quốc áp dụng chính sách "Cấm cửa" trên môi trường mạng internet.
 Tất cả các tiện ích online của Google và một số mạng xã hội khác đều bị chặn, Vì vậy những site sử dụng NukeViet đều bị "đơ" khi xem từ IP của TQ.
 */

if ($client_info['country'] == 'CN' and defined('NV_SYSTEM')) {
    // Không dùng reCAPTCHA nếu truy cập từ trung quốc. Khi đó dùng: Cool php captcha
    if ($global_config['captcha_type'] == 2) {
        $global_config['captcha_type'] = 1;
    }

    // Không load google Analytics khi truy cập từ trung quốc.
    $global_config['googleAnalyticsID'] = '';
}