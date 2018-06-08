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
$inputFileName = NV_ROOTDIR . '/libs/ip/GeoLite2-Country-Blocks-IPv6.csv';

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

    echo ('<pre><code>');

    if ($maxRow <= 1) {
        echo ('Kết thúc');
    } else {
        for ($i = $startRow; $i <= $maxRow; $i++) {
            $ip_range = $sheetData->getCell('A' . $i)->getValue();
            $geo_id = $sheetData->getCell('B' . $i)->getValue();

            if (isset($array_geo_info[$geo_id])) {
                list($range, $netmask) = explode('/', $ip_range, 2);
                if (!empty($netmask)) {
                    $x = explode(':', $range);
                    $a = $x[0];

                    if (!isset($loaded_file[$a]) and file_exists(NV_ROOTDIR . '/release/ip6/' . $a . '.php')) {
                        $ranges = array();
                        include NV_ROOTDIR . '/release/ip6/' . $a . '.php';
                        $loaded_file[$a] = $ranges;
                    } elseif (isset($loaded_file[$a])) {
                        $ranges = $loaded_file[$a];
                    } else {
                        $ranges = array();
                    }

                    $ranges[$ip_range] = $array_geo_info[$geo_id];
                    $loaded_file[$a] = $ranges;
                } else {
                    trigger_error('IP range invalid on line ' . $startRow, 256);
                }
            }
        }
        echo('Hãy đợi, tiến trình còn đang chạy. Vui lòng không tắt trình duyệt');
        echo ('<meta http-equiv="refresh" content="0;url=/' . basename(__file__) . '?offsetRow=' . ($offsetRow + $chunkSize) . '&t=' . time() . '">');
    }

    foreach ($loaded_file as $fname => $fdata) {
        $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges = " . nv_print_variable_ip6($fdata) . ";\n";
        file_put_contents(NV_ROOTDIR . '/release/ip6/' . $fname . '.php', $file_content, LOCK_EX);
    }

    echo ('</code></pre>');
} catch (Exception $ex) {
    echo ('<pre><code>');
    print_r($ex);
    echo ('</code></pre>');
}
