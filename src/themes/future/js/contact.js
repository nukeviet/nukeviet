/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function () {
    // JS điều khiển cho block supporter
    document.querySelectorAll('[data-supporter-tabs]').forEach(function (dd) {
        const cnt = document.getElementById(dd.dataset.supporterTabs);
        if (!cnt) return;
        const btn = dd.querySelector('.dropdown-toggle');
        dd.addEventListener('click', function (e) {
            const item = e.target.closest('[data-dep]');
            if (!item) return;
            e.preventDefault();
            btn.textContent = item.textContent.trim();
            dd.querySelectorAll('.dropdown-item').forEach(a => a.classList.remove('active'));
            item.classList.add('active');
            cnt.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            const t = document.getElementById(item.dataset.dep);
            if (t) t.classList.add('active');
        });
    });
});
