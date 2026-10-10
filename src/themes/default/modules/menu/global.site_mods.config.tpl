<div class="row mb-3">
    <div class="col-sm-9 offset-sm-3">
        <div class="alert alert-info mb-0" role="alert">{$LANG->getModule('menu_note_auto')}</div>
    </div>
</div>
<div class="row mb-3">
    <label for="config_title_length" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-7">
        <input type="text" class="form-control" name="config_title_length" id="config_title_length" value="{$CONFIG.title_length}">
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('module_display')}:</label>
    <div class="col-sm-7">
        <ul id="config_module_in_menu" class="list-group">
            {foreach from=$MODS item=mod}
            <li class="list-group-item">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"{if $mod.checked} checked{/if} value="{$mod.name}" name="module_in_menu[]" id="module_in_menu_{$mod.name}">
                        <label class="form-check-label" for="module_in_menu_{$mod.name}">{$mod.title}</label>
                    </div>
                    <i class="fa-solid fa-sort"></i>
                </div>
            </li>
            {/foreach}
        </ul>
    </div>
</div>
<script>
$(function() {
    $('#config_module_in_menu').sortable().disableSelection();
});
</script>
