//develop from: https://github.com/w3craftsman/ckeditor-plugin-googledoc
	
CKEDITOR.plugins.add('googledocs', {
  icons: 'googledocs',
  lang: 'en,vi',

  init: function(editor) {
    editor.addCommand('googledocs', new CKEDITOR.dialogCommand('googledocs'));

    editor.ui.addButton('Googledocs', {
      label: editor.lang.googledocs.button,
      command: 'googledocs',
      toolbar: 'insert'
    });

    CKEDITOR.dialog.add('googledocs', this.path + 'dialogs/googledocs.js');
  }
});