<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $title = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($title);
    $alias = strtolower($alias);
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topic where alias = :alias');
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        $id = $nv_Request->get_int('id', 'post', 0);
        if ($id > 0) {
            $main_alias = $db->query('SELECT alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topic WHERE id=' . $id)->fetchColumn();
            $alias = $main_alias . '-' . $alias;
        } else {
            $weight = $db->query('SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topic')->fetchColumn();
            $weight = intval($weight) + 1;
            $alias = $alias . '-' . $weight;
        }
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_FixWeightTopic()
 *
 * @param integer $parentid
 * @return
 */
function nv_FixWeightTopic($parentid = 0)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET weight=" . $weight . " WHERE id=" . $row['id']);
    }
}

/**
 * nv_del_topic()
 *
 * @param mixed $tid
 * @return
 */
function nv_del_topic($tid)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE tid=" . $tid;
    $result = $db->query($sql);
    $in = array();
    while ($row = $result->fetch())
        $in[] = $row['id'];
    $in = implode(",", $in);

    if (!empty($in)) {
        $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_hit WHERE tid IN (" . $in . ")";
        $db->query($sql);

        $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id IN (" . $in . ")";
        $db->query($sql);
    }

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $tid;
    $result = $db->query($sql);
    while (list ($id) = $result->fetch(3))
        nv_del_topic($id);

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $db->query($sql);
}

$array = array();
$error = "";

