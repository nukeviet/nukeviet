/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

/**
 * Các script thực thi ngay sau khi nhận được html.
 * Đảm bảo không nhìn thấy sự thay đổi về giao diện trong quá trình render trang web
 * Bởi mặc định script sẽ được kéo xuống cuối footer
 */

const nvSetThemeMode = theme => {
    if (theme === 'auto') {
        document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    } else {
        document.documentElement.setAttribute('data-bs-theme', theme);
    }
}

(() => {
    const setTheme = () => {
        if (document.documentElement.getAttribute('data-theme') === 'auto') {
            nvSetThemeMode('auto');
        }
    }
    setTheme();

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        setTheme();
    });
})();
