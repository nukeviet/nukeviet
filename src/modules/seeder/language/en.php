<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '02/05/2026, 12:00';
$lang_translator['copyright'] = '@Copyright (C) 2026 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

// Skeleton tối thiểu — chỉ giữ key tối thiểu cho admin EN khỏi vỡ.
// Theo plan README §8.1 row 4: chỉ tiếng Việt, EN không seed nội dung.

$lang_module['menu_dashboard'] = 'Dashboard';
$lang_module['menu_run'] = 'Run seed';
$lang_module['menu_reset'] = 'Reset data';

$lang_module['module_title'] = 'Seeder — Demo data generator';
$lang_module['back_to_dashboard'] = 'Back to Dashboard';

$lang_module['dashboard_title'] = 'Seed status overview';
$lang_module['dashboard_desc'] = 'This page shows summary of seeded items per step.';
$lang_module['dashboard_step'] = 'Step';
$lang_module['dashboard_count_created'] = 'Created';
$lang_module['dashboard_count_updated'] = 'Updated';
$lang_module['dashboard_count_skipped'] = 'Skipped';
$lang_module['dashboard_count_failed'] = 'Failed';
$lang_module['dashboard_last_run'] = 'Last run';
$lang_module['dashboard_no_data'] = 'No seed data yet. Go to "Run seed", tick steps, then click "Run all".';

$lang_module['run_title'] = 'Run sample data seed';
$lang_module['run_desc'] = 'Tick the steps you want to run, then click "Run selected", or click "Run all" to seed everything in dependency order.';
$lang_module['run_step'] = 'Step';
$lang_module['run_all'] = 'Run all (in order)';
$lang_module['run_btn'] = 'Run';
$lang_module['run_output'] = 'Output';
$lang_module['run_error_no_step'] = 'Please select a step.';

$lang_module['reset_title'] = 'Reset seeded data';
$lang_module['reset_desc'] = 'Reset only deletes items created by the seeder (per log table). User-created items will NOT be deleted.';
$lang_module['reset_warning'] = 'WARNING: This action cannot be undone. Backup your DB first.';
$lang_module['reset_confirm_label'] = 'Type exactly YES to confirm';
$lang_module['reset_btn'] = 'Reset all';
$lang_module['reset_error_confirm'] = 'You must type YES (uppercase) to confirm.';

$lang_module['step_categories'] = 'Categories (News)';
$lang_module['step_topics'] = 'Topics (News)';
$lang_module['step_departments'] = 'Departments (Contact)';
$lang_module['step_menus'] = 'Menus';
$lang_module['step_banner-positions'] = 'Banner positions';
$lang_module['step_banners'] = 'Sample banners';
$lang_module['step_articles'] = 'Sample articles';
$lang_module['step_users'] = 'Sample users';
$lang_module['step_theme-config'] = 'Theme config';
