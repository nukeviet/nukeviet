// Including core.js
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = CKEDITOR.basePath + 'plugins/ckeditor_wiris/core/core.js';
document.getElementsByTagName('head')[0].appendChild(script);

// Configuration
var _wrs_conf_editorEnabled = true;		// Specifies if fomula editor is enabled.
var _wrs_conf_CASEnabled = true;		// Specifies if WIRIS cas is enabled.

var _wrs_conf_imageMathmlAttribute = 'data-mathml';	// Specifies the image tag where we should save the formula editor mathml code.
var _wrs_conf_CASMathmlAttribute = 'alt';	// Specifies the image tag where we should save the WIRIS cas mathml code.

var _wrs_conf_editorPath = CKEDITOR.basePath + 'plugins/ckeditor_wiris/integration/editor.php';			// Specifies where is the editor HTML code (for popup window).
var _wrs_conf_editorAttributes = 'width=570, height=450, scroll=no, resizable=yes';							// Specifies formula editor window options.
var _wrs_conf_CASPath = CKEDITOR.basePath + 'plugins/ckeditor_wiris/integration/cas.php';					// Specifies where is the WIRIS cas HTML code (for popup window).
var _wrs_conf_CASAttributes = 'width=640, height=480, scroll=no, resizable=yes';							// Specifies WIRIS cas window options.

var _wrs_conf_createimagePath = CKEDITOR.basePath + 'plugins/ckeditor_wiris/integration/createimage.php';			// Specifies where is createimage script.
var _wrs_conf_createcasimagePath = CKEDITOR.basePath + 'plugins/ckeditor_wiris/integration/createcasimage.php';	// Specifies where is createcasimage script.

var _wrs_conf_getmathmlPath = CKEDITOR.basePath + 'plugins/ckeditor_wiris/integration/getmathml.php';			// Specifies where is the getmathml script.
var _wrs_conf_servicePath = CKEDITOR.basePath + 'plugins/ckeditor_wiris/integration/service.php';			// Specifies where is the service script.

var _wrs_conf_saveMode = 'tags';					// This value can be 'tags', 'xml' or 'safeXml'.
var _wrs_conf_parseModes = ['latex'];				// This value can contain 'latex'.

var _wrs_conf_enableAccessibility = false;

// Vars
var _wrs_int_editorIcon = CKEDITOR.basePath + 'plugins/ckeditor_wiris/core/icons/formula.gif';
var _wrs_int_CASIcon = CKEDITOR.basePath + 'plugins/ckeditor_wiris/core/icons/cas.gif';
var _wrs_int_temporalIframe;
var _wrs_int_window;
var _wrs_int_window_opened = false;
var _wrs_int_temporalImageResizing;

/*
 * Fix for a bug in CKEditor when there is more than one editor in the same page
 * It removes wiris element from config array when more than one is found
 */
var wirisButtonIncluded = false;

for (var i = 0; i < CKEDITOR.config.toolbar_Full.length; ++i) {
	if (CKEDITOR.config.toolbar_Full[i].name == 'wiris') {
		if (!wirisButtonIncluded) {
			wirisButtonIncluded = true;
		}
		else {
			CKEDITOR.config.toolbar_Full.splice(i, 1);
			i--;
		}
	}
}

// Plugin integration
CKEDITOR.plugins.add('ckeditor_wiris', {
	'init': function (editor) {
		var iframe;
		
		function whenDocReady() {
			if (window.wrs_initParse) {
				editor.setData(wrs_initParse(editor.getData()), function () {
					editor.on('beforeGetData', function () {
						if (typeof editor._.data != 'string') {
							if (editor.element && editor.elementMode == 1) {
								editor._.data = editor.element.is('textarea') ? editor.element.getValue() : editor.element.getHtml();
							}
							else {
								editor._.data = '';
							}
						}
						
						editor._.data = wrs_endParse(editor._.data);
					});
					
					if (editor._.events.doubleclick) {					// When the iframe is double clicked, a dialog is open. This should be avoided.
						editor._.events.doubleclick.listeners = [];
					}
				});
			}
			else {
				setTimeout(whenDocReady, 50);
			}
		}
		
		whenDocReady();
		
		function checkIframe() {
			try {
				var newIframe = document.getElementById('cke_contents_' + editor.name).getElementsByTagName('iframe')[0];
				
				if (iframe != newIframe) {
					wrs_addIframeEvents(newIframe, function (iframe, element) {
						wrs_int_doubleClickHandler(editor, iframe, element);
					}, wrs_int_mousedownHandler, wrs_int_mouseupHandler);
					
					iframe = newIframe;
				}
			}
			catch (e) {
			}
		}
		
		// CKEditor replaces several times the iframe element during its execution, so we must assign the events again.
		setInterval(checkIframe, 500);
		
		if (_wrs_conf_editorEnabled) {
			editor.addCommand('ckeditor_wiris_openFormulaEditor', {
				'async': false,
				'canUndo': false,
				'editorFocus': false,
				
				'exec': function (editor) {
					wrs_int_openNewFormulaEditor(iframe, editor.langCode);
				}
			});
			
			editor.ui.addButton('ckeditor_wiris_formulaEditor', {
				'label': 'WIRIS editor',
				'command': 'ckeditor_wiris_openFormulaEditor',
				'icon': _wrs_int_editorIcon
			});
		}
		
		if (_wrs_conf_CASEnabled) {
			editor.addCommand('ckeditor_wiris_openCAS', {
				'async': false,								// The command need some time to complete after exec function returns.
				'canUndo': false,
				'editorFocus': false,
				
				'exec': function (editor) {
					wrs_int_openNewCAS(iframe, editor.langCode);
				}
			});
			
			editor.ui.addButton('ckeditor_wiris_CAS', {
				'label': 'WIRIS cas',
				'command': 'ckeditor_wiris_openCAS',
				'icon': _wrs_int_CASIcon
			});
		}
	}
});

