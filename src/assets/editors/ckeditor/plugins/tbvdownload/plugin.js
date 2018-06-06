/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16-03-2015 12:55
 */

CKEDITOR.plugins.add('tbvdownload', {
	requires : ['sourcearea', 'dialog'],
	lang: ['en', 'vi'],
	init : function(editor) {
		var pluginName = 'tbvdownload';
		CKEDITOR.dialog.add('tbvdownloadDialog', this.path + 'dialogs/tbvdownload.js');
		editor.addCommand(pluginName, new CKEDITOR.dialogCommand('tbvdownloadDialog'));
		editor.ui.addButton(pluginName, {
			label : editor.lang.tbvdownload.title,
			command : pluginName,
			icon : this.path + 'icons/tbvdownload.png',
		});
	}
});
