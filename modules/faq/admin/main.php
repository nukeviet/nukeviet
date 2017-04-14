<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/14/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

/**
 * nv_FixWeight()
 *
 * @param mixed $catid
 * @return void
 */
function nv_FixWeight($catid)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $catid . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $weight . " WHERE id=" . $row['id']);
    }
}

//Add, edit file
if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit', 'get')) {
    if ($nv_Request->isset_request('edit', 'get')) {
        $id = $nv_Request->get_int('id', 'get', 0);

        if ($id) {
            $query = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
            $result = $db->query($query);
            $numrows = $result->rowCount();
            if ($numrows != 1) {
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                exit();
            }

            define('IS_EDIT', true);
            $page_title = $lang_module['faq_editfaq'];

            $row = $result->fetch();
        } else {
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            exit();
        }
    } else {
        define('IS_ADD', true);
        $page_title = $lang_module['faq_addfaq'];
    }

    $array = array();
    $is_error = false;
    $error = "";

    if ($nv_Request->isset_request('submit', 'post')) {
        $array['catid'] = $nv_Request->get_int('catid', 'post', 0);
        $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $array['question'] = $nv_Request->get_textarea('question', '', NV_ALLOWED_HTML_TAGS);
        $array['answer'] = $nv_Request->get_editor('answer', '', NV_ALLOWED_HTML_TAGS);
		$array['hot_post'] = $nv_Request->get_int('hot_post', 'post', 0);

        $alias = change_alias($array['title']);

        if (defined('IS_ADD')) {
            $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias=" . $db->quote($alias);
            $result = $db->query($sql);
            $is_exists = $result->fetchColumn();
        } else {
            $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id!=" . $id . " AND alias=" . $db->quote($alias);
            $result = $db->query($sql);
            $is_exists = $result->fetchColumn();
        }

        if (empty($array['title'])) {
            $is_error = true;
            $error = $lang_module['faq_error_title'];
        } elseif ($is_exists) {
            $is_error = true;
            $error = $lang_module['faq_title_exists'];
        } elseif (empty($array['question'])) {
            $is_error = true;
            $error = $lang_module['faq_error_question'];
        } elseif (empty($array['answer'])) {
            $is_error = true;
            $error = $lang_module['faq_error_answer'];
        }
		elseif (empty($array['catid'])) {
            $is_error = true;
            $error = $lang_module['faq_error_cat'];
        } else {
            $array['question'] = nv_nl2br($array['question'], "<br />");
            $array['answer'] = nv_editor_nl2br($array['answer']);
            if (defined('IS_EDIT')) {
                if ($array['catid'] != $row['catid']) {
                    $sql = "SELECT MAX(weight) AS new_weight FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $array['catid'];
                    $result = $db->query($sql);
                    $new_weight = $result->fetchColumn();
                    $new_weight = ( int )$new_weight;
                    ++$new_weight;
                } else {
                    $new_weight = $row['weight'];
                }
				if(!empty($array['hot_post'])) $status=2;
				else $status=1;
                $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
                catid=" . $array['catid'] . ",
                title=" . $db->quote($array['title']) . ",
                alias=" . $db->quote($alias) . ",
                question=" . $db->quote($array['question']) . ",
                answer=" . $db->quote($array['answer']) . ",
                weight=" . $new_weight . ",
                status=" . $status . ",
                admin_id=". $admin_info['admin_id'] .",
                pubtime=" . NV_CURRENTTIME . "
                WHERE id=" . $id;
                $result = $db->query($sql);

                if (! $result) {
                    $is_error = true;
                    $error = $lang_module['faq_error_notResult'];
                } else {
                    nv_update_keywords($array['catid']);

                    if ($array['catid'] != $row['catid']) {
                        nv_FixWeight($row['catid']);
                        nv_update_keywords($row['catid']);
                    }

                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                    exit();
                }
            } elseif (defined('IS_ADD')) {
                $sql = "SELECT MAX(weight) AS new_weight FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $array['catid'];
                $result = $db->query($sql);
                $new_weight = $result->fetchColumn();
                $new_weight = ( int )$new_weight;
                ++$new_weight;
				if(!empty($array['hot_post'])) $status=2;
				else $status=1;
               $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "(catid,title,alias,question,answer,weight,status,addtime,admin_id,userid,pubtime) VALUES (
                " . $array['catid'] . ",
                " . $db->quote($array['title']) . ",
                " . $db->quote($alias) . ",
                " . $db->quote($array['question']) . ",
                " . $db->quote($array['answer']) . ",
                " . $new_weight . ",
                " . $status . ",
                 " . NV_CURRENTTIME . ",
                 " . $admin_info['admin_id'] . ",
                 " . $admin_info['admin_id'] . ",
                 " . NV_CURRENTTIME . ")";
                if (! $db->insert_id($sql)) {
                    $is_error = true;
                    $error = $lang_module['faq_error_notResult2'];
                } else {
                    nv_update_keywords($array['catid']);

                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                    exit();
                }
            }
        }
    } else {
        if (defined('IS_EDIT')) {
            $array['catid'] = ( int )$row['catid'];
            $array['title'] = $row['title'];
            $array['answer'] = nv_editor_br2nl($row['answer']);
            $array['question'] = nv_br2nl($row['question']);
            $array['hot_post']=$row['status'];
        } else {
            $array['catid'] = 0;
            $array['title'] = $array['answer'] = $array['question'] = "";
        }
    }

    if (! empty($array['answer'])) {
        $array['answer'] = nv_htmlspecialchars($array['answer']);
    }
    if (! empty($array['question'])) {
        $array['question'] = nv_htmlspecialchars($array['question']);
    }

    $listcats = array();
    $listcats[0] = array(
        'id' => 0, //
        'name' => $lang_module['nocat'], //
        'selected' => $array['catid'] == 0 ? " selected=\"selected\"" : "" //
    );
    $listcats = $listcats + nv_listcats($array['catid']);
    if (empty($listcats)) {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1");
        exit();
    }

    if (defined('NV_EDITOR')) {
        require_once(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
    }

    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $array['answer'] = nv_aleditor('answer', '100%', '300px', $array['answer']);
    } else {
        $array['answer'] = "<textarea style=\"width:100%; height:300px\" name=\"answer\" id=\"answer\">" . $array['answer'] . "</textarea>";
    }

    $xtpl = new XTemplate("content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);

    if (defined('IS_EDIT')) {
        $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . $id);
    } else {
        $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;add=1");
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $array);
	if(!empty($array['hot_post']) and $array['hot_post']==2) {
			$xtpl->assign('HOST_POST', ($array['hot_post']) ? ' checked="checked"' : '');
		}
    if (! empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    foreach ($listcats as $cat) {
        $xtpl->assign('LISTCATS', $cat);
        $xtpl->parse('main.catid');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

//change weight
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $new = $nv_Request->get_int('new', 'post', 0);

    if (empty($id)) {
        die('NO');
    }

    $query = "SELECT catid FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1) {
        die('NO');
    }
    $catid = $result->fetchColumn();

    $query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id!=" . $id . " AND catid=" . $catid . " ORDER BY weight ASC";
    $result = $db->query($query);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new) {
            ++$weight;
        }
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $new . " WHERE id=" . $id;
    $db->query($sql);
    die('OK');
}

