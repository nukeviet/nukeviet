/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function () {
    // Click outside counter_button popover to close it
    document.addEventListener('click', e => {
        const btn = document.querySelector('[data-nv-counter-btn]');
        if (!btn) return;
        if (!btn.contains(e.target) && !document.querySelector('.popover')?.contains(e.target)) {
            bootstrap.Popover.getInstance(btn)?.hide();
        }
    });
});
