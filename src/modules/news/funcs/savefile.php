<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

// Chặn BOT truy cập vào khu vực này
$nv_BotManager->setPrivate()->outputToHeaders($headers, $sys_info);

/**
 * nv_src_href_callback()
 *
 * @param array $matches
 * @return string
 */
function nv_src_href_callback($matches)
{
    // Bỏ qua URL tuyệt đối, URL không có giao thức, neo trong trang và các scheme đặc biệt
    if (!empty($matches[2]) and !preg_match('/^(https?\:\/\/|\/\/|#|mailto\:|tel\:|javascript|data\:)/', $matches[2])) {
        if (preg_match("/^\//", $matches[2])) {
            $_url = NV_MY_DOMAIN;
        } else {
            $_url = NV_MY_DOMAIN . '/';
        }
        $matches[2] = $_url . $matches[2];
    }

    return $matches[1] . '="' . $matches[2] . '"';
}

/**
 * Nhúng ảnh thuộc site vào thẻ img dạng data URI để xem được khi không có mạng
 *
 * @param array $matches
 * @return string
 */
function nv_img_embed_callback($matches)
{
    global $global_config;

    // Thẻ img ban đầu phải có thuộc tính src
    $tag = $matches[0];
    if (!preg_match('/\ssrc\s*=\s*(["\'])(.*?)\1/is', $tag, $m)) {
        return $tag;
    }

    $path = html_entity_decode(trim($m[2]), ENT_QUOTES, 'UTF-8');
    if (preg_match('/^(https?\:)?\/\//i', $path)) {
        // URL tuyệt đối chỉ nhận khi host thuộc các domain của site, ảnh remote giữ nguyên
        $url = parse_url(str_starts_with($path, '//') ? 'http:' . $path : $path);
        if (!is_array($url) or empty($url['host']) or !in_array(strtolower($url['host']), $global_config['my_domains'], true)) {
            return $tag;
        }
        $path = $url['path'] ?? '';
    } elseif (preg_match('/^[a-z][a-z0-9\+\.\-]*\:/i', $path)) {
        return $tag;
    } elseif (!str_starts_with($path, '/')) {
        $path = NV_BASE_SITEURL . $path;
    }
    $path = rawurldecode(preg_replace('/[\?#].*$/', '', $path));

    // Bỏ qua đường dẫn không hợp lệ
    if (preg_match('/[\x00-\x1F\x7F&"\'<>]/', $path)) {
        return $tag;
    }

    if (!nv_is_file($path, [NV_UPLOADS_DIR, NV_FILES_DIR, NV_ASSETS_DIR . '/images'])) {
        return $tag;
    }
    $filepath = realpath(NV_DOCUMENT_ROOT . $path);
    $mime = nv_get_mime_type($filepath);
    if (empty($mime) or !str_starts_with($mime, 'image/')) {
        return $tag;
    }
    if (str_starts_with($mime, 'image/svg')) {
        $mime = 'image/svg+xml';
    }
    $data = file_get_contents($filepath);
    if ($data === false) {
        return $tag;
    }

    $tag = str_replace($m[0], ' src="data:' . $mime . ';base64,' . base64_encode($data) . '"', $tag);

    // Bỏ srcset, sizes để trình duyệt không ưu tiên tải ảnh qua mạng
    return preg_replace('/\s(srcset|sizes)\s*=\s*(["\']).*?\2/is', '', $tag);
}

$id = $catid = 0;
if (isset($array_op[2])) {
    $alias_cat_url = $array_op[1];
    $array_page = explode('-', $array_op[2]);
    $id = (int) (end($array_page));
}
foreach ($global_array_cat as $catid_i => $array_cat_i) {
    if ($alias_cat_url == $array_cat_i['alias']) {
        $catid = $catid_i;
        break;
    }
}

