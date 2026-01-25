<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/src/functions.php';
require NV_ROOTDIR . '/src/geoidInfo.php';

set_time_limit(0);
ini_set('memory_limit', '-1');

/*
 * Bắt đầu tool
 */
if (is_dir(NV_ROOTDIR . '/release/ip')) {
    echo "Old data exists, please remove release/ip and try again\n";
    exit(1);
}

// Tạo các thư mục nếu chưa có
if (!is_dir(NV_ROOTDIR . '/release')) {
    mkdir(NV_ROOTDIR . '/release');
}
if (!is_dir(NV_ROOTDIR . '/release/ip')) {
    mkdir(NV_ROOTDIR . '/release/ip');
}

$inputFileName = NV_ROOTDIR . '/libs/GeoLite2-Country-Blocks-IPv4.csv';
if (!file_exists($inputFileName)) {
    echo "File not exists: libs/GeoLite2-Country-Blocks-IPv4.csv\n";
    exit(1);
}

echo "Begin read GeoLite2:\n";

$array = [];
$f = fopen($inputFileName, 'r');
if ($f === false) {
    echo "Cannot open the file: libs/GeoLite2-Country-Blocks-IPv4.csv\n";
    exit(1);
}
$stt = $maxRow = $ignoreRows = 0;
while (($row = fgetcsv($f)) !== false) {
    if ($stt ++ == 0) {
        // Bỏ qua cột tiêu đề
        continue;
    }
    $ip_range = $row[0] ?? '';
    $geo_id = $row[1] ?? '';
    if (empty($ip_range) or empty($geo_id) or !isset($array_geo_info[$geo_id])) {
        $ignoreRows++;
        continue;
    }
    list($range, $netmask) = explode('/', $ip_range, 2);
    if (empty($netmask)) {
        echo "IP range invalid on line " . $stt . "\n";
        exit(1);
    }

    $maxRow++;
    $array[] = [
        'range' => $range,
        'netmask' => $netmask,
        'geoname_id' => $geo_id
    ];
}
fclose($f);

if ($maxRow < 1) {
    echo "No data\n";
    exit(2);
}

echo "Total rows: " . $maxRow . "\n";
echo "Ignore rows: " . $ignoreRows . "\n";
echo "SUM rows in CSV: " . ($ignoreRows + $maxRow + 1) . "\n\n";

echo "Begin processing the data:\n";

$loaded_file = [];
$loaded_file_reverse = [];

$keyloop = 0;
$keyecho = 0;
foreach ($array as $index => $row) {
    ++$keyloop;
    if ($keyloop % 1000 == 0) {
        ++$keyecho;
        echo 'Current ' . ($index + 1) . '/' . $maxRow . " (" . round((($index + 1) / $maxRow * 100), 2) . "%)\n";
    }

    $x = explode('.', $row['range']);
    while (count($x) < 4) {
        $x[] = '0';
    }
    list($a, $b, $c, $d) = $x;

    // Xác định ip bắt đầu và kết thúc
    $ip_start = ($a * 16777216) + ($b * 65536) + ($c * 256) + ($d);
    $ip_end = $ip_start + pow(2, 32 - (int) $row['netmask']) - 1;

    if (isset($loaded_file[$a])) {
        // Xác định dải IP này đã xử lý rồi, xử lý tiếp
        $ranges = $loaded_file[$a];
        $ranges_reverse = $loaded_file_reverse[$a];
    } else {
        // Dải IP này chưa từng xử lý, xử lý mới
        $ranges = [];
        $ranges_reverse = [];
    }

    $ip_sbefore = $ip_start - 1;

    // Nếu tồn tại dải IP liền kề trước đó cùng quốc gia thì gộp vào làm 1 dải liền nhau
    if (isset($ranges_reverse[$ip_sbefore]) and $ranges_reverse[$ip_sbefore][1] == $array_geo_info[$row['geoname_id']]) {
        $ip_start = $ranges_reverse[$ip_sbefore][0];
        unset($ranges_reverse[$ip_sbefore]);
    }

    $ranges[$ip_start] = [$ip_end, $array_geo_info[$row['geoname_id']]];

    // Đảo ngược lại điểm kết thúc và bắt đầu của dải này để kiểm tra ngược
    $ranges_reverse[$ip_end] = [$ip_start, $array_geo_info[$row['geoname_id']]];

    $loaded_file[$a] = $ranges;
    $loaded_file_reverse[$a] = $ranges_reverse;
}

// Dải IP tăng từ 0 đến 255 nên lặp 0 đến 255 thêm vào cho đủ
for ($i = 0; $i < 256; $i++) {
    if (!isset($loaded_file[$i])) {
        $loaded_file[$i] = [];
    }
}

echo "\nBegin write file:\n";
foreach ($loaded_file as $fname => $fdata) {
    echo "release/ip/" . $fname . ".php\n";
    $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges=" . nv_print_variable_ip($fdata) . ";\n";
    file_put_contents(NV_ROOTDIR . '/release/ip/' . $fname . '.php', $file_content, LOCK_EX);
}

echo "\nBegin test file:\n";
for ($i = 0; $i < 256; $i++) {
    echo "release/ip/" . $i . ".php\n";
    require NV_ROOTDIR . '/release/ip/' . $i . '.php';
}

echo "Finish!\n";
