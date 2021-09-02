<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

/**
 * nv_src_href_callback()
 *
 * @param array $matches
 * @return string
 */
function nv_src_href_callback($matches)
{
    if (!empty($matches[2]) and !preg_match("/^http\:\/\//", $matches[2]) and !preg_match("/^https\:\/\//", $matches[2]) and !preg_match("/^mailto\:/", $matches[2]) and !preg_match("/^tel\:/", $matches[2]) and !preg_match('/^javascript/', $matches[2])) {
        if (preg_match("/^\//", $matches[2])) {
            $_url = NV_MY_DOMAIN;
        } else {
            $_url = NV_MY_DOMAIN . '/';
        }
        $matches[2] = $_url . $matches[2];
    }

    return $matches[1] . '="' . $matches[2] . '"';
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
if ($id > 0 and $catid > 0) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id =' . $id;
    $result = $db_slave->query($sql);
    $content = $result->fetch();
    unset($sql, $result);
    if ($content['id'] > 0) {
        $body_contents = $db_slave->query('SELECT bodyhtml as bodytext, sourcetext, imgposition, copyright, allowed_save FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $content['id'])->fetch();
        $content = array_merge($content, $body_contents);
        unset($body_contents);

        if ($content['allowed_save'] == 1 and (defined('NV_IS_MODADMIN') or ($content['status'] == 1 and $content['publtime'] < NV_CURRENTTIME and ($content['exptime'] == 0 or $content['exptime'] > NV_CURRENTTIME)))) {
            $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=savefile/' . $global_array_cat[$catid]['alias'] . '/' . $content['alias'] . '-' . $id . $global_config['rewrite_exturl'];
            $canonicalUrl = getCanonicalUrl($page_url, true, true);

            $sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $content['sourceid'];
            $result = $db_slave->query($sql);
            $sourcetext = $result->fetchColumn();
            unset($sql, $result);

            $meta_tags = nv_html_meta_tags();
            $content['bodytext'] = $db_slave->query('SELECT bodyhtml FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $content['id'])->fetchColumn();

            $result = [
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
                'link' => '<a href="' . $canonicalUrl . '" title="' . $content['title'] . '">' . $canonicalUrl . "</a>\n",
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
            $contents = call_user_func('news_print', $result);
            header('Content-Type: text/x-delimtext; name="' . $result['alias'] . '.html"');
            header('Content-disposition: attachment; filename=' . $result['alias'] . '.html');

            include NV_ROOTDIR . '/includes/header.php';
            echo preg_replace_callback("/(src|href)\=\"([^\"]+)\"/", 'nv_src_href_callback', nv_url_rewrite(nv_site_theme($contents, false)));
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }
}

header('Location: ' . $global_config['site_url']);
exit();
