/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function formXSSsanitize(form) {
    $(form).find("input, textarea").not(":submit, :reset, :image, :file, :disabled").not('[data-sanitize-ignore]').each(function() {
        $(this).val(DOMPurify.sanitize($(this).val(), {}));
    });
}

$(function() {
    $('body').on('click', '[type=submit]:not([name])', function(e) {
        // Check if button is inside CKEditor UI
        // Selector matches elements with class starting with 'ck-' or containing ' ck-'
        // This covers all CKEditor 5 UI elements (dialogs, dropdowns, toolbars, etc.)
        if ($(this).closest('[class^="ck-"], [class*=" ck-"]').length) {
            return;
        }

        var form = $(this).parents('form');
        if (XSSsanitize && !$('[name=submit]', form).length) {
            // Khi không xử lý XSS thì trình submit mặc định sẽ thực hiện
            e.preventDefault();

            // Đưa CKEditor 5 trình soạn thảo vào textarea trước khi submit
            $(form).find("textarea").each(function() {
                if (this.dataset.editorname && window.nveditor && window.nveditor[this.dataset.editorname]) {
                    $(this).val(window.nveditor[this.dataset.editorname].getData());
                }
            });

            formXSSsanitize(form);
            $(form).submit();
        }
    });

    // Nút chia sẻ mạng xã hội
    $('body').on('click', '[data-toggle="nv-social-share"]', function(e) {
        e.preventDefault();
        const url = $(this).data('url') || window.location.href;
        const title = $(this).data('title') || document.title;
        let shareUrl = '';

        switch ($(this).data('platform')) {
            case 'facebook':
                shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
                break;
            case 'twitter':
                shareUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(url);
                break;
            case 'linkedin':
                shareUrl = 'https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title);
                break;
            case 'reddit':
                shareUrl = 'https://www.reddit.com/submit?url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title);
                break;
            case 'pocket':
                shareUrl = 'https://getpocket.com/save?url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title);
                break;
            default:
                return;
        }

        const options = 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0';
        window.open(shareUrl, '_blank', options);
    });
});
