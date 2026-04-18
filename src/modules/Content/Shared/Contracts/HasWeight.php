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
 * HasWeight — Marker Interface đánh dấu Entity có cột 'weight' sắp xếp thứ tự.
 *
 * Entity implement interface này sẽ được:
 * - WeightRepositoryTrait: cung cấp getMaxWeight(), reorderWeight(), autoCorrectWeight()
 * - BaseCrudService: tự động gán weight khi thêm mới, tự động reorder khi xóa
 */
interface HasWeight {}