//Kich hoat - dinh chi
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);

    if (empty($id)) {
        die('NO');
    }

    $query = "SELECT catid, status FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1) {
        die('NO');
    }

    list($catid, $status) = $result->fetch(3);
    $status = $status ? 0 : 1;

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET status=" . $status . " WHERE id=" . $id;
    $db->query($sql);

    nv_update_keywords($catid);

    die('OK');
}

//Xoa
if ($nv_Request->isset_request('del', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);

    if (empty($id)) {
        die('NO');
    }

    $sql = "SELECT COUNT(*) AS count, catid FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query($sql);
    list($count, $catid) = $result->fetch(3);

    if ($count != 1) {
        die('NO');
    }

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $db->query($sql);

    nv_update_keywords($catid);

    nv_FixWeight($catid);

    die('OK');
}

//List faq
$listcats = array();
$listcats[0] = array(
    'id' => 0, //
    'name' => $lang_module['nocat'], //
    'title' => $lang_module['nocat'], //
    'selected' => 0 == 0 ? " selected=\"selected\"" : "" //
);
$listcats = $listcats + nv_listcats(0);
if (empty($listcats)) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1");
    exit();
}

$page_title = $lang_module['faq_manager'];

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

if ($nv_Request->isset_request("catid", "get")) {
    $catid = $nv_Request->get_int('catid', 'get', 0);
    if (! $catid or ! isset($listcats[$catid])) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        exit();
    }

    $caption = sprintf($lang_module['faq_list_by_cat'], $listcats[$catid]['title']);
    $sql .= " WHERE catid=" . $catid . " ORDER BY weight ASC";
    $base_url .= "&amp;catid=" . $catid;

    define('NV_IS_CAT', true);
} else {
    $caption = $lang_module['faq_manager'];
    $sql .= " ORDER BY id DESC";
}

if(!empty($page)) {
	$sql .= " LIMIT "  . $per_page." OFFSET ".($page - 1) * $per_page;
}
else {
	$sql .= " LIMIT "  . $per_page;
}

$query = $db->query($sql);

$result = $db->query("SELECT FOUND_ROWS()");
$all_page = $result->fetchColumn();

if (! $all_page) {
    if (defined('NV_IS_CAT')) {
        $contents = "";
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
        exit();
    } else {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&add=1");
        exit();
    }
}

$array = array();

while ($row = $query->fetch()) {
    $array[$row['id']] = array( //
        'id' => ( int )$row['id'], //
        'title' => $row['title'], //
        'cattitle' => $listcats[$row['catid']]['title'], //
        'catlink' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;catid=" . $row['catid'], //
        'status' => $row['status'] //
        );

    if (defined('NV_IS_CAT')) {
        $weight = array();
        for ($i = 1; $i <= $all_page; ++$i) {
            $weight[$i]['title'] = $i;
            $weight[$i]['pos'] = $i;
            $weight[$i]['selected'] = ($i == $row['weight']) ? " selected=\"selected\"" : "";
        }

        $array[$row['id']]['weight'] = $weight;
    }
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

$array_status = array( $lang_module['faq_no_active'],$lang_module['faq_active'],$lang_module['hot_post'] );
$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TABLE_CAPTION', $caption);
$xtpl->assign('ADD_NEW_FAQ', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;add=1");

if (defined('NV_IS_CAT')) {
    $xtpl->parse('main.is_cat1');
}

if (! empty($array)) {
    $a = 0;
    foreach ($array as $row) {
        $xtpl->assign('CLASS', $a % 2 == 1 ? " class=\"second\"" : "");
        $xtpl->assign('ROW', $row);

        if (defined('NV_IS_CAT')) {
            foreach ($row['weight'] as $weight) {
                $xtpl->assign('WEIGHT', $weight);
                $xtpl->parse('main.row.is_cat2.weight');
            }
            $xtpl->parse('main.row.is_cat2');
        }
        $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . $row['id']);
         foreach ($array_status as $key => $val) {
        $xtpl->assign('STATUS', array(
            'key' => $key,
            'val' => $val,
            'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
        ));

        $xtpl->parse('main.row.status');
    	}
        $xtpl->parse('main.row');
        ++$a;
    }
}

if (! empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
