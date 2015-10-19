<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_area VALUES
	(1, 0, 'Giao-duc-1', 'Giáo dục', '', '', 1412265295, 1),
	(2, 0, 'Phap-quy-2', 'Pháp quy', '', '', 1412265295, 2)" );

$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat VALUES
	(1, 0, 'Cong-van', 'Công văn', '', '', 5, 1412265295, 1),
	(2, 0, 'Thong-tu', 'Thông tư', '', '', 5, 1412265295, 2),
	(3, 0, 'Quyet-dinh', 'Quyết định', '', '', 5, 1412265295, 3),
	(4, 0, 'Nghi-dinh', 'Nghị định', '', '', 5, 1412265295, 4),
	(5, 0, 'Thong-bao', 'Thông báo', '', '', 5, 1412998152, 5),
	(6, 0, 'Huong-dan', 'Hướng dẫn', '', '', 5, 1412998170, 6),
	(7, 0, 'Bao-cao', 'Báo cáo', '', '', 5, 1412998182, 7),
	(8, 0, 'Chi-thi', 'Chỉ thị', '', '', 5, 1412998193, 8),
	(9, 0, 'Ke-hoach', 'Kế hoạch', '', '', 5, 1412998208, 9)" );

$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_signer VALUES
	(1, 'Phạm Vũ Luận', '', 'Bộ trưởng', 1412265295),
	(2, 'Bùi Văn Ga', '', 'Thứ trưởng', 1412265295),
	(3, 'Nguyễn Thị Nghĩa', '', 'Thứ trưởng', 1412265295),
	(4, 'Nguyễn Vinh Hiển', '', 'Thứ trưởng', 1412265295),
	(5, 'Khác', '', '', 1412265295)" );

$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_subject VALUES
	(1, 'Bo-GD-DT', 'Bộ GD&amp;ĐT', '', '', 0, 5, 1412265295, 1),
	(2, 'So-GD-DT', 'Sở GD&amp;ĐT', '', '', 0, 5, 1412265295, 2),
	(3, 'Phong-GD-DT', 'Phòng GD', '', '', 0, 5, 1412265295, 3),
	(4, 'Khac', 'Khác', '', '', 0, 5, 1412265295, 4)" );