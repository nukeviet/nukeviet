/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16-03-2015 12:55
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'tbvdownload', {

	// Register the icons.
	icons: 'tbvdownload',
    lang : ['vi', 'en'],
	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog window.
		editor.addCommand( 'tbvdownload', new CKEDITOR.dialogCommand( 'tbvdownloadDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'tbvdownload', {

			// The text part of the button (if available) and the tooltip.
			label: editor.lang.tbvdownload.tooltip,

			// The command to execute on click.
			command: 'tbvdownload',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'insert'
		});

		// Register our dialog file -- this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'tbvdownloadDialog', this.path + 'dialogs/tbvdownload.js' );
	}
});
