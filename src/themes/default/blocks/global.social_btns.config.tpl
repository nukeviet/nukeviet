<script>
$(function() {
    $("body").on("click", ".social-add", function() {
        var t = $(this).parents(".social-item"),
            a = t.clone();
        $("input", a).attr("value", "").val("");
        t.after(a);
    });
    $("body").on("click", ".social-del", function() {
        var t = $(this).parents(".social-btns"),
            a = $(this).parents(".social-item");
        $(".social-item", t).length > 1 ? a.remove() : $("input", a).attr("value", "").val("");
    });
});
</script>
<div class="social-btns">
    {foreach from=$NAMES key=key item=name}
    <div class="row g-2 mb-3 social-item">
        <div class="col-sm-3">
            <div class="input-group">
                <button class="btn btn-secondary social-add" type="button" aria-label="{$LANG->getGlobal('add')}"><i class="fa-solid fa-plus"></i></button>
                <button class="btn btn-secondary social-del" type="button" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark"></i></button>
                <input type="text" name="social_name[]" class="form-control" value="{$name}" placeholder="{$LANG->getModule('social_name')}">
            </div>
        </div>
        <div class="col-sm-5">
            <div>
                <input type="text" name="social_url[]" class="form-control" value="{$URLS[$key]}" placeholder="{$LANG->getModule('social_url')}">
            </div>
        </div>
        <div class="col-sm-2">
            <div>
                <input type="text" name="social_icon[]" class="form-control" value="{$ICONS[$key]}" placeholder="{$LANG->getModule('social_icon')}">
            </div>
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <span class="input-group-text">#</span>
                <input type="text" name="social_color[]" class="form-control" value="{$COLORS[$key]}" placeholder="{$LANG->getModule('social_color')}">
            </div>
        </div>
    </div>
    {/foreach}
</div>
