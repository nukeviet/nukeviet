<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (! defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}

$alias_cat_url = $array_op[1];
$array_page = explode('-', $array_op[2]);
$id = intval(end($array_page));
$catid = 0;
foreach ($global_array_cat as $catid_i => $array_cat_i) {
    if ($alias_cat_url == $array_cat_i['alias']) {
        $catid = $catid_i;
        break;
    }
}
if ($id > 0 and $catid > 0) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id =' . $id;
    $result = $db_slave->query($sql);

    if ($result->rowCount() !== 1) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    $content = $result->fetch();

    $body_contents = $db_slave->query('SELECT bodyhtml as bodytext, sourcetext, imgposition, copyright, allowed_print FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $content['id'])->fetch();
    $content = array_merge($content, $body_contents);
    unset($sql, $result, $body_contents);

    if ($content['allowed_print'] == 1 and (defined('NV_IS_MODADMIN') or ($content['status'] == 1 and $content['publtime'] < NV_CURRENTTIME and ($content['exptime'] == 0 or $content['exptime'] > NV_CURRENTTIME)))) {
        $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print/' . $global_array_cat[$catid]['alias'] . '/' . $content['alias'] . '-' . $id . $global_config['rewrite_exturl'], true);
        if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
            nv_redirect_location($base_url_rewrite);
        }

        $sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $content['sourceid'];
        $result = $db_slave->query($sql);
        $sourcetext = $result->fetchColumn();
        unset($sql, $result);

        $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $content['alias'] . '-' . $id . $global_config['rewrite_exturl'], true);

        $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;

        $meta_tags = nv_html_meta_tags();

        $result = array(
            'url' => $global_config['site_url'],
            'meta_tags' => $meta_tags,
            'sitename' => $global_config['site_name'],
            'title' => $content['title'],
            'alias' => $content['alias'],
            'image' => '',
            'position' => $content['imgposition'],
            'time' => nv_date('l - d/m/Y H:i', $content['publtime']),
            'status' => $content['status'],
            'hometext' => $content['hometext'],
            'bodytext' => $content['bodytext'],
            'copyright' => $content['copyright'],
            'copyvalue' => $module_config[$module_name]['copyright'],
            'link' => "<a href=\"" . NV_MY_DOMAIN . $base_url_rewrite . "\" title=\"" . $content['title'] . "\">" . NV_MY_DOMAIN . $base_url_rewrite . "</a>\n",
            'contact' => $global_config['site_email'],
            'author' => $content['author'],
            'source' => $sourcetext
        );

        if (! empty($content['homeimgfile']) and $content['imgposition'] > 0) {
            $src = $alt = $note = '';
            $width = $height = 0;
            if ($content['homeimgthumb'] == 1 and $content['imgposition'] == 1) {
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

            $result['image'] = array(
                'src' => $src,
                'width' => $width,
                'alt' => $alt,
                'note' => $content['homeimgalt'],
                'position' => $content['imgposition']
            );
        }

        $page_title = $content['title'];
        $contents = call_user_func('news_print', $result);
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}
header('Location: ' . $global_config['site_url']);
exit();