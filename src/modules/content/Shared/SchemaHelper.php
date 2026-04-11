<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\content\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * SchemaHelper — Hằng số dùng chung thay cho global.functions.php
 * Tránh ô nhiễm biến global, chỉ cần gọi khi có dùng
 */
class SchemaHelper
{
    /** @var array Các loại dữ liệu có cấu trúc Schema.org */
    public static array $schema_types = [
        'webpage' => 'WebPage',
        'newsarticle' => 'NewsArticle',
        'blogposting' => 'BlogPosting',
        'article' => 'Article'
    ];

    /** @var array Các loại chủ thể Schema.org */
    public static array $schema_abouts = [
        'organization' => 'Organization',
        'person' => 'Person',
        'systemrequirements' => 'SystemRequirements',
        'servicecommitment' => 'ServiceCommitment',
        'privacypolicy' => 'PrivacyPolicy',
        'termsofservice' => 'TermsOfService',
        'copyrightnotice' => 'CopyrightNotice'
    ];
}
