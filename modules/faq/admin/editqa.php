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
		$id = $nv_Request->get_int('id', 'get', 0);
		$userid = $nv_Request->get_int('userid', 'get', 0);
		$email = $nv_Request->get_string('email', 'get', '');
        if ($id) {
            $query = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE id=" . $id;
            $result = $db->query($query);
            //define('IS_EDIT', true);
            $page_title = $lang_module['faq_editfaq'];
            $row = $result->fetch();
        }

		$listcats = array();
	    $listcats[0] = array(
	        'id' => 0, //
	        'name' => $lang_module['nocat'], //
	        'selected' => $row['catid'] == 0 ? " selected=\"selected\"" : "" //
	    );
	    $listcats = $listcats + nv_listcats($row['catid']);
	    if (empty($listcats)) {
	        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1");
	        exit();
	    }
		$array = array();
	    $is_error = false;
	    $error = "";
		if (($nv_Request->isset_request('accept', 'post')) or ($nv_Request->isset_request('save', 'post'))) {
        $array['catid'] = $nv_Request->get_int('catid', 'post', 0);
        $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $array['question'] = $nv_Request->get_textarea('question', '', NV_ALLOWED_HTML_TAGS);
        $array['answer'] = $nv_Request->get_editor('answer', '', NV_ALLOWED_HTML_TAGS);
		$array['hot_post'] = $nv_Request->get_int('hot_post', 'post', 0);

        $alias = change_alias($array['title']);

       	$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias=" . $db->quote($alias);
        $result = $db->query($sql);
        $is_exists = $result->fetchColumn();

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
        } else {
            $array['question'] = nv_nl2br($array['question'], "<br />");
            $array['answer'] = nv_editor_nl2br($array['answer']);

		if ($nv_Request->isset_request('accept', 'post')) {
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
                 " . $userid . ",
                 " . NV_CURRENTTIME . ")";
                if (! $db->insert_id($sql)) {
                    $is_error = true;
                    $error = $lang_module['faq_error_notResult2'];
                } else {
                $sql='SELECT id FROM '. NV_PREFIXLANG . "_" . $module_data .' ORDER BY `id` DESC LIMIT 1';
				$result=$db->query($sql)->fetch();
				$link=NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
                if($module_setting['type_main'] == 0 and !empty($listcats[$array['catid']]['alias'])) {
                	$link.="&". NV_OP_VARIABLE ."=". $listcats[$row['catid']]['alias'];
                }
				$link= nv_url_rewrite($link, true);
				$link=NV_MAIN_DOMAIN.$link.'#faq'.$result['id'];
				nv_sendmail( array(
				$lang_module['email_titile'],
				$global_config['smtp_username']
			), $email, $lang_module['email_titile_accept'], "<strong>" . sprintf( $lang_module['email_body_accept'], NV_SERVER_NAME, $link ) . "</strong>" );
                	$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE id=" . $id;
    				$db->query($sql);
                    nv_update_keywords($array['catid']);
                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                    exit();
                }
			}
		elseif ($nv_Request->isset_request('save', 'post')) {
				$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_tmp SET
                catid=" . $array['catid'] . ",
                title=" . $db->quote($array['title']) . ",
                question=" . $db->quote($array['question']) . ",
                answer=" . $db->quote($array['answer']) . "
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

                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name.'&'.  NV_OP_VARIABLE .'=acceptqa');
                    exit();
                }
			}

        }
    }

		$array['catid'] = ( int )$row['catid'];
        $array['title'] = $row['title'];
        $array['answer'] = nv_editor_br2nl($row['answer']);
        $array['question'] = nv_br2nl($row['question']);



	    if (defined('NV_EDITOR')) {
	        require_once(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
	    }

	    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
	        $array['answer'] = nv_aleditor('answer', '100%', '300px', $array['answer']);
	    } else {
	        $array['answer'] = "<textarea style=\"width:100%; height:300px\" name=\"answer\" id=\"answer\">" . $array['answer'] . "</textarea>";
	    }

	    $xtpl = new XTemplate("edit_qa.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);

	    $xtpl->assign('LANG', $lang_module);
	    $xtpl->assign('DATA', $array);
		if(!empty($array['hot_post'])) {
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