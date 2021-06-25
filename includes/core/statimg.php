<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if ($global_config['online_upd']) {
    $online = $db->query('SELECT COUNT(*) FROM ' . NV_SESSIONS_GLOBALTABLE . ' WHERE onl_time >= ' . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME))->fetchColumn();
    $online = str_pad($online, 3, '0', STR_PAD_LEFT);
} else {
    $online = 'Hits';
}

$hits = $db->query('SELECT c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'total' AND c_val= 'hits'")->fetchColumn();

$hits = str_pad($hits, 8, '0', STR_PAD_LEFT);

$image = imagecreatefrompng(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/images/banner88x31.png');
$text_color1 = imagecolorallocate($image, 50, 50, 50);
$text_color2 = imagecolorallocate($image, 255, 255, 255);
$font = NV_ROOTDIR . '/includes/fonts/visitor2.ttf';
$font_size = 10;
$y_value1 = 12;
$x_value1 = 25;
$y_value2 = 26;
$x_value2 = 5;

imagettftext($image, $font_size, 0, $x_value1, $y_value1, $text_color1, $font, $online);
imagettftext($image, $font_size, 0, $x_value2, $y_value2, $text_color2, $font, $hits);
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
exit();
