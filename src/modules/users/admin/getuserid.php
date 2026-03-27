<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$area = $nv_Request->get_title('area', 'get', '');
$return = $nv_Request->get_title('return', 'get,post', '');
if (empty($area)) {
    nv_error404();
}

$access_viewlist = empty($access_admin['access_viewlist'][$admin_info['level']]) ? false : true;

$page_title = $nv_Lang->getModule('pagetitle');
$filtersql = $nv_Request->get_string('filtersql', 'get', '');

$nv_Lang->setModule('fullname', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));

if ($nv_Request->isset_request('save', 'get')) {
    $array_user = [];

    $orderid = $nv_Request->get_title('orderid', 'get', '');
    $orderusername = $nv_Request->get_title('orderusername', 'get', '');
    $orderemail = $nv_Request->get_title('orderemail', 'get', '');
    $orderregdate = $nv_Request->get_title('orderregdate', 'get', '');

    if ($orderid != 'DESC' and $orderid != '') {
        $orderid = 'ASC';
    }
    if ($orderusername != 'DESC' and $orderusername != '') {
        $orderusername = 'ASC';
    }
    if ($orderemail != 'DESC' and $orderemail != '') {
        $orderemail = 'ASC';
    }
    if ($orderregdate != 'DESC' and $orderregdate != '') {
        $orderregdate = 'ASC';
    }

    $array = [];
    $array['username'] = $nv_Request->get_title('username', 'get', '');
    $array['full_name'] = $nv_Request->get_title('full_name', 'get', '');
    $array['email'] = $nv_Request->get_title('email', 'get', '');
    $array['sig'] = $nv_Request->get_title('sig', 'get', '');
    $array['regdatefrom'] = nv_d2u_get($nv_Request->get_title('regdatefrom', 'get', ''));
    $array['regdateto'] = nv_d2u_get($nv_Request->get_title('regdateto', 'get', ''), 23, 59, 59);
    $array['last_loginfrom'] = nv_d2u_get($nv_Request->get_title('last_loginfrom', 'get', ''));
    $array['last_loginto'] = nv_d2u_get($nv_Request->get_title('last_loginto', 'get', ''), 23, 59, 59);
    $array['last_ip'] = $nv_Request->get_title('last_ip', 'get', '');
    $array['gender'] = $nv_Request->get_title('gender', 'get', '');

    $array_where = [];
    $params = [];
    if ($global_config['idsite'] > 0) {
        $array_where[] = '(idsite = :idsite OR userid = :admin_id)';
        $params[':idsite'] = $global_config['idsite'];
        $params[':admin_id'] = $admin_info['admin_id'];
    }

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&amp;area=' . $area . '&amp;return=' . $return . '&amp;save=1';

    if (!empty($array['username'])) {
        $base_url .= '&amp;username=' . rawurlencode($array['username']);
        $array_where[] = '( username LIKE :username )';
        $params[':username'] = '%' . $array['username'] . '%';
    }

    if (!empty($array['full_name'])) {
        $base_url .= '&amp;full_name=' . rawurlencode($array['full_name']);
        $where_fullname = $global_config['name_show'] == 0 ? 'concat(last_name,\' \',first_name)' : 'concat(first_name,\' \',last_name)';
        $array_where[] = '(' . $where_fullname . ' LIKE :full_name )';
        $params[':full_name'] = '%' . $array['full_name'] . '%';
    }

    if (!empty($array['email'])) {
        $base_url .= '&amp;email=' . rawurlencode($array['email']);
        $array_where[] = '( email LIKE :email )';
        $params[':email'] = '%' . $array['email'] . '%';
    }

    if (!empty($array['sig'])) {
        $base_url .= '&amp;sig=' . rawurlencode($array['sig']);
        $array_where[] = '( sig LIKE :sig )';
        $params[':sig'] = '%' . $array['sig'] . '%';
    }

    if (!empty($array['last_ip'])) {
        $base_url .= '&amp;last_ip=' . rawurlencode($array['last_ip']);
        $array_where[] = '( last_ip LIKE :last_ip )';
        $params[':last_ip'] = '%' . $array['last_ip'] . '%';
    }

    if (!empty($array['gender'])) {
        $base_url .= '&amp;gender=' . rawurlencode($array['gender']);
        $array_where[] = '( gender = :gender )';
        $params[':gender'] = $array['gender'];
    }

    if (!empty($array['regdatefrom'])) {
        $base_url .= '&amp;regdatefrom=' . rawurlencode(nv_u2d_get($array['regdatefrom']));
        $array_where[] = '( regdate >= :regdatefrom )';
        $params[':regdatefrom'] = $array['regdatefrom'];
    }

    if (!empty($array['regdateto'])) {
        $base_url .= '&amp;regdateto=' . rawurlencode(nv_u2d_get($array['regdateto']));
        $array_where[] = '( regdate <= :regdateto )';
        $params[':regdateto'] = $array['regdateto'];
    }

    if (!empty($array['last_loginfrom'])) {
        $base_url .= '&amp;last_loginfrom=' . rawurlencode(nv_u2d_get($array['last_loginfrom']));
        $array_where[] = '( last_login >= :last_loginfrom )';
        $params[':last_loginfrom'] = $array['last_loginfrom'];
    }

    if (!empty($array['last_loginto'])) {
        $base_url .= '&amp;last_loginto=' . rawurlencode(nv_u2d_get($array['last_loginto']));
        $array_where[] = '( last_login <= :last_loginto )';
        $params[':last_loginto'] = $array['last_loginto'];
    }

    if (!empty($filtersql)) {
        $data_str = $crypt->decrypt($filtersql, NV_CHECK_SESSION);
        if (!empty($data_str)) {
            // This part is assumed to be safe or handled externally, as it's a decrypted string
            // If it contains user input, it should also be parameterized.
            $array_where[] = $data_str;
        }
    }

    // Order data
    $order_id = [
        'url' => ($orderid == 'ASC') ? $base_url . '&amp;orderid=DESC' : $base_url . '&amp;orderid=ASC',
        'class' => ($orderid == '') ? 'nooder' : strtolower($orderid)
    ];

    $order_username = [
        'url' => ($orderusername == 'ASC') ? $base_url . '&amp;orderusername=DESC' : $base_url . '&amp;orderusername=ASC',
        'class' => ($orderusername == '') ? 'nooder' : strtolower($orderusername)
    ];

    $order_email = [
        'url' => ($orderemail == 'ASC') ? $base_url . '&amp;orderemail=DESC' : $base_url . '&amp;orderemail=ASC',
        'class' => ($orderemail == '') ? 'nooder' : strtolower($orderemail)
    ];

    $order_regdate = [
        'url' => ($orderregdate == 'ASC') ? $base_url . '&amp;orderregdate=DESC' : $base_url . '&amp;orderregdate=ASC',
        'class' => ($orderregdate == '') ? 'nooder' : strtolower($orderregdate)
    ];

    // SQL data
    $order_by = '';
    if (!empty($orderid)) {
        $base_url .= '&amp;orderid=' . $orderid;
        $order_by = 'userid ' . $orderid;
    } elseif (!empty($orderusername)) {
        $base_url .= '&amp;orderusername=' . $orderusername;
        $order_by = 'username ' . $orderusername;
    } elseif (!empty($orderemail)) {
        $base_url .= '&amp;orderemail=' . $orderemail;
        $order_by = 'email ' . $orderemail;
    } elseif (!empty($orderregdate)) {
        $base_url .= '&amp;orderregdate=' . $orderregdate;
        $order_by = 'regdate ' . $orderregdate;
    }

    $page = $nv_Request->get_page('page', 'get', 1);
    $per_page = 10;

    $where_sql = !empty($array_where) ? ' WHERE ' . implode(' AND ', $array_where) : '';

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . $where_sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    $num_items = $stmt->fetchColumn();

    $select_return = 'userid, username, email, regdate';
    $_array_f_return = explode(',', $select_return);
    $_array_f_return = array_map('trim', $_array_f_return);
    $return = (in_array($return, $_array_f_return, true)) ? $return : 'userid';

    $order_clause = !empty($order_by) ? ' ORDER BY ' . $order_by : '';

    if ($access_viewlist) {
        $limit_clause = ' LIMIT :limit OFFSET :offset';
    } else {
        $limit_clause = ' LIMIT 5';
    }

    $stmt = $db->prepare('SELECT ' . $select_return . ' FROM ' . NV_MOD_TABLE . $where_sql . $order_clause . $limit_clause);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    if ($access_viewlist) {
        $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
    }
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $row['regdate'] = nv_datetime_format($row['regdate']);
        $row['return'] = $row[$return];
        $array_user[] = $row;
    }
    $stmt->closeCursor();

    $pagination = '';
    if ($access_viewlist) {
        $pagination = nv_generate_page($base_url, $num_items, $per_page, $page);
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('getuserid_result.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('AREA', $area);
    $tpl->assign('ACCESS_VIEWLIST', $access_viewlist);
    $tpl->assign('ROWS', $array_user);
    $tpl->assign('ORDER_ID', $order_id);
    $tpl->assign('ORDER_USERNAME', $order_username);
    $tpl->assign('ORDER_EMAIL', $order_email);
    $tpl->assign('ORDER_REGDATE', $order_regdate);
    $tpl->assign('PAGINATION', $pagination);

    $contents = $tpl->fetch('getuserid_result.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('getuserid.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('AREA', $area);
    $tpl->assign('RETURN', $return);
    $tpl->assign('FILTERSQL', $filtersql);
    $tpl->assign('GCONFIG', $global_config);

    $contents = $tpl->fetch('getuserid.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, 0);
    include NV_ROOTDIR . '/includes/footer.php';
}
