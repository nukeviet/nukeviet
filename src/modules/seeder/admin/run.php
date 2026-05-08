<?php

/**
 * Seeder — Admin Run
 *
 * Chạy seed trực tiếp trong context admin. Hỗ trợ:
 *  - Tick nhiều step + "Chạy các bước đã chọn"
 *  - Nút "Chạy tất cả" (chạy 9 step theo thứ tự dependencies)
 *  - Capture output (out_*) qua ob_start, strip ANSI codes trước khi render HTML
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

$page_title = $nv_Lang->getModule('run_title');

// 9 step đã implement (P2.A + P2.B) — thứ tự dependencies
$availableSteps = [
    'categories'       => $nv_Lang->getModule('step_categories'),
    'topics'           => $nv_Lang->getModule('step_topics'),
    'departments'      => $nv_Lang->getModule('step_departments'),
    'banner-positions' => $nv_Lang->getModule('step_banner-positions'),
    'users'            => $nv_Lang->getModule('step_users'),
    'menus'            => $nv_Lang->getModule('step_menus'),
    'articles'         => $nv_Lang->getModule('step_articles'),
    'banners'          => $nv_Lang->getModule('step_banners'),
    'theme-config'     => $nv_Lang->getModule('step_theme-config'),
];

// Step cần internet (tải ảnh picsum.photos)
$stepsNeedNet = ['articles', 'banners'];

$selectedSteps = [];
$output = '';
$error = '';
$runAll = false;
$elapsedMs = 0;

// Theme target — list folder trong data/seeder/
$availableThemes = seeder_list_themes();
$defaultTheme = !empty($global_config['site_theme']) && in_array($global_config['site_theme'], $availableThemes, true)
    ? $global_config['site_theme']
    : (count($availableThemes) > 0 ? $availableThemes[0] : '');
$selectedTheme = $defaultTheme;

// CSRF (NV5 chuẩn)
$csrf_key = 'seeder_run';
$csrf = csrf_create($csrf_key);

if ($nv_Request->isset_request('submit', 'post')) {
    csrf_check($nv_Request->get_string('csrf', 'post'), $csrf_key);

    $postedTheme = $nv_Request->get_title('theme', 'post', '');
    if ($postedTheme !== '' && in_array($postedTheme, $availableThemes, true)) {
        $selectedTheme = $postedTheme;
    }

    $runAll = $nv_Request->get_int('run_all', 'post', 0) === 1;
    if ($runAll) {
        $selectedSteps = array_keys($availableSteps);
    } else {
        $rawSteps = $nv_Request->get_array('steps', 'post', []);
        foreach ($rawSteps as $s) {
            $s = (string) $s;
            if (isset($availableSteps[$s])) {
                $selectedSteps[] = $s;
            }
        }
    }

    if (empty($availableThemes)) {
        $error = 'Chưa có folder data theme nào trong data/seeder/. Vui lòng tạo folder và file manifest trước.';
    } elseif ($selectedTheme === '') {
        $error = 'Hãy chọn theme để seed.';
    } elseif (empty($selectedSteps)) {
        $error = $nv_Lang->getModule('run_error_no_step');
    } else {
        // Set theme target trước khi seed (load_manifest sẽ đọc theo theme này)
        try {
            seeder_set_theme($selectedTheme);
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        if ($error === '') {
            // Tăng giới hạn — articles/banners có thể tải 90 ảnh từ picsum mất ~60s
            @set_time_limit(900);

            $tracker = new SeedTracker($db, $db_config['prefix']);

            // Capture stdout của các hàm out_*
            ob_start();
            $tStart = microtime(true);
            try {
                seed_run_steps($selectedSteps, $tracker);
            } catch (Throwable $e) {
                out_err('Fatal: ' . $e->getMessage());
            }
            $elapsedMs = (int) ((microtime(true) - $tStart) * 1000);
            $rawOutput = (string) ob_get_clean();

            // Strip ANSI color codes trước khi render HTML
            $output = preg_replace('/\x1b\[[0-9;]*m/', '', $rawOutput);
        }
    }

    // CSRF token mới sau submit
    $csrf = csrf_create($csrf_key);
}

// ────────── Render ──────────

$contents = '<div class="container-fluid">';

$contents .= '<h3>' . nv_htmlspecialchars($nv_Lang->getModule('run_title')) . '</h3>';
$contents .= '<p class="text-muted">' . nv_htmlspecialchars($nv_Lang->getModule('run_desc')) . '</p>';

if ($error !== '') {
    $contents .= '<div class="alert alert-danger">' . nv_htmlspecialchars($error) . '</div>';
}

// Form
$contents .= '<form method="post" action="" class="card mb-3"><div class="card-body">';
$contents .= '<input type="hidden" name="csrf" value="' . nv_htmlspecialchars($csrf) . '">';
$contents .= '<input type="hidden" name="run_all" id="nv-run-all" value="0">';

// Theme target dropdown
$contents .= '<div class="mb-3">';
$contents .= '<label class="form-label fw-bold">Theme target — đọc manifest từ <code>data/seeder/&lt;theme&gt;/</code></label>';
if (empty($availableThemes)) {
    $contents .= '<div class="alert alert-warning mb-0"><i class="fa-solid fa-triangle-exclamation"></i> Chưa có folder nào trong <code>data/seeder/</code>. Tạo folder + file manifest JSON trước.</div>';
} else {
    $contents .= '<select name="theme" class="form-select">';
    foreach ($availableThemes as $t) {
        $sel = ($t === $selectedTheme) ? ' selected' : '';
        $isActive = (!empty($global_config['site_theme']) && $t === $global_config['site_theme']) ? ' (đang active)' : '';
        $contents .= '<option value="' . nv_htmlspecialchars($t) . '"' . $sel . '>'
            . nv_htmlspecialchars($t) . nv_htmlspecialchars($isActive)
            . '</option>';
    }
    $contents .= '</select>';
    $contents .= '<small class="form-text text-muted">Default: theme đang active. Tạo folder mới trong <code>data/seeder/</code> để hỗ trợ thêm theme.</small>';
}
$contents .= '</div>';

$contents .= '<label class="form-label fw-bold">' . nv_htmlspecialchars($nv_Lang->getModule('run_step')) . '</label>';
$contents .= '<div class="mb-3">';
foreach ($availableSteps as $key => $label) {
    $checked = in_array($key, $selectedSteps, true) && !$runAll ? ' checked' : '';
    $netBadge = in_array($key, $stepsNeedNet, true)
        ? ' <span class="badge bg-warning text-dark" title="Cần internet để tải ảnh từ picsum.photos"><i class="fa-solid fa-cloud-arrow-down"></i> internet</span>'
        : '';
    $contents .= '<div class="form-check">'
        . '<input class="form-check-input" type="checkbox" name="steps[]" id="step_' . nv_htmlspecialchars($key) . '" value="' . nv_htmlspecialchars($key) . '"' . $checked . '>'
        . '<label class="form-check-label" for="step_' . nv_htmlspecialchars($key) . '">'
        . '<code>' . nv_htmlspecialchars($key) . '</code> &mdash; ' . nv_htmlspecialchars($label) . $netBadge
        . '</label></div>';
}
$contents .= '</div>';

$contents .= '<div class="d-flex gap-2 flex-wrap">';
$contents .= '<button type="submit" name="submit" value="1" class="btn btn-primary" onclick="document.getElementById(\'nv-run-all\').value=0">'
    . '<i class="fa-solid fa-play"></i> ' . nv_htmlspecialchars($nv_Lang->getModule('run_btn'))
    . '</button>';
$contents .= '<button type="submit" name="submit" value="1" class="btn btn-success" onclick="document.getElementById(\'nv-run-all\').value=1">'
    . '<i class="fa-solid fa-rocket"></i> ' . nv_htmlspecialchars($nv_Lang->getModule('run_all'))
    . '</button>';
$contents .= '</div>';

$contents .= '</div></form>';

// Output
if ($output !== '') {
    $stepsLabel = $runAll
        ? $nv_Lang->getModule('run_all')
        : implode(', ', $selectedSteps);

    $contents .= '<h5>'
        . nv_htmlspecialchars($nv_Lang->getModule('run_output'))
        . ' <small class="text-muted">(' . nv_htmlspecialchars($stepsLabel)
        . ' &middot; ' . $elapsedMs . ' ms)</small></h5>';

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
