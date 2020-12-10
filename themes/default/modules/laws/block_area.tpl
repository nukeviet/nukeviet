<!-- BEGIN: main -->
<div id="smoothmenu2-{CONFIG.bid}" class="ddsmoothmenu-v smoothmenu2">
    <ul>
        {CONTENT}
    </ul>
</div>
<script>
$(document).ready(function() {
    $('#smoothmenu2-{CONFIG.bid} .toggle-subs').on("click", function() {
        var submenu = $(this).parent().children('ul');
        if (submenu.is(":visible")) {
            submenu.slideUp(200);
        } else {
            submenu.slideDown(200);
        }
    });
});
</script>
<!-- END: main -->