//them the loai
if ($nv_Request->isset_request('add', 'get')) {
    $array = array();
    $error = array();
    $array['id'] = $nv_Request->get_int('id', 'post,get', 0);
    $page_title = $lang_module['addtopic_titlebox'];

    $is_error = false;

    if ($nv_Request->isset_request('submit', 'post')) {
        $array['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
        $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $array['description'] = $nv_Request->get_title('description', 'post', '');
        $array['keywords'] = $nv_Request->get_title('keywords', 'post', '', 1);
        $array['alias'] = $nv_Request->get_title('alias', 'post,get', '');
        $array['alias'] = (empty($row['alias'])) ? change_alias($array['title']) : change_alias($array['alias']);

        if (empty($array['alias'])) {
            $error[]= $lang_module['error_required_alias'];
        }
        if (empty($array['title'])) {
            $error = $lang_module['error1'];
            $is_error = true;
        }elseif (!empty($array['parentid'])) {
            $sql = "SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $array['parentid'];
            $result = $db->query($sql);
            $count = $result->fetchColumn();

            if (!$count) {
                $error = $lang_module['error2'];
                $is_error = true;
            }
        }

        if (!$is_error) {

            $array['img'] = "";
            $homeimg = $nv_Request->get_title('img', 'post');
            if (!empty($homeimg)) {
                $homeimg = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $homeimg);
                if (preg_match("/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg)) {
                    $image = NV_ROOTDIR . "/" . $homeimg;
                    $image = nv_is_image($image);
                    if (!empty($image)) $array['img'] = $homeimg;
                }
            }

            if (empty($array['keywords'])) {
                $array['keywords'] = nv_get_keywords($array['description']);
            } else {
                $array['keywords'] = explode(",", $array['keywords']);
                $array['keywords'] = array_map("trim", $array['keywords']);
                $array['keywords'] = array_unique($array['keywords']);
                $array['keywords'] = implode(",", $array['keywords']);
            }

            $sql = "SELECT MAX(weight) AS new_weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $array['parentid'];
            $result = $db->query($sql);
            $new_weight = $result->fetchColumn();
            $new_weight = (int) $new_weight;
            ++$new_weight;

            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_topic VALUES (
            NULL,
            " . $array['parentid'] . ",
            " . $db->quote($array['title']) . ",
            " . $db->quote($array['alias']) . ",
            " . $db->quote($array['description']) . ",
            " . $new_weight . ",
            " . $db->quote($array['img']) . ",
            1,
            " . $db->quote($array['keywords']) . ")";

            $tid = $db->insert_id($sql);

            if (!$tid) {
                $error = $lang_module['error4'];
                $is_error = true;
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addtopic_titlebox'], "ID " . $tid, $admin_info['userid']);
                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                exit();
            }
        }
    } else {
        $array['parentid'] = 0;
        $array['title'] = "";
        $array['alias'] = "";
        $array['description'] = "";
        $array['keywords'] = "";
        $array['img'] = "";
    }

    if (!empty($array['img'])) $array['img'] = NV_BASE_SITEURL . $array['img'];

    $listTopics = array(
        array(
            'id' => 0,
            'name' => $lang_module['is_maintopic'],
            'selected' => ""
        )
    );
    $listTopics = $listTopics + nv_listTopics($array['parentid']);

    $xtpl = new XTemplate("topic_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1");
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
    $xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
    $xtpl->assign('DATA', $array);

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    foreach ($listTopics as $cat) {
        $xtpl->assign('LISTCATS', $cat);
        $xtpl->parse('main.parentid');
    }

    $xtpl->parse('main.auto_get_alias');

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';

    exit();
}

//Sua the loai
if ($nv_Request->isset_request('edit', 'get')) {
    $page_title = $lang_module['edittopic_titlebox'];

    $tid = $nv_Request->get_int('tid', 'get', 0);

    if (empty($tid)) {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
        exit();
    }

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($sql);
    $numcat = $result->rowCount();

    if ($numcat != 1) {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
        exit();
    }

    $row = $result->fetch();

    $is_error = false;

    if ($nv_Request->isset_request('submit', 'post')) {
        $array['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
        $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $array['description'] = $nv_Request->get_title('description', 'post', '');
        $array['keywords'] = $nv_Request->get_title('keywords', 'post', '', 1);

        $array['alias'] = $nv_Request->get_title('alias', 'post', '', 1);
        $array['alias'] = (empty($array['alias'])) ? change_alias($array['title']) : change_alias($array['alias']);

        if (empty($array['title'])) {
            $error = $lang_module['error1'];
            $is_error = true;
        } else {
            if (!empty($array['parentid'])) {
                $sql = "SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $array['parentid'];
                $result = $db->query($sql);
                $count = $result->fetchColumn();

                if (!$count) {
                    $error = $lang_module['error2'];
                    $is_error = true;
                }
            }

            if (!$is_error) {
                $sql = "SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id!=" . $tid . " AND alias=" . $db->quote($array['alias']) . " AND parentid=" . $array['parentid'];
                $result = $db->query($sql);
                $count = $result->fetchColumn();

                if ($count) {
                    $error = $lang_module['error3'];
                    $is_error = true;
                }
            }
        }

        if (!$is_error) {
            $array['img'] = "";
            $homeimg = $nv_Request->get_title('img', 'post');
            if (!empty($homeimg)) {
                $homeimg = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $homeimg);
                if (preg_match("/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg)) {
                    $image = NV_ROOTDIR . "/" . $homeimg;
                    $image = nv_is_image($image);
                    if (!empty($image)) $array['img'] = $homeimg;
                }
            }
            if (empty($array['keywords'])) {
                $array['keywords'] = nv_get_keywords($array['description']);
            } else {
                $array['keywords'] = explode(",", $array['keywords']);
                $array['keywords'] = array_map("trim", $array['keywords']);
                $array['keywords'] = array_unique($array['keywords']);
                $array['keywords'] = implode(",", $array['keywords']);
            }

            if ($array['parentid'] != $row['parentid']) {
                $sql = "SELECT MAX(weight) AS new_weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $array['parentid'];
                $result = $db->query($sql);
                $new_weight = $result->fetchColumn();
                $new_weight = (int) $new_weight;
                ++$new_weight;
            } else {
                $new_weight = $row['weight'];
            }

            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET
            parentid=" . $array['parentid'] . ",
            title=" . $db->quote($array['title']) . ",
            alias=" . $db->quote($array['alias']) . ",
            description=" . $db->quote($array['description']) . ",
            keywords=" . $db->quote($array['keywords']) . ",
            img=" . $db->quote($array['img']) . ",
            weight=" . $new_weight . "
            WHERE id=" . $tid;
            $result = $db->query($sql);

            if (!$result) {
                $error = $lang_module['error4'];
                $is_error = true;
            } else {
                if ($array['parentid'] != $row['parentid']) {
                    nv_FixWeightTopic($row['parentid']);
                }

                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edittopic_titlebox'], "ID " . $tid, $admin_info['userid']);
                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                exit();
            }
        }
    } else {
        $array['parentid'] = (int) $row['parentid'];
        $array['title'] = $row['title'];
        $array['alias'] = $row['alias'];
        $array['description'] = $row['description'];
        $array['keywords'] = $row['keywords'];
        $array['img'] = $row['img'];
    }

    if (!empty($array['img'])) $array['img'] = NV_BASE_SITEURL . $array['img'];

    $listTopics = array(
        array(
            'id' => 0,
            'name' => $lang_module['is_maintopic'],
            'selected' => ""
        )
    );
    $listTopics = $listTopics + nv_listTopics($array['parentid'], $tid);

    $xtpl = new XTemplate("topic_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;tid=" . $tid);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name);
    $xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name);
    $xtpl->assign('DATA', $array);

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    foreach ($listTopics as $cat) {
        $xtpl->assign('LISTCATS', $cat);
        $xtpl->parse('main.parentid');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';

    exit();
}

//Xoa chu de
if ($nv_Request->isset_request('del', 'post,get')) {
    if (!defined('NV_IS_AJAX')) die('Wrong URL');

    $tid = $nv_Request->get_int('tid', 'post,get', 0);

    if (empty($tid)) {
        die('NO');
    }

    $sql = "SELECT COUNT(*) AS count, parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($sql);
    list ($count, $parentid) = $result->fetch(3);

    if ($count != 1) {
        die('NO');
    }

    nv_del_topic($tid);
    nv_FixWeightTopic($parentid);

    die('OK');
}

//Chinh thu tu chu de
if ($nv_Request->isset_request('changeweight', 'post,get')) {
    if (!defined('NV_IS_AJAX')) die('Wrong URL');

    $tid = $nv_Request->get_int('tid', 'post,get', 0);
    $new = $nv_Request->get_int('new', 'post,get', 0);

    if (empty($tid)) die('NO');

    $query = "SELECT parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1) die('NO');
    $parentid = $result->fetchColumn();

    $query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id!=" . $tid . " AND parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($query);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new) ++$weight;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET weight=" . $new . " WHERE id=" . $tid;
    $db->query($sql);
    die('OK');
}

