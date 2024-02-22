//  check whether jQuery is loaded
if (!window.jQuery) {
    //  if not - load jQuery
    var jq = document.createElement('script');
    jq.type = 'text/javascript';
    jq.src = 'http://code.jquery.com/jquery-2.0.3.min.js';
    document.getElementsByTagName('head')[0].appendChild(jq);
}

CKEDITOR.plugins.add('docviewer', {
    requires: 'dialog,fakeobjects',
    icons: 'docviewer',
    lang: 'en,vi,fr,ru',
    onLoad: function() {
        CKEDITOR.addCss('img.cke-docviewer' +
            '{' +
                'background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0NDggNTEyIj48IS0tIUZvbnQgQXdlc29tZSBGcmVlIDYuNS4xIGJ5IEBmb250YXdlc29tZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tIExpY2Vuc2UgLSBodHRwczovL2ZvbnRhd2Vzb21lLmNvbS9saWNlbnNlL2ZyZWUgQ29weXJpZ2h0IDIwMjQgRm9udGljb25zLCBJbmMuLS0+PHBhdGggZmlsbD0icmVkIiBkPSJNMTI5LjYgMTc2aDM5LjFjMS41LTI3IDYuNS01MS40IDE0LjItNzAuNC0yNy43IDEzLjItNDggMzkuMi01My4zIDcwLjR6bTAgMzJjNS4zIDMxLjIgMjUuNiA1Ny4yIDUzLjMgNzAuNC03LjctMTkuMS0xMi43LTQzLjQtMTQuMi03MC40aC0zOS4xek0yMjQgMjg2LjdjNy43LTcuNSAyMC44LTM0LjQgMjMuNC03OC43aC00Ni45YzIuNyA0NC4zIDE1LjggNzEuMiAyMy40IDc4Ljd6TTIwMC42IDE3Nmg0Ni45Yy0yLjctNDQuMy0xNS43LTcxLjItMjMuNC03OC43LTcuNyA3LjUtMjAuOCAzNC40LTIzLjQgNzguN3ptNjQuNSAxMDIuNGMyNy43LTEzLjIgNDgtMzkuMiA1My4zLTcwLjRoLTM5LjFjLTEuNSAyNy02LjUgNTEuNC0xNC4yIDcwLjR6TTQxNiAwSDY0QzI4LjcgMCAwIDI4LjcgMCA2NHYzODRjMCAzNS40IDI4LjcgNjQgNjQgNjRoMzUyYzE3LjcgMCAzMi0xNC4zIDMyLTMyVjMyYzAtMTcuNy0xNC4zLTMyLTMyLTMyem0tODAgNDE2SDExMmMtOC44IDAtMTYtNy4yLTE2LTE2czcuMi0xNiAxNi0xNmgyMjRjOC44IDAgMTYgNy4yIDE2IDE2cy03LjIgMTYtMTYgMTZ6bS0xMTItOTZjLTcwLjcgMC0xMjgtNTcuMy0xMjgtMTI4UzE1My4zIDY0IDIyNCA2NHMxMjggNTcuMyAxMjggMTI4LTU3LjMgMTI4LTEyOCAxMjh6bTQxLjEtMjE0LjRjNy43IDE5LjEgMTIuNyA0My40IDE0LjIgNzAuNGgzOS4xYy01LjMtMzEuMi0yNS42LTU3LjItNTMuMy03MC40eiIvPjwvc3ZnPg==");' +
                'background-position: center center;' +
                'background-repeat: no-repeat;' +
                'background-size: 20px 23px;' +
                'background-color: #f2f2f2;' +
                'border: 1px solid #ddd;' +
                'width: 80px;' +
                'height: 80px;' +
                'border-radius: 2px;' +
                'display: block;' +
                'margin-bottom: 7px;' +
            '}'
        );
    },
    init: function(editor) {
        editor.addCommand('docviewer', new CKEDITOR.dialogCommand('docviewer'));

        editor.ui.addButton('docviewer', {
            label: editor.lang.docviewer.button,
            command: 'docviewer',
            toolbar: 'insert'
        });

        CKEDITOR.document.appendStyleSheet(CKEDITOR.getUrl(this.path + 'dialogs/docviewer.css'));
        CKEDITOR.dialog.add('docviewer', this.path + 'dialogs/docviewer.js');

        editor.on('doubleclick', function(evt) {
            var element = evt.data.element;
            if (element.is('img') && element.data('cke-real-element-type') == 'docviewer') {
                evt.data.dialog = 'docviewer';
            }
        });

        if (editor.addMenuItems) {
            editor.addMenuItems({
                docviewer: {
                    label: editor.lang.docviewer.contextMenu,
                    command: 'docviewer',
                    group: 'image'
                }
            });
        }

        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element) {
                if (element && element.is('img') && element.data('cke-real-element-type') == 'docviewer') {
                    return {docviewer: CKEDITOR.TRISTATE_OFF};
                }
            });
        }
    },
    afterInit: function(editor) {
        // Xử lý chuyển từ div[class="nv-docviewer"] thành fakeelement khi chuyển từ chế độ HTML sang soạn thảo
        var dataProcessor = editor.dataProcessor,
            dataFilter = dataProcessor && dataProcessor.dataFilter;
        if (dataFilter) {
            dataFilter.addRules({
                elements: {
                    div: function(element) {
                        if (element.hasClass('nv-docviewer')) {
                            var nodeReplaced = editor.createFakeParserElement(element, 'cke-docviewer', 'docviewer', true);
                            var nodeStyle = element.attributes.style || '';
                            if (nodeStyle != '' && !nodeStyle.match(/\;[\s]*$/)) {
                                nodeStyle += ';';
                            }
                            if (!nodeStyle.match(/position[\s]*\:[\s]*relative/i)) {
                                nodeStyle += ' position: relative;';
                            }
                            if (typeof element.attributes['data-p'] != 'undefined' && element.attributes['data-p'] != 0) {
                                nodeStyle += ' padding-bottom: ' + element.attributes['data-p'] + '%;';
                            }
                            // fakeelement không hỗ trợ set attribute nên xử lý chuyển từ
                            // style element cũ sang fakeelement
                            nodeReplaced.attributes.style = nodeStyle;
                            return nodeReplaced;
                        }
                    }
                }
            });
        }
    }
});
