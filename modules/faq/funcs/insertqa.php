<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/14/2017 09:47
 */

if (!defined('NV_IS_MOD_FAQ')) die('Stop!!!');

if ($module_setting['user_post'] != 1 or !defined('NV_IS_USER')) {
    Header('Location:' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    exit();
}

$id = '';
//Add, edit file
if ($nv_Request->isset_request('edit', 'get')) {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id) {
        $query = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id=' . $id;
        $result = $db->query($query);
        $numrows = $result->rowCount();
        
        if ($numrows != 1) {
            Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            exit();
        }
        
        define('IS_EDIT', true);
        $page_title = $lang_module['faq_editfaq'];
        $row = $result->fetch();
    } else {
        $row = array();
    }
} else {
    define('IS_ADD', true);
    $page_title = $lang_module['faq_addfaq'];
}

$array = array();
$is_error = false;
$error = '';

if ($nv_Request->isset_request('submit', 'post')) {
    $array['catid'] = $nv_Request->get_int('catid', 'post', 0);
    $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $array['question'] = $nv_Request->get_textarea('question', '', NV_ALLOWED_HTML_TAGS);
    $array['answer'] = $nv_Request->get_editor('answer', '', NV_ALLOWED_HTML_TAGS);
    
    if ($global_config['captcha_type'] == 2) {
        $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } else {
        $fcode = $nv_Request->get_title('fcode', 'post', '');
    }
    if (defined('IS_ADD')) {
        $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE alias=' . $db->quote($alias);
        $result = $db->query($sql);
        $is_exists = $result->fetchColumn();
    } else {
        $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id!=' . $id . ' AND alias=' . $db->quote($alias);
        $result = $db->query($sql);
        $is_exists = $result->fetchColumn();
    }
    
    if (empty($array['title'])) {
        $is_error = true;
        $error = $lang_module['faq_error_title'];
    } elseif (empty($array['catid'])) {
        $is_error = true;
        $error = $lang_module['faq_error_cat'];
    } elseif ($is_exists) {
        $is_error = true;
        $error = $lang_module['faq_title_exists'];
    } elseif (empty($array['question'])) {
        $is_error = true;
        $error = $lang_module['faq_error_question'];
    } elseif (!nv_capcha_txt($fcode)) {
        $is_error = true;
        $error = ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']);
    } else {
        $array['question'] = nv_nl2br($array['question'], '<br />');
        $array['answer'] = nv_editor_nl2br($array['answer']);
        
        if (defined('IS_EDIT')) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                catid=' . $array['catid'] . ',
                title=' . $db->quote($array['title']) . ',
                question=' . $db->quote($array['question']) . ',
                answer=' . $db->quote($array['answer']) . '
                WHERE id=' . $id . ' AND userid=' . $user_info['userid'];
            $result = $db->query($sql);
            
            if (!$result) {
                $is_error = true;
                $error = $lang_module['faq_error_notResult'];
            } else {
                nv_update_keywords($array['catid']);
                
                if ($array['catid'] != $row['catid']) {
                    nv_update_keywords($row['catid']);
                }
                
                Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=list');
                exit();
            }
        } elseif (defined('IS_ADD')) {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp(catid,title,question,answer,addtime,userid) VALUES (
                ' . $array['catid'] . ',
                ' . $db->quote($array['title']) . ',
                ' . $db->quote($array['question']) . ',
                ' . $db->quote($array['answer']) . ',
                ' . NV_CURRENTTIME . ',
                ' . $user_info['userid'] . ')';
            
            if (!$db->insert_id($sql)) {
                $is_error = true;
                $error = $lang_module['faq_error_notResult2'];
            } else {
                nv_update_keywords($array['catid']);
                Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=list');
                exit();
            }
        }
    }
} else {
    if (defined('IS_EDIT')) {
        $array['catid'] = (int) $row['catid'];
        $array['title'] = $row['title'];
        $array['answer'] = nv_editor_br2nl($row['answer']);
        $array['question'] = nv_br2nl($row['question']);
    } else {
        $array['catid'] = 0;
        $array['title'] = $array['answer'] = $array['question'] = '';
    }
}

if (!empty($array['answer'])) {
    $array['answer'] = nv_htmlspecialchars($array['answer']);
}
if (!empty($array['question'])) {
    $array['question'] = nv_htmlspecialchars($array['question']);
}

$listcats = array();
$listcats[0] = array(
    'id' => 0,
    'name' => $lang_module['nocat'],
    'selected' => $array['catid'] == 0 ? ' selected="selected"' : ''
);

$listcats = $listcats + nv_listcats($array['catid']);

if (defined('NV_EDITOR')) {
    require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['answer'] = nv_aleditor('answer', '100%', '300px', $array['answer']);
} else {
    $array['answer'] = '<textarea style="width:100%; height:300px" name="answer" id="answer">' . $array['answer'] . '</textarea>';
}

$contents = theme_insert_faq($array, $error, $listcats, $id);
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';