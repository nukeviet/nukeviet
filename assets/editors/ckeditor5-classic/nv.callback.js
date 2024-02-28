/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

window.NVFileManagerSelectCallback = function(href, alt) {
    let editorId = $('[name="editor_id"]').val();
    let editor = window.opener || [];
    editor = editor.nveditor || [];
    editor = editor[editorId] || [];
    if (!editor) {
        throw new Error('No Editor!!!');
    }

    if (alt != '') {
        // Insert full image
        editor.commands.get('nvbox').execute(href, {
            alt: alt
        });
    } else {
        // Insert file
        editor.commands.get('nvbox').execute(href);
    }
    editor.editing.view.focus();
    window.close();
}
