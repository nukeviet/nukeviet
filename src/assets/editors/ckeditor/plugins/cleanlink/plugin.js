CKEDITOR.plugins.add('cleanlink', {
	requires : ['dialog'],
	lang: ['en', 'vi'],
	init : function(editor) {
		editor.addCommand('cleanlink', new CKEDITOR.dialogCommand('cleanlink'));
		editor.ui.addButton('Cleanlink', {
			label : editor.lang.cleanlink.title,
			command : 'cleanlink',
			icon : this.path + 'icons/cleanlink.png',
		});
		CKEDITOR.dialog.add('cleanlink', this.path + 'dialogs/cleanlink.js');
	}
});
