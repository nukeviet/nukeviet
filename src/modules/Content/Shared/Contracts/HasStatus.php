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
 * HasStatus — Marker Interface đánh dấu Entity có cột 'status' (0/1).
 *
 * Entity implement interface này sẽ được:
 * - StatusRepositoryTrait: cung cấp toggleStatus()
 * - BaseCrudService: tự động xử lý changeStatus()
 */
interface HasStatus {}
