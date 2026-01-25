/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

var nukeviet = nukeviet || {};
nukeviet.Picker = nukeviet.Picker || {};
nukeviet.Picker.EditorCallback = nukeviet.Picker.EditorCallback || [];
nukeviet.Picker.EditorCallback.push((editorId, data) => {
    let editor = window.opener || {};
    editor = editor.nveditor || [];
    editor = editor[editorId] || null;
    if (!editor) {
        return;
    }

    if (data.alt && data.alt != '') {
        // Insert full image
        editor.commands.get('nvbox').execute(data.href, {
            alt: data.alt
        });
    } else {
        // Insert file
        editor.commands.get('nvbox').execute(data.href);
    }
    editor.editing.view.focus();
});
