<!-- BEGIN: main -->
<div class="form-group">
    <label for="nvchoosetheme{CONFIG.bid}">{GLANG.selecttheme}:</label>
    <select class="form-control" data-toggle="nvchoosetheme" data-tokend="{TOKEND}" id="nvchoosetheme{CONFIG.bid}">
        <!-- BEGIN: loop -->
        <option value="{USER_THEME.key}" {USER_THEME.selected}>{USER_THEME.title}</option>
        <!-- END: loop -->
        <option value="">{GLANG.default_theme}</option>
    </select>
</div>
<!-- END: main -->