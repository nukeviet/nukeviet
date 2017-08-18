<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}

if (empty($module_config[$module_name]['instant_articles_active'])) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

if (!empty($module_config[$module_name]['instant_articles_httpauth'])) {
    $auth = nv_set_authorization();

    if (empty($auth['auth_user']) or empty($auth['auth_pw']) or $auth['auth_user'] !== $module_config[$module_name]['instant_articles_username'] or $auth['auth_pw'] !== $crypt->decrypt($module_config[$module_name]['instant_articles_password'])) {
        header('WWW-Authenticate: Basic realm="Private Area"');
        header(NV_HEADERSTATUS . ' 401 Unauthorized');
        if (php_sapi_name() !== 'cgi-fcgi') {
            header('status: 401 Unauthorized');
        }
        nv_info_die($global_config['site_description'], $lang_global['site_info'], $lang_module['insrss_not_auth'], 401);
    }
}

$channel = array();
$items = array();
$gettime = empty($module_config[$module_name]['instant_articles_gettime']) ? 0 : (NV_CURRENTTIME - ($module_config[$module_name]['instant_articles_gettime'] * 60));

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

$catid = 0;
if (isset($array_op[1])) {
    $alias_cat_url = $array_op[1];
    $cattitle = '';
    foreach ($global_array_cat as $catid_i => $array_cat_i) {
        if ($alias_cat_url == $array_cat_i['alias']) {
            $catid = $catid_i;
            break;
        }
    }
}

$db_slave->sqlreset()
    ->select('id, catid, author, publtime, edittime, title, alias, hometext, homeimgfile, homeimgalt, instant_template, instant_creatauto')
    ->order($order_articles_by . ' DESC')
    ->limit(1000);

if (!empty($catid)) {
    $channel['title'] = $module_info['custom_title'] . ' - ' . $global_array_cat[$catid]['title'];
    $channel['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias_cat_url;
    $channel['description'] = $global_array_cat[$catid]['description'];

    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)->where('status=1 AND instant_active=1' . ($gettime ? ' AND (publtime>= ' . $gettime . ' OR edittime >= ' . $gettime . ')' : ''));
} else {
    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_rows')->where('status=1 AND inhome=1 AND instant_active=1' . ($gettime ? ' AND (publtime>= ' . $gettime . ' OR edittime >= ' . $gettime . ')' : ''));
}

// Lấy RSS từ cache
$cacheFile = NV_LANG_DATA . '_instantrss' . $catid . '_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 60 * intval($module_config[$module_file]['instant_articles_livetime']);

$FBIA = new \NukeViet\Facebook\InstantArticles($lang_module);

if (!defined('NV_IS_MODADMIN') and ($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $items = unserialize($cache);
} else {
    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        $row['catalias'] = $global_array_cat[$row['catid']]['alias'];
        $row['hometext'] = strip_tags($row['hometext']);
        $items[$row['id']] = array(
            'title' => $row['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['catalias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
            'guid' => md5($module_name . '_' . $row['id']),
            'description' => $row['hometext'],
            'pubdate' => $row['publtime'],
            'modifydate' => $row['edittime'],
            'author' => $row['author'],
            'homeimgfile' => $row['homeimgfile'],
            'homeimgalt' => $row['homeimgalt'],
            'cattitle' => $global_array_cat[$row['catid']]['title'],
            'instant_template' => $row['instant_template'],
            'instant_creatauto' => $row['instant_creatauto']
        );
    }

    if (!empty($items)) {
        $sql = "SELECT id, bodyhtml FROM " . NV_PREFIXLANG . "_" . $module_data . "_detail WHERE id IN(" . implode(',', array_keys($items)) . ")";
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $content = array();
            $FBIA->setArticle($row['bodyhtml']);
            if ($items[$row['id']]['instant_creatauto']) {
                $content['html'] = $FBIA->hardProcces();
            } else {
                $content['html'] = $FBIA->preProcces();
            }

            if (!empty($items[$row['id']]['homeimgfile'])) {
                if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $items[$row['id']]['homeimgfile'])) {
                    $content['image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $items[$row['id']]['homeimgfile'];
                } else {
                    $content['image'] = $items[$row['id']]['homeimgfile'];
                }
                $content['image_caption'] = empty($items[$row['id']]['homeimgalt']) ? $items[$row['id']]['title'] : $items[$row['id']]['homeimgalt'];
            }
            $content['opkicker'] = $items[$row['id']]['cattitle'];
            $content['template'] = $items[$row['id']]['instant_template'];
            $content['pubdate'] = $items[$row['id']]['pubdate'];
            $content['modifydate'] = $items[$row['id']]['modifydate'];
            if (empty($content['template'])) {
                $content['template'] = $module_config[$module_name]['instant_articles_template'];
            }

            $items[$row['id']]['content'] = $content;
        }
    }

    if (!defined('NV_IS_MODADMIN')) {
        $cache = serialize($items);
        $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
    }
}

nv_rss_generate($channel, $items, 'ISO8601');
die();