if ($id <= 0 or $catid <= 0) {
    nv_error404();
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id =' . $id;
$result = $db->query($sql);
$content = $result->fetch();
unset($sql, $result);

if (empty($content)) {
    nv_error404();
}

$body_contents = $db->query('SELECT bodyhtml as bodytext, sourcetext, imgposition, copyright, allowed_save FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $content['id'])->fetch();
$content = array_merge($content, $body_contents);
unset($body_contents);

if (!($content['allowed_save'] == 1 and (defined('NV_IS_MODADMIN') or ($content['status'] == 1 and $content['publtime'] < NV_CURRENTTIME and ($content['exptime'] == 0 or $content['exptime'] > NV_CURRENTTIME))))) {
    nv_error404();
}

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=savefile/' . $global_array_cat[$catid]['alias'] . '/' . $content['alias'] . '-' . $id . $global_config['rewrite_exturl'];
$canonicalUrl = getCanonicalUrl($page_url);

$sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $content['sourceid'];
$result = $db->query($sql);
$sourcetext = $result->fetchColumn();
unset($sql, $result);

$meta_tags = nv_html_meta_tags();
$content['bodytext'] = $db->query('SELECT bodyhtml FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $content['id'])->fetchColumn();

$result = [
    'url' => $global_config['site_url'],
    'meta_tags' => $meta_tags,
    'sitename' => $global_config['site_name'],
    'title' => $content['title'],
    'alias' => $content['alias'],
    'image' => '',
    'position' => $content['imgposition'],
    'time' => nv_date_format(0, $content['publtime']) . ' ' . nv_time_format(1, $content['publtime']),
    'status' => $content['status'],
    'hometext' => $content['hometext'],
    'bodytext' => $content['bodytext'],
    'copyright' => $content['copyright'],
    'copyvalue' => $module_config[$module_name]['copyright'],
    'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $content['alias'] . '-' . $id . $global_config['rewrite_exturl'], NV_MY_DOMAIN),
    'contact' => $global_config['site_email'],
    'author' => $content['author'],
    'source' => $sourcetext
];

$authors = [];
$db->sqlreset()
    ->select('l.alias,l.pseudonym')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist l LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_author a ON l.aid=a.id')
    ->where('l.id = ' . $id . ' AND a.active=1');
$author_result = $db->query($db->sql());
while ($row = $author_result->fetch()) {
    $authors[] = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=author/' . $row['alias'] . '">' . $row['pseudonym'] . '</a>';
}
if (!empty($content['author'])) {
    $authors[] = $content['author'];
}
$result['author'] = !empty($authors) ? implode(', ', $authors) : '';

$page_title = $result['title'];

if (!empty($content['homeimgfile']) and $content['imgposition'] > 0) {
    $src = $alt = $note = '';
    $width = $height = 0;
    if ($content['homeimgthumb'] == 1 and $content['imgposition'] == 1 and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $content['homeimgfile'])) {
        $src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $content['homeimgfile'];
        $width = $module_config[$module_name]['homewidth'];
    } elseif ($content['homeimgthumb'] == 3) {
        $src = $content['homeimgfile'];
        $width = ($content['imgposition'] == 1) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
    } elseif (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $content['homeimgfile'])) {
        $src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $content['homeimgfile'];
        $width = ($content['imgposition'] == 1) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
    }
    $alt = (empty($content['homeimgalt'])) ? $content['title'] : $content['homeimgalt'];

    $result['image'] = [
        'src' => $src,
        'width' => $width,
        'alt' => $alt,
        'note' => $content['homeimgalt'],
        'position' => $content['imgposition']
    ];
}
// Tài liệu độc lập: không qua layout của site, không JS, CSS nhúng inline, ảnh thuộc site nhúng base64
$contents = call_user_func('news_savefile', $result);
$contents = preg_replace_callback("/(src|href)\=\"([^\"]+)\"/", 'nv_src_href_callback', nv_url_rewrite($contents));
$contents = preg_replace_callback('/<img\b[^>]*>/i', 'nv_img_embed_callback', $contents);

nv_htmlOutput($contents, 'html', [
    'Content-Disposition' => 'attachment; filename="' . $result['alias'] . '.html"'
]);
