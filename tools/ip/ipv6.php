<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require NV_ROOTDIR . '/src/functions.php';
require NV_ROOTDIR . '/src/Large-CSVReader.php';
require NV_ROOTDIR . '/src/geoidInfo.php';

set_time_limit(0);
ini_set('memory_limit', '-1');

/**
 * Bắt đầu tool
 */

// Tạo các thư mục nếu chưa có
if (!is_dir(NV_ROOTDIR . '/release')) {
    mkdir(NV_ROOTDIR . '/release');
}
if (!is_dir(NV_ROOTDIR . '/release/ip6')) {
    mkdir(NV_ROOTDIR . '/release/ip6');
}

$inputFileType = 'Csv';
$inputFileName = NV_ROOTDIR . '/libs/ip/GeoLite2-Country-Blocks-IPv6.csv';

// Số row mỗi chunk
$chunkSize = 10000;
$offsetRow = 0;

while (1) {
    try {
        echo('Offset: ' . $offsetRow . PHP_EOL);
        echo 'Reading...' . PHP_EOL;

        $chunkFilter = new NukeViet\Files\ChunkReadFilter();

        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadFilter($chunkFilter)->setContiguous(true);
        $spreadsheet = new Spreadsheet();

        $sheet = 0;
        $startRow = 2 + $offsetRow;

        $chunkFilter->setRows($startRow, $chunkSize);
        $reader->setSheetIndex($sheet);
        $reader->loadIntoExisting($inputFileName, $spreadsheet);
        $sheetData = $spreadsheet->getActiveSheet();

        $maxRow = $sheetData->getHighestRow();

        $loaded_file = array();

        if ($maxRow <= 1) {
            echo 'No data.' . PHP_EOL;
            die('Finish!' . PHP_EOL);
        } else {
            $keyloop = 0;
            $keyecho = 0;

            for ($i = $startRow; $i <= $maxRow; $i++) {
                $keyloop++;
                if ($keyloop % 100 == 0) {
                    $keyecho++;
                    echo 'Writing step ' . $keyecho . '...' . PHP_EOL;
                }

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
                        die('IP range invalid on line ' . $startRow . PHP_EOL);
                    }
                }
            }
            $offsetRow = ($offsetRow + $chunkSize);
        }

        foreach ($loaded_file as $fname => $fdata) {
            $file_content = "<?php\n\n" . IP_FILEHEAD . "\n\$ranges = " . nv_print_variable_ip6($fdata) . ";\n";
            file_put_contents(NV_ROOTDIR . '/release/ip6/' . $fname . '.php', $file_content, LOCK_EX);
        }

        unset($chunkFilter, $reader, $spreadsheet, $sheetData, $file_content, $loaded_file);
        sleep(1);
    } catch (Exception $ex) {
        echo 'Error:' . PHP_EOL;
        print_r($ex);
        die(PHP_EOL);
    }
}
