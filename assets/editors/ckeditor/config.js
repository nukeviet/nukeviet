/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.enterMode = CKEDITOR.ENTER_BR;
	config.language = nv_lang_interface;
	config.allowedContent = true;
	config.extraPlugins = 'video,eqneditor,switchbar,tbvdownload,googledocs';
	config.entities = false;
	config.youtube_width = '640';
	config.youtube_height = '480';
	config.youtube_related = false;
	config.youtube_older = false;
	config.youtube_privacy = false;
	config.youtube_autoplay = true;
	config.codeSnippet_theme = 'github';
	// Default setting.
	config.toolbarGroups = [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'insert' },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'links' },
		'/',
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'editing', groups: [ 'find', 'selection'] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'tools' }
	];

	config.toolbar_Basic =
	[
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		'/',
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'styles', items : [ 'Font', 'FontSize', 'TextColor', 'BGColor' ] },
		{ name: 'tools', items : ['SwitchBar',  'Maximize'] }
	];
};