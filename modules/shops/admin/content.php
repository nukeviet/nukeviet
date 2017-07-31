<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (empty($global_array_shops_cat)) {
    $url_back = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat';
    $contents = nv_theme_alert($lang_module['error_cat_empty_title'], $lang_module['error_cat_empty_content'], 'warning', $url_back, $lang_module['continue']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($money_config)) {
    $url_back = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=money';
    $contents = nv_theme_alert($lang_module['error_munit_empty_title'], $lang_module['error_munit_empty_content'], 'warning', $url_back, $lang_module['continue']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m');
if (!file_exists($currentpath)) {
    nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_upload, date('Y_m'), true);
}

$table_name = $db_config['prefix'] . '_' . $module_data . '_rows';
$array_block_cat_module = array();
$id_block_content = array();
$array_custom = $array_custom_old = array();
$custom = array();

$sql = 'SELECT bid, adddefault, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db->query($sql);
while (list($bid_i, $adddefault_i, $title_i) = $result->fetch(3)) {
    $array_block_cat_module[$bid_i] = $title_i;
    if ($adddefault_i) {
        $id_block_content[] = $bid_i;
    }
}

$catid = $nv_Request->get_int('catid', 'get', 0);

$rowcontent = array(
    'id' => 0,
    'listcatid' => $catid,
    'user_id' => $admin_info['admin_id'],
    'addtime' => NV_CURRENTTIME,
    'edittime' => NV_CURRENTTIME,
    'status' => 0,
    'publtime' => NV_CURRENTTIME,
    'exptime' => 0,
    'archive' => 1,
    'product_code' => '',
    'product_number' => 1,
    'product_price' => 1,
    'discount_id' => 0,
    'money_unit' => $pro_config['money_unit'],
    'product_unit' => '',
    'product_weight' => '',
    'weight_unit' => $pro_config['weight_unit'],
    'homeimgfile' => '',
    'homeimgthumb' => '',
    'homeimgalt' => '',
    'imgposition' => 0,
    'copyright' => 0,
    'inhome' => 1,
    'allowed_comm' => $module_config[$module_name]['setcomm'],
    'allowed_rating' => 1,
    'ratingdetail' => '0',
    'allowed_send' => 1,
    'allowed_print' => 1,
    'allowed_save' => 1,
    'hitstotal' => 0,
    'hitscm' => 0,
    'hitslm' => 0,
    'showprice' => 1,
    'com_id' => 0,
    'title' => '',
    'alias' => '',
    'hometext' => '',
    'bodytext' => '',
    'note' => '',
    'keywords' => '',
    'keywords_old' => '',
    'files' => array(),
    'files_old' => array(),
    'gift_content' => '',
    'gift_from' => NV_CURRENTTIME,
    'gift_to' => 0,
    'tag_title' => '',
    'tag_description' => ''
);

$page_title = $lang_module['content_add'];
$error = '';
$groups_list = nv_groups_list();

$is_copy = $nv_Request->isset_request('copy', 'get');
$rowcontent['id'] = $nv_Request->get_int('id', 'get,post', 0);

$group_id_old = array( );
if ($rowcontent['id'] > 0) {
    $rowcontent['listcatid'] = $db->query("SELECT listcatid FROM " . $db_config['prefix'] . "_" . $module_data . "_rows where id=" . $rowcontent['id'])->fetchColumn();

    // Old group
    $group_id_old = getGroupID($rowcontent['id']);

    // Old keywords
    $array_keywords_old = array( );
    $_query = $db->query('SELECT tid, keyword FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_id_' . NV_LANG_DATA . ' WHERE id=' . $rowcontent['id'] . ' ORDER BY keyword ASC');
    while ($row = $_query->fetch()) {
        $array_keywords_old[$row['tid']] = $row['keyword'];
    }
    $rowcontent['keywords'] = implode(', ', $array_keywords_old);
    $rowcontent['keywords_old'] = $rowcontent['keywords'];

    // Old files
    $result = $db->query("SELECT id_files FROM " . $db_config['prefix'] . "_" . $module_data . "_files_rows WHERE id_rows=" . $rowcontent['id']);
    if ($result->rowCount() > 0) {
        while (list($id_files) = $result->fetch(3)) {
            $rowcontent['files'][] = $id_files;
        }
        $rowcontent['files_old'] = $rowcontent['files'];
    }

    // Custom fields
    $idtemplate = $db->query('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE alias = "' . preg_replace("/[\_]/", "-", $global_array_shops_cat[$rowcontent['listcatid']]['form']) . '"')->fetchColumn();
    if ($idtemplate) {
        $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_field_value_" . NV_LANG_DATA . " WHERE rows_id=" . $rowcontent['id']);
        while ($row = $result->fetch()) {
            $custom[$row['field_id']] = $row['field_value'];
            $array_custom_old[] = $row['field_id'];
        }
    }
}

if ($nv_Request->get_int('save', 'post') == 1) {
    $field_lang = nv_file_table($table_name);
    $id_block_content = array_unique($nv_Request->get_typed_array('bids', 'post', 'int', array( )));
    $rowcontent['listcatid'] = $nv_Request->get_int('catid', 'post', 0);
    $rowcontent['group_id'] = array_unique($nv_Request->get_typed_array('groupids', 'post', 'int', array( )));
    $rowcontent['showprice'] = $nv_Request->get_int('showprice', 'post', 0);
    $rowcontent['showorder'] = $nv_Request->get_int('showorder', 'post', 0);
    $publ_date = $nv_Request->get_title('publ_date', 'post', '');
    $exp_date = $nv_Request->get_title('exp_date', 'post', '');

    if (!empty($publ_date) and !preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date)) {
        $publ_date = '';
    }
    if (!empty($exp_date) and !preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date)) {
        $exp_date = '';
    }

    if (empty($publ_date)) {
        $rowcontent['publtime'] = NV_CURRENTTIME;
    } else {
        $phour = $nv_Request->get_int('phour', 'post', 0);
        $pmin = $nv_Request->get_int('pmin', 'post', 0);
        unset($m);
        preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m);
        $rowcontent['publtime'] = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
    }

    if (empty($exp_date)) {
        $rowcontent['exptime'] = 0;
    } else {
        $ehour = $nv_Request->get_int('ehour', 'post', 0);
        $emin = $nv_Request->get_int('emin', 'post', 0);
        unset($m);
        preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m);
        $rowcontent['exptime'] = mktime($ehour, $emin, 0, $m[2], $m[1], $m[3]);
    }

    $rowcontent['archive'] = $nv_Request->get_int('archive', 'post', 0);

    if ($rowcontent['archive'] > 0) {
        $rowcontent['archive'] = ($rowcontent['exptime'] > NV_CURRENTTIME) ? 1 : 2;
    }

    $rowcontent['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $rowcontent['note'] = $nv_Request->get_title('note', 'post', '', 1);
    $rowcontent['address'] = $nv_Request->get_title('address', 'post', '', 1);
    $rowcontent['files'] = $nv_Request->get_typed_array('files', 'post', 'int', array());

    $rowcontent['gift_content'] = $nv_Request->get_textarea('gift_content', '', 'br');
    $rowcontent['gift_from'] = $nv_Request->get_title('gift_from', 'post', '');
    $rowcontent['gift_to'] = $nv_Request->get_title('gift_to', 'post', '');
    if (!empty($rowcontent['gift_content']) and preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $rowcontent['gift_from'], $m)) {
        $gift_from_h = $nv_Request->get_int('gift_from_h', 'post', 0);
        $gift_from_m = $nv_Request->get_int('gift_from_m', 'post', 0);
        $rowcontent['gift_from'] = mktime($gift_from_h, $gift_from_m, 0, $m[2], $m[1], $m[3]);
    } else {
        $rowcontent['gift_from'] = 0;
    }

    if (!empty($rowcontent['gift_content']) and preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $rowcontent['gift_to'], $m)) {
        $gift_to_h = $nv_Request->get_int('gift_to_h', 'post', 23);
        $gift_to_m = $nv_Request->get_int('gift_to_m', 'post', 59);
        $rowcontent['gift_to'] = mktime($gift_to_h, $gift_to_m, 59, $m[2], $m[1], $m[3]);
    } else {
        $rowcontent['gift_to'] = 0;
    }

    $alias = nv_substr($nv_Request->get_title('alias', 'post', '', 1), 0, 255);
    $rowcontent['alias'] = ($alias == '') ? change_alias($rowcontent['title']) : change_alias($alias);

    if (!empty($rowcontent['alias'])) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id !=' . $rowcontent['id'] . ' AND '  . NV_LANG_DATA . '_alias = :alias');
        $stmt->bindParam(':alias', $rowcontent['alias'], PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $rows_id = $rowcontent['id'];
            if ($rows_id == 0) {
                $rows_id = $db->query('SELECT MAX(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows')->fetchColumn();
                $rows_id = intval($rows_id) + 1;
            }
            $rowcontent['alias'] = $rowcontent['alias'] . '-' . $rows_id;
        }
    }

    $hometext = $nv_Request->get_string('hometext', 'post', '');
    $rowcontent['hometext'] = defined('NV_EDITOR') ? nv_nl2br($hometext, '') : nv_nl2br(nv_htmlspecialchars(strip_tags($hometext)), '<br />');

    $rowcontent['product_code'] = nv_substr($nv_Request->get_title('product_code', 'post', '', 1), 0, 255);
    $rowcontent['product_number'] = $nv_Request->get_int('product_number', 'post', 0);
    $rowcontent['product_price'] = $nv_Request->get_string('product_price', 'post', '');
    $rowcontent['product_price'] = floatval(preg_replace('/[^0-9\.]/', '', $rowcontent['product_price']));

    $rowcontent['discount_id'] = $nv_Request->get_int('discount_id', 'post', 0);
    $rowcontent['money_unit'] = $nv_Request->get_string('money_unit', 'post', '');

    $rowcontent['product_weight'] = $nv_Request->get_string('product_weight', 'post', '');
    $rowcontent['product_weight'] = floatval(preg_replace('/[^0-9\.]/', '', $rowcontent['product_weight']));
    $rowcontent['weight_unit'] = $nv_Request->get_string('weight_unit', 'post', '');

    $rowcontent['product_unit'] = $nv_Request->get_int('product_unit', 'post', 0);
    $rowcontent['homeimgfile'] = $nv_Request->get_title('homeimg', 'post', '');
    $rowcontent['homeimgalt'] = $nv_Request->get_title('homeimgalt', 'post', '', 1);

    $typeprice = ($rowcontent['listcatid']) ? $global_array_shops_cat[$rowcontent['listcatid']]['typeprice'] : 0;
    if ($typeprice == 2) {
        $price_config = $nv_Request->get_array('price_config', 'post');
        $sortArray = array( );
        foreach ($price_config as $price_config_i) {
            $sortArray['number_to'][] = intval($price_config_i['number_to']);
            $sortArray['price'][] = floatval(preg_replace('/[^0-9\.]/', '', $price_config_i['price']));
        }
        array_multisort($sortArray['number_to'], SORT_ASC, $price_config);

        $price_config_save = array( );
        $i = 0;
        foreach ($price_config as $key => $price_config_i) {
            $price_config_i['number_to'] = intval($price_config_i['number_to']);
            $price_config_i['price'] = floatval(preg_replace('/[^0-9\.]/', '', $price_config_i['price']));
            if ($price_config_i['number_to'] > 0 and $price_config_i['price'] >= 0) {
                $price_config_i['id'] = ++$i;
                $price_config_save[$i] = $price_config_i;
            }
        }
        $rowcontent['product_price'] = isset($price_config_save[1]) ? $price_config_save[1]['price'] : 0;
        $rowcontent['price_config'] = serialize($price_config_save);
    } else {
        $rowcontent['product_price'] = $nv_Request->get_string('product_price', 'post', '');
        $rowcontent['product_price'] = floatval(preg_replace('/[^0-9\.]/', '', $rowcontent['product_price']));

        $rowcontent['price_config'] = '';
    }

    $bodytext = $nv_Request->get_string('bodytext', 'post', '');
    $rowcontent['bodytext'] = defined('NV_EDITOR') ? nv_nl2br($bodytext, '') : nv_nl2br(nv_htmlspecialchars(strip_tags($bodytext)), '<br />');

    $rowcontent['copyright'] = ( int )$nv_Request->get_bool('copyright', 'post');
    $rowcontent['inhome'] = ( int )$nv_Request->get_bool('inhome', 'post');

    $rowcontent['tag_title'] = $nv_Request->get_title('tag_title', 'post', '');
    $rowcontent['tag_description'] = $nv_Request->get_textarea('tag_description', '', NV_ALLOWED_HTML_TAGS);

    $_groups_post = $nv_Request->get_array('allowed_comm', 'post', array( ));
    $rowcontent['allowed_comm'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $rowcontent['allowed_rating'] = ( int )$nv_Request->get_bool('allowed_rating', 'post');
    $rowcontent['allowed_send'] = ( int )$nv_Request->get_bool('allowed_send', 'post');
    $rowcontent['allowed_print'] = ( int )$nv_Request->get_bool('allowed_print', 'post');
    $rowcontent['allowed_save'] = ( int )$nv_Request->get_bool('allowed_save', 'post');

    $rowcontent['keywords'] = $nv_Request->get_array('keywords', 'post', '');
    $rowcontent['keywords'] = implode(', ', $rowcontent['keywords']);

    $array_custom = $nv_Request->get_array('custom', 'post');
    $custom = $array_custom;

    // Xu ly anh minh hoa khac
    $otherimage = $nv_Request->get_typed_array('otherimage', 'post', 'string');
    $array_otherimage = array( );
    foreach ($otherimage as $otherimage_i) {
        if (!nv_is_url($otherimage_i) and file_exists(NV_DOCUMENT_ROOT . $otherimage_i)) {
            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
            $otherimage_i = substr($otherimage_i, $lu);
        } elseif (!nv_is_url($otherimage_i)) {
            $otherimage_i = '';
        }
        if (!empty($otherimage_i)) {
            $array_otherimage[] = $otherimage_i;
        }
    }
    $rowcontent['otherimage'] = implode('|', $array_otherimage);

    // Kiem tra ma san pham trung
    $error_product_code = false;
    if (!empty($rowcontent['product_code'])) {
        $stmt = $db->prepare('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE product_code= :product_code AND id!=' . $rowcontent['id']);
        $stmt->bindParam(':product_code', $rowcontent['product_code'], PDO::PARAM_STR);
        $stmt->execute();
        $id_err = $stmt->rowCount();

        $stmt = $db->prepare('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE product_code= :product_code');
        $stmt->bindParam(':product_code', $rowcontent['product_code'], PDO::PARAM_STR);
        $stmt->execute();
        if ($rowcontent['id'] == 0 and $stmt->rowCount()) {
            $error_product_code = true;
        } elseif ($id_err) {
            $error_product_code = true;
        }
    }

    if (empty($rowcontent['title'])) {
        $error = $lang_module['error_title'];
    } elseif ($error_product_code) {
        $error = $lang_module['error_product_code'];
    } elseif (empty($rowcontent['listcatid'])) {
        $error = $lang_module['error_cat'];
    } elseif ($pro_config['use_shipping'] and empty($rowcontent['product_weight'])) {
        $error = $lang_module['error_weight'];
    } elseif (trim(strip_tags($rowcontent['hometext'])) == '') {
        $error = $lang_module['error_hometext'];
    } elseif (trim(strip_tags($rowcontent['bodytext'])) == '') {
        $error = $lang_module['error_bodytext'];
    } elseif ($rowcontent['product_price'] <= 0 and $rowcontent['showprice']) {
        $error = $lang_module['error_product_price'];
    }

    // Kiem tra nhom bat buoc
    $group_cat = array();
    $result = $db->query('SELECT groupid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid WHERE cateid = ' . $rowcontent['listcatid']);
    if ($result->rowCount() > 0) {
        while (list($groupid) = $result->fetch(3)) {
            if ($global_array_group[$groupid]['is_require']) {
                $group_cat[] = $groupid;
            }
        }
    }

    if (! empty($group_cat)) {
        foreach ($group_cat as $groupid) {
            $check = 0;
            $listsub = $global_array_group[$groupid]['subgroupid'];
            $listsub = explode(',', $listsub);
            $listsub = array_map('intval', $listsub);

            foreach ($rowcontent['group_id'] as $group_id) {
                if (in_array($group_id, $listsub)) {
                    $check = 1;
                    break;
                }
            }

            if (!$check) {
                $error = sprintf($lang_module['group_require_content'], $global_array_group[$groupid]['title']);
                break;
            }
        }
    }

    if (isset($global_array_shops_cat[$rowcontent['listcatid']]) and $global_array_shops_cat[$rowcontent['listcatid']]['form'] != '') {
        require NV_ROOTDIR . '/modules/' . $module_file . '/fields.check.php';
    }

    if (empty($error)) {
        // Xu ly tu khoa
        if ($rowcontent['keywords'] == '') {
            $keywords = ($rowcontent['hometext'] != '') ? $rowcontent['hometext'] : $rowcontent['bodyhtml'];
            $keywords = nv_get_keywords($keywords, 100);
            $keywords = explode(',', $keywords);

            // Ưu tiên lọc từ khóa theo các từ khóa đã có trong tags thay vì đọc từ từ điển
            $keywords_return = array( );
            $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . "_" . $module_data . '_tags_id_' . NV_LANG_DATA . ' where keyword = :keyword');
            foreach ($keywords as $keyword_i) {
                $sth->bindParam(':keyword', $keyword_i, PDO::PARAM_STR);
                $sth->execute();
                if ($sth->fetchColumn()) {
                    $keywords_return[] = $keyword_i;
                    if (sizeof($keywords_return) > 20) {
                        break;
                    }
                }
            }
            $sth->closeCursor();

            if (sizeof($keywords_return) < 20) {
                foreach ($keywords as $keyword_i) {
                    if (!in_array($keyword_i, $keywords_return)) {
                        $keywords_return[] = $keyword_i;
                        if (sizeof($keywords_return) > 20) {
                            break;
                        }
                    }
                }
            }
            $rowcontent['keywords'] = implode(',', $keywords);
        }
        $rowcontent['status'] = ($nv_Request->isset_request('status1', 'post')) ? 1 : 0;

        // Xu ly anh minh hoa
        $rowcontent['homeimgthumb'] = 0;
        if (!nv_is_url($rowcontent['homeimgfile']) and is_file(NV_DOCUMENT_ROOT . $rowcontent['homeimgfile'])) {
            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
            $rowcontent['homeimgfile'] = substr($rowcontent['homeimgfile'], $lu);
            if (file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
                $rowcontent['homeimgthumb'] = 1;
            } else {
                $rowcontent['homeimgthumb'] = 2;
            }
        } elseif (nv_is_url($rowcontent['homeimgfile'])) {
            $rowcontent['homeimgthumb'] = 3;
        } else {
            $rowcontent['homeimgfile'] = '';
        }

        $listfield = '';
        $listvalue = '';
        foreach ($field_lang as $field_lang_i) {
            list($flang, $fname) = $field_lang_i;
            $listfield .= ', ' . $flang . '_' . $fname;
            $listvalue .= ', :' . $flang . '_' . $fname;
        }

        if ($rowcontent['id'] == 0 or $is_copy) {
            $rowcontent['publtime'] = ($rowcontent['publtime'] > NV_CURRENTTIME) ? $rowcontent['publtime'] : NV_CURRENTTIME;
            if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
                $rowcontent['status'] = 2;
            }

            $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt,otherimage,imgposition, copyright, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, showprice " . $listfield . ")
				 VALUES ( NULL ,
				 :listcatid,
				 " . intval($rowcontent['user_id']) . ",
				 " . intval($rowcontent['addtime']) . ",
				 " . intval($rowcontent['edittime']) . ",
				 " . intval($rowcontent['status']) . ",
				 " . intval($rowcontent['publtime']) . ",
				 " . intval($rowcontent['exptime']) . ",
				 " . intval($rowcontent['archive']) . ",
				 :product_code,
				 " . intval($rowcontent['product_number']) . ",
				 :product_price,
				 :price_config,
				 :money_unit,
				 " . intval($rowcontent['product_unit']) . ",
				 :product_weight,
				 :weight_unit,
				 " . intval($rowcontent['discount_id']) . ",
				 :homeimgfile,
				 :homeimgthumb,
				 :homeimgalt,
				 :otherimage,
				 " . intval($rowcontent['imgposition']) . ",
				 " . intval($rowcontent['copyright']) . ",
				 " . intval($rowcontent['inhome']) . ",
				 :allowed_comm,
				 " . intval($rowcontent['allowed_rating']) . ",
				 :ratingdetail,
				 " . intval($rowcontent['allowed_send']) . ",
				 " . intval($rowcontent['allowed_print']) . ",
				 " . intval($rowcontent['allowed_save']) . ",
				 " . intval($rowcontent['hitstotal']) . ",
				 " . intval($rowcontent['hitscm']) . ",
				 " . intval($rowcontent['hitslm']) . ",
				 " . intval($rowcontent['showprice']) . "
				" . $listvalue . "
			)";

            $data_insert = array( );
            $data_insert['listcatid'] = $rowcontent['listcatid'];
            $data_insert['product_code'] = $rowcontent['product_code'];
            $data_insert['product_price'] = $rowcontent['product_price'];
            $data_insert['price_config'] = $rowcontent['price_config'];
            $data_insert['product_weight'] = $rowcontent['product_weight'];
            $data_insert['weight_unit'] = $rowcontent['weight_unit'];
            $data_insert['money_unit'] = $rowcontent['money_unit'];
            $data_insert['homeimgfile'] = $rowcontent['homeimgfile'];
            $data_insert['homeimgthumb'] = $rowcontent['homeimgthumb'];
            $data_insert['homeimgalt'] = $rowcontent['homeimgalt'];
            $data_insert['otherimage'] = $rowcontent['otherimage'];
            $data_insert['ratingdetail'] = $rowcontent['ratingdetail'];
            $data_insert['allowed_comm'] = $rowcontent['allowed_comm'];

            foreach ($field_lang as $field_lang_i) {
                list($flang, $fname) = $field_lang_i;
                $data_insert[$flang . '_' . $fname] = $rowcontent[$fname];
            }

            unset($sth);
            $rowcontent['id'] = $db->insert_id($sql, 'catid', $data_insert);

            if ($rowcontent['id'] > 0) {
                // Them du lieu tuy bien
                if ($global_array_shops_cat[$rowcontent['listcatid']]['form'] != '') {
                    foreach ($array_custom as $field_id => $value) {
                        $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_field_value_' . NV_LANG_DATA . '(rows_id, field_id, field_value) VALUES (:rows_id, :field_id, :field_value)');

                        $sth->bindParam(':rows_id', $rowcontent['id'], PDO::PARAM_INT);
                        $sth->bindParam(':field_id', $field_id, PDO::PARAM_INT);
                        $sth->bindParam(':field_value', $value, PDO::PARAM_STR, strlen($value));
                        $sth->execute();
                    }
                }

                // Them nhom san pham
                if (!empty($rowcontent['group_id'])) {
                    $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_group_items VALUES(' . $rowcontent['id'] . ', :group_id)');

                    foreach ($rowcontent['group_id'] as $group_id_i) {
                        $stmt->bindParam(':group_id', $group_id_i, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }

                // Cap nhat ma san pham
                $auto_product_code = '';
                if (!empty($pro_config['format_code_id']) and empty($rowcontent['product_code'])) {
                    $i = 1;
                    $auto_product_code = vsprintf($pro_config['format_code_id'], $rowcontent['id']);

                    $stmt = $db->prepare('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE product_code= :product_code');
                    $stmt->bindParam(':product_code', $auto_product_code, PDO::PARAM_STR);
                    $stmt->execute();
                    while ($stmt->rowCount()) {
                        $auto_product_code = vsprintf($pro_config['format_code_id'], ($rowcontent['id'] + $i++));
                    }

                    $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_code= :product_code WHERE id=' . $rowcontent['id']);
                    $stmt->bindParam(':product_code', $auto_product_code, PDO::PARAM_STR);
                    $stmt->execute();
                }

                // Add file download
                if ($pro_config['download_active']) {
                    if (!empty($rowcontent['files'])) {
                        foreach ($rowcontent['files'] as $id_files) {
                            $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_files_rows (id_rows, id_files, download_hits) VALUES (' . $rowcontent['id'] . ', ' . $id_files .', 0)');
                        }
                    }
                }

                nv_fix_group_count($rowcontent['group_id']);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add A Product', 'ID: ' . $rowcontent['id'], $admin_info['userid']);
            } else {
                $error = $lang_module['errorsave'];
            }
        } else {
            $rowcontent_old = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows where id=' . $rowcontent['id'])->fetch();

            $rowcontent['user_id'] = $rowcontent_old['user_id'];

            if ($rowcontent_old['status'] == 1) {
                $rowcontent['status'] = 1;
            }
            if (intval($rowcontent['publtime']) < intval($rowcontent_old['addtime'])) {
                $rowcontent['publtime'] = $rowcontent_old['addtime'];
            }

            if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
                $rowcontent['status'] = 2;
            }

            $stmt = $db->prepare("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET
			 listcatid= :listcatid,
			 user_id=" . intval($rowcontent['user_id']) . ",
			 status=" . intval($rowcontent['status']) . ",
			 publtime=" . intval($rowcontent['publtime']) . ",
			 exptime=" . intval($rowcontent['exptime']) . ",
			 edittime= " . NV_CURRENTTIME . " ,
			 archive=" . intval($rowcontent['archive']) . ",
			 product_code = :product_code,
			 product_number = product_number + " . intval($rowcontent['product_number']) . ",
			 product_price = :product_price,
			 price_config = :price_config,
			 money_unit = :money_unit,
			 product_unit = " . intval($rowcontent['product_unit']) . ",
			 product_weight = :product_weight,
			 weight_unit = :weight_unit,
			 discount_id = " . intval($rowcontent['discount_id']) . ",
			 homeimgfile= :homeimgfile,
			 homeimgalt= :homeimgalt,
			 otherimage= :otherimage,
			 homeimgthumb= :homeimgthumb,
			 imgposition=" . intval($rowcontent['imgposition']) . ",
			 copyright=" . intval($rowcontent['copyright']) . ",
			 gift_from=" . intval($rowcontent['gift_from']) . ",
			 gift_to=" . intval($rowcontent['gift_to']) . ",
			 inhome=" . intval($rowcontent['inhome']) . ",
			 allowed_comm= :allowed_comm,
			 allowed_rating=" . intval($rowcontent['allowed_rating']) . ",
			 allowed_send=" . intval($rowcontent['allowed_send']) . ",
			 allowed_print=" . intval($rowcontent['allowed_print']) . ",
			 allowed_save=" . intval($rowcontent['allowed_save']) . ",
			 showprice = " . intval($rowcontent['showprice']) . ",
			 " . NV_LANG_DATA . "_title= :title,
			  " . NV_LANG_DATA . "_address= :address,
			 " . NV_LANG_DATA . "_alias= :alias,
			 " . NV_LANG_DATA . "_hometext= :hometext,
			 " . NV_LANG_DATA . "_bodytext= :bodytext,
			 " . NV_LANG_DATA . "_gift_content= :gift_content,
			 " . NV_LANG_DATA . "_tag_title= :tag_title,
			 " . NV_LANG_DATA . "_tag_description= :tag_description
			 WHERE id =" . $rowcontent['id']);

            $stmt->bindParam(':listcatid', $rowcontent['listcatid'], PDO::PARAM_STR);
            $stmt->bindParam(':product_code', $rowcontent['product_code'], PDO::PARAM_STR);
            $stmt->bindParam(':money_unit', $rowcontent['money_unit'], PDO::PARAM_STR);
            $stmt->bindParam(':product_price', $rowcontent['product_price'], PDO::PARAM_STR);
            $stmt->bindParam(':price_config', $rowcontent['price_config'], PDO::PARAM_INT);
            $stmt->bindParam(':product_weight', $rowcontent['product_weight'], PDO::PARAM_STR);
            $stmt->bindParam(':weight_unit', $rowcontent['weight_unit'], PDO::PARAM_STR);
            $stmt->bindParam(':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR);
            $stmt->bindParam(':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR);
            $stmt->bindParam(':otherimage', $rowcontent['otherimage'], PDO::PARAM_STR);
            $stmt->bindParam(':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_STR);
            $stmt->bindParam(':title', $rowcontent['title'], PDO::PARAM_STR);
            $stmt->bindParam(':address', $rowcontent['address'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $rowcontent['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':hometext', $rowcontent['hometext'], PDO::PARAM_STR, strlen($rowcontent['hometext']));
            $stmt->bindParam(':bodytext', $rowcontent['bodytext'], PDO::PARAM_STR, strlen($rowcontent['bodytext']));
            $stmt->bindParam(':gift_content', $rowcontent['gift_content'], PDO::PARAM_STR);
            $stmt->bindParam(':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR);
            $stmt->bindParam(':tag_title', $rowcontent['tag_title'], PDO::PARAM_STR);
            $stmt->bindParam(':tag_description', $rowcontent['tag_description'], PDO::PARAM_STR, strlen($rowcontent['tag_description']));

            if ($stmt->execute()) {
                // Cap nhat lai group neu co thay doi
                if ($group_id_old != $rowcontent['group_id']) {
                    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE pro_id = ' . $rowcontent['id'];
                    $db->query($sql);

                    if (!empty($rowcontent['group_id'])) {
                        $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_group_items(pro_id, group_id) VALUES(' . $rowcontent['id'] . ', :group_id)');
                        foreach ($rowcontent['group_id'] as $group_id_i) {
                            $stmt->bindParam(':group_id', $group_id_i, PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    }

                    nv_fix_group_count($rowcontent['group_id']);
                    nv_fix_group_count($group_id_old);
                }

                // Cap nhat lai files neu co thay doi
                if ($pro_config['download_active']) {
                    $rowcontent['files'] = array_map('intval', $rowcontent['files']);
                    if ($rowcontent['files'] != $rowcontent['files_old']) {
                        foreach ($rowcontent['files'] as $id_files) {
                            if (!in_array($id_files, $rowcontent['files_old'])) {
                                $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_files_rows (id_rows, id_files) VALUES (' . $rowcontent['id'] .', ' . $id_files . ')');
                            }
                        }

                        foreach ($rowcontent['files_old'] as $id_files_old) {
                            if (!in_array($id_files_old, $rowcontent['files'])) {
                                $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_files_rows WHERE id_files = ' . $id_files_old);
                            }
                        }
                    }
                }

                if ($global_array_shops_cat[$rowcontent_old['listcatid']]['form'] != '') {
                    foreach ($array_custom as $field_id => $value) {
                        $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_field_value_' . NV_LANG_DATA . ' WHERE rows_id=' . $rowcontent['id'] . ' AND field_id=' . $field_id)->fetchColumn();
                        if ($count > 0) {
                            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field_value_' . NV_LANG_DATA . ' SET field_value = :field_value WHERE rows_id = :rows_id AND field_id = :field_id');
                        } else {
                            $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_field_value_' . NV_LANG_DATA . '(rows_id, field_id, field_value) VALUES (:rows_id, :field_id, :field_value)');
                        }
                        $sth->bindParam(':rows_id', $rowcontent['id'], PDO::PARAM_INT);
                        $sth->bindParam(':field_id', $field_id, PDO::PARAM_INT);
                        $sth->bindParam(':field_value', $value, PDO::PARAM_STR, strlen($value));
                        $sth->execute();
                    }
                }

                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit A Product', 'ID: ' . $rowcontent['id'], $admin_info['userid']);
            } else {
                $error = $lang_module['errorsave'];
            }
        }

        nv_set_status_module();

        if ($error == '') {
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE id = ' . $rowcontent['id']);

            foreach ($id_block_content as $bid_i) {
                $db->query("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES ('" . $bid_i . "', '" . $rowcontent['id'] . "', '0')");
            }

            foreach ($array_block_cat_module as $bid_i) {
                nv_news_fix_block($bid_i);
            }

            // Update tags list
            if ($rowcontent['keywords'] != $rowcontent['keywords_old']) {
                $keywords = explode(',', $rowcontent['keywords']);
                $keywords = array_map('strip_punctuation', $keywords);
                $keywords = array_map('trim', $keywords);
                $keywords = array_diff($keywords, array( '' ));
                $keywords = array_unique($keywords);

                foreach ($keywords as $keyword) {
                    if (!in_array($keyword, $array_keywords_old)) {
                        $alias_i = ($module_config[$module_name]['tags_alias']) ? change_alias($keyword) : str_replace(' ', '-', $keyword);
                        $alias_i = nv_strtolower($alias_i);
                        $sth = $db->prepare('SELECT tid, alias, description, keywords FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_' . NV_LANG_DATA . ' where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0');
                        $sth->bindParam(':alias', $alias_i, PDO::PARAM_STR);
                        $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                        $sth->execute();

                        list($tid, $alias, $keywords_i) = $sth->fetch(3);
                        if (empty($tid)) {
                            $array_insert = array( );
                            $array_insert['alias'] = $alias_i;
                            $array_insert['keyword'] = $keyword;

                            $tid = $db->insert_id("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_" . NV_LANG_DATA . " (numpro, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)", "tid", $array_insert);
                        } else {
                            if ($alias != $alias_i) {
                                if (!empty($keywords_i)) {
                                    $keyword_arr = explode(',', $keywords_i);
                                    $keyword_arr[] = $keyword;
                                    $keywords_i2 = implode(',', array_unique($keyword_arr));
                                } else {
                                    $keywords_i2 = $keyword;
                                }
                                if ($keywords_i != $keywords_i2) {
                                    $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags_' . NV_LANG_DATA . ' SET keywords= :keywords WHERE tid =' . $tid);
                                    $sth->bindParam(':keywords', $keywords_i2, PDO::PARAM_STR);
                                    $sth->execute();
                                }
                            }
                            $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags_' . NV_LANG_DATA . ' SET numpro = numpro+1 WHERE tid = ' . $tid);
                        }

                        // insert keyword for table _tags_id
                        try {
                            $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_tags_id_' . NV_LANG_DATA . ' (id, tid,  keyword) VALUES (' . $rowcontent['id'] . ', ' . intval($tid) . ', :keyword)');
                            $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                            $sth->execute();
                        } catch (PDOException $e) {
                            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags_id_' . NV_LANG_DATA . ' SET  keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . intval($tid));
                            $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                            $sth->execute();
                        }
                        unset($array_keywords_old[$tid]);
                    }
                }

                foreach ($array_keywords_old as $tid => $keyword) {
                    if (!in_array($keyword, $keywords)) {
                        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags_' . NV_LANG_DATA . ' SET numpro = numpro-1 WHERE tid = ' . $tid);
                        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_id_' . NV_LANG_DATA . ' WHERE id = ' . $rowcontent['id'] . ' AND tid=' . $tid);
                    }
                }
            }

            $nv_Cache->delMod($module_name);
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
            die();
        }

        $nv_Cache->delMod($module_name);
    }
} elseif ($rowcontent['id'] > 0) {
    $files = $rowcontent['files'];
    $keyword = $rowcontent['keywords'];
    $rowcontent = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows where id=" . $rowcontent['id'])->fetch();
    $rowcontent['title'] = $rowcontent[NV_LANG_DATA . '_title'];
    $rowcontent['alias'] = $rowcontent[NV_LANG_DATA . '_alias'];
    $rowcontent['hometext'] = $rowcontent[NV_LANG_DATA . '_hometext'];
    $rowcontent['bodytext'] = $rowcontent[NV_LANG_DATA . '_bodytext'];
    $rowcontent['gift_content'] = $rowcontent[NV_LANG_DATA . '_gift_content'];
    $rowcontent['address'] = $rowcontent[NV_LANG_DATA . '_address'];
    $rowcontent['tag_title'] = $rowcontent[NV_LANG_DATA . '_tag_title'];
    $rowcontent['tag_description'] = $rowcontent[NV_LANG_DATA . '_tag_description'];
    $rowcontent['group_id'] = $group_id_old;
    $rowcontent['keywords'] = $keyword;
    $rowcontent['files'] = $files;

    $page_title = $lang_module['content_edit'];

    if ($is_copy) {
        $rowcontent['alias'] = '';
        $rowcontent['product_code'] = '';
        $rowcontent['publtime'] = NV_CURRENTTIME;
        $rowcontent['status'] = 0;
    }

    $id_block_content = array( );
    $sql = 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE id=' . $rowcontent['id'];
    $result = $db->query($sql);

    while (list($bid_i) = $result->fetch(3)) {
        $id_block_content[] = $bid_i;
    }
}

if (!empty($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
    $rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'];
    $currentpath = dirname($rowcontent['homeimgfile']);
}

$tdate = date('H|i', $rowcontent['publtime']);
$publ_date = date('d/m/Y', $rowcontent['publtime']);
list($phour, $pmin) = explode('|', $tdate);
if ($rowcontent['exptime'] == 0) {
    $emin = $ehour = 0;
    $exp_date = '';
} else {
    $exp_date = date('d/m/Y', $rowcontent['exptime']);
    $tdate = date('H|i', $rowcontent['exptime']);
    list($ehour, $emin) = explode('|', $tdate);
}

$gift_from_h = !empty($rowcontent['gift_from']) ? nv_date('H', $rowcontent['gift_from']) : '0';
$gift_from_m = !empty($rowcontent['gift_from']) ? nv_date('i', $rowcontent['gift_from']) : '0';
$gift_to_h = !empty($rowcontent['gift_to']) ? nv_date('H', $rowcontent['gift_to']) : '23';
$gift_to_m = !empty($rowcontent['gift_to']) ? nv_date('i', $rowcontent['gift_to']) : '59';
$rowcontent['gift_from'] = !empty($rowcontent['gift_from']) ? nv_date('d/m/Y', $rowcontent['gift_from']) : '';
$rowcontent['gift_to'] = !empty($rowcontent['gift_to']) ? nv_date('d/m/Y', $rowcontent['gift_to']) : '';

if (!empty($rowcontent['otherimage'])) {
    $otherimage = explode('|', $rowcontent['otherimage']);
} else {
    $otherimage = array();
}

$rowcontent['product_weight'] = empty($rowcontent['product_weight']) ? '' : $rowcontent['product_weight'];
$rowcontent['product_price'] = number_format($rowcontent['product_price']);
$array_files = array();
if ($pro_config['download_active']) {
    $sql = 'SELECT id, ' . NV_LANG_DATA . '_title title FROM ' . $db_config['prefix'] . '_' . $module_data . '_files WHERE status=1';
    $array_files = $nv_Cache->db($sql, 'id', $module_name);
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('rowcontent', $rowcontent);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('CURRENT', $currentpath);

if ($error != '') {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}

if ($rowcontent['status'] == 1) {
    $xtpl->parse('main.status');
} else {
    $xtpl->parse('main.status0');
}

// Other image
$items = 0;
if (!empty($otherimage)) {
    foreach ($otherimage as $otherimage_i) {
        if (!empty($otherimage_i) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $otherimage_i)) {
            $otherimage_i = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $otherimage_i;
        }
        $data_otherimage_i = array(
            'id' => $items,
            'value' => $otherimage_i
        );
        $xtpl->assign('DATAOTHERIMAGE', $data_otherimage_i);
        $xtpl->parse('main.otherimage');
        ++$items;
    }
}
$xtpl->assign('FILE_ITEMS', $items);

foreach ($global_array_shops_cat as $catid_i => $rowscat) {
    $xtitle_i = '';
    if ($rowscat['lev'] > 0) {
        for ($i = 1; $i <= $rowscat['lev']; $i++) {
            $xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
    }
    $rowscat['title'] = $xtitle_i . $rowscat['title'];
    $rowscat['selected'] = ($catid_i == $rowcontent['listcatid']) ? ' selected="selected"' : '';

    $xtpl->assign('ROWSCAT', $rowscat);
    $xtpl->parse('main.rowscat');
}

// List group
if (!empty($rowcontent['group_id'])) {
    $array_groupid_in_row = $rowcontent['group_id'];
} else {
    $array_groupid_in_row = array( );
}

$inrow = nv_base64_encode(serialize($array_groupid_in_row));
$xtpl->assign('url_load', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=getgroup&cid=' . $rowcontent['listcatid'] . '&inrow=' . $inrow);
$xtpl->assign('inrow', $inrow);
$xtpl->parse('main.listgroup');

// Time update
$xtpl->assign('publ_date', $publ_date);
$select = ''; $_gift_from_h = $_gift_to_h = '';
for ($i = 0; $i <= 23; $i++) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $phour) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    $_gift_from_h .= "<option value=\"" . $i . "\"" . (($i == $gift_from_h) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    $_gift_to_h .= "<option value=\"" . $i . "\"" . (($i == $gift_to_h) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour', $select);
$xtpl->assign('gift_from_h', $_gift_from_h);
$xtpl->assign('gift_to_h', $_gift_to_h);

$select = ""; $_gift_from_m = $_gift_to_m = '';
for ($i = 0; $i < 60; $i++) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $pmin) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    $_gift_from_m .= "<option value=\"" . $i . "\"" . (($i == $gift_from_m) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    $_gift_to_m .= "<option value=\"" . $i . "\"" . (($i == $gift_to_m) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin', $select);
$xtpl->assign('gift_from_m', $_gift_from_m);
$xtpl->assign('gift_to_m', $_gift_to_m);

// Time exp
$xtpl->assign('exp_date', $exp_date);
$select = "";
for ($i = 0; $i <= 23; $i++) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $ehour) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('ehour', $select);

$select = '';
for ($i = 0; $i < 60; $i++) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $emin) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('emin', $select);

// Allowed comm
$allowed_comm = explode(',', $rowcontent['allowed_comm']);
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('ALLOWED_COMM', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $allowed_comm) ? ' checked="checked"' : '',
        'title' => $_title
    ));
    $xtpl->parse('main.allowed_comm');
}

$rowcontent['hometext'] = htmlspecialchars(nv_editor_br2nl($rowcontent['hometext']));
if (defined('NV_EDITOR') and function_exists('nv_aleditor')) {
    $edits = nv_aleditor('hometext', '100%', '150px', $rowcontent['hometext'], 'Basic');
} else {
    $edits = "<textarea style=\"width: 100%\" name=\"hometext\" id=\"hometext\" cols=\"20\" rows=\"15\">" . $rowcontent['hometext'] . "</textarea>";
}
$xtpl->assign('edit_hometext', $edits);

$rowcontent['bodytext'] = htmlspecialchars(nv_editor_br2nl($rowcontent['bodytext']));
if (defined('NV_EDITOR') and function_exists('nv_aleditor')) {
    $edits = nv_aleditor('bodytext', '100%', '300px', $rowcontent['bodytext']);
} else {
    $edits = "<textarea style=\"width: 100%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"15\">" . $rowcontent['bodytext'] . "</textarea>";
}
$xtpl->assign('edit_bodytext', $edits);

$shtm = '';
if (count($array_block_cat_module) > 0) {
    foreach ($array_block_cat_module as $bid_i => $bid_title) {
        $ch = in_array($bid_i, $id_block_content) ? " checked=\"checked\"" : "";
        $shtm .= "<input class=\"news_checkbox\" type=\"checkbox\" name=\"bids[]\" value=\"" . $bid_i . "\"" . $ch . ">" . $bid_title . "<br />\n";
    }
    $xtpl->assign('row_block', $shtm);
    $xtpl->parse('main.block_cat');
}

if (!empty($money_config)) {
    foreach ($money_config as $code => $info) {
        $info['select'] = ($rowcontent['money_unit'] == $code) ? "selected=\"selected\"" : "";
        $xtpl->assign('MON', $info);
        $xtpl->parse('main.product_price.money_unit');
        $xtpl->parse('main.typeprice2.money_unit');
    }
}

$typeprice = ($rowcontent['listcatid']) ? $global_array_shops_cat[$rowcontent['listcatid']]['typeprice'] : 1;
if ($typeprice == 1) {
    // List discount
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts ORDER BY add_time DESC';
    $_result = $db->query($sql);
    while ($_discount = $_result->fetch()) {
        $_discount['selected'] = ($_discount['did'] == $rowcontent['discount_id']) ? "selected=\"selected\"" : "";
        $xtpl->assign('DISCOUNT', $_discount);
        $xtpl->parse('main.typeprice1.discount');
    }
    $xtpl->parse('main.typeprice1');

    $xtpl->parse('main.product_price');
} elseif ($typeprice == 2) {
    $_arr_price_config = (empty($rowcontent['price_config'])) ? array( ) : unserialize($rowcontent['price_config']);
    $i = sizeof($_arr_price_config);
    ++$i;
    $_arr_price_config[$i] = array(
        'id' => $i,
        'number_to' => ($i == 1) ? 1 : '',
        'price' => ($i == 1) ? $rowcontent['product_price'] : '',
    );

    foreach ($_arr_price_config as $price_config) {
        $price_config['price'] = ($price_config['price'] > 0) ? number_format($price_config['price'], nv_get_decimals($pro_config['money_unit'])) : '';
        $xtpl->assign('PRICE_CONFIG', $price_config);
        $xtpl->parse('main.typeprice2.loop');
    }
    $xtpl->parse('main.typeprice2');
} else {
    $xtpl->parse('main.product_price');
}

// List discount
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts';
$_result = $db->query($sql);
while ($_discount = $_result->fetch()) {
    $_discount['selected'] = ($_discount['did'] == $rowcontent['discount_id']) ? "selected=\"selected\"" : "";
    $xtpl->assign('DISCOUNT', $_discount);
    $xtpl->parse('main.discount');
}

// List pro_unit
$sql = 'SELECT id, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_units';
$result_unit = $db->query($sql);
if ($result_unit->rowCount() == 0) {
    $url_back = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=prounit';
    $contents = nv_theme_alert($lang_module['error_punit_empty_title'], $lang_module['error_punit_empty_content'], 'warning', $url_back, $lang_module['continue']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

while (list($unitid_i, $title_i) = $result_unit->fetch(3)) {
    $xtpl->assign('utitle', $title_i);
    $xtpl->assign('uid', $unitid_i);
    $uch = ($rowcontent['product_unit'] == $unitid_i) ? "selected=\"selected\"" : "";
    $xtpl->assign('uch', $uch);
    $xtpl->parse('main.rowunit');
}

// Print tags
if (!empty($rowcontent['keywords'])) {
    $keywords_array = explode(',', $rowcontent['keywords']);
    foreach ($keywords_array as $keywords) {
        $xtpl->assign('KEYWORDS', $keywords);
        $xtpl->parse('main.keywords');
    }
}

if ($pro_config['download_active']) {
    if (!empty($array_files)) {
        foreach ($array_files as $files) {
            $files['selected'] = in_array($files['id'], $rowcontent['files']) ? 'selected="selected"' : '';
            $xtpl->assign('FILES', $files);
            $xtpl->parse('main.files.loop');
        }
    }
    $xtpl->parse('main.files');
    $xtpl->parse('main.files_js');
}

if ($module_config[$module_name]['auto_tags']) {
    $xtpl->parse('main.auto_tags');
}

$archive_checked = ($rowcontent['archive']) ? " checked=\"checked\"" : "";
$xtpl->assign('archive_checked', $archive_checked);

$inhome_checked = ($rowcontent['inhome']) ? " checked=\"checked\"" : "";
$xtpl->assign('inhome_checked', $inhome_checked);

$allowed_rating_checked = ($rowcontent['allowed_rating']) ? " checked=\"checked\"" : "";
$xtpl->assign('allowed_rating_checked', $allowed_rating_checked);

$allowed_send_checked = ($rowcontent['allowed_send']) ? " checked=\"checked\"" : "";
$xtpl->assign('allowed_send_checked', $allowed_send_checked);

$allowed_print_checked = ($rowcontent['allowed_print']) ? " checked=\"checked\"" : "";
$xtpl->assign('allowed_print_checked', $allowed_print_checked);

$allowed_save_checked = ($rowcontent['allowed_save']) ? " checked=\"checked\"" : "";
$xtpl->assign('allowed_save_checked', $allowed_save_checked);

$showprice_checked = ($rowcontent['showprice']) ? " checked=\"checked\"" : "";
$xtpl->assign('ck_showprice', $showprice_checked);

if (!empty($weight_config)) {
    foreach ($weight_config as $code => $info) {
        $info['select'] = ($rowcontent['weight_unit'] == $code) ? "selected=\"selected\"" : "";
        $xtpl->assign('WEIGHT', $info);
        $xtpl->parse('main.weight_unit');
    }
}

if ($pro_config['active_gift']) {
    $xtpl->parse('main.gift');
}

if ($rowcontent['id'] > 0 and !$is_copy) {
    $op = 'items';
}

if (empty($rowcontent['alias'])) {
    $xtpl->parse('main.getalias');
}

if (!$pro_config['active_warehouse']) {
    if ($rowcontent['id'] > 0) {
        $xtpl->parse('main.warehouse.edit');
    } else {
        $xtpl->parse('main.warehouse.add');
    }
    $xtpl->parse('main.warehouse');
}

// Custom fiels
if ($pro_config['template_active'] and $rowcontent['listcatid'] and !empty($global_array_shops_cat[$rowcontent['listcatid']]['form'])) {
    $form = $global_array_shops_cat[$rowcontent['listcatid']]['form'];
    if (nv_is_file(NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $module_name . '/files_tpl/cat_form_' . $form . '.tpl', NV_ASSETS_DIR . '/' . $module_name)) {
        $datacustom_form = nv_show_custom_form($rowcontent['id'], $form, $custom);
        $xtpl->assign('DATACUSTOM_FORM', $datacustom_form);
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';