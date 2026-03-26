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

$page_title = $nv_Lang->getModule('upload_manager');

/**
 * @param string $message
 */
function show_error($message)
{
    global $nv_Request;

    if ($nv_Request->isset_request('checkss', 'post')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $message
        ]);
    }

    $contents = nv_theme_alert('', $message, 'danger');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$request = [];

/*
 * Hiển thị cây thư mục tại đây, mặc định là NV_UPLOADS_DIR. Nếu là mặc định NV_UPLOADS_DIR:
 * Điều hành chung list hết thư mục con trong uploads
 * Quản lí module list các module mình có quyền quản lí
 */
$request['path_request'] = trim($nv_Request->get_string('path', 'post,get', ''), ' /');
$request['path'] = nv_check_path_upload($request['path_request']);
if ($request['path_request'] !== $request['path']) {
    show_error($nv_Lang->getModule('notallowed'));
}

// Đứng ở đây so với path bên trên
$request['currentpath_request'] = trim($nv_Request->get_string('currentpath', 'post,get', ''), ' /');
$request['currentpath'] = nv_check_path_upload($request['currentpath_request']);
if ($request['path_request'] !== $request['path']) {
    show_error($nv_Lang->getModule('notallowed'));
}

$request['type'] = $nv_Request->get_title('type', 'post,get', '');
$request['show_file'] = (int) $nv_Request->get_bool('show_file', 'post', false);
$request['show_folder'] = (int) $nv_Request->get_bool('show_folder', 'post', false);
$request['area'] = htmlspecialchars(trim($nv_Request->get_string('area', 'post,get')), ENT_QUOTES);
$request['alt'] = htmlspecialchars(trim($nv_Request->get_string('alt', 'post,get')), ENT_QUOTES);

$request['q'] = nv_string_to_filename(htmlspecialchars(trim($nv_Request->get_string('q', 'post')), ENT_QUOTES));
$request['page'] = $nv_Request->get_absint('page', 'post', 1);
if ($request['page'] > 9999 or $request['page'] < 1) {
    $request['page'] = 1;
}
$request['order'] = $nv_Request->get_int('order', 'post', 0);
$request['author'] = $nv_Request->get_int('author', 'post', 0);

if ($request['type'] != 'image') {
    $request['type'] = 'file';
}

$request['popup'] = (int) $nv_Request->get_bool('popup', 'post,get', false);
$request['CKEditorFuncNum'] = $nv_Request->get_int('CKEditorFuncNum', 'post,get', 0);
$request['editor_id'] = $nv_Request->get_title('editor_id', 'post,get', '');

/*
 * Kiểm tra tệp được chọn có thuộc thư mục quản lí không nếu có lấy nó, không thì bỏ ra
 * Từ cái này xác định currentpath và ưu tiên hơn currentpath
 * Áp dụng khi chọn tệp vào 1 ô sau đó chọn tiếp lần sau thì tự lấy và select tệp cũ
 */
$selectfile = '';
$currentfile = $nv_Request->get_string('currentfile', 'get,post', '');
if (!empty($currentfile)) {
    $selectfile = nv_string_to_filename(pathinfo($currentfile, PATHINFO_BASENAME));
    $currentfilepath = nv_check_path_upload(pathinfo($currentfile, PATHINFO_DIRNAME));
    if (!empty($currentfilepath) and !empty($selectfile) and !empty(nv_check_allow_upload_dir($currentfilepath))) {
        $request['currentpath'] = $currentfilepath;
    } else {
        $selectfile = '';
    }
}
$request['currentfile'] = $selectfile;

/*
 * Khi chỉ ra path hoặc current path mà không thuộc quyền quản lý sẽ chặn thao tác
 * thay vì trỏ về thư mục gốc. Nhằm mục đích nếu lập trình nút duyệt file ở module trỏ về sai thư mục
 * thì không cho upload ở thư mục khác, như thế khi lưu hoặc xử lý sẽ sai lệch tính độc lập của module
 */
if ((!empty($request['path']) and $request['path'] != NV_UPLOADS_DIR and empty(nv_check_allow_upload_dir($request['path']))) or (!empty($request['currentpath']) and $request['currentpath'] != NV_UPLOADS_DIR and empty(nv_check_allow_upload_dir($request['currentpath'])))) {
    show_error($nv_Lang->getModule('notallowed'));
}

