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

$array_except_flood_site = $array_except_flood_admin = [];
$ip_exclusion = false;
if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/efloodip.php')) {
    include NV_ROOTDIR . '/' . NV_DATADIR . '/efloodip.php';
}

foreach ($array_except_flood_site as $e => $f) {
    if ($f['begintime'] < NV_CURRENTTIME and ($f['endtime'] == 0 or $f['endtime'] > NV_CURRENTTIME) and ((empty($f['ip6']) and preg_replace($f['mask'], '', NV_CLIENT_IP) == preg_replace($f['mask'], '', $e)) or (!empty($f['ip6']) and $ips->checkIp6(NV_CLIENT_IP, $f['mask']) === true))) {
        $ip_exclusion = true;
        break;
    }
}

if (!$ip_exclusion) {
    $rules = [
        '60' => $global_config['max_requests_60'],
        '300' => $global_config['max_requests_300']
    ];

    $flb = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
    $flb->trackFlood($rules);

    if ($flb->is_flooded) {
        // Nếu recaptcha được kích hoạt, dùng nó để xác nhận khi bị chặn
        $captchaPass = (!empty($global_config['recaptcha_sitekey']) and !empty($global_config['recaptcha_secretkey']) and ($global_config['recaptcha_ver'] == 2 or $global_config['recaptcha_ver'] == 3));
        if ($captchaPass) {
            if ($nv_Request->isset_request('captcha_pass_flood', 'post')) {
                $tokend = $nv_Request->get_title('tokend', 'post', '');
                $captcha_txt = $nv_Request->get_title('g-recaptcha-response', 'post', '');
                $redirect = $nv_Request->get_title('redirect', 'post', '');

                if ($tokend === NV_CHECK_SESSION and nv_capcha_txt($captcha_txt, 'recaptcha')) {
                    $flb->resetTrackFlood();

                    $redirect = nv_redirect_decrypt($redirect);
                    if (empty($redirect)) {
                        nv_redirect_location(NV_BASE_SITEURL);
                    }
                    nv_redirect_location($redirect);
                }
            }
        }

        if (!defined('NV_IS_AJAX') and file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl/flood_blocker.tpl')) {
            http_response_code(429);
            header('Retry-After: ' . $flb->flood_block_time);

            // Tính năng block flood của system. Không tùy biến giao diện
            $tpl = new \NukeViet\Template\NVSmarty();
            $tpl->setTemplateDir(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl');
            $tpl->assign('LANG', $nv_Lang);
            $tpl->assign('FLB', $flb);
            $tpl->assign('REDIRECT', nv_redirect_encrypt($client_info['selfurl']));
            $tpl->assign('CAPTCHA_PASS', $captchaPass);
            $tpl->assign('GCONFIG', $global_config);

            include NV_ROOTDIR . '/includes/header.php';
            echo $tpl->fetch('flood_blocker.tpl');
            include NV_ROOTDIR . '/includes/footer.php';
        }
        $headers = ['Retry-After: ' . $flb->flood_block_time];
        nv_info_die($nv_Lang->getGlobal('flood_page_title'), $nv_Lang->getGlobal('flood_page_title'), $nv_Lang->getGlobal('flood_info1'), 429, '', '', '', '', $headers);
    }

    unset($rules, $flb);
}

unset($ip_exclusion, $e, $f, $array_except_flood_site, $array_except_flood_admin);
