<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department (full_name, alias, phone, fax, email, address, note, others, cats, admins, act, weight, is_default) VALUES (:full_name, :alias, :phone, :fax, :email, '', :note, :others, :cats, '1/1/1/0;', 1, :weight, :is_default)");

$full_name = 'Phòng Chăm sóc khách hàng';
$alias = 'Cham-soc-khach-hang';
$phone = '(08) 38.000.000[+84838000000]';
$fax = '08 38.000.001';
$email = 'customer@mysite.com';
$note = 'Bộ phận tiếp nhận và giải quyết các yêu cầu, đề nghị, ý kiến liên quan đến hoạt động chính của doanh nghiệp';
$others = json_encode(array(
    'viber' => 'myViber',
    'skype' => 'mySkype',
    'yahoo' => 'myYahoo'
));
$cats = 'Tư vấn|Khiếu nại, phản ánh|Đề nghị hợp tác';
$weight = 1;
$is_default = 1;
$sth->bindParam(':full_name', $full_name, PDO::PARAM_STR, strlen($full_name));
$sth->bindParam(':alias', $alias, PDO::PARAM_STR, strlen($alias));
$sth->bindParam(':phone', $phone, PDO::PARAM_STR, strlen($phone));
$sth->bindParam(':fax', $fax, PDO::PARAM_STR, strlen($fax));
$sth->bindParam(':email', $email, PDO::PARAM_STR, strlen($email));
$sth->bindParam(':note', $note, PDO::PARAM_STR, strlen($note));
$sth->bindParam(':others', $others, PDO::PARAM_STR, strlen($others));
$sth->bindParam(':cats', $cats, PDO::PARAM_STR, strlen($cats));
$sth->bindValue(':weight', $weight, PDO::PARAM_INT);
$sth->bindValue(':is_default', $is_default, PDO::PARAM_INT);
$sth->execute();

$full_name = 'Phòng Kỹ thuật';
$alias = 'Ky-thuat';
$phone = '(08) 38.000.002[+84838000002]';
$fax = '08 38.000.003';
$email = 'technical@mysite.com';
$note = 'Bộ phận tiếp nhận và giải quyết các câu hỏi liên quan đến kỹ thuật';
$others = json_encode(array(
    'viber' => 'myViber2',
    'skype' => 'mySkype2',
    'yahoo' => 'myYahoo2'
));
$cats = 'Thông báo lỗi|Góp ý cải tiến';
$weight = 2;
$is_default = 0;
$sth->bindParam(':full_name', $full_name, PDO::PARAM_STR, strlen($full_name));
$sth->bindParam(':alias', $alias, PDO::PARAM_STR, strlen($alias));
$sth->bindParam(':phone', $phone, PDO::PARAM_STR, strlen($phone));
$sth->bindParam(':fax', $fax, PDO::PARAM_STR, strlen($fax));
$sth->bindParam(':email', $email, PDO::PARAM_STR, strlen($email));
$sth->bindParam(':note', $note, PDO::PARAM_STR, strlen($note));
$sth->bindParam(':others', $others, PDO::PARAM_STR, strlen($others));
$sth->bindParam(':cats', $cats, PDO::PARAM_STR, strlen($cats));
$sth->bindValue(':weight', $weight, PDO::PARAM_INT);
$sth->bindValue(':is_default', $is_default, PDO::PARAM_INT);
$sth->execute();

$bodytext = 'Để không ngừng nâng cao chất lượng dịch vụ và đáp ứng tốt hơn nữa các yêu cầu của Quý khách, chúng tôi mong muốn nhận được các thông tin phản hồi. Nếu Quý khách có bất kỳ thắc mắc hoặc đóng góp nào, xin vui lòng liên hệ với chúng tôi theo thông tin dưới đây. Chúng tôi sẽ phản hồi lại Quý khách trong thời gian sớm nhất.';
$sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value=:config_value WHERE lang='" . $lang . "' AND module='" . $module_name . "' AND config_name='bodytext'");
$sth->bindParam(':config_value', $bodytext, PDO::PARAM_STR, strlen($bodytext));
$sth->execute();
