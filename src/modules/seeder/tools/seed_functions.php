<?php

/**
 * Seeder — Library of seed_* functions
 *
 * File này chứa các hàm seed_* và helpers, được include từ admin pages
 * (admin/run.php, admin/reset.php). Không có CLI — toàn bộ chạy qua Admin UI.
 *
 * Yêu cầu context khi include:
 *  - $db, $db_config (NV5 globals) đã sẵn sàng
 *  - NV_LANG_DATA, NV_CURRENTTIME đã define (qua mainfile.php)
 *
 * Output: các hàm out_* echo trực tiếp. Admin pages dùng ob_start() để capture
 * rồi strip ANSI codes trước khi render trong <pre>.
 *
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

require_once __DIR__ . '/../lib/SeedTracker.php';

use Seeder\SeedTracker;

// ────────────────────────────────────────────────────────────────────────────
// Helpers chung
// ────────────────────────────────────────────────────────────────────────────

const STEPS_ORDER = [
    'categories',
    'topics',
    'departments',
    'menus',
    'banner-positions',
    'users',
    'banners',
    'articles',
    'theme-config',
];

const COLOR_GREEN  = "\033[32m";
const COLOR_YELLOW = "\033[33m";
const COLOR_RED    = "\033[31m";
const COLOR_CYAN   = "\033[36m";
const COLOR_RESET  = "\033[0m";

function out_ok(string $msg): void
{
    echo COLOR_GREEN . '  ✓ ' . COLOR_RESET . $msg . PHP_EOL;
}
function out_warn(string $msg): void
{
    echo COLOR_YELLOW . '  ⚠ ' . COLOR_RESET . $msg . PHP_EOL;
}
function out_err(string $msg): void
{
    echo COLOR_RED . '  ✗ ' . COLOR_RESET . $msg . PHP_EOL;
}
function out_info(string $msg): void
{
    echo COLOR_CYAN . $msg . COLOR_RESET . PHP_EOL;
}

/**
 * Đọc 1 manifest JSON, validate cấu trúc tối thiểu.
 *
 * @return array<mixed>
 */
/**
 * Set global theme target — gọi 1 lần đầu chương trình (CLI hoặc admin) trước khi seed.
 * Để hỗ trợ nhiều theme cùng tồn tại, manifest đặt trong `src/data/seeder/<theme>/`.
 */
function seeder_set_theme(string $theme): void
{
    if ($theme === '' || !preg_match('#^[a-z0-9_-]+$#i', $theme)) {
        throw new RuntimeException("Theme name không hợp lệ: '$theme' (chỉ a-z0-9_-)");
    }
    $GLOBALS['SEEDER_THEME'] = $theme;
}

/**
 * Lấy theme hiện tại đang seed. Ưu tiên:
 *   1. $GLOBALS['SEEDER_THEME'] (set bởi seeder_set_theme())
 *   2. $global_config['site_theme'] (theme đang active của site)
 *   3. 'newsviet' (fallback cuối)
 */
function seeder_get_theme(): string
{
    if (!empty($GLOBALS['SEEDER_THEME'])) {
        return (string) $GLOBALS['SEEDER_THEME'];
    }
    global $global_config;
    if (!empty($global_config['site_theme'])) {
        return (string) $global_config['site_theme'];
    }
    return 'newsviet';
}

/**
 * Đường dẫn tuyệt đối tới folder data manifest của theme hiện tại.
 * vd: <NV_ROOTDIR>/data/seeder/newsviet
 */
function seeder_get_data_dir(?string $theme = null): string
{
    $theme = $theme !== null ? $theme : seeder_get_theme();
    if (!preg_match('#^[a-z0-9_-]+$#i', $theme)) {
        throw new RuntimeException("Theme name không hợp lệ (chống path traversal): '$theme'");
    }
    return NV_ROOTDIR . '/data/seeder/' . $theme;
}

/**
 * List các theme có folder manifest trong src/data/seeder/.
 *
 * @return string[]  vd ['newsviet', 'shop_theme']
 */
function seeder_list_themes(): array
{
    $base = NV_ROOTDIR . '/data/seeder';
    if (!is_dir($base)) {
        return [];
    }
    $out = [];
    foreach (scandir($base) ?: [] as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        if (is_dir($base . '/' . $entry) && preg_match('#^[a-z0-9_-]+$#i', $entry)) {
            $out[] = $entry;
        }
    }
    sort($out);
    return $out;
}

/**
 * Đọc 1 manifest JSON từ data/<theme>/<name>.json.
 *
 * @return array<mixed>
 */
function load_manifest(string $name): array
{
    $path = seeder_get_data_dir() . '/' . $name . '.json';
    if (!is_file($path)) {
        throw new RuntimeException("Manifest không tồn tại: $path");
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($data)) {
        throw new RuntimeException("Manifest không hợp lệ: $path");
    }
    return $data;
}

/**
 * Sinh alias từ tiêu đề (clone tối giản logic NukeViet — đủ cho seeder).
 */
function make_alias(string $title): string
{
    $title = trim($title);
    $title = mb_strtolower($title, 'UTF-8');
    $map = [
        'à',
        'á',
        'ạ',
        'ả',
        'ã',
        'â',
        'ầ',
        'ấ',
        'ậ',
        'ẩ',
        'ẫ',
        'ă',
        'ằ',
        'ắ',
        'ặ',
        'ẳ',
        'ẵ',
        'è',
        'é',
        'ẹ',
        'ẻ',
        'ẽ',
        'ê',
        'ề',
        'ế',
        'ệ',
        'ể',
        'ễ',
        'ì',
        'í',
        'ị',
        'ỉ',
        'ĩ',
        'ò',
        'ó',
        'ọ',
        'ỏ',
        'õ',
        'ô',
        'ồ',
        'ố',
        'ộ',
        'ổ',
        'ỗ',
        'ơ',
        'ờ',
        'ớ',
        'ợ',
        'ở',
        'ỡ',
        'ù',
        'ú',
        'ụ',
        'ủ',
        'ũ',
        'ư',
        'ừ',
        'ứ',
        'ự',
        'ử',
        'ữ',
        'ỳ',
        'ý',
        'ỵ',
        'ỷ',
        'ỹ',
        'đ',
    ];
    $rep = [
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'i',
        'i',
        'i',
        'i',
        'i',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'y',
        'y',
        'y',
        'y',
        'y',
        'd',
    ];
    $alias = str_replace($map, $rep, $title);
    $alias = preg_replace('/[^a-z0-9]+/u', '-', $alias) ?? '';
    return trim($alias, '-');
}

// ────────────────────────────────────────────────────────────────────────────
// Hàm seed — P2.A
// ────────────────────────────────────────────────────────────────────────────

/**
 * Seed 14 chuyên mục news.
 * - Mỗi cat mới: INSERT vào `_news_cat` + CREATE TABLE `_news_<catid>` LIKE `_news_rows`
 * - Idempotent theo natural key `alias`
 */
