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

// Russian cyrillic
$utf8_lookup_lang = [
    'а' => 'a', 'А' => 'A', 'б' => 'b', 'Б' => 'B', 'в' => 'v', 'В' => 'V', 'г' => 'g', 'Г' => 'G', 'д' => 'd', 'Д' => 'D',
    'е' => 'e', 'Е' => 'E', 'ё' => 'jo', 'Ё' => 'Jo', 'ж' => 'zh', 'Ж' => 'Zh', 'з' => 'z', 'З' => 'Z', 'и' => 'i', 'И' => 'I',
    'й' => 'j', 'Й' => 'J', 'к' => 'k', 'К' => 'K', 'л' => 'l', 'Л' => 'L', 'м' => 'm', 'М' => 'M', 'н' => 'n', 'Н' => 'N',
    'о' => 'o', 'О' => 'O', 'п' => 'p', 'П' => 'P', 'р' => 'r', 'Р' => 'R', 'с' => 's', 'С' => 'S', 'т' => 't', 'Т' => 'T',
    'у' => 'u', 'У' => 'U', 'ф' => 'f', 'Ф' => 'F', 'х' => 'x', 'Х' => 'X', 'ц' => 'c', 'Ц' => 'C', 'ч' => 'ch', 'Ч' => 'Ch',
    'ш' => 'sh', 'Ш' => 'Sh', 'щ' => 'sch', 'Щ' => 'Sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'Y', 'ь' => '', 'Ь' => '',
    'э' => 'eh', 'Э' => 'Eh', 'ю' => 'ju', 'Ю' => 'Ju', 'я' => 'ja', 'Я' => 'Ja'
];
