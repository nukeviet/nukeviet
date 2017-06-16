<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_clip where alias = :alias');
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $weight = $db->query('SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_clip')->fetchColumn();
        $weight = intval($weight) + 1;
        $alias = $alias . '-' . $weight;
    }

    die($alias);
}

$topicList = nv_listTopics(0);

if (empty($topicList)) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topic&add");
    exit();
}

$page_title = $lang_module['main'];
$contents = "";

$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip";
$result = $db->query($sql);
$count = $result->fetch();

if (empty($count['count']) and !$nv_Request->isset_request('add', 'get')) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add");
    die();
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('NV_ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('module', $module_data);

$groups_list = nv_groups_list();

if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit, id', 'get')) {
    if (defined('NV_EDITOR')) {
        require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
    }

    $post = array();
    $is_error = false;
    $info = "";

    if ($nv_Request->isset_request('edit, id', 'get')) {
        $post['id'] = $nv_Request->get_int('id', 'get', 0);

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id=" . $post['id'];
        $result = $db->query($sql);
        $num = $result->rowCount();
        if ($num != 1) {
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            die();
        }

        $row = $result->fetch();
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        $post['tid'] = $nv_Request->get_int('tid', 'post', 0);
        $post['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $post['alias'] = $nv_Request->get_title('alias', 'post', '', 1);
        $post['alias'] = (empty($post['alias'])) ? change_alias($post['title']) : change_alias($post['alias']);
        $post['hometext'] = $nv_Request->get_title('hometext', 'post', '', 1);
        $post['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
        $post['keywords'] = $nv_Request->get_title('keywords', 'post', '', 1);
        $post['internalpath'] = $nv_Request->get_title('internalpath', 'post');
        $post['externalpath'] = $nv_Request->get_title('externalpath', 'post');
        $post['comm'] = $nv_Request->get_int('comm', 'post', 0);
        $post['redirect'] = $nv_Request->get_int('redirect', 'post', 0);

        if (!empty($post['internalpath'])) {
            $post['internalpath'] = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $post['internalpath']);
            if (!preg_match("/^([a-z0-9\/\.\-\_]+)\.([a-z0-9]+)$/i", $post['internalpath']) or !file_exists(NV_ROOTDIR . "/" . $post['internalpath']))
                $post['internalpath'] = "";
        }

        if (!empty($post['externalpath']) and !nv_is_url($post['externalpath']))
            $post['externalpath'] = "";

        if (!isset($topicList[$post['tid']]))
            $post['tid'] = 0;
        $post['hometext'] = nv_nl2br($post['hometext']);

        $where = isset($post['id']) ? " id!=" . $post['id'] . " AND" : "";

        if (empty($post['title'])) {
            $info = $lang_module['error1'];
            $is_error = true;
        } elseif (empty($post['hometext'])) {
            $info = $lang_module['error7'];
            $is_error = true;
        } elseif (empty($post['internalpath']) and empty($post['externalpath'])) {
            $info = $lang_module['error5'];
            $is_error = true;
        }

        $post['img'] = "";
        $homeimg = $nv_Request->get_title('img', 'post');
        if (!empty($homeimg)) {
            $homeimg = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $homeimg);
            if (preg_match("/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg)) {
                $image = NV_ROOTDIR . "/" . $homeimg;
                $image = nv_is_image($image);
                if (!empty($image))
                    $post['img'] = $homeimg;
            }

            if (empty($post['img'])) {
                $info = $lang_module['error6'];
                $is_error = true;
            }
        }

        if (!$is_error) {
            $test_content = strip_tags($post['bodytext']);
            $test_content = trim($test_content);
            $post['bodytext'] = !empty($test_content) ? nv_editor_nl2br($post['bodytext']) : "";

            if (empty($post['keywords'])) {
                $post['keywords'] = nv_get_keywords($post['hometext'] . " " . $post['bodytext']);
            } else {
                $post['keywords'] = explode(",", $post['keywords']);
                $post['keywords'] = array_map("trim", $post['keywords']);
                $post['keywords'] = array_unique($post['keywords']);
                $post['keywords'] = implode(",", $post['keywords']);
            }

            if (isset($post['id'])) {
                $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_clip SET
                tid=" . $post['tid'] . ",
                alias=" . $db->quote($post['alias']) . ",
                title=" . $db->quote($post['title']) . ",
                img=" . $db->quote($post['img']) . ",
                hometext=" . $db->quote($post['hometext']) . ",
                bodytext=" . $db->quote($post['bodytext']) . ",
                keywords=" . $db->quote($post['keywords']) . ",
                internalpath=" . $db->quote($post['internalpath']) . ",
                externalpath=" . $db->quote($post['externalpath']) . ",

                 comm=" . $post['comm'] . "
                WHERE id=" . $post['id'];

                $db->query($query);

                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['editClip'], "Id: " . $post['id'], $admin_info['userid']);
            } else {
                $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_clip VALUES
                (NULL, " . $post['tid'] . ", " . $db->quote($post['title']) . ", " . $db->quote($post['alias']) . ",
                " . $db->quote($post['hometext']) . ", " . $db->quote($post['bodytext']) . ",
                " . $db->quote($post['keywords']) . ", " . $db->quote($post['img']) . ",
                " . $db->quote($post['internalpath']) . ", " . $db->quote($post['externalpath']) . ",
                " . $post['comm'] . ",
                1, " . NV_CURRENTTIME . ");";
                $_id = $db->insert_id($query);

                $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_hit VALUES (" . $_id . ", 0, 0, 0, 0);";
                $db->query($query);

                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addClip'], "Id: " . $_id, $admin_info['userid']);
            }
            $nv_Cache->delMod($module_name);
            if ($post['redirect']) {
                Header("Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $post['alias']);
                die();
            }
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            die();
        }
    } elseif (isset($post['id'])) {
        $post = $row;
        $post['hometext'] = nv_br2nl($post['hometext']);
        $post['bodytext'] = nv_editor_br2nl($post['bodytext']);
        $post['keywords'] = preg_replace("/\,[\s]*/", ", ", $post['keywords']);
        $post['redirect'] = $nv_Request->get_int('redirect', 'get', 0);
    } else {
        $post['title'] = $post['hometext'] = $post['bodytext'] = $post['img'] = $post['keywords'] = $post['internalpath'] = $post['externalpath'] = "";
        $post['tid'] = 0;
        $post['comm'] = 1;
        $post['redirect'] = 0;
    }

    if (!empty($post['bodytext']))
        $post['bodytext'] = nv_htmlspecialchars($post['bodytext']);
    if (!empty($post['img']))
        $post['img'] = NV_BASE_SITEURL . $post['img'];
    if (!empty($post['internalpath']))
        $post['internalpath'] = NV_BASE_SITEURL . $post['internalpath'];
    $post['comm'] = $post['comm'] ? "  checked=\"checked\"" : "";

    if (!empty($info)) {
        $xtpl->assign('ERROR_INFO', $info);
        $xtpl->parse('add.error');
    }

    if (isset($post['id'])) {
        $post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&edit&id=" . $post['id'];
        $informationtitle = $lang_module['editClip'];
    } else {
        $post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&add";
        $informationtitle = $lang_module['addClip'];
        $xtpl->parse('add.auto_get_alias');
    }

    $xtpl->assign('INFO_TITLE', $informationtitle);
    $xtpl->assign('POST', $post);

    foreach ($topicList as $_tid => $_value) {
        $option = array(
            'value' => $_tid,
            'name' => $_value['name'],
            'selected' => $_tid == $post['tid'] ? " selected=\"selected\"" : "");
        $xtpl->assign('OPTION3', $option);
        $xtpl->parse('add.option3');
    }

    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $_cont = nv_aleditor('bodytext', '100%', '300px', $post['bodytext']);
    } else {
        $_cont = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\" id=\"bodytext\">" . $post['bodytext'] . "</textarea>";
    }
    $xtpl->assign('CONTENT', $_cont);

    $xtpl->parse('add');
    $contents = $xtpl->text('add');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->isset_request('changeStatus', 'post')) {
    $id = $nv_Request->get_int('changeStatus', 'post', 0);
    $sql = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id=" . $id;
    $result = $db->query($sql);
    $status = $result->fetchColumn();

    $newStatus = $status ? 0 : 1;
    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_clip SET status=" . $newStatus . " WHERE id=" . $id;
    $db->query($query);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['cstatus'], "Id: " . $id, $admin_info['userid']);

    $alt = $newStatus ? $lang_module['status1'] : $lang_module['status0'];
    $tit = $newStatus ? $lang_module['tit1'] : $lang_module['tit0'];
    $icon = $newStatus ? '<i style="color: red; font-size: 16px" class="fa fa-check-square-o"></i>' : '<i style="color: #333; font-size: 16px" class="fa fa-square-o"></i>';


    die($icon . ' ' . $tit);
}
if ($nv_Request->isset_request('del', 'post')) {
    $id = $nv_Request->get_int('del', 'post', 0);
    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_hit WHERE cid = " . $id;
    $db->query($query);
    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id = " . $id;
    $db->query($query);
    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['delClip'], "Id: " . $id, $admin_info['userid']);
    die('OK');
}

