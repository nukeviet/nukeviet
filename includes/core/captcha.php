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

mt_srand(microtime(true) * 1000000);
$maxran = 1000000;
$random_num = mt_rand(1, $maxran);

$nv_Request->set_Session('random_num', $random_num);

$datekey = date('F j');
$rcode = strtoupper(md5(NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey));
$code = substr($rcode, 2, NV_GFX_NUM);

$image = imagecreatetruecolor(NV_GFX_WIDTH, NV_GFX_HEIGHT);

// Tạo nền màu sáng ngẫu nhiên
$bg_color = imagecolorallocate($image, mt_rand(220, 255), mt_rand(220, 255), mt_rand(220, 255));
imagefilledrectangle($image, 0, 0, NV_GFX_WIDTH, NV_GFX_HEIGHT, $bg_color);

// Nhiễu nền 1: Hình elip mờ
for ($i = 0; $i < 10; $i++) {
    $r = mt_rand(180, 230);
    $g = mt_rand(180, 230);
    $b = mt_rand(180, 230);
    $color_elipse = imagecolorallocate($image, $r, $g, $b);
    $cx = mt_rand(0, NV_GFX_WIDTH);
    $cy = mt_rand(0, NV_GFX_HEIGHT);
    $rx = mt_rand(10, NV_GFX_WIDTH / 2);
    $ry = mt_rand(10, NV_GFX_HEIGHT / 2);
    imagefilledellipse($image, $cx, $cy, $rx, $ry, $color_elipse);
}

// Nhiễu nền 2: Các hạt nhiễu (Salt and Pepper Noise)
for ($i = 0; $i < 300; $i++) {
    $dot_color = imagecolorallocate($image, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200));
    imagesetpixel($image, mt_rand(0, NV_GFX_WIDTH), mt_rand(0, NV_GFX_HEIGHT), $dot_color);
}

// Vẽ từng ký tự để chúng có thể dính vào nhau và biến dạng độc lập
$len = strlen($code);
if ($len > 0) {
    // Thu hẹp khoảng cách cơ bản để ép các chữ dính vào nhau
    $char_step = (NV_GFX_WIDTH - 10) / ($len + 1);
    $x = mt_rand(5, 10); // Vị trí bắt đầu

    for ($i = 0; $i < $len; $i++) {
        $ff = mt_rand(1, 15);
        $font = NV_ROOTDIR . '/includes/fonts/captcha/font' . $ff . '.ttf';

        // Chữ màu tối để nổi bật trên nền sáng
        $text_color = imagecolorallocate($image, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

        // Góc xoay vừa phải để dễ đọc hơn
        $angle = mt_rand(-15, 15);
        // Kích thước chữ vừa phải
        $size = mt_rand(14, 18);
        // Vị trí Y ngẫu nhiên
        $y = mt_rand(22, NV_GFX_HEIGHT - 2);

        if (file_exists($font) and nv_function_exists('imagettftext')) {
            imagettftext($image, $size, $angle, $x, $y, $text_color, $font, $code[$i]);
        } else {
            imagestring($image, 5, $x, mt_rand(4, 10), $code[$i], $text_color);
        }

        // Cập nhật tọa độ X cho ký tự tiếp theo với khoảng cách vừa phải
        $x += $char_step + mt_rand(-2, 2);
    }
}

// Lớp nhiễu 3: Kẻ các đường xuyên qua chữ (Cực kỳ hiệu quả chống OCR)
// Bước này phải làm SAU khi vẽ chữ để đường line đè lên chữ.
$num_lines = mt_rand(2, 4);
for ($i = 0; $i < $num_lines; $i++) {
    imagesetthickness($image, 1);
    $line_color = imagecolorallocate($image, mt_rand(100, 150), mt_rand(100, 150), mt_rand(100, 150));
    $y1 = mt_rand(5, NV_GFX_HEIGHT - 5);
    $y2 = mt_rand(5, NV_GFX_HEIGHT - 5);
    imageline($image, 0, $y1, NV_GFX_WIDTH, $y2, $line_color);
}

// Xuất ảnh
header('Content-type: image/jpeg');
header('Cache-Control:');
header('Pragma:');
header('Set-Cookie:');
imagejpeg($image, null, 85);
version_compare(PHP_VERSION, '8.0.0', '<') && imagedestroy($image);
exit();
