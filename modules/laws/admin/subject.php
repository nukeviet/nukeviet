<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 18/2/2011, 5:29
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['subject'];

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    nv_htmlOutput($alias);
}

$contents = "";
$sList = nv_sList();
$scount = count($sList);

if (empty($sList) and !$nv_Request->isset_request('add', 'get')) {
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=subject&add");
}

if ($nv_Request->isset_request('cWeight, id', 'post')) {
    $id = $nv_Request->get_int('id', 'post');
    $cWeight = $nv_Request->get_int('cWeight', 'post');
    if (!isset($sList[$id])) die("ERROR");
    
    if ($cWeight > $scount) $cWeight = $scount;
    
    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_subject WHERE id!=" . $id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        if ($weight == $cWeight) $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_subject SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($query);
    }
    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_subject SET weight=" . $cWeight . " WHERE id=" . $id;
    $db->query($query);
    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['logChangesWeight'], "Id: " . $id, $admin_info['userid']);
    nv_htmlOutput('OK');
}

if ($nv_Request->isset_request('del', 'post')) {
    $id = $nv_Request->get_int('del', 'post', 0);
    if (!isset($sList[$id])) die($lang_module['errorSubjectNotExists']);
    $sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE sid=" . $id;
    $result = $db->query($sql);
    $row = $result->fetch();
    if ($row['count']) die($lang_module['errorSubjectYesRow']);
    
    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_subject WHERE id = " . $id;
    $db->query($query);
    fix_subjectWeight();
    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['logDelSubject'], "Id: " . $id, $admin_info['userid']);
    nv_htmlOutput('OK');
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE);

if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit, id', 'get')) {
    $post = array();
    if ($nv_Request->isset_request('edit', 'get')) {
        $post['id'] = $nv_Request->get_int('id', 'get');
        if (empty($post['id']) or !isset($sList[$post['id']])) {
            nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=subject");
        }
        
        $xtpl->assign('PTITLE', $lang_module['editSubject']);
        $xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=subject&edit&id=" . $post['id']);
        $log_title = $lang_module['editSubject'];
    } else {
        $xtpl->assign('PTITLE', $lang_module['addSubject']);
        $xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=subject&add");
        $log_title = $lang_module['addSubject'];
    }
    
    if ($nv_Request->isset_request('save', 'post')) {
        $post['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $post['introduction'] = $nv_Request->get_title('introduction', 'post', '', 1);
        $post['introduction'] = nv_nl2br($post['introduction'], "<br />");
        $post['keywords'] = $nv_Request->get_title('keywords', 'post', '', 1);
        if (!empty($post['keywords'])) {
            $post['keywords'] = explode(",", $post['keywords']);
            $post['keywords'] = array_map("trim", $post['keywords']);
            $post['keywords'] = array_unique($post['keywords']);
            $post['keywords'] = implode(",", $post['keywords']);
        }
        
        $post['alias'] = $nv_Request->get_title('alias', 'post', '', 1);
        if (empty($post['alias'])) {
            $post['alias'] = change_alias($post['title']);
            
            $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject WHERE id !=' . $post['id'] . ' AND alias = :alias');
            $stmt->bindParam(':alias', $post['alias'], PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->fetchColumn()) {
                $weight = $db->query('SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject')->fetchColumn();
                $weight = intval($weight) + 1;
                $post['alias'] = $post['alias'] . '-' . $weight;
            }
        }
        
        if (empty($post['title'])) {
            die($lang_module['errorIsEmpty'] . ": " . $lang_module['title']);
        }
        
        $_sList = $sList;
        if (isset($post['id'])) unset($_sList[$post['id']]);
        
        if (isset($post['id'])) {
            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_subject SET
                    alias=" . $db->quote($post['alias']) . ",
                    title=" . $db->quote($post['title']) . ",
                    introduction=" . $db->quote($post['introduction']) . ",
                    keywords=" . $db->quote($post['keywords']) . "
                    WHERE id=" . $post['id'];
            $db->query($query);
        } else {
            $weight = $scount + 1;
            
            $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_subject
                VALUES (NULL, " . $db->quote($post['alias']) . ", " . $db->quote($post['title']) . ",
                " . $db->quote($post['introduction']) . ", " . $db->quote($post['keywords']) . ",
                0, 5, " . NV_CURRENTTIME . ", " . $weight . ");";
            $db->query($query);
        }
        
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid']);
        nv_htmlOutput('OK');
    }
    
    if ($nv_Request->isset_request('edit', 'get')) {
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_subject WHERE id=" . $post['id'];
        $result = $db->query($sql);
        $row = $result->fetch();
        $post['title'] = $row['title'];
        $post['alias'] = $row['alias'];
        $post['introduction'] = nv_br2nl($row['introduction']);
        $post['keywords'] = $row['keywords'];
    } else {
        $post['title'] = "";
        $post['alias'] = "";
        $post['introduction'] = "";
        $post['keywords'] = "";
    }
    
    $xtpl->assign('CAT', $post);
    
    if (empty($post['id'])) {
        $xtpl->parse('action.auto_get_alias');
    }
    
    $xtpl->parse('action');
    $contents = $xtpl->text('action');
    
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('list', 'get')) {
    $a = 0;
    foreach ($sList as $id => $values) {
        $loop = array(
            'id' => $id,
            'title' => $values['title']
        );
        $xtpl->assign('LOOP', $loop);
        
        for ($i = 1; $i <= $scount; $i++) {
            $opt = array(
                'value' => $i,
                'selected' => $i == $values['weight'] ? " selected=\"selected\"" : ""
            );
            $xtpl->assign('NEWWEIGHT', $opt);
            $xtpl->parse('list.loop.option');
        }
        
        for ($i = 0; $i <= 20; ++$i) {
            $xtpl->assign('NUMLINKS', array(
                'key' => $i,
                'title' => $i,
                'selected' => $i == $values['numlink'] ? ' selected="selected"' : ''
            ));
            $xtpl->parse('list.loop.numlinks');
        }
        
        $xtpl->parse('list.loop');
        $a++;
    }
    $xtpl->parse('list');
    $xtpl->out('list');
    exit();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';