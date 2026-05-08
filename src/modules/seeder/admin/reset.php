<?php

/**
 * Seeder — Admin Reset
 *
 * Form reset dữ liệu seed. Yêu cầu admin gõ chính xác "YES" để xác nhận.
 * Gọi seed_reset_all() — chỉ xóa item có action='created' trong log,
 * KHÔNG động item user nhập tay.
 *
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

require_once NV_ROOTDIR . '/modules/' . $module_file . '/tools/seed_functions.php';

use Seeder\SeedTracker;

$page_title = $nv_Lang->getModule('reset_title');

$output = '';
$error = '';
$resetDone = false;
$elapsedMs = 0;
$alsoClearCache = false;

// CSRF
$csrf_key = 'seeder_reset';
$csrf = csrf_create($csrf_key);

if ($nv_Request->isset_request('submit', 'post')) {
    csrf_check($nv_Request->get_string('csrf', 'post'), $csrf_key);

    $confirm = $nv_Request->get_string('confirm', 'post', '');
    $alsoClearCache = $nv_Request->get_int('clear_cache', 'post', 0) === 1;

    if ($confirm !== 'YES') {
        $error = $nv_Lang->getModule('reset_error_confirm');
    } else {
        @set_time_limit(300);

        $tracker = new SeedTracker($db, $db_config['prefix']);

        ob_start();
        $tStart = microtime(true);
        try {
            seed_reset_all($tracker);
            if ($alsoClearCache) {
                seed_clear_cache();
            }
            $resetDone = true;
        } catch (Throwable $e) {
            out_err('Fatal: ' . $e->getMessage());
        }
        $elapsedMs = (int) ((microtime(true) - $tStart) * 1000);
        $rawOutput = (string) ob_get_clean();

        $output = preg_replace('/\x1b\[[0-9;]*m/', '', $rawOutput);
    }

    $csrf = csrf_create($csrf_key);
}

// ────────── Render ──────────

$contents = '<div class="container-fluid">';

$contents .= '<h3>' . nv_htmlspecialchars($nv_Lang->getModule('reset_title')) . '</h3>';
$contents .= '<p class="text-muted">' . nv_htmlspecialchars($nv_Lang->getModule('reset_desc')) . '</p>';

$contents .= '<div class="alert alert-danger">'
    . '<i class="fa-solid fa-triangle-exclamation"></i> '
    . '<strong>' . nv_htmlspecialchars($nv_Lang->getModule('reset_warning')) . '</strong>'
    . '</div>';

if ($error !== '') {
    $contents .= '<div class="alert alert-danger">' . nv_htmlspecialchars($error) . '</div>';
}

if ($resetDone) {
    $contents .= '<div class="alert alert-success">'
        . '<i class="fa-solid fa-check-circle"></i> Reset hoàn tất sau ' . $elapsedMs . ' ms.'
        . '</div>';
}

// Form
$contents .= '<form method="post" action="" class="card mb-3"><div class="card-body">';
$contents .= '<input type="hidden" name="csrf" value="' . nv_htmlspecialchars($csrf) . '">';

$contents .= '<div class="mb-3">';
$contents .= '<label class="form-label fw-bold">' . nv_htmlspecialchars($nv_Lang->getModule('reset_confirm_label')) . '</label>';
$contents .= '<input type="text" name="confirm" class="form-control" placeholder="YES" autocomplete="off" required>';
$contents .= '</div>';

$contents .= '<div class="form-check mb-3">';
$contents .= '<input class="form-check-input" type="checkbox" name="clear_cache" id="clear_cache" value="1" checked>';
$contents .= '<label class="form-check-label" for="clear_cache">'
    . '<i class="fa-solid fa-broom"></i> Xóa cache NV5 sau khi reset (.cache + smarty-compile)'
    . '</label>';
$contents .= '</div>';

$contents .= '<button type="submit" name="submit" value="1" class="btn btn-danger" '
    . 'onclick="return confirm(\'Xác nhận xóa toàn bộ dữ liệu seeder đã tạo? Hành động không thể hoàn tác.\')">'
    . '<i class="fa-solid fa-trash"></i> ' . nv_htmlspecialchars($nv_Lang->getModule('reset_btn'))
    . '</button>';
$contents .= '</div></form>';

// Output
if ($output !== '') {
    $contents .= '<h5>Kết quả reset <small class="text-muted">(' . $elapsedMs . ' ms)</small></h5>';
    $contents .= '<pre class="bg-dark text-light p-3 rounded" style="max-height:600px;overflow:auto"><code>'
        . nv_htmlspecialchars($output)
        . '</code></pre>';

    $contents .= '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '" class="btn btn-secondary">'
        . '<i class="fa-solid fa-arrow-left"></i> ' . nv_htmlspecialchars($nv_Lang->getModule('back_to_dashboard'))
        . '</a>';
}

$contents .= '</div>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
