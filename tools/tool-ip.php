<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if (isset($_GET['response_headers_detect'])) {
    exit(0);
}

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

require NV_ROOTDIR . '/src/functions.php';
require NV_ROOTDIR . '/src/Large-CSVReader.php';
require NV_ROOTDIR . '/src/geoidInfo.php';

if ($sys_info['ini_set_support']) {
    set_time_limit(0);
    ini_set('memory_limit', '-1');
}

/**
 * Bắt đầu tool
 */

// Tạo các thư mục nếu chưa có
if (!is_dir(NV_ROOTDIR . '/release')) {
    nv_mkdir(NV_ROOTDIR, 'release');
}
if (!is_dir(NV_ROOTDIR . '/release/ip')) {
    nv_mkdir(NV_ROOTDIR . '/release', 'ip');
}
if (!is_dir(NV_ROOTDIR . '/release/ip6')) {
    nv_mkdir(NV_ROOTDIR . '/release', 'ip6');
}

$inputFileType = 'Csv';
$inputFileName = NV_ROOTDIR . '/libs/ip/GeoLite2-Country-Blocks-IPv4.csv';

// Số row mỗi chunk
$chunkSize = 10000;

try {
    $chunkFilter = new NukeViet\Files\ChunkReadFilter();

    $reader = IOFactory::createReader($inputFileType);
    $reader->setReadFilter($chunkFilter)->setContiguous(true);
    $spreadsheet = new Spreadsheet();

    $offsetRow = $nv_Request->get_int('offsetRow', 'get', 0);

    $sheet = 0;
    $startRow = 2 + $offsetRow;

    $chunkFilter->setRows($startRow, $chunkSize);
    $reader->setSheetIndex($sheet);
    $reader->loadIntoExisting($inputFileName, $spreadsheet);
    $sheetData = $spreadsheet->getActiveSheet();

    $maxRow = $sheetData->getHighestRow();

    $loaded_file = array();
    $loaded_file_reverse = array();

    echo ('<pre><code>');

    if ($maxRow <= 1) {
        // Ghi lại các file không có từ 0 - 255
        for ($i = 0; $i <= 255; $i++) {
            if (!file_exists(NV_ROOTDIR . '/release/ip/' . $i . '.php')) {
                $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges=array();\n";
                file_put_contents(NV_ROOTDIR . '/release/ip/' . $i . '.php', $file_content, LOCK_EX);
            }
        }
        echo ('Kết thúc');
    } else {
        for ($i = $startRow; $i <= $maxRow; $i++) {
            $ip_range = $sheetData->getCell('A' . $i)->getValue();
            $geo_id = $sheetData->getCell('B' . $i)->getValue();

            if (isset($array_geo_info[$geo_id])) {
                list($range, $netmask) = explode('/', $ip_range, 2);
                if (!empty($netmask)) {
                    $x = explode('.', $range);
                    while (count($x) < 4) {
                        $x[] = '0';
                    }
                    list($a, $b, $c, $d) = $x;
                    //$range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);

                    $ip_start = ($a * 16777216) + ($b * 65536) + ($c * 256) + ($d);
                    $ip_end = $ip_start + pow(2, 32 - intval($netmask)) - 1;

                    if (!isset($loaded_file[$a]) and file_exists(NV_ROOTDIR . '/release/ip/' . $a . '.php')) {
                        $ranges = array();
                        $ranges_reverse = array();
                        include NV_ROOTDIR . '/release/ip/' . $a . '.php';
                        $loaded_file[$a] = $ranges;
                        foreach ($ranges as $rkey => $rval) {
                            $ranges_reverse[$rval[0]] = array($rkey, $rval[1]);
                        }
                        $loaded_file_reverse[$a] = $ranges_reverse;
                    } elseif (isset($loaded_file[$a])) {
                        $ranges = $loaded_file[$a];
                        $ranges_reverse = $loaded_file_reverse[$a];
                    } else {
                        $ranges = array();
                        $ranges_reverse = array();
                    }

                    $ip_sbefore = $ip_start - 1;

                    if (isset($ranges_reverse[$ip_sbefore]) and $ranges_reverse[$ip_sbefore][1] == $array_geo_info[$geo_id]) {
                        $ip_start = $ranges_reverse[$ip_sbefore][0];
                        unset($ranges_reverse[$ip_sbefore]);
                    }

                    $ranges[$ip_start] = array($ip_end, $array_geo_info[$geo_id]);
                    $ranges_reverse[$ip_end] = array($ip_start, $array_geo_info[$geo_id]);

                    $loaded_file[$a] = $ranges;
                    $loaded_file_reverse[$a] = $ranges_reverse;
                } else {
                    trigger_error('IP range invalid on line ' . $startRow, 256);
                }
            }
        }
        echo('Hãy đợi, tiến trình còn đang chạy. Vui lòng không tắt trình duyệt');
        echo ('<meta http-equiv="refresh" content="0;url=/' . basename(__file__) . '?offsetRow=' . ($offsetRow + $chunkSize) . '&t=' . time() . '">');
    }

    foreach ($loaded_file as $fname => $fdata) {
        $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges=" . nv_print_variable_ip($fdata) . ";\n";
        file_put_contents(NV_ROOTDIR . '/release/ip/' . $fname . '.php', $file_content, LOCK_EX);
    }

    echo ('</code></pre>');
} catch (Exception $ex) {
    echo ('<pre><code>');
    print_r($ex);
    echo ('</code></pre>');
}