foreach ($topicList as $id => $name) {
    $option = array('id' => $id, 'name' => $name['name']);
    $xtpl->assign('OPTION4', $option);
    $xtpl->parse('main.psopt4');
}

$where = "";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
$ptitle = $lang_module['main'];

$_where = array();
$q = $nv_Request->get_title('q', 'get', '');

if (!empty($q)) {
    $_where[] = " title LIKE '%" . $q . "%' OR alias LIKE '%" . $q . "%' OR hometext LIKE '%" . $q . "%' OR keywords LIKE '%" . $q . "%' ";
    $base_url .= "q=" . $q;
}

if ($nv_Request->isset_request('tid', 'get')) {
    $top = $nv_Request->get_int('tid', 'get', 0);
    if (isset($topicList[$top])) {
        if ($top != 0) {
            $_where[] = " tid=" . $top;
        }

        $base_url .= "&tid=" . $top;
        $ptitle = sprintf($lang_module['listClipByTid'], $topicList[$top]['title']);
    }
}

if (!empty($_where)) {
    $where = ' WHERE ' . implode(' AND ', $_where);
}

$xtpl->assign('Q', $q);
$xtpl->assign('PTITLE', $ptitle);

$sql = "SELECT COUNT(*) as ccount FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip" . $where;
$result = $db->query($sql);
$all_page = $result->fetch();
$all_page = $all_page['ccount'];

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 50;

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip" . $where . " ORDER BY addtime DESC LIMIT " . $per_page . " OFFSET " . (($page - 1) * $per_page);
$result = $db->query($sql);

$a = 0;
while ($row = $result->fetch()) {
    $xtpl->assign('CLASS', $a % 2 ? " class=\"second\"" : "");

    $row['adddate'] = date("d-m-Y H:i", $row['addtime']);
    $row['topicname'] = isset($topicList[$row['tid']]) ? $topicList[$row['tid']]['title'] : "";
    $row['icon'] = $row['status'] ? '<i class="fa fa-check-square-o" style="color: red; font-size: 16px"></i>' : '<i style="color: #333; font-size: 16px" class="fa fa-square-o"></i>';
    $row['status'] = $row['status'] ? $lang_module['tit1'] : $lang_module['tit0'];
    $row['alt'] = $row['status'] ? $lang_module['status1'] : $lang_module['status0'];
    $row['link_view'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=video-' . $row['alias'], true);
    $xtpl->assign('DATA', $row);
    $xtpl->parse('main.loop');
    $a++;
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