// Khi không chỉ ra thì path mặc định là thư mục uploads. Trường hợp truy cập vào module quản lý file sẽ ra cái này
if (empty($request['path'])) {
    $request['path'] = NV_UPLOADS_DIR;
}
if (empty($request['currentpath'])) {
    $request['currentpath'] = $request['path'];
}
// Kiểm tra currentpath phải bằng path hoặc nằm trong path
if ($request['currentpath'] != $request['path'] and !preg_match('/^' . nv_preg_quote($request['path'] . '/') . '/', $request['currentpath'])) {
    $request['currentpath'] = $request['path'];
}

// Kiểm tra lại currentfile có nằm trong currentpath hay không
if (!empty($request['currentfile']) and !preg_match('/^' . nv_preg_quote($request['currentpath'] . '/' . $request['currentfile']) . '$/', $currentfile)) {
    $request['currentfile'] = '';
}

/**
 * Đệ quy cây thư mục
 *
 * @param string $dir
 * @param array $array_folders
 * @return array
 */
function viewdirtree($dir, $array_folders)
{
    global $array_dirname, $request;

    if (empty($dir)) {
        return [];
    }

    $tree = [];
    $_dirlist = preg_grep('/^(' . nv_preg_quote($dir) . ')\/([^\/]+)$/', array_keys($array_dirname));

    foreach ($_dirlist as $_dir) {
        $check_allowed = nv_check_allow_upload_dir($_dir);
        if (empty($check_allowed)) {
            continue;
        }

        $tree[] = [
            'uuid' => uniqid(),
            'title' => basename($_dir),
            'path' => $_dir,
            'fetch_path' => $_dir,
            'allowed' => $check_allowed,
            'size' => empty($array_folders[$_dir]) ? 0 : nv_convertfromBytes($array_folders[$_dir]),
            'sub' => viewdirtree($_dir, $array_folders),
            'open' => ($_dir == $request['currentpath'] or str_contains($request['currentpath'], $_dir . '/')),
            'active' => $_dir == $request['currentpath']
        ];
    }

    return $tree;
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('uploadconfig.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('REQUEST', $request);
$tpl->assign('CHECKSS', csrf_create($_csrf_key));

// Xử lý yêu cầu qua ajax
if ($nv_Request->isset_request('checkss', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $respon = [
        'status' => 'success',
        'folders' => '',
        'files' => '',
        'pagination' => '',
        'view' => null
    ];

    // Lấy thư mục
    if ($request['show_folder']) {
        $array_folders = [];
        if (!empty($global_config['show_folder_size'])) {
            $stmt = $db->prepare('SELECT dirname, total_size FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname = :dirname OR dirname LIKE :dirnamelike');
            $stmt->bindValue(':dirname', $request['path'], PDO::PARAM_STR);
            $stmt->bindValue(':dirnamelike', $request['path'] . '/%', PDO::PARAM_STR);
            $stmt->execute();
            while ($_row_folder = $stmt->fetch()) {
                $array_folders[$_row_folder['dirname']] = $_row_folder['total_size'];
            }
            $stmt->closeCursor();
        }

        $allowed = nv_check_allow_upload_dir($request['path']);
        $trees = [[
            'uuid' => uniqid(),
            'title' => basename($request['path']),
            'path' => $request['path'],
            'fetch_path' => ($request['path'] == NV_UPLOADS_DIR and !defined('NV_IS_SPADMIN')) ? '' : $request['path'],
            'allowed' => $allowed,
            'size' => (empty($array_folders[$request['path']]) or empty($allowed)) ? 0 : nv_convertfromBytes($array_folders[$request['path']]),
            'sub' => viewdirtree($request['path'], $array_folders),
            'open' => true,
            'active' => $request['path'] == $request['currentpath']
        ]];

        $tpl->assign('TREES', $trees);
        $respon['folders'] = $tpl->fetch('foldlist.tpl');
    }

    // Lấy tệp tin
    if ($request['show_file']) {
        $allowed = nv_check_allow_upload_dir($request['currentpath']);
        if (!empty($allowed['view_dir']) and isset($array_dirname[$request['currentpath']])) {
            if ($refresh) {
                if ($sys_info['allowed_set_time_limit']) {
                    set_time_limit(0);
                }
                nv_filesListRefresh($request['currentpath']);
            }

            $sql_count = 'SELECT COUNT(tb1.name) FROM ' . NV_UPLOAD_GLOBALTABLE . '_file tb1';
            $sql_select = 'SELECT ';

            $join = '';
            $where = [];
            $params = [];

            if (!empty($request['q'])) {
                $join = ' INNER JOIN ' . NV_UPLOAD_GLOBALTABLE . '_dir tb2 ON tb1.did=tb2.did';
                $select = 'tb1.*, tb2.dirname';

                $where[] = '(tb1.title LIKE :q_title OR tb1.alt LIKE :q_alt)';
                $params[':q_title'] = '%' . $request['q'] . '%';
                $params[':q_alt'] = '%' . $request['q'] . '%';

                $where[] = '(tb2.dirname = :dirname OR tb2.dirname LIKE :dirnamelike)';
                $params[':dirname'] = $request['currentpath'];
                $params[':dirnamelike'] = $request['currentpath'] . '/%';
            } else {
                $select = 'tb1.*';
                $where[] = 'tb1.did = :did';
                $params[':did'] = (int) $array_dirname[$request['currentpath']];
            }

            if ($request['type'] != 'file') {
                $where[] = 'tb1.type = :type';
                $params[':type'] = $request['type'];
            }
            if ($request['author'] == 1) {
                $where[] = 'tb1.userid = :userid';
                $params[':userid'] = $admin_info['admin_id'];
            }

            $where_str = '';
            if (!empty($where)) {
                $where_str = ' WHERE ' . implode(' AND ', $where);
            }

            $sql_count .= $join . $where_str;
            
            $stmt_count = $db->prepare($sql_count);
            foreach ($params as $key => $value) {
                $stmt_count->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt_count->execute();
            $num_items = $stmt_count->fetchColumn();

            $per_page = 60;

            $sql_query = $sql_select . $select . ' FROM ' . NV_UPLOAD_GLOBALTABLE . '_file tb1' . $join . $where_str;
            
            if ($request['order'] == 1) {
                $sql_query .= ' ORDER BY tb1.mtime ASC';
            } elseif ($request['order'] == 2) {
                $sql_query .= ' ORDER BY tb1.title ASC';
            } else {
                $sql_query .= ' ORDER BY tb1.mtime DESC';
            }

            $sql_query .= ' LIMIT :limit OFFSET :offset';

            $stmt = $db->prepare($sql_query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($request['page'] - 1) * $per_page, PDO::PARAM_INT);
            $stmt->execute();

            $files = [];
            $num_file = $num_images = 0;
            while ($_row_file = $stmt->fetch()) {
                $file = [];
                $file['src'] = NV_BASE_SITEURL . $_row_file['src'] . '?' . $_row_file['mtime'];
                $file['thumb'] = NV_BASE_SITEURL . $_row_file['src'];
                $file['alt'] = $_row_file['alt'];
                $file['name'] = $_row_file['name'];
                $file['real_name'] = $_row_file['title'];
                $file['ext'] = $_row_file['ext'];
                $file['uuid'] = uniqid();
                $file['path'] = NV_BASE_SITEURL . (empty($_row_file['dirname']) ? $request['currentpath'] : $_row_file['dirname']) . '/' . $_row_file['title'];
                $file['abs_path'] = NV_MY_DOMAIN . $file['path'];
                $file['nocache_path'] = $file['path'] . '?' . $_row_file['mtime'];
                $file['dir_path'] = (empty($_row_file['dirname']) ? $request['currentpath'] : $_row_file['dirname']) . '/' . $_row_file['title'];
                $file['dir'] = (empty($_row_file['dirname']) ? $request['currentpath'] : $_row_file['dirname']);
                $file['mtime'] = nv_datetime_format($_row_file['mtime'], 0, 0);
                $file['type'] = $_row_file['type'];
                $file['filesize_show'] = nv_convertfromBytes($_row_file['filesize']);

                $sizes = explode('|', $_row_file['sizes']);
                $file['width'] = 0;
                $file['height'] = 0;
                if (!empty($sizes[1])) {
                    $file['width'] = intval($sizes[0]);
                    $file['height'] = intval($sizes[1]);
                }

                if ($_row_file['type'] == 'image' or $_row_file['ext'] == 'swf') {
                    $num_images++;
                    $file['size'] = str_replace('|', ' x ', $_row_file['sizes']) . ' px';
                    $file['size_detail'] = $file['size'] . ' (' . $file['filesize_show'] . ')';
                } else {
                    $num_file++;
                    $file['size'] = $file['size_detail'] = $file['filesize_show'];
                }

                $files[] = $file;
            }
            $stmt->closeCursor();

            $tpl->assign('FILES', $files);
            $respon['files'] = $tpl->fetch('listfile.tpl');
            $respon['pagination'] = nv_generate_page('#page', $num_items, $per_page, $request['page']);
            $respon['view'] = $num_file > ($num_images * 2) ? 'list' : 'grid';
        }
    }

    nv_jsonOutput($respon);
}

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, !$request['popup']);
include NV_ROOTDIR . '/includes/footer.php';