//Kich hoat - dinh chi
if ($nv_Request->isset_request('changestatus', 'post,get')) {
    if (!defined('NV_IS_AJAX')) die('Wrong URL');

    $tid = $nv_Request->get_int('tid', 'post,get', 0);

    if (empty($tid)) die('NO');

    $query = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1) die('NO');

    $status = $result->fetchColumn();
    $status = $status ? 0 : 1;

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET status=" . $status . " WHERE id=" . $tid;
    $db->query($sql);
    die('OK');
}

//Danh sach chu de
$page_title = $lang_module['topic_management'];

$pid = $nv_Request->get_int('pid', 'get', 0);
$q = $nv_Request->get_title('q', 'get', '');

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $pid;

if (!empty($q)) {
    $sql .= " AND (title LIKE '%" . $q . "%' OR alias LIKE '%" . $q . "%' OR description LIKE '%" . $q . "%' OR keywords LIKE '%" . $q . "%' )";
}

$sql .= ' ORDER BY weight ASC';
$result = $db->query($sql);
$num = $result->rowCount();

if (!$num and empty($q)) {
    if ($pid) {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    } else {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add=1");
    }
    exit();
}

if ($pid) {
    $sql2 = "SELECT title,parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $pid;
    $result2 = $db->query($sql2);
    list ($parentid, $parentid2) = $result2->fetch(3);
    $caption = sprintf($lang_module['listSubTopic'], $parentid);
    $parentid = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;pid=" . $parentid2 . "\">" . $parentid . "</a>";
} else {
    $caption = $lang_module['listMainTopic'];
    $parentid = $lang_module['is_maintopic'];
}

$list = array();
$a = 0;

while ($row = $result->fetch()) {
    $numsub = $db->query("SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $row['id'])->rowCount();
    if ($numsub) {
        $numsub = " (<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;pid=" . $row['id'] . "\">" . $numsub . " " . $lang_module['subtopic'] . "</a>)";
    } else {
        $numsub = "";
    }

    $weight = array();
    for ($i = 1; $i <= $num; ++$i) {
        $weight[$i]['title'] = $i;
        $weight[$i]['pos'] = $i;
        $weight[$i]['selected'] = ($i == $row['weight']) ? " selected=\"selected\"" : "";
    }

    $class = ($a % 2) ? " class=\"second\"" : "";

    $list[$row['id']] = array(
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'titlelink' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tid=" . $row['id'],
        'numsub' => $numsub,
        'parentid' => $parentid,
        'weight' => $weight,
        'status' => $row['status'] ? " checked=\"checked\"" : "",
        'class' => $class
    );

    ++$a;
}

$xtpl = new XTemplate("topic_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('ADD_NEW_TOPIC', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1");
$xtpl->assign('TABLE_CAPTION', $caption);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('Q', $q);

foreach ($list as $row) {
    $xtpl->assign('ROW', $row);

    foreach ($row['weight'] as $weight) {
        $xtpl->assign('WEIGHT', $weight);
        $xtpl->parse('main.row.weight');
    }

    $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;tid=" . $row['id']);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';