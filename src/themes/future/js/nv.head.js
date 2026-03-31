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

var nukeviet = nukeviet || {};
nukeviet.mobileBreakPoint = '(min-width: 992px)';
nukeviet.isMScreen = () => {
    const hasViewport = !!document.querySelector('meta[name="viewport"]');
    return !window.matchMedia(nukeviet.mobileBreakPoint).matches && hasViewport;
};

const nvSetThemeMode = theme => {
    if (theme === 'auto') {
        document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    } else {
        document.documentElement.setAttribute('data-bs-theme', theme);
    }
    document.dispatchEvent(new Event('changed.nv.thememode'));
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

nukeviet.toggleThemeScreen = () => {
    let da = document.querySelector('[data-toggle="site-user-area"]');
    let ma = document.querySelector('[data-toggle="site-user-area-mobile"]');
    if (!da || !ma) {
        return;
    }
    if (nukeviet.isMScreen()) {
        // Desktop > Mobile
        while (da.firstChild) {
            ma.appendChild(da.firstChild);
        }
        return;
    }
    // Mobile > Desktop
    while (ma.firstChild) {
        da.appendChild(ma.firstChild);
    }
};
document.addEventListener('DOMContentLoaded', () => {
    nukeviet.toggleThemeScreen();
});
window.matchMedia(nukeviet.mobileBreakPoint).addEventListener('change', () => {
    nukeviet.toggleThemeScreen();
});
