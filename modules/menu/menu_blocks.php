<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_menu_blocks()
 *
 * @param array $block_config
 * @return string
 */
function nv_menu_blocks($block_config)
{
    global $nv_Cache, $global_config, $lang_global;

    $list_cats = [];
    $sql = 'SELECT id, parentid, title, link, icon, note, subitem, groups_view, module_name, op, target, css, active_type FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY weight ASC';
    $list = $nv_Cache->db($sql, '', $block_config['module']);

    $search = ['&amp;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];
    $replace = ['&', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~'];

    foreach ($list as $row) {
        if (nv_user_in_groups($row['groups_view'])) {
            if ($row['link'] != '' and $row['link'] != '#') {
                $row['link'] = str_replace($search, $replace, $row['link']);
                $row['link'] = nv_url_rewrite($row['link'], true);

                switch ($row['target']) {
                    case 1:
                        $row['target'] = '';
                        break;
                    case 3:
                        $row['target'] = ' onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,\');return false;"';
                        break;
                    default:
                        $row['target'] = ' onclick="this.target=\'_blank\'"';
                }
            } else {
                $row['target'] = '';
            }

            if (!empty($row['icon']) and file_exists(NV_UPLOADS_REAL_DIR . '/menu/' . $row['icon'])) {
                $row['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/menu/' . $row['icon'];
            } else {
                $row['icon'] = '';
            }
            $list_cats[$row['id']] = [
                'id' => $row['id'],
                'parentid' => $row['parentid'],
                'subcats' => $row['subitem'],
                'title' => $row['title'],
                'title_trim' => nv_clean60($row['title'], $block_config['title_length']),
                'target' => $row['target'],
                'note' => empty($row['note']) ? $row['title'] : $row['note'],
                'link' => $row['link'],
                'icon' => $row['icon'],
                'html_class' => $row['css'],
                'current' => nv_menu_check_current($row['link'], $row['active_type'])
            ];
        }
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/menu/' . $block_config['block_name'] . '.tpl')) {
        $block_theme = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/menu/' . $block_config['block_name'] . '.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }

    $xtpl = new XTemplate($block_config['block_name'] . '.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('BLOCK_THEME', $block_theme);
    $xtpl->assign('BLOCK_CONFIG', $block_config);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

    if (!empty($list_cats)) {
        foreach ($list_cats as $cat) {
            if (empty($cat['parentid'])) {
                if (!empty($cat['subcats'])) {
                    $submenu_active = [];
                    $html_content = nv_smenu_blocks($block_config['block_name'], $list_cats, $cat['subcats'], $submenu_active, $block_theme);
                    $xtpl->assign('HTML_CONTENT', $html_content);
                    if ($html_content != '') {
                        $xtpl->parse('main.loopcat1.cat2');
                        $xtpl->parse('main.loopcat1.expand');
                    }
                    if (!empty($submenu_active)) {
                        $cat['current'] = true;
                    }
                }
                $cat['class'] = nv_menu_blocks_active($cat);

                $xtpl->assign('CAT1', $cat);
                if (!empty($cat['icon'])) {
                    $xtpl->parse('main.loopcat1.icon');
                }
                $xtpl->parse('main.loopcat1');
            }
        }
        $xtpl->assign('MENUID', $block_config['bid']);
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_menu_blocks_active()
 *
 * @param array $cat
 * @return string
 */
function nv_menu_blocks_active($cat)
{
    if ($cat['current'] === true and !$cat['html_class']) {
        $class = ' class="current"';
    } elseif ($cat['current'] === false and $cat['html_class']) {
        $class = ' class="' . $cat['html_class'] . '"';
    } elseif ($cat['current'] === true and $cat['html_class']) {
        $class = ' class="' . $cat['html_class'] . ' current"';
    } else {
        $class = '';
    }

    return $class;
}

/**
 * nv_menu_check_current()
 *
 * @param string $url
 * @param int    $type
 * @return bool
 */
function nv_menu_check_current($url, $type = 0)
{
    global $home, $client_info, $global_config;

    if ($client_info['selfurl'] == $url) {
        return true;
    }
    // Chinh xac tuyet doi

    $_curr_url = NV_BASE_SITEURL . str_replace($global_config['site_url'] . '/', '', $client_info['selfurl']);
    $_url = nv_url_rewrite($url, true);

    if ($home and ($_url == nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA) or $_url == NV_BASE_SITEURL . 'index.php' or $_url == NV_BASE_SITEURL)) {
        return true;
    }
    if ($_url != NV_BASE_SITEURL) {
        if ($type == 2) {
            if (preg_match('#' . preg_quote($_url, '#') . '#', $_curr_url)) {
                return true;
            }

            return false;
        }
        if ($type == 1) {
            if (preg_match('#^' . preg_quote($_url, '#') . '#', $_curr_url)) {
                return true;
            }

            return false;
        }
        if ($_curr_url == $_url) {
            return true;
        }
    }

    return false;
}

/**
 * nv_smenu_blocks()
 *
 * @param string $style
 * @param array  $list_cats
 * @param string $list_sub
 * @param array  $submenu_active
 * @param string $block_theme
 * @return string
 */
function nv_smenu_blocks($style, $list_cats, $list_sub, &$submenu_active, $block_theme)
{
    $xtpl = new XTemplate($style . '.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');

    if (empty($list_sub)) {
        return '';
    }
    $list = array_map('intval', explode(',', $list_sub));

    foreach ($list_cats as $cat) {
        $catid = $cat['id'];
        if (in_array((int) $catid, $list, true)) {
            $list_cats[$catid]['class'] = nv_menu_blocks_active($list_cats[$catid]);
            if ($list_cats[$catid]['current'] === true) {
                $submenu_active[] = $catid;
            }

            $xtpl->assign('MENUTREE', $list_cats[$catid]);
            if (!empty($list_cats[$catid]['icon'])) {
                $xtpl->parse('tree.icon');
            }
            if (!empty($list_cats[$catid]['subcats'])) {
                $tree = nv_smenu_blocks($style, $list_cats, $list_cats[$catid]['subcats'], $submenu_active, $block_theme);

                $xtpl->assign('TREE_CONTENT', $tree);
                $xtpl->parse('tree.tree_content');
            }

            $xtpl->parse('tree');
        }
    }

    return $xtpl->text('tree');
}
