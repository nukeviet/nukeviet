<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */
// === XSS output ===
// Dữ liệu đã qua get_title() khi nhập → đã escape HTML → KHÔNG cần escape lại
// ✅ Đúng: echo $row['title'];
// ❌ Sai (double-encode): echo nv_htmlspecialchars($row['title']);

// Dữ liệu từ get_string(), get_editor(), get_textarea() hoặc raw từ DB → CẦN escape khi echo
// ❌ Sai: echo $row['description'];
// ✅ Đúng:
echo nv_htmlspecialchars($row['description']);

// === Kiểm tra file — dùng nv_is_file ===
// ❌ Sai: is_file(NV_DOCUMENT_ROOT . $path_from_user);
// ✅ Đúng:
nv_is_file($path_from_user, $uploads_dir_user);

// === Open Redirect — không dùng selfurl trực tiếp ===
// ❌ Sai: nv_redirect_location($client_info['selfurl']);
// ✅ Đúng:
nv_redirect_location($page_url);
// Nếu hiển thị html: nv_htmlspecialchars($client_info['selfurl'])

// === Upload file ===
// ❌ Sai: Xử lý $_FILES thủ công
// ✅ Đúng — Dùng class Upload của NukeViet
require_once NV_ROOTDIR . '/includes/class/upload.class.php';
$upload = new \NukeViet\Files\Upload($allow_exts, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_UPLOADS_DIR, $nv_is_admin);
// $upload_info = $upload->save_file($_FILES['f'], $upload_dir, $replace_if_exists);

// === Phân quyền admin ===
// ❌ Chưa đủ an toàn: if (!defined('NV_IS_ADMIN')) { exit('Stop!!!'); }
// ✅ Đúng — File admin: if (!defined('NV_IS_FILE_ADMIN')) { exit('Stop!!!'); }
if (defined('NV_IS_SPADMIN')) {
    // Chỉ super admin mới được làm ...
}

// === unserialize() — Nguy cơ Object Injection ===
// ❌ Sai: $data = unserialize($row['others']);
// ✅ Đúng — Chặn instantiate class bất kỳ:
$data = unserialize($row['others'], ['allowed_classes' => false]);
// ✅ Tốt nhất — Migrate sang JSON:
$data = json_decode($row['others'], true);
