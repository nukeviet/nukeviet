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

// Thai
$utf8_lookup_lang = [
    'ก' => 'k', 'ข' => 'kh', 'ฃ' => 'kh', 'ค' => 'kh', 'ฅ' => 'kh', 'ฆ' => 'kh', 'ง' => 'ng', 'จ' => 'ch', 'ฉ' => 'ch', 'ช' => 'ch',
    'ซ' => 's', 'ฌ' => 'ch', 'ญ' => 'y', 'ฎ' => 'd', 'ฏ' => 't', 'ฐ' => 'th', 'ฑ' => 'd', 'ฒ' => 'th', 'ณ' => 'n', 'ด' => 'd',
    'ต' => 't', 'ถ' => 'th', 'ท' => 'th', 'ธ' => 'th', 'น' => 'n', 'บ' => 'b', 'ป' => 'p', 'ผ' => 'ph', 'ฝ' => 'f', 'พ' => 'ph',
    'ฟ' => 'f', 'ภ' => 'ph', 'ม' => 'm', 'ย' => 'y', 'ร' => 'r', 'ฤ' => 'rue', 'ฤๅ' => 'rue', 'ล' => 'l', 'ฦ' => 'lue', 'ฦๅ' => 'lue',
    'ว' => 'w', 'ศ' => 's', 'ษ' => 's', 'ส' => 's', 'ห' => 'h', 'ฬ' => 'l', 'ฮ' => 'h', 'ะ' => 'a', '–ั' => 'a', 'รร' => 'a', 'า' => 'a',
    'รร' => 'an', 'ำ' => 'am', '–ิ' => 'i', '–ี' => 'i', '–ึ' => 'ue', '–ื' => 'ue', '–ุ' => 'u', '–ู' => 'u', 'เะ' => 'e',
    'เ–็' => 'e', 'เ' => 'e', 'แะ' => 'ae', 'แ' => 'ae', 'โะ' => 'o', 'โ' => 'o', 'เาะ' => 'o', 'อ' => 'o', 'เอะ' => 'oe', 'เ–ิ' => 'oe',
    'เอ' => 'oe', 'เ–ียะ' => 'ia', 'เ–ีย' => 'ia', 'เ–ือะ' => 'uea', 'เ–ือ' => 'uea', '–ัวะ' => 'ua', '–ัว' => 'ua',
    'ว' => 'ua', 'ใ' => 'ai', 'ไ' => 'ai', '–ัย' => 'ai', 'ไย' => 'ai', 'าย' => 'ai', 'เา' => 'ao', 'าว' => 'ao', '–ุย' => 'ui',
    'โย' => 'oi', 'อย' => 'oi', 'เย' => 'oei', 'เ–ือย' => 'ueai', 'วย' => 'uai', '–ิว' => 'io', 'เ–็ว' => 'eo', 'เว' => 'eo',
    'แ–็ว' => 'aeo', 'แว' => 'aeo', 'เ–ียว' => 'iao'
];
