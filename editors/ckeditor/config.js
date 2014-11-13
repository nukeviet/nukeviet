/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.language = nv_sitelang;
	config.allowedContent = true;
	config.extraPlugins = 'video';
	config.entities = false;
	config.youtube_width = '640';
	config.youtube_height = '480';
	config.youtube_related = false;
	config.youtube_older = true;
	config.youtube_privacy = false;
	config.youtube_autoplay = true;
	config.codeSnippet_theme = 'github';
	// Default setting.
	config.toolbarGroups = [
	    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
	    { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
	    { name: 'forms' },
	    { name: 'links' },
	    { name: 'insert' },
	    { name: 'tools' },
	    { name: 'others' },
	    { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
	    '/',
	    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	    { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
	    { name: 'styles' },
	    { name: 'colors' },
	];

	config.toolbar_Basic =
	[
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'
                 ,'Iframe' ] },
                '/',
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'styles', items : [ 'Font', 'FontSize', 'TextColor', 'BGColor' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] },
	];
};