<?php

/**
 * Seeder — Admin Dashboard
 *
 * Đọc bảng `nv5_seeder_log` qua SeedTracker, hiển thị tổng hợp
 * số lượng đã created/updated/skipped/failed cho từng step.
 *
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

require NV_ROOTDIR . '/modules/' . $module_file . '/lib/SeedTracker.php';

use Seeder\SeedTracker;

$page_title = $nv_Lang->getModule('dashboard_title');

$tracker = new SeedTracker($db, $db_config['prefix']);
$summary = $tracker->summary();

// Thứ tự step để render bảng dashboard
$stepsOrder = [
    'categories', 'topics', 'departments', 'menus', 'banner-positions',
    'users', 'banners', 'articles', 'theme-config',
];

$rows = [];
foreach ($stepsOrder as $step) {
    $s = $summary[$step] ?? null;
    $rows[] = [
        'step'     => $step,
        'label'    => $nv_Lang->getModule('step_' . $step) ?: $step,
        'created'  => $s['created'] ?? null,
        'updated'  => $s['updated'] ?? null,
        'skipped'  => $s['skipped'] ?? null,
        'failed'   => $s['failed']  ?? null,
        'last_run' => !empty($s['last_run']) ? nv_date('d/m/Y H:i:s', $s['last_run']) : '—',
        'has_data' => $s !== null,
    ];
}

// ────────── Render ──────────

$contents = '<div class="container-fluid">';

$contents .= '<h3>' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_title')) . '</h3>';
$contents .= '<p class="text-muted">' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_desc')) . '</p>';

if (empty($summary)) {
    $contents .= '<div class="alert alert-warning">'
        . nv_htmlspecialchars($nv_Lang->getModule('dashboard_no_data'))
        . '</div>';
}

$contents .= '<div class="table-responsive"><table class="table table-bordered table-striped">';
$contents .= '<thead class="table-light"><tr>'
    . '<th>' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_step')) . '</th>'
    . '<th class="text-end">' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_count_created')) . '</th>'
    . '<th class="text-end">' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_count_updated')) . '</th>'
    . '<th class="text-end">' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_count_skipped')) . '</th>'
    . '<th class="text-end">' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_count_failed')) . '</th>'
    . '<th>' . nv_htmlspecialchars($nv_Lang->getModule('dashboard_last_run')) . '</th>'
    . '</tr></thead><tbody>';

foreach ($rows as $r) {
    $contents .= '<tr' . ($r['has_data'] ? '' : ' class="text-muted"') . '>';
    $contents .= '<td><code>' . nv_htmlspecialchars($r['step']) . '</code> &mdash; ' . nv_htmlspecialchars($r['label']) . '</td>';
    $contents .= '<td class="text-end text-success">' . ($r['created'] ?? '&mdash;') . '</td>';
    $contents .= '<td class="text-end text-primary">' . ($r['updated'] ?? '&mdash;') . '</td>';
    $contents .= '<td class="text-end text-secondary">' . ($r['skipped'] ?? '&mdash;') . '</td>';
    $contents .= '<td class="text-end ' . (($r['failed'] ?? 0) > 0 ? 'text-danger fw-bold' : '') . '">' . ($r['failed'] ?? '&mdash;') . '</td>';
    $contents .= '<td><small>' . nv_htmlspecialchars($r['last_run']) . '</small></td>';
    $contents .= '</tr>';
}

$contents .= '</tbody></table></div>';

$contents .= '</div>'; // .container-fluid

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
