'use strict';

(function() {
    function getDomain() {
        var domain = window.location.origin;
        if (!domain) {
            domain = window.location.protocol + '//' + window.location.host;
            if (window.location.port != '80' && window.location.port != '443') {
                domain += ':' + window.location.port;
            }
        }
        return domain;
    }

    function convertPercentToRatio(percent) {
        var gcd = function(a, b) {
            if (b < 0.0000001) return a;
            return gcd(b, Math.floor(a % b));
        };

        var gcdValue = gcd(percent, 100);
        var numerator = percent / gcdValue;
        var denominator = 100 / gcdValue;

        return denominator + ':' + numerator;
    }

    function loadValue(domNode, editor) {
        if (domNode.getChildCount() != 1) {
            return;
        }
        var iframeNode = domNode.getChild(0);
        if (iframeNode.getName() != 'iframe') {
            return;
        }
        // Xác định SRC
        var src = '',
            domain = getDomain();
        if (iframeNode.hasAttribute('src')) {
            src = iframeNode.getAttribute('src');
        }
        if (this.id == 'txtUrl') {
            var url = src;
            url = url.replace('https://docs.google.com/viewer?url=', '');
            url = url.replace('https://view.officeapps.live.com/op/embed.aspx?src=', '');
            url = decodeURIComponent(url.replace('&embedded=true', ''));
            if (url.indexOf(domain) == 0) {
                url = url.substring(domain.length);
            }
            this.setValue(url);
            return;
        }

        // Tìm tỉ lệ %. 0 là kích thước cố định
        var percent = 0;
        if (domNode.hasAttribute('data-p')) {
            percent = domNode.getAttribute('data-p');
            if (!isNaN(percent)) {
                percent = parseFloat(percent);
            }
            if (percent < 0) {
                percent = 0;
            }
        }

        // Xác định tỉ lệ
        if (this.id == 'txtRatio' && percent > 0) {
            this.setValue(convertPercentToRatio(percent));
            return;
        }

        // Xác định kiểu
        if (this.id == 'txtRenderMode') {
            this.setValue(percent > 0 ? 'responsive' : 'fixedsize');
            return;
        }

        // Xác định trình xem
        if (this.id == 'txtViewer') {
            if (src.match(/\:\/\/docs\.google\.com/i)) {
                this.setValue('google');
            } else {
                this.setValue('microsoft');
            }
            return;
        }

        // Chiều rộng
        if (this.id == 'txtWidth' && percent == 0) {
            this.setValue(CKEDITOR.tools.convertToPx(CKEDITOR.tools.cssLength(domNode.getStyle('width'))));
            return;
        }

        // Chiều cao
        if (this.id == 'txtHeight' && percent == 0) {
            this.setValue(CKEDITOR.tools.convertToPx(CKEDITOR.tools.cssLength(domNode.getStyle('height'))));
            return;
        }
    }

    CKEDITOR.dialog.add('docviewer', function(editor) {
        var config = editor.config,
            hasFileBrowser = !!(config.filebrowserImageBrowseUrl || config.filebrowserBrowseUrl);

        // Nút url của ảnh và nút duyệt (nếu có)
        var srcBoxChildren = [{
            type: 'text',
            id: 'txtUrl',
            label: editor.lang.docviewer.url,
            required: true,
            validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.docviewer.alertUrl),
            setup: loadValue
        }];
        if (hasFileBrowser) {
            srcBoxChildren.push({
                id: 'browse',
                type: 'button',
                filebrowser: 'settingsTab:txtUrl',
                hidden: true,
                label: editor.lang.common.browseServer,
                style: 'display:inline-block;margin-top:16px;',
                align: 'center',
                hidden: true
            });
        }

        return {
            title: editor.lang.docviewer.title,
            minWidth: 300,
            minHeight: 150,
            getModel: function(editor) {
                var element = editor.getSelection().getSelectedElement();
                if (element && element.data('cke-real-element-type') === 'docviewer') {
                    return element;
                }
                return null;
            },
            onLoad: function() {},
            onOk: function() {
                var domNode, domData, iframeNode, match,
                    nodeStyles = {},
                    nodeAttributes = {},
                    iframeStyles = {},
                    iframeAttributes = {},
                    testIsDomain = /^[a-zA-Z0-9\:]*\/\//i;
                if (!this.fakeImage) {
                    // Trường hợp thêm cái mới
                    domNode = new CKEDITOR.dom.element('div');
                    domNode.append(new CKEDITOR.dom.element('iframe'));
                } else {
                    // Trường hợp sửa cái cũ
                    domNode = this.domNode;
                }

                domData = {
                    src: this.getValueOf('settingsTab', 'txtUrl'),
                    viewer: this.getValueOf('settingsTab', 'txtViewer'),
                    renderMode: this.getValueOf('settingsTab', 'txtRenderMode'),
                    width: this.getValueOf('settingsTab', 'txtWidth'),
                    height: this.getValueOf('settingsTab', 'txtHeight'),
                    ratio: this.getValueOf('settingsTab', 'txtRatio'),
                    ratioPercent: 0,
                }
                iframeNode = domNode.getChild(0);
                if (domData.width == '' || domData.width == 0 || domData.height == '' || domData.height == 0) {
                    alert(editor.lang.docviewer.errorWh);
                    return false;
                }
                var match = domData.ratio.match(/([0-9\.]+)[\s]*\:[\s]*([0-9\.]+)/);
                if (!match) {
                    alert(editor.lang.docviewer.ratioErrorRule);
                    return false;
                }
                match[1] = parseFloat(match[1]);
                match[2] = parseFloat(match[2]);
                if (match[2] == 0) {
                    alert(editor.lang.docviewer.ratioErrorH);
                    return false;
                }
                domData.ratioPercent = (match[2] / match[1]) * 100;
                if (!testIsDomain.test(domData.src)) {
                    domData.src = getDomain() + domData.src;
                }

                // Xử lý node theo data thiết lập
                if (editor.plugins.iframe) {
                    iframeAttributes = editor.plugins.iframe._.getIframeAttributes(editor, iframeNode);
                } else {
                    // Mặc định attrs
                    iframeAttributes = {
                        sandbox: 'allow-scripts allow-same-origin',
                        allow: 'autoplay'
                    };
                }
                if (domData.viewer == 'google') {
                    iframeAttributes.src = 'https://docs.google.com/viewer?url=' + encodeURIComponent(domData.src) + '&embedded=true';
                } else {
                    iframeAttributes.src = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(domData.src);
                }
                iframeStyles.position = 'absolute';
                iframeStyles.width = '100%';
                iframeStyles.height = '100%';
                iframeStyles.top = '0px';
                iframeStyles.left = '0px';

                if (!domNode.hasClass('nv-docviewer')) {
                    domNode.addClass('nv-docviewer');
                }

                nodeStyles.position = 'relative';
                if (domData.renderMode == 'responsive') {
                    nodeStyles.width = '100%';
                    nodeStyles.height = '0px';
                    nodeStyles['padding-bottom'] = domData.ratioPercent + '%';

                    nodeAttributes.width = '100%';
                    nodeAttributes.height = '0px';
                    nodeAttributes['data-p'] = domData.ratioPercent;
                } else {
                    nodeStyles.width = domData.width + 'px';
                    nodeStyles.height = domData.height + 'px';
                    nodeStyles['padding-bottom'] = '0px';

                    nodeAttributes.width = domData.width + 'px';
                    nodeAttributes.height = domData.height + 'px';
                    nodeAttributes['data-p'] = 0;
                }

                iframeNode.setAttributes(iframeAttributes);
                iframeNode.setStyles(iframeStyles);
                domNode.setAttributes(nodeAttributes);
                domNode.setStyles(nodeStyles);

                var newFakeImage = editor.createFakeElement(domNode, 'cke-docviewer', 'docviewer', true);
                newFakeImage.setAttributes(nodeAttributes);
                newFakeImage.setStyles(nodeStyles);
                if (this.fakeImage) {
                    newFakeImage.replace(this.fakeImage);
                    editor.getSelection().selectElement(newFakeImage);
                } else {
                    editor.insertElement(newFakeImage);
                }
            },
            onShow: function() {
                this.fakeImage = this.domNode = null;

                var fakeImage = this.getSelectedElement();
                if (fakeImage && fakeImage.data('cke-real-element-type') && fakeImage.data('cke-real-element-type') == 'docviewer') {
                    this.fakeImage = fakeImage;

                    var domNode = editor.restoreRealElement(fakeImage);
                    this.domNode = domNode;
                    this.setupContent(domNode, editor);
                }
            },
            contents: [{
                // Document settings tab
                id: 'settingsTab',
                label: editor.lang.docviewer.settingsTab,
                elements: [{
                    type: 'vbox',
                    padding: 0,
                    children: [{
                        type: 'hbox',
                        widths: ['100%'],
                        children: srcBoxChildren
                    }]
                }, {
                    type: 'hbox',
                    widths: ['60%', '40%'],
                    children: [{
                        type: 'select',
                        id: 'txtViewer',
                        label: editor.lang.docviewer.viewer,
                        items: [
                            ['Google Docs', 'google'],
                            ['Microsoft Office', 'microsoft']
                        ],
                        'default': 'google',
                        setup: loadValue
                    }, {
                        type: 'select',
                        id: 'txtRenderMode',
                        label: editor.lang.docviewer.renderMode,
                        items: [
                            [editor.lang.docviewer.renderModeR, 'responsive'],
                            [editor.lang.docviewer.renderModeF, 'fixedsize']
                        ],
                        'default': 'responsive',
                        setup: loadValue
                    }]
                }, {
                    // options
                    type: 'hbox',
                    widths: ['33.3333%', '33.3333%', '33.3333%'],
                    className: 'docviewer',
                    children: [{
                        // width
                        type: 'text',
                        width: '45px',
                        id: 'txtWidth',
                        label: editor.lang.common.width,
                        'default': 710,
                        required: true,
                        validate: CKEDITOR.dialog.validate.integer(editor.lang.docviewer.alertWidth),
                        setup: loadValue
                    }, {
                        // height
                        type: 'text',
                        id: 'txtHeight',
                        width: '45px',
                        label: editor.lang.common.height,
                        'default': 920,
                        required: true,
                        validate: CKEDITOR.dialog.validate.integer(editor.lang.docviewer.alertHeight),
                        setup: loadValue
                    }, {
                        // Tỉ lệ
                        type: 'text',
                        id: 'txtRatio',
                        width: '45px',
                        label: editor.lang.docviewer.ratio,
                        'default': '1:2',
                        required: true,
                        setup: loadValue
                    }]
                }]
            }, {
                // upload tab
                id: 'uploadTab',
                label: editor.lang.docviewer.uploadTab,
                filebrowser: 'uploadButton',
                elements: [{
                    // file input
                    type: 'file',
                    id: 'upload'
                }, {
                    // submit button
                    type: 'fileButton',
                    id: 'uploadButton',
                    label: editor.lang.docviewer.btnUpload,
                    filebrowser: {
                        action: 'QuickUpload',
                        target: 'settingsTab:txtUrl'
                    },
                    'for': ['uploadTab', 'upload']
                }]
            }, {
                // info tab
                id: 'infoTab',
                label: editor.lang.docviewer.info,
                elements: [{
                    type: 'html',
                    html: '\
                        <p class="docviewer-p">- ' + editor.lang.docviewer.infoPlugin1 + '.</p>\
                        <p class="docviewer-p">- ' + editor.lang.docviewer.infoPlugin2 + '.</p>\
                        <p class="docviewer-p">- ' + editor.lang.docviewer.infoPlugin3 + '.</p>\
                        <p class="docviewer-p">- ' + editor.lang.docviewer.infoPlugin4 + '.</p>\
                    '
                }]
            }]
        };
    });
})();
