<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (! defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " VALUES (:vid, :question, '', 1, 0, 1, '6', 1275318563, 0, 1)");
$sth->bindValue(':vid', 2, PDO::PARAM_INT);
$sth->bindValue(':question', 'Bạn biết gì về NukeViet 4?', PDO::PARAM_STR);
$sth->execute();

$sth->bindValue(':vid', 3, PDO::PARAM_INT);
$sth->bindValue(':question', 'Lợi ích của phần mềm nguồn mở là gì?', PDO::PARAM_STR);
$sth->execute();

$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows VALUES (?, ?, ?, ?, ?)");
$sth->execute(array(5, 2, 'Một bộ sourcecode cho web hoàn toàn mới.', '', 0));
$sth->execute(array(6, 2, 'Mã nguồn mở, sử dụng miễn phí.', '', 0));
$sth->execute(array(7, 2, 'Sử dụng HTML5, CSS3 và hỗ trợ Ajax', '', 0));
$sth->execute(array(8, 2, 'Tất cả các ý kiến trên', '', 0));
$sth->execute(array(9, 3, 'Liên tục được cải tiến, sửa đổi bởi cả thế giới.', '', 0));
$sth->execute(array(10, 3, 'Được sử dụng miễn phí không mất tiền.', '', 0));
$sth->execute(array(11, 3, 'Được tự do khám phá, sửa đổi theo ý thích.', '', 0));
$sth->execute(array(12, 3, 'Phù hợp để học tập, nghiên cứu vì được tự do sửa đổi theo ý thích.', '', 0));
$sth->execute(array(13, 3, 'Tất cả các ý kiến trên', '', 0));