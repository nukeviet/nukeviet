/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.language = nv_sitelang;
	config.extraPlugins = 'autosave';
	config.entities = false;
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
};