/**
 * Opens formula editor.
 * @param object iframe Target
 */
function wrs_int_openNewFormulaEditor(iframe, language) {
	if (_wrs_int_window_opened) {
		_wrs_int_window.focus();
	}
	else {
		_wrs_int_window_opened = true;
		_wrs_isNewElement = true;
		_wrs_int_temporalIframe = iframe;
		_wrs_int_window = wrs_openEditorWindow(language, iframe, true);
	}
}

/**
 * Opens CAS.
 * @param object iframe Target
 */
function wrs_int_openNewCAS(iframe, language) {
	if (_wrs_int_window_opened) {
		_wrs_int_window.focus();
	}
	else {
		_wrs_int_window_opened = true;
		_wrs_isNewElement = true;
		_wrs_int_temporalIframe = iframe;
		_wrs_int_window = wrs_openCASWindow(iframe, true, language);
	}
}

/**
 * Handles a double click on the iframe.
 * @param object iframe Target
 * @param object element Element double clicked
 */
function wrs_int_doubleClickHandler(editor, iframe, element) {
	if (element.nodeName.toLowerCase() == 'img') {
		if (wrs_containsClass(element, 'Wirisformula')) {
			if (!_wrs_int_window_opened) {
				_wrs_temporalImage = element;
				wrs_int_openExistingFormulaEditor(iframe, editor.langCode);
			}
			else {
				_wrs_int_window.focus();
			}
		}
		else if (wrs_containsClass(element, 'Wiriscas')) {
			if (!_wrs_int_window_opened) {
				_wrs_temporalImage = element;
				wrs_int_openExistingCAS(iframe, editor.langCode);
			}
			else {
				_wrs_int_window.focus();
			}
		}
	}
}

/**
 * Opens formula editor to edit an existing formula.
 * @param object iframe Target
 */
function wrs_int_openExistingFormulaEditor(iframe, language) {
	_wrs_int_window_opened = true;
	_wrs_isNewElement = false;
	_wrs_int_temporalIframe = iframe;
	_wrs_int_window = wrs_openEditorWindow(language, iframe, true);
}

/**
 * Opens CAS to edit an existing formula.
 * @param object iframe Target
 */
function wrs_int_openExistingCAS(iframe, language) {
	_wrs_int_window_opened = true;
	_wrs_isNewElement = false;
	_wrs_int_temporalIframe = iframe;
	_wrs_int_window = wrs_openCASWindow(iframe, true, language);
}

/**
 * Handles a mouse down event on the iframe.
 * @param object iframe Target
 * @param object element Element mouse downed
 */
function wrs_int_mousedownHandler(iframe, element) {
	if (element.nodeName.toLowerCase() == 'img') {
		if (wrs_containsClass(element, 'Wirisformula') || wrs_containsClass(element, 'Wiriscas')) {
			_wrs_int_temporalImageResizing = element;
		}
	}
}

/**
 * Handles a mouse up event on the iframe.
 */
function wrs_int_mouseupHandler() {
	if (_wrs_int_temporalImageResizing) {
		setTimeout(function () {
			_wrs_int_temporalImageResizing.removeAttribute('style');
			_wrs_int_temporalImageResizing.removeAttribute('width');
			_wrs_int_temporalImageResizing.removeAttribute('height');
		}, 10);
	}
}

/**
 * Calls wrs_updateFormula with well params.
 * @param string mathml
 */
function wrs_int_updateFormula(mathml, editMode, language) {
	wrs_updateFormula(_wrs_int_temporalIframe.contentWindow, _wrs_int_temporalIframe.contentWindow, mathml, null, editMode, language);
}

/**
 * Calls wrs_updateCAS with well params.
 * @param string appletCode
 * @param string image
 * @param int width
 * @param int height
 */
function wrs_int_updateCAS(appletCode, image, width, height) {
	wrs_updateCAS(_wrs_int_temporalIframe.contentWindow, _wrs_int_temporalIframe.contentWindow, appletCode, image, width, height);
}

/**
 * Handles window closing.
 */
function wrs_int_notifyWindowClosed() {
	_wrs_int_window_opened = false;
}
