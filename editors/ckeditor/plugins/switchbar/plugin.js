/**
 * This plugin was created so that to give the possibility to switch between
 * the basic and full toolbars
 */
(function()
{
	var _buttonName = "SwitchBar";
	var _pluginCommand='switchbar';

	var _currentBar = "";

	var commandDefinition =
	{
		exec: function( editor )
		{
			if ( _currentBar == editor.config.switchBarSimple ) {
				editor.config.switchBarDefault = editor.config.switchBarReach;
			} else {
				editor.config.switchBarDefault = editor.config.switchBarSimple;
			}
			editor.config.toolbar = editor.config.switchBarDefault;

			//the only way is to destroy the editor and recreate it again with new configurations
			var areaID = editor.name;
			var config = editor.config;
			editor.destroy();
			CKEDITOR.replace( areaID, config );
		},

		editorFocus : false,
		canUndo     : false
	};
	CKEDITOR.plugins.add( _pluginCommand,
	{
		init: function( editor )
		{
			var pluginIcon = "";
			_currentBar = editor.config.switchBarDefault;
			if ( _currentBar == editor.config.switchBarSimple ) {
				pluginIcon = editor.config.switchBarSimpleIcon;
			} else {
				pluginIcon = editor.config.switchBarReachIcon;
			}
			editor.addCommand( _pluginCommand, commandDefinition );
			editor.ui.addButton( _buttonName,
			{
				'label' : 'Switch Toolbar',
				icon    : CKEDITOR.getUrl( this.path ) + 'images/' + pluginIcon,
				command : _pluginCommand
			});
		}
	});
})();

/**
 * The simple toolbar
 * @type string
 */
CKEDITOR.config.switchBarSimple = 'Basic';

/**
 * The reach toolbar
 * @type string
 */
CKEDITOR.config.switchBarReach = 'Full';

/**
 * The default toolbar which will be set up at the beginning
 * @type string
 */
CKEDITOR.config.switchBarDefault = 'Full';

/**
 * The image to the icong for simple toolbar
 * @type string
 */
CKEDITOR.config.switchBarSimpleIcon = 'maximise.gif';

/**
 * The image to the icong for reach toolbar
 * @type string
 */
CKEDITOR.config.switchBarReachIcon = 'minimise.gif';