function seed_categories(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('categories');
    $items    = $manifest['items'] ?? [];
    $lang     = NV_LANG_DATA;
    $catTable = $db_config['prefix'] . '_' . $lang . '_news_cat';
    $rowsTable = $db_config['prefix'] . '_' . $lang . '_news_rows';

    out_info("[seeder] step=categories run_id={$tracker->getRunId()} table={$catTable}");

    $created = $updated = $failed = 0;

    foreach ($items as $row) {
        $alias = (string) ($row['alias'] ?? '');
        $title = (string) ($row['title'] ?? '');
        if ($alias === '' || $title === '') {
            out_err("Item thiếu alias/title — bỏ qua");
            $failed++;
            continue;
        }

        try {
            $sth = $db->prepare("SELECT catid FROM {$catTable} WHERE alias = :alias");
            $sth->bindValue(':alias', $alias);
            $sth->execute();
            $catid = (int) $sth->fetchColumn();

            $now = NV_CURRENTTIME;
            $description = (string) ($row['description'] ?? '');
            $viewcat = (string) ($row['viewcat'] ?? 'viewcat_main_left');
            $weight  = (int)   ($row['weight'] ?? 0);

            if ($catid > 0) {
                $upd = $db->prepare("UPDATE {$catTable}
                    SET title = :title, description = :description, viewcat = :viewcat, weight = :weight, edit_time = :edit_time, status = 1
                    WHERE catid = :catid");
                $upd->bindValue(':title',       $title);
                $upd->bindValue(':description', $description);
                $upd->bindValue(':viewcat',     $viewcat);
                $upd->bindValue(':weight',      $weight, PDO::PARAM_INT);
                $upd->bindValue(':edit_time',   $now,    PDO::PARAM_INT);
                $upd->bindValue(':catid',       $catid,  PDO::PARAM_INT);
                $upd->execute();

                $tracker->log('categories', $alias, $catid, $catTable, SeedTracker::ACTION_UPDATED);
                out_ok("updated  {$alias} (catid={$catid})");
                $updated++;
            } else {
                $ins = $db->prepare("INSERT INTO {$catTable}
                    (parentid, title, alias, description, weight, viewcat, status, add_time, edit_time, lev, numsubcat, admins, groups_view)
                    VALUES (0, :title, :alias, :description, :weight, :viewcat, 1, :add_time, :edit_time, 0, 0, '', '6')");
                $ins->bindValue(':title',       $title);
                $ins->bindValue(':alias',       $alias);
                $ins->bindValue(':description', $description);
                $ins->bindValue(':weight',      $weight, PDO::PARAM_INT);
                $ins->bindValue(':viewcat',     $viewcat);
                $ins->bindValue(':add_time',    $now, PDO::PARAM_INT);
                $ins->bindValue(':edit_time',   $now, PDO::PARAM_INT);
                $ins->execute();

                $catid = (int) $db->lastInsertId();

                // Clone bảng `_news_<catid>` từ `_news_rows` (mô phỏng modules/news/admin/cat.php:205)
                $catSpecificTable = $db_config['prefix'] . '_' . $lang . '_news_' . $catid;
                $db->exec("DROP TABLE IF EXISTS {$catSpecificTable}");
                $db->exec("CREATE TABLE {$catSpecificTable} LIKE {$rowsTable}");

                $tracker->log('categories', $alias, $catid, $catTable, SeedTracker::ACTION_CREATED, "Cloned {$catSpecificTable} LIKE {$rowsTable}");
                out_ok("created  {$alias} (catid={$catid}, +bảng {$catSpecificTable})");
                $created++;
            }
        } catch (Throwable $e) {
            $tracker->log('categories', $alias, 0, $catTable, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$alias} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done categories  created={$created} updated={$updated} failed={$failed}");
}

/**
 * Seed 3 chủ đề news.
 * - Idempotent theo natural key `alias`
 */
function seed_topics(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('topics');
    $items    = $manifest['items'] ?? [];
    $lang     = NV_LANG_DATA;
    $tbl      = $db_config['prefix'] . '_' . $lang . '_news_topics';

    out_info("[seeder] step=topics run_id={$tracker->getRunId()} table={$tbl}");

    $created = $updated = $failed = 0;

    foreach ($items as $row) {
        $alias = (string) ($row['alias'] ?? '');
        $title = (string) ($row['title'] ?? '');
        if ($alias === '' || $title === '') {
            out_err("Item thiếu alias/title — bỏ qua");
            $failed++;
            continue;
        }

        try {
            $sth = $db->prepare("SELECT topicid FROM {$tbl} WHERE alias = :alias");
            $sth->bindValue(':alias', $alias);
            $sth->execute();
            $topicid = (int) $sth->fetchColumn();

            $now = NV_CURRENTTIME;
            $description = (string) ($row['description'] ?? '');
            $weight      = (int) ($row['weight'] ?? 0);

            if ($topicid > 0) {
                $upd = $db->prepare("UPDATE {$tbl}
                    SET title = :title, description = :description, weight = :weight, edit_time = :edit_time
                    WHERE topicid = :id");
                $upd->bindValue(':title',       $title);
                $upd->bindValue(':description', $description);
                $upd->bindValue(':weight',      $weight, PDO::PARAM_INT);
                $upd->bindValue(':edit_time',   $now, PDO::PARAM_INT);
                $upd->bindValue(':id',          $topicid, PDO::PARAM_INT);
                $upd->execute();

                $tracker->log('topics', $alias, $topicid, $tbl, SeedTracker::ACTION_UPDATED);
                out_ok("updated  {$alias} (topicid={$topicid})");
                $updated++;
            } else {
                $ins = $db->prepare("INSERT INTO {$tbl}
                    (title, alias, description, weight, add_time, edit_time)
                    VALUES (:title, :alias, :description, :weight, :add_time, :edit_time)");
                $ins->bindValue(':title',       $title);
                $ins->bindValue(':alias',       $alias);
                $ins->bindValue(':description', $description);
                $ins->bindValue(':weight',      $weight, PDO::PARAM_INT);
                $ins->bindValue(':add_time',    $now, PDO::PARAM_INT);
                $ins->bindValue(':edit_time',   $now, PDO::PARAM_INT);
                $ins->execute();

                $topicid = (int) $db->lastInsertId();
                $tracker->log('topics', $alias, $topicid, $tbl, SeedTracker::ACTION_CREATED);
                out_ok("created  {$alias} (topicid={$topicid})");
                $created++;
            }
        } catch (Throwable $e) {
            $tracker->log('topics', $alias, 0, $tbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$alias} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done topics  created={$created} updated={$updated} failed={$failed}");
}

/**
 * Seed 8 phòng ban contact.
 * - Idempotent theo natural key `alias`
 */
function seed_departments(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('departments');
    $items    = $manifest['items'] ?? [];
    $lang     = NV_LANG_DATA;
    $tbl      = $db_config['prefix'] . '_' . $lang . '_contact_department';

    out_info("[seeder] step=departments run_id={$tracker->getRunId()} table={$tbl}");

    $created = $updated = $failed = 0;

    foreach ($items as $row) {
        $alias     = (string) ($row['alias'] ?? '');
        $fullName  = (string) ($row['full_name'] ?? '');
        if ($alias === '' || $fullName === '') {
            out_err("Item thiếu alias/full_name — bỏ qua");
            $failed++;
            continue;
        }

        try {
            $sth = $db->prepare("SELECT id FROM {$tbl} WHERE alias = :alias");
            $sth->bindValue(':alias', $alias);
            $sth->execute();
            $id = (int) $sth->fetchColumn();

            $email     = (string) ($row['email'] ?? '');
            $phone     = (string) ($row['phone'] ?? '');
            $fax       = (string) ($row['fax'] ?? '');
            $address   = (string) ($row['address'] ?? '');
            $note      = (string) ($row['note'] ?? '');
            $weight    = (int)    ($row['weight'] ?? 0);
            $act       = (int)    ($row['act'] ?? 1);
            $isDefault = (int)    ($row['is_default'] ?? 0);

            if ($id > 0) {
                $upd = $db->prepare("UPDATE {$tbl}
                    SET full_name = :full_name, email = :email, phone = :phone, fax = :fax, address = :address,
                        note = :note, weight = :weight, act = :act, is_default = :is_default
                    WHERE id = :id");
                $upd->bindValue(':full_name',  $fullName);
                $upd->bindValue(':email',      $email);
                $upd->bindValue(':phone',      $phone);
                $upd->bindValue(':fax',        $fax);
                $upd->bindValue(':address',    $address);
                $upd->bindValue(':note',       $note);
                $upd->bindValue(':weight',     $weight, PDO::PARAM_INT);
                $upd->bindValue(':act',        $act,    PDO::PARAM_INT);
                $upd->bindValue(':is_default', $isDefault, PDO::PARAM_INT);
                $upd->bindValue(':id',         $id, PDO::PARAM_INT);
                $upd->execute();

                $tracker->log('departments', $alias, $id, $tbl, SeedTracker::ACTION_UPDATED);
                out_ok("updated  {$alias} (id={$id})");
                $updated++;
            } else {
                $ins = $db->prepare("INSERT INTO {$tbl}
                    (full_name, alias, image, phone, fax, email, address, note, others, cats, admins, act, weight, is_default)
                    VALUES (:full_name, :alias, '', :phone, :fax, :email, :address, :note, '', '', '', :act, :weight, :is_default)");
                $ins->bindValue(':full_name',  $fullName);
                $ins->bindValue(':alias',      $alias);
                $ins->bindValue(':phone',      $phone);
                $ins->bindValue(':fax',        $fax);
                $ins->bindValue(':email',      $email);
                $ins->bindValue(':address',    $address);
                $ins->bindValue(':note',       $note);
                $ins->bindValue(':act',        $act,    PDO::PARAM_INT);
                $ins->bindValue(':weight',     $weight, PDO::PARAM_INT);
                $ins->bindValue(':is_default', $isDefault, PDO::PARAM_INT);
                $ins->execute();

                $id = (int) $db->lastInsertId();
                $tracker->log('departments', $alias, $id, $tbl, SeedTracker::ACTION_CREATED);
                out_ok("created  {$alias} (id={$id})");
                $created++;
            }
        } catch (Throwable $e) {
            $tracker->log('departments', $alias, 0, $tbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$alias} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done departments  created={$created} updated={$updated} failed={$failed}");
}

/**
 * Seed 6 menu (topnav, mega, footer 1-4).
 * - Bảng `_menu`: lookup theo title (UNIQUE)
 * - Bảng `_menu_rows`: tạo lại từ đầu mỗi lần seed (xóa hết item của menu rồi insert lại)
 */
function seed_menus(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('menus');
    $items    = $manifest['items'] ?? [];
    $lang     = NV_LANG_DATA;
    $menuTbl  = $db_config['prefix'] . '_' . $lang . '_menu';
    $rowsTbl  = $db_config['prefix'] . '_' . $lang . '_menu_rows';
    $catTbl   = $db_config['prefix'] . '_' . $lang . '_news_cat';

    out_info("[seeder] step=menus run_id={$tracker->getRunId()} tables={$menuTbl} + {$rowsTbl}");

    // Pre-fetch cat alias → catid để map các item dạng {catalias}
    $catMap = [];
    $sth = $db->query("SELECT catid, alias, title FROM {$catTbl}");
    foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $catMap[(string) $r['alias']] = ['catid' => (int) $r['catid'], 'title' => (string) $r['title']];
    }

    $created = $updated = $failed = 0;

    foreach ($items as $menu) {
        $title = (string) ($menu['title'] ?? '');
        if ($title === '') {
            out_err("Menu thiếu title — bỏ qua");
            $failed++;
            continue;
        }

        try {
            $sth = $db->prepare("SELECT id FROM {$menuTbl} WHERE title = :title");
            $sth->bindValue(':title', $title);
            $sth->execute();
            $mid = (int) $sth->fetchColumn();
            $isCreated = false;

            if ($mid === 0) {
                $ins = $db->prepare("INSERT INTO {$menuTbl} (title) VALUES (:title)");
                $ins->bindValue(':title', $title);
                $ins->execute();
                $mid = (int) $db->lastInsertId();
                $isCreated = true;
            }

            // Reset items: xóa hết menu_rows của menu này rồi insert lại
            $del = $db->prepare("DELETE FROM {$rowsTbl} WHERE mid = :mid");
            $del->bindValue(':mid', $mid, PDO::PARAM_INT);
            $del->execute();

            $insertedCount = 0;

            if (isset($menu['groups']) && is_array($menu['groups'])) {
                // Mega menu — group node parentid=0, item con parentid=group_id
                $groupSort = 0;
                foreach ($menu['groups'] as $group) {
                    $groupSort++;
                    $groupNodeId = menu_insert_row($db, $rowsTbl, $mid, 0, (string) $group['title'], '#group', $groupSort, 0);
                    $itemSort = 0;
                    foreach (($group['items'] ?? []) as $item) {
                        $itemSort++;
                        $resolved = menu_resolve_item($item, $catMap);
                        if ($resolved === null) continue;
                        menu_insert_row($db, $rowsTbl, $mid, $groupNodeId, $resolved['title'], $resolved['link'], $itemSort, 1);
                        $insertedCount++;
                    }
                }
            } else {
                // Menu phẳng
                $itemSort = 0;
                foreach (($menu['items'] ?? []) as $item) {
                    $itemSort++;
                    $resolved = menu_resolve_item($item, $catMap);
                    if ($resolved === null) continue;
                    menu_insert_row($db, $rowsTbl, $mid, 0, $resolved['title'], $resolved['link'], $itemSort, 0);
                    $insertedCount++;
                }
            }

            $action = $isCreated ? SeedTracker::ACTION_CREATED : SeedTracker::ACTION_UPDATED;
            $tracker->log('menus', $title, $mid, $menuTbl, $action, "{$insertedCount} items");

            if ($isCreated) {
                out_ok("created  '{$title}' (id={$mid}, items={$insertedCount})");
                $created++;
            } else {
                out_ok("updated  '{$title}' (id={$mid}, items={$insertedCount})");
                $updated++;
            }
        } catch (Throwable $e) {
            $tracker->log('menus', $title, 0, $menuTbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   '{$title}' — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done menus  created={$created} updated={$updated} failed={$failed}");
}

/**
 * Resolve 1 menu item về cặp [title, link].
 *
 * @param array<string,mixed> $item
 * @param array<string, array{catid:int,title:string}> $catMap
 * @return array{title:string, link:string}|null
 */
function menu_resolve_item(array $item, array $catMap): ?array
{
    if (isset($item['catalias'])) {
        $alias = (string) $item['catalias'];
        if (!isset($catMap[$alias])) {
            out_warn("    catalias không tìm thấy trong DB: {$alias} — skip");
            return null;
        }
        return [
            'title' => $catMap[$alias]['title'],
            'link'  => '?nv=news&op=' . $alias,
        ];
    }
    if (isset($item['title']) && isset($item['link'])) {
        return ['title' => (string) $item['title'], 'link' => (string) $item['link']];
    }
    return null;
}

/**
 * Insert 1 dòng vào menu_rows. Trả về id mới.
 */
function menu_insert_row(PDO $db, string $tbl, int $mid, int $parentid, string $title, string $link, int $sort, int $lev): int
{
    $sql = "INSERT INTO {$tbl}
        (parentid, mid, title, link, icon, image, note, weight, sort, lev, subitem, groups_view, module_name, op, target, css, active_type, status)
        VALUES (:parentid, :mid, :title, :link, '', '', '', :weight, :sort, :lev, '', '6', '', '', 0, '', 0, 1)";
    $sth = $db->prepare($sql);
    $sth->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $sth->bindValue(':mid',      $mid,      PDO::PARAM_INT);
    $sth->bindValue(':title',    $title);
    $sth->bindValue(':link',     $link);
    $sth->bindValue(':weight',   $sort,     PDO::PARAM_INT);
    $sth->bindValue(':sort',     $sort,     PDO::PARAM_INT);
    $sth->bindValue(':lev',      $lev,      PDO::PARAM_INT);
    $sth->execute();
    return (int) $db->lastInsertId();
}

/**
 * Seed 5 vị trí banner (banners_plans).
 * - Idempotent theo natural key (title, blang). KEY title không UNIQUE → SELECT trước.
 */
function seed_banner_plans(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('banner-positions');
    $items    = $manifest['items'] ?? [];
    $tbl      = $db_config['prefix'] . '_banners_plans';

    out_info("[seeder] step=banner-positions run_id={$tracker->getRunId()} table={$tbl}");

    $created = $updated = $failed = 0;

    foreach ($items as $row) {
        $title = (string) ($row['title'] ?? '');
        $blang = (string) ($row['blang'] ?? 'vi');
        if ($title === '') {
            out_err("Item thiếu title — bỏ qua");
            $failed++;
            continue;
        }

        try {
            $sth = $db->prepare("SELECT id FROM {$tbl} WHERE title = :title AND blang = :blang");
            $sth->bindValue(':title', $title);
            $sth->bindValue(':blang', $blang);
            $sth->execute();
            $id = (int) $sth->fetchColumn();

            $form          = (string) ($row['form'] ?? 'image');
            $width         = (int)    ($row['width'] ?? 0);
            $height        = (int)    ($row['height'] ?? 0);
            $act           = (int)    ($row['act'] ?? 1);
            $description   = (string) ($row['description'] ?? '');
            $requireImage  = (int)    ($row['require_image'] ?? 1);
            $uploadtype    = (string) ($row['uploadtype'] ?? '');
            $uploadgroup   = (string) ($row['uploadgroup'] ?? '');

            if ($id > 0) {
                $upd = $db->prepare("UPDATE {$tbl}
                    SET description = :description, form = :form, width = :width, height = :height, act = :act,
                        require_image = :require_image, uploadtype = :uploadtype, uploadgroup = :uploadgroup
                    WHERE id = :id");
                $upd->bindValue(':description',   $description);
                $upd->bindValue(':form',          $form);
                $upd->bindValue(':width',         $width,  PDO::PARAM_INT);
                $upd->bindValue(':height',        $height, PDO::PARAM_INT);
                $upd->bindValue(':act',           $act,    PDO::PARAM_INT);
                $upd->bindValue(':require_image', $requireImage, PDO::PARAM_INT);
                $upd->bindValue(':uploadtype',    $uploadtype);
                $upd->bindValue(':uploadgroup',   $uploadgroup);
                $upd->bindValue(':id',            $id, PDO::PARAM_INT);
                $upd->execute();

                $tracker->log('banner-positions', $title, $id, $tbl, SeedTracker::ACTION_UPDATED);
                out_ok("updated  {$title} (id={$id})");
                $updated++;
            } else {
                $ins = $db->prepare("INSERT INTO {$tbl}
                    (blang, title, description, form, width, height, act, require_image, uploadtype, uploadgroup, exp_time)
                    VALUES (:blang, :title, :description, :form, :width, :height, :act, :require_image, :uploadtype, :uploadgroup, 0)");
                $ins->bindValue(':blang',         $blang);
                $ins->bindValue(':title',         $title);
                $ins->bindValue(':description',   $description);
                $ins->bindValue(':form',          $form);
                $ins->bindValue(':width',         $width,  PDO::PARAM_INT);
                $ins->bindValue(':height',        $height, PDO::PARAM_INT);
                $ins->bindValue(':act',           $act,    PDO::PARAM_INT);
                $ins->bindValue(':require_image', $requireImage, PDO::PARAM_INT);
                $ins->bindValue(':uploadtype',    $uploadtype);
                $ins->bindValue(':uploadgroup',   $uploadgroup);
                $ins->execute();

                $id = (int) $db->lastInsertId();
                $tracker->log('banner-positions', $title, $id, $tbl, SeedTracker::ACTION_CREATED);
                out_ok("created  {$title} (id={$id})");
                $created++;
            }
        } catch (Throwable $e) {
            $tracker->log('banner-positions', $title, 0, $tbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$title} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done banner-positions  created={$created} updated={$updated} failed={$failed}");
}

// ────────────────────────────────────────────────────────────────────────────
// Hàm seed — P2.B
// ────────────────────────────────────────────────────────────────────────────

require_once __DIR__ . '/../lib/LoremNewsGenerator.php';
require_once __DIR__ . '/../lib/PicsumImageDownloader.php';

use Seeder\LoremNewsGenerator;
use Seeder\PicsumImageDownloader;

/**
 * Seed 3 user demo.
 * - Idempotent theo username (UNIQUE)
 * - Hash password qua $crypt->hash_password() — KHÔNG dùng password_hash() PHP thuần
 */
function seed_users(SeedTracker $tracker): void
{
    global $db, $db_config, $crypt, $global_config;

    $manifest = load_manifest('users');
    $items    = $manifest['items'] ?? [];
    $defaultPassword = (string) ($manifest['_default_password'] ?? 'newsviet@2026');
    $tbl = $db_config['prefix'] . '_users';

    out_info("[seeder] step=users run_id={$tracker->getRunId()} table={$tbl}");

    if (!isset($crypt) || !is_object($crypt)) {
        out_err('Không tìm thấy $crypt trong context. NV5 mainfile đã bootstrap chưa?');
        return;
    }

    $hashprefix = (string) ($global_config['hashprefix'] ?? '{SSHA}');

    $created = $updated = $failed = 0;

    foreach ($items as $row) {
        $username = (string) ($row['username'] ?? '');
        $email    = (string) ($row['email'] ?? '');
        if ($username === '' || $email === '') {
            out_err("Item thiếu username/email — bỏ qua");
            $failed++;
            continue;
        }

        try {
            $sth = $db->prepare("SELECT userid FROM {$tbl} WHERE username = :username");
            $sth->bindValue(':username', $username);
            $sth->execute();
            $userid = (int) $sth->fetchColumn();

            $now         = NV_CURRENTTIME;
            $firstName   = (string) ($row['first_name'] ?? '');
            $lastName    = (string) ($row['last_name'] ?? '');
            $gender      = (string) ($row['gender'] ?? '');
            $groupId     = (int)    ($row['group_id'] ?? 4);
            $active      = (int)    ($row['active'] ?? 1);
            $note        = (string) ($row['note'] ?? '');
            $md5username = md5($username);

            if ($userid > 0) {
                // UPDATE — không đổi password để khỏi reset login đang dùng
                $upd = $db->prepare("UPDATE {$tbl}
                    SET email = :email, first_name = :first_name, last_name = :last_name,
                        gender = :gender, group_id = :group_id, active = :active,
                        sig = :sig, last_update = :last_update
                    WHERE userid = :userid");
                $upd->bindValue(':email',       $email);
                $upd->bindValue(':first_name',  $firstName);
                $upd->bindValue(':last_name',   $lastName);
                $upd->bindValue(':gender',      $gender);
                $upd->bindValue(':group_id',    $groupId, PDO::PARAM_INT);
                $upd->bindValue(':active',      $active,  PDO::PARAM_INT);
                $upd->bindValue(':sig',         $note);
                $upd->bindValue(':last_update', $now,    PDO::PARAM_INT);
                $upd->bindValue(':userid',      $userid, PDO::PARAM_INT);
                $upd->execute();

                $tracker->log('users', $username, $userid, $tbl, SeedTracker::ACTION_UPDATED);
                out_ok("updated  {$username} (userid={$userid})");
                $updated++;
            } else {
                $passwordHash = $crypt->hash_password($defaultPassword, $hashprefix);

                $ins = $db->prepare("INSERT INTO {$tbl}
                    (group_id, username, md5username, password, email, first_name, last_name,
                     gender, birthday, sig, regdate, question, answer, active, language,
                     pass_creation_time, last_update)
                    VALUES (:group_id, :username, :md5username, :password, :email, :first_name, :last_name,
                            :gender, 0, :sig, :regdate, '', '', :active, 'vi',
                            :pass_creation_time, :last_update)");
                $ins->bindValue(':group_id',           $groupId, PDO::PARAM_INT);
                $ins->bindValue(':username',           $username);
                $ins->bindValue(':md5username',        $md5username);
                $ins->bindValue(':password',           $passwordHash);
                $ins->bindValue(':email',              $email);
                $ins->bindValue(':first_name',         $firstName);
                $ins->bindValue(':last_name',          $lastName);
                $ins->bindValue(':gender',             $gender);
                $ins->bindValue(':sig',                $note);
                $ins->bindValue(':regdate',            $now, PDO::PARAM_INT);
                $ins->bindValue(':active',             $active, PDO::PARAM_INT);
                $ins->bindValue(':pass_creation_time', $now, PDO::PARAM_INT);
                $ins->bindValue(':last_update',        $now, PDO::PARAM_INT);
                $ins->execute();

                $userid = (int) $db->lastInsertId();
                $tracker->log('users', $username, $userid, $tbl, SeedTracker::ACTION_CREATED, "Default password: {$defaultPassword}");
                out_ok("created  {$username} (userid={$userid}, password='{$defaultPassword}')");
                $created++;
            }
        } catch (Throwable $e) {
            $tracker->log('users', $username, 0, $tbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$username} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done users  created={$created} updated={$updated} failed={$failed}");
}

/**
 * Seed 80 bài viết mẫu.
 * - Mỗi bài: INSERT vào `_news_rows` + `_news_<catid>` (clone từ _rows) + `_news_detail`
 * - Idempotent theo natural key (catid, alias)
 * - Ảnh: tải picsum.photos theo image_seed, lưu vào uploads/news/<catalias>/
 */
function seed_articles(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('articles.sample');
    $items    = $manifest['items'] ?? [];
    $imageSizes = $manifest['_image_size'] ?? [];
    $lang     = NV_LANG_DATA;

    $catTbl    = $db_config['prefix'] . '_' . $lang . '_news_cat';
    $rowsTbl   = $db_config['prefix'] . '_' . $lang . '_news_rows';
    $detailTbl = $db_config['prefix'] . '_' . $lang . '_news_detail';
    $topicTbl  = $db_config['prefix'] . '_' . $lang . '_news_topics';

    out_info("[seeder] step=articles run_id={$tracker->getRunId()} count=" . count($items));

    // Pre-fetch cat + topic
    $catMap = [];
    foreach ($db->query("SELECT catid, alias FROM {$catTbl}")->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $catMap[(string) $r['alias']] = (int) $r['catid'];
    }
    $topicMap = [];
    foreach ($db->query("SELECT topicid, alias FROM {$topicTbl}")->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $topicMap[(string) $r['alias']] = (int) $r['topicid'];
    }

    $picsum = new PicsumImageDownloader(NV_ROOTDIR . '/uploads', false);

    $created = $updated = $skipped = $failed = 0;
    $publtime = NV_CURRENTTIME;

    // Thư mục ảnh theo Y_m (vd 2026_05) — dễ tạo thumb hàng loạt sau.
    $imgSubDir = 'news/' . date('Y_m', NV_CURRENTTIME);

    foreach ($items as $row) {
        $catalias = (string) ($row['catalias'] ?? '');
        $title    = (string) ($row['title'] ?? '');
        if ($catalias === '' || $title === '') {
            out_err("Item thiếu catalias/title — bỏ qua");
            $failed++;
            continue;
        }

        $catid = $catMap[$catalias] ?? 0;
        if ($catid === 0) {
            out_warn("    cat '{$catalias}' chưa có trong DB — skip");
            $skipped++;
            continue;
        }

        $alias = make_alias($title);
        if ($alias === '') {
            out_err("Không sinh được alias từ title: {$title}");
            $failed++;
            continue;
        }

        try {
            $catSpecificTbl = $db_config['prefix'] . '_' . $lang . '_news_' . $catid;

            $sth = $db->prepare("SELECT id FROM {$rowsTbl} WHERE alias = :alias AND catid = :catid");
            $sth->bindValue(':alias', $alias);
            $sth->bindValue(':catid', $catid, PDO::PARAM_INT);
            $sth->execute();
            $articleId = (int) $sth->fetchColumn();

            $hometop  = (int) ($row['hometop'] ?? 0);
            $imageSeed = (string) ($row['image_seed'] ?? $alias);

            // Sinh nội dung
            $hometext = LoremNewsGenerator::generateHometext($title, $catalias);
            $bodyhtml = LoremNewsGenerator::generateBodytext($title, $catalias);

            // Tải ảnh đại diện
            $imgWidth  = $hometop ? ($imageSizes['hometop'][0] ?? 1200)
                : ($imageSizes['default'][0] ?? 800);
            $imgHeight = $hometop ? ($imageSizes['hometop'][1] ?? 630)
                : ($imageSizes['default'][1] ?? 500);
            if ($catalias === 'chan-dung') {
                $imgWidth  = $imageSizes['chan-dung'][0] ?? 600;
                $imgHeight = $imageSizes['chan-dung'][1] ?? 800;
            }
            // PicsumImageDownloader trả về 'uploads/news/<Y_m>/<filename>'.
            // Convention NV5 module news: homeimgfile lưu relative từ `uploads/news/`,
            // tức chỉ '<Y_m>/<filename>' (xem modules/news/admin/content.php:498-499,1115).
            // Lưu theo Y_m (vd 2026_05) thay vì <catalias> để tiện tạo thumb hàng loạt.
            $picsumPath = $picsum->fetch($imageSeed, $imgWidth, $imgHeight, $imgSubDir);
            $homeimgfile = $picsumPath !== null
                ? preg_replace('#^uploads/news/#', '', $picsumPath)
                : '';
            $homeimgalt  = $title;

            // Topic ID nếu có
            $topicid = 0;
            if (isset($row['topic_alias'])) {
                $topicid = $topicMap[(string) $row['topic_alias']] ?? 0;
            }

            $now = NV_CURRENTTIME;

            $db->beginTransaction();

            if ($articleId > 0) {
                // UPDATE: chỉ update _rows + _<catid> + _detail; image giữ nếu đã có
                $upd = $db->prepare("UPDATE {$rowsTbl}
                    SET title = :title, hometext = :hometext, homeimgfile = :homeimgfile, homeimgalt = :homeimgalt,
                        homeimgthumb = :homeimgthumb, topicid = :topicid, inhome = :inhome, edittime = :edittime
                    WHERE id = :id");
                $upd->bindValue(':title',       $title);
                $upd->bindValue(':hometext',    $hometext);
                $upd->bindValue(':homeimgfile', $homeimgfile);
                $upd->bindValue(':homeimgalt',  $homeimgalt);
                $upd->bindValue(':homeimgthumb',  $homeimgfile !== '' ? 2 : 0, PDO::PARAM_INT);
                $upd->bindValue(':topicid',     $topicid, PDO::PARAM_INT);
                $upd->bindValue(':inhome',      $hometop, PDO::PARAM_INT);
                $upd->bindValue(':edittime',    $now, PDO::PARAM_INT);
                $upd->bindValue(':id',          $articleId, PDO::PARAM_INT);
                $upd->execute();

                // Update bảng _<catid> tương ứng (clone giữ schema giống _rows)
                $upd2 = $db->prepare("UPDATE {$catSpecificTbl}
                    SET title = :title, hometext = :hometext, homeimgfile = :homeimgfile, homeimgalt = :homeimgalt,
                        homeimgthumb = :homeimgthumb, topicid = :topicid, inhome = :inhome, edittime = :edittime
                    WHERE id = :id");
                $upd2->bindValue(':title',       $title);
                $upd2->bindValue(':hometext',    $hometext);
                $upd2->bindValue(':homeimgfile', $homeimgfile);
                $upd2->bindValue(':homeimgalt',  $homeimgalt);
                $upd2->bindValue(':homeimgthumb',  $homeimgfile !== '' ? 2 : 0, PDO::PARAM_INT);
                $upd2->bindValue(':topicid',     $topicid, PDO::PARAM_INT);
                $upd2->bindValue(':inhome',      $hometop, PDO::PARAM_INT);
                $upd2->bindValue(':edittime',    $now, PDO::PARAM_INT);
                $upd2->bindValue(':id',          $articleId, PDO::PARAM_INT);
                $upd2->execute();

                $upd3 = $db->prepare("UPDATE {$detailTbl}
                    SET bodyhtml = :bodyhtml WHERE id = :id");
                $upd3->bindValue(':bodyhtml', $bodyhtml);
                $upd3->bindValue(':id',       $articleId, PDO::PARAM_INT);
                $upd3->execute();

                $db->commit();

                $tracker->log('articles', $alias, $articleId, $rowsTbl, SeedTracker::ACTION_UPDATED, "img={$homeimgfile}");
                out_ok("updated  {$catalias}/{$alias} (id={$articleId})");
                $updated++;
            } else {
                // INSERT _rows
                $sql = "INSERT INTO {$rowsTbl}
                    (catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime,
                     status, weight, publtime, exptime, archive, title, alias, hometext,
                     homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating,
                     external_link, hitstotal)
                    VALUES (:catid, :listcatid, :topicid, 1, 'Demo', 0, :addtime, :edittime,
                            1, 0, :publtime, 0, 0, :title, :alias, :hometext,
                            :homeimgfile, :homeimgalt, :homeimgthumb, :inhome, '1', 0, 0, 0)";
                $ins = $db->prepare($sql);
                $ins->bindValue(':catid',        $catid, PDO::PARAM_INT);
                $ins->bindValue(':listcatid',    (string) $catid);
                $ins->bindValue(':topicid',      $topicid, PDO::PARAM_INT);
                $ins->bindValue(':addtime',      $now, PDO::PARAM_INT);
                $ins->bindValue(':edittime',     $now, PDO::PARAM_INT);
                $ins->bindValue(':publtime',     $publtime, PDO::PARAM_INT);
                $ins->bindValue(':title',        $title);
                $ins->bindValue(':alias',        $alias);
                $ins->bindValue(':hometext',     $hometext);
                $ins->bindValue(':homeimgfile',  $homeimgfile);
                $ins->bindValue(':homeimgalt',   $homeimgalt);
                $ins->bindValue(':homeimgthumb', $homeimgfile !== '' ? 2 : 0, PDO::PARAM_INT);
                $ins->bindValue(':inhome',       $hometop, PDO::PARAM_INT);
                $ins->execute();

                $articleId = (int) $db->lastInsertId();

                // INSERT _<catid> với cùng id (set explicit)
                $sql2 = "INSERT INTO {$catSpecificTbl}
                    (id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime,
                     status, weight, publtime, exptime, archive, title, alias, hometext,
                     homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating,
                     external_link, hitstotal)
                    VALUES (:id, :catid, :listcatid, :topicid, 1, 'Demo', 0, :addtime, :edittime,
                            1, 0, :publtime, 0, 0, :title, :alias, :hometext,
                            :homeimgfile, :homeimgalt, :homeimgthumb, :inhome, '1', 0, 0, 0)";
                $ins2 = $db->prepare($sql2);
                $ins2->bindValue(':id',           $articleId, PDO::PARAM_INT);
                $ins2->bindValue(':catid',        $catid, PDO::PARAM_INT);
                $ins2->bindValue(':listcatid',    (string) $catid);
                $ins2->bindValue(':topicid',      $topicid, PDO::PARAM_INT);
                $ins2->bindValue(':addtime',      $now, PDO::PARAM_INT);
                $ins2->bindValue(':edittime',     $now, PDO::PARAM_INT);
                $ins2->bindValue(':publtime',     $publtime, PDO::PARAM_INT);
                $ins2->bindValue(':title',        $title);
                $ins2->bindValue(':alias',        $alias);
                $ins2->bindValue(':hometext',     $hometext);
                $ins2->bindValue(':homeimgfile',  $homeimgfile);
                $ins2->bindValue(':homeimgalt',   $homeimgalt);
                $ins2->bindValue(':homeimgthumb', $homeimgfile !== '' ? 2 : 0, PDO::PARAM_INT);
                $ins2->bindValue(':inhome',       $hometop, PDO::PARAM_INT);
                $ins2->execute();

                // INSERT _detail
                $ins3 = $db->prepare("INSERT INTO {$detailTbl}
                    (id, titlesite, description, bodyhtml, keywords)
                    VALUES (:id, :titlesite, :description, :bodyhtml, :keywords)");
                $ins3->bindValue(':id',          $articleId, PDO::PARAM_INT);
                $ins3->bindValue(':titlesite',   $title);
                $ins3->bindValue(':description', mb_substr(strip_tags($hometext), 0, 250));
                $ins3->bindValue(':bodyhtml',    $bodyhtml);
                $ins3->bindValue(':keywords',    '');
                $ins3->execute();

                $db->commit();

                $imgInfo = $homeimgfile !== '' ? ", image={$homeimgfile}" : ', no image';
                $tracker->log('articles', $alias, $articleId, $rowsTbl, SeedTracker::ACTION_CREATED, $homeimgfile);
                out_ok("created  {$catalias}/{$alias} (id={$articleId}{$imgInfo})");
                $created++;
            }
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $tracker->log('articles', $alias, 0, $rowsTbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$catalias}/{$alias} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done articles  created={$created} updated={$updated} skipped={$skipped} failed={$failed}");
}

/**
 * Seed 10 banner mẫu.
 * - Lookup pid từ banners_plans.title
 * - Idempotent theo natural key (title)
 * - Ảnh: tải picsum theo image_seed với kích thước = plan.width × plan.height
 */
function seed_banners(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('banners.sample');
    $items    = $manifest['items'] ?? [];
    $plansTbl = $db_config['prefix'] . '_banners_plans';
    $rowsTbl  = $db_config['prefix'] . '_banners_rows';

    out_info("[seeder] step=banners run_id={$tracker->getRunId()} table={$rowsTbl}");

    // Pre-fetch plan map
    $planMap = [];
    foreach ($db->query("SELECT id, title, width, height FROM {$plansTbl}")->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $planMap[(string) $p['title']] = ['id' => (int) $p['id'], 'width' => (int) $p['width'], 'height' => (int) $p['height']];
    }

    $picsum = new PicsumImageDownloader(NV_ROOTDIR . '/uploads', false);

    $created = $updated = $skipped = $failed = 0;
    $now = NV_CURRENTTIME;

    foreach ($items as $row) {
        $title    = (string) ($row['title'] ?? '');
        $planTitle = (string) ($row['plan_title'] ?? '');
        if ($title === '' || $planTitle === '') {
            out_err("Item thiếu title/plan_title — bỏ qua");
            $failed++;
            continue;
        }

        if (!isset($planMap[$planTitle])) {
            out_warn("    plan '{$planTitle}' chưa có trong DB — skip");
            $skipped++;
            continue;
        }

        $plan = $planMap[$planTitle];
        $pid    = $plan['id'];
        $width  = $plan['width'];
        $height = $plan['height'];

        try {
            $sth = $db->prepare("SELECT id FROM {$rowsTbl} WHERE title = :title");
            $sth->bindValue(':title', $title);
            $sth->execute();
            $bid = (int) $sth->fetchColumn();

            $imageSeed = (string) ($row['image_seed'] ?? make_alias($title));
            $clickUrl  = (string) ($row['click_url'] ?? '#');
            $fileAlt   = (string) ($row['file_alt'] ?? $title);
            $act       = (int)    ($row['act'] ?? 1);
            $weight    = (int)    ($row['weight'] ?? 0);

            // Tải ảnh
            $imagePath = $picsum->fetch($imageSeed, $width, $height, 'banners') ?? '';
            $fileName  = $imagePath !== '' ? basename($imagePath) : '';
            $fileExt   = 'jpg';
            $fileMime  = 'image/jpeg';

            if ($bid > 0) {
                $upd = $db->prepare("UPDATE {$rowsTbl}
                    SET title = :title, pid = :pid, file_name = :file_name, file_ext = :file_ext, file_mime = :file_mime,
                        width = :width, height = :height, file_alt = :file_alt, click_url = :click_url,
                        target = '_blank', act = :act, weight = :weight
                    WHERE id = :id");
                $upd->bindValue(':title',     $title);
                $upd->bindValue(':pid',       $pid, PDO::PARAM_INT);
                $upd->bindValue(':file_name', $fileName);
                $upd->bindValue(':file_ext',  $fileExt);
                $upd->bindValue(':file_mime', $fileMime);
                $upd->bindValue(':width',     $width, PDO::PARAM_INT);
                $upd->bindValue(':height',    $height, PDO::PARAM_INT);
                $upd->bindValue(':file_alt',  $fileAlt);
                $upd->bindValue(':click_url', $clickUrl);
                $upd->bindValue(':act',       $act, PDO::PARAM_INT);
                $upd->bindValue(':weight',    $weight, PDO::PARAM_INT);
                $upd->bindValue(':id',        $bid, PDO::PARAM_INT);
                $upd->execute();

                $tracker->log('banners', $title, $bid, $rowsTbl, SeedTracker::ACTION_UPDATED, "img={$imagePath}");
                out_ok("updated  {$title} (id={$bid})");
                $updated++;
            } else {
                $ins = $db->prepare("INSERT INTO {$rowsTbl}
                    (title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt,
                     imageforswf, click_url, target, bannerhtml, add_time, publ_time, exp_time,
                     hits_total, act, weight)
                    VALUES (:title, :pid, 0, :file_name, :file_ext, :file_mime, :width, :height, :file_alt,
                            '', :click_url, '_blank', '', :add_time, :publ_time, 0,
                            0, :act, :weight)");
                $ins->bindValue(':title',     $title);
                $ins->bindValue(':pid',       $pid, PDO::PARAM_INT);
                $ins->bindValue(':file_name', $fileName);
                $ins->bindValue(':file_ext',  $fileExt);
                $ins->bindValue(':file_mime', $fileMime);
                $ins->bindValue(':width',     $width, PDO::PARAM_INT);
                $ins->bindValue(':height',    $height, PDO::PARAM_INT);
                $ins->bindValue(':file_alt',  $fileAlt);
                $ins->bindValue(':click_url', $clickUrl);
                $ins->bindValue(':add_time',  $now, PDO::PARAM_INT);
                $ins->bindValue(':publ_time', $now, PDO::PARAM_INT);
                $ins->bindValue(':act',       $act, PDO::PARAM_INT);
                $ins->bindValue(':weight',    $weight, PDO::PARAM_INT);
                $ins->execute();

                $bid = (int) $db->lastInsertId();
                $tracker->log('banners', $title, $bid, $rowsTbl, SeedTracker::ACTION_CREATED, "img={$imagePath}");
                $imgInfo = $imagePath !== '' ? ", image={$imagePath}" : ', no image';
                out_ok("created  {$title} (id={$bid}{$imgInfo})");
                $created++;
            }
        } catch (Throwable $e) {
            $tracker->log('banners', $title, 0, $rowsTbl, SeedTracker::ACTION_FAILED, $e->getMessage());
            out_err("failed   {$title} — " . $e->getMessage());
            $failed++;
        }
    }

    out_info("[seeder] done banners  created={$created} updated={$updated} skipped={$skipped} failed={$failed}");
}

/**
 * Seed theme config — ghi vào themes/<TARGET>/language/vi.php phần $lang_global mở rộng.
 * KHÔNG động vào config.ini của theme (positions đã chốt).
 *
 * Theme target được đọc từ manifest field `_target_theme` (vd "newsviet").
 * Mặc định nếu không khai báo: dùng theme đang active của site ($global_config['site_theme']).
 *
 * Ghi cuối file vi.php một block `// === SEEDER-MARKER ===` ... `// === END SEEDER ===`
 * để admin có thể nhìn thấy block tự động và edit/xóa nếu cần.
 */
function seed_theme_config(SeedTracker $tracker): void
{
    global $global_config;

    $manifest = load_manifest('theme-config');

    // Theme target: manifest field _target_theme > theme đang active > 'default'
    $targetTheme = (string) ($manifest['_target_theme'] ?? '');
    if ($targetTheme === '') {
        $targetTheme = (string) ($global_config['site_theme'] ?? 'default');
    }
    $themeLangFile = NV_ROOTDIR . '/themes/' . $targetTheme . '/language/vi.php';

    out_info("[seeder] step=theme-config run_id={$tracker->getRunId()} target_theme={$targetTheme} file={$themeLangFile}");

    if (!is_file($themeLangFile)) {
        out_err("Theme '{$targetTheme}' chưa được cài hoặc không có language/vi.php. File không tồn tại: {$themeLangFile}");
        $tracker->log('theme-config', 'lang_file', 0, '(file)', SeedTracker::ACTION_FAILED, 'theme file missing');
        return;
    }

    $marker = '// === SEEDER-MARKER ===';
    $endMarker = '// === END SEEDER ===';

    $current = file_get_contents($themeLangFile);
    if ($current === false) {
        out_err("Không đọc được {$themeLangFile}");
        return;
    }

    // Build block PHP để ghi
    $logo     = $manifest['logo'] ?? [];
    $hotline  = $manifest['hotline'] ?? [];
    $newsroom = $manifest['newsroom'] ?? [];
    $hq       = $manifest['hq'] ?? [];
    $hcm      = $manifest['hcm'] ?? [];
    $ads      = $manifest['ads'] ?? [];
    $channels = $manifest['channels'] ?? [];
    $hotKws   = $manifest['hot_keywords'] ?? [];

    $genTime = date('Y-m-d H:i:s', NV_CURRENTTIME);
    $lines = [];
    $lines[] = $marker;
    $lines[] = "// Sinh tự động bởi Seeder lúc {$genTime}. Sửa tay sẽ bị ghi đè khi seed lại.";
    $lines[] = "\$lang_global['nv_logo_text']    = " . var_export((string) ($logo['logo_text'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_logo_slogan']  = " . var_export((string) ($logo['logo_slogan'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hotline_hn']   = " . var_export((string) ($hotline['hn'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hotline_hcm']  = " . var_export((string) ($hotline['hcm'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_chief_editor'] = " . var_export((string) ($newsroom['chief_editor'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_license']      = " . var_export((string) ($newsroom['license_number'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_license_date'] = " . var_export((string) ($newsroom['license_date'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_founded']      = " . var_export((string) ($newsroom['founded_date'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_governing']    = " . var_export((string) ($newsroom['governing_body'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hq_address']   = " . var_export((string) ($hq['address'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hq_phone']     = " . var_export((string) ($hq['phone'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hq_fax']       = " . var_export((string) ($hq['fax'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hcm_address']  = " . var_export((string) ($hcm['address'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hcm_phone']    = " . var_export((string) ($hcm['phone'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hcm_fax']      = " . var_export((string) ($hcm['fax'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_ads_hotline']  = " . var_export((string) ($ads['hotline'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_ads_email']    = " . var_export((string) ($ads['email'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_channel_email']= " . var_export((string) ($channels['email'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_channel_fb']   = " . var_export((string) ($channels['fb'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_channel_rss']  = " . var_export((string) ($channels['rss'] ?? ''), true) . ';';
    $lines[] = "\$lang_global['nv_hot_keywords'] = " . var_export(array_values((array) $hotKws), true) . ';';
    $lines[] = $endMarker;

    $newBlock = "\n" . implode("\n", $lines) . "\n";

    // Replace block cũ (nếu có) hoặc append vào cuối file
    $pattern = '/\n*' . preg_quote($marker, '/') . '.*?' . preg_quote($endMarker, '/') . '\n*/s';
    if (preg_match($pattern, $current)) {
        $updated = preg_replace($pattern, $newBlock, $current);
        $action  = SeedTracker::ACTION_UPDATED;
    } else {
        $updated = rtrim($current) . "\n" . $newBlock;
        $action  = SeedTracker::ACTION_CREATED;
    }

    if (!is_writable($themeLangFile)) {
        out_err("File không có quyền ghi: {$themeLangFile}");
        $tracker->log('theme-config', 'lang_file', 0, '(file)', SeedTracker::ACTION_FAILED, 'file not writable');
        return;
    }

    if (file_put_contents($themeLangFile, $updated) === false) {
        out_err("Ghi file thất bại: {$themeLangFile}");
        $tracker->log('theme-config', 'lang_file', 0, '(file)', SeedTracker::ACTION_FAILED, 'write failed');
        return;
    }

    $tracker->log('theme-config', 'lang_file', 0, $themeLangFile, $action, count($lines) - 2 . ' keys');
    out_ok(($action === SeedTracker::ACTION_CREATED ? 'created' : 'updated') . "  {$themeLangFile} (" . (count($lines) - 2) . " keys)");
    out_info("[seeder] done theme-config");
}

// ────────────────────────────────────────────────────────────────────────────
// Helper public — gọi 1 step theo tên (dùng cho admin/run.php)
// ────────────────────────────────────────────────────────────────────────────

/**
 * Chạy 1 step seed theo tên. Return true nếu step được implement, false nếu không.
 */
function seed_run_step(string $step, SeedTracker $tracker): bool
{
    switch ($step) {
        case 'categories':
            seed_categories($tracker);
            return true;
        case 'topics':
            seed_topics($tracker);
            return true;
        case 'departments':
            seed_departments($tracker);
            return true;
        case 'menus':
            seed_menus($tracker);
            return true;
        case 'banner-positions':
            seed_banner_plans($tracker);
            return true;
        case 'users':
            seed_users($tracker);
            return true;
        case 'articles':
            seed_articles($tracker);
            return true;
        case 'banners':
            seed_banners($tracker);
            return true;
        case 'theme-config':
            seed_theme_config($tracker);
            return true;
        default:
            return false;
    }
}

/**
 * Chạy nhiều step theo thứ tự. Skip step không hợp lệ.
 *
 * @param array<int, string> $steps
 */
function seed_run_steps(array $steps, SeedTracker $tracker): void
{
    foreach ($steps as $step) {
        if (!seed_run_step($step, $tracker)) {
            out_warn("Bỏ qua step '{$step}' — chưa hỗ trợ.");
        }
    }
}

/**
 * Chạy ALL — toàn bộ 9 step theo thứ tự dependencies.
 */
function seed_run_all(SeedTracker $tracker): void
{
    $orderedSteps = [
        'categories',        // tạo cat + bảng _<catid>
        'topics',            // độc lập
        'departments',       // độc lập
        'banner-positions',  // independent
        'users',             // independent
        'menus',             // depend on categories
        'articles',          // depend on cat + topic
        'banners',           // depend on banner-positions
        'theme-config',      // ghi file theme/lang
    ];
    out_info("[seeder] === RUN ALL (9 steps theo thứ tự dependencies) ===");
    seed_run_steps($orderedSteps, $tracker);
    out_info("[seeder] === DONE ALL ===");
}

/**
 * Reset toàn bộ — xóa các item do seeder đã TẠO MỚI (action='created'), không động item user nhập tay.
 * Đọc danh sách từ bảng nv5_seeder_log.
 */
function seed_reset_all(SeedTracker $tracker): void
{
    global $db, $db_config;

    out_info("[seeder] === RESET (xóa item do seeder tạo, giữ item user nhập tay) ===");

    // Reset theo thứ tự ngược dependencies: articles → menus → banners → ... → categories
    $resetOrder = [
        'articles',
        'banners',
        'menus',
        'banner-positions',
        'departments',
        'topics',
        'users',
        'categories',  // cuối cùng — xóa cat + drop bảng _<catid>
        // theme-config: file I/O — reset bằng cách xóa block marker
    ];

    $totalDeleted = 0;
    foreach ($resetOrder as $step) {
        $items = $tracker->getCreatedItems($step);
        if (empty($items)) {
            out_info("  [{$step}] không có item nào do seeder tạo — skip");
            continue;
        }

        $stepDeleted = 0;
        foreach ($items as $item) {
            try {
                $dbId  = (int) $item['db_id'];
                $table = (string) $item['db_table'];
                $key   = (string) $item['natural_key'];

                if ($dbId <= 0 || $table === '') {
                    continue;
                }

                if ($step === 'articles') {
                    // Xóa _rows + _<catid> + _detail
                    $lang = NV_LANG_DATA;
                    $rowsTbl   = $db_config['prefix'] . '_' . $lang . '_news_rows';
                    $detailTbl = $db_config['prefix'] . '_' . $lang . '_news_detail';

                    // Lookup catid trước
                    $sth = $db->prepare("SELECT catid FROM {$rowsTbl} WHERE id = :id");
                    $sth->bindValue(':id', $dbId, PDO::PARAM_INT);
                    $sth->execute();
                    $catid = (int) $sth->fetchColumn();

                    $db->prepare("DELETE FROM {$rowsTbl} WHERE id = :id")->execute([':id' => $dbId]);
                    if ($catid > 0) {
                        $catSpecific = $db_config['prefix'] . '_' . $lang . '_news_' . $catid;
                        $db->prepare("DELETE FROM {$catSpecific} WHERE id = :id")->execute([':id' => $dbId]);
                    }
                    $db->prepare("DELETE FROM {$detailTbl} WHERE id = :id")->execute([':id' => $dbId]);
                } elseif ($step === 'categories') {
                    // Drop bảng _<catid> rồi xóa cat
                    $lang = NV_LANG_DATA;
                    $catSpecific = $db_config['prefix'] . '_' . $lang . '_news_' . $dbId;
                    $db->exec("DROP TABLE IF EXISTS {$catSpecific}");
                    $db->prepare("DELETE FROM {$table} WHERE catid = :id")->execute([':id' => $dbId]);
                } elseif ($step === 'menus') {
                    // Xóa menu + tất cả menu_rows liên quan
                    $lang = NV_LANG_DATA;
                    $menuRowsTbl = $db_config['prefix'] . '_' . $lang . '_menu_rows';
                    $db->prepare("DELETE FROM {$menuRowsTbl} WHERE mid = :mid")->execute([':mid' => $dbId]);
                    $db->prepare("DELETE FROM {$table} WHERE id = :id")->execute([':id' => $dbId]);
                } elseif ($step === 'topics') {
                    $db->prepare("DELETE FROM {$table} WHERE topicid = :id")->execute([':id' => $dbId]);
                } elseif ($step === 'users') {
                    $db->prepare("DELETE FROM {$table} WHERE userid = :id")->execute([':id' => $dbId]);
                } else {
                    // departments, banner-positions, banners — đều dùng PK 'id'
                    $db->prepare("DELETE FROM {$table} WHERE id = :id")->execute([':id' => $dbId]);
                }

                $stepDeleted++;
            } catch (Throwable $e) {
                out_err("    [{$step}] xóa {$key} (id={$dbId}) thất bại: " . $e->getMessage());
            }
        }

        $tracker->clearStep($step);
        out_ok("  [{$step}] đã xóa {$stepDeleted} item");
        $totalDeleted += $stepDeleted;
    }

    out_info("[seeder] === RESET DONE — đã xóa tổng cộng {$totalDeleted} item ===");
    out_info("Lưu ý: theme-config (file lang) không reset tự động — nếu cần, sửa tay file themes/<target>/language/vi.php hoặc xóa block giữa marker SEEDER.");
}

/**
 * Xóa cache NV5 (cache + smarty-compile). Gọi sau khi seed/reset xong.
 */
function seed_clear_cache(): void
{
    out_info("[seeder] === CLEAR CACHE ===");
    $patterns = [
        NV_ROOTDIR . '/' . NV_CACHEDIR . '/*/*.cache',
        NV_ROOTDIR . '/' . NV_CACHEDIR . '/smarty-compile/*.php',
    ];
    $totalDeleted = 0;
    foreach ($patterns as $pattern) {
        $files = glob($pattern) ?: [];
        foreach ($files as $f) {
            if (@unlink($f)) {
                $totalDeleted++;
            }
        }
    }
    out_ok("Đã xóa {$totalDeleted} file cache");
}
