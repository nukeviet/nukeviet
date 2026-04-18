<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Shared\Contracts;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * HasAlias — Marker Interface đánh dấu Entity có cột 'alias' duy nhất.
 *
 * Entity implement interface này sẽ được:
 * - AliasRepositoryTrait: cung cấp isAliasExists(), findByAlias()
 * - BaseCrudService: tự động sinh alias từ title nếu rỗng, kiểm tra trùng
 */
interface HasAlias {}
