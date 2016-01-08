/*
 * @file Video plugin for CKEditor
 * Copyright (C) 2011 Alfonso Martínez de Lizarrondo
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 */

(function() {

	CKEDITOR.plugins.add('video', {
		// Translations, available at the end of this file, without extra requests
		lang : ['en', 'vi'],

		getPlaceholderCss : function() {
			return 'img.cke_video' + '{' + 'background-image: url(' + CKEDITOR.getUrl(this.path + 'images/placeholder.png') + ');' + 'background-position: center center;' + 'background-repeat: no-repeat;' + 'background-color:gray;' + 'border: 1px solid #a9a9a9;' + 'width: 80px;' + 'height: 80px;' + '}';
		},

		onLoad : function() {
			CKEDITOR.addCss(this.getPlaceholderCss());
		},

		init : function(editor) {

			CKEDITOR.dialog.add('video', this.path + 'dialogs/video.js');

			editor.addCommand('Video', new CKEDITOR.dialogCommand('video'));
			editor.ui.addButton('Video', {
				label : editor.lang.video.toolbar,
				command : 'Video',
				icon : this.path + 'images/icon.png'
			});

			// If the "menu" plugin is loaded, register the menu items.
			if (editor.addMenuItems) {
				editor.addMenuItems({
					video : {
						label : editor.lang.video.properties,
						command : 'Video',
						group : 'flash'
					}
				});
			}

			editor.on('doubleclick', function(evt) {
				var element = evt.data.element;

				if (element.is('img') && element.data('cke-real-element-type') == 'video')
					evt.data.dialog = 'video';
			});

			// If the "contextmenu" plugin is loaded, register the listeners.
			if (editor.contextMenu) {
				editor.contextMenu.addListener(function(element, selection) {
					if (element && element.is('img') && !element.isReadOnly() && element.data('cke-real-element-type') == 'video')
						return {
							video : CKEDITOR.TRISTATE_OFF
						};
				});
			}

			// Add special handling for these items
			CKEDITOR.dtd.$empty['cke:source'] = 1;
			CKEDITOR.dtd.$empty['source'] = 1;

			editor.lang.fakeobjects.video = editor.lang.video.fakeObject;

		}, //Init

		afterInit : function(editor) {
			var dataProcessor = editor.dataProcessor, htmlFilter = dataProcessor && dataProcessor.htmlFilter, dataFilter = dataProcessor && dataProcessor.dataFilter;

			// dataFilter : conversion from html input to internal data
			dataFilter.addRules({

				elements : {
					$ : function(realElement) {
						if (realElement.name == 'video') {
							realElement.name = 'cke:video';
							for (var i = 0; i < realElement.children.length; i++) {
								if (realElement.children[i].name == 'source')
									realElement.children[i].name = 'cke:source';
							}

							var fakeElement = editor.createFakeParserElement(realElement, 'cke_video', 'video', false), fakeStyle = fakeElement.attributes.style || '';

							var width = realElement.attributes.width, height = realElement.attributes.height, poster = realElement.attributes.poster;

							if ( typeof width != 'undefined')
								fakeStyle = fakeElement.attributes.style = fakeStyle + 'width:' + CKEDITOR.tools.cssLength(width) + ';';

							if ( typeof height != 'undefined')
								fakeStyle = fakeElement.attributes.style = fakeStyle + 'height:' + CKEDITOR.tools.cssLength(height) + ';';

							if (poster)
								fakeStyle = fakeElement.attributes.style = fakeStyle + 'background-image:url(' + poster + ');';

							return fakeElement;
						}
					}
				}

			});

		} // afterInit
	});
	// plugins.add
})();