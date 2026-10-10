<div class="row mb-3">
    <label for="config_menuid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('menu')}:</label>
    <div class="col-sm-5">
        <select name="menuid" id="config_menuid" class="form-select">
            {foreach from=$MENUS item=menu}
            <option value="{$menu.id}" {if $DATA.menuid eq $menu.id}selected{/if}>{$menu.title}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="config_title_length" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="config_title_length" id="config_title_length" value="{$DATA.title_length}">
        <div class="form-text">{$LANG->getModule('title_length_note')}</div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-9 offset-sm-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="config_show_home" id="config_show_home" value="1"{if not empty($DATA.show_home)} checked{/if}>
            <label for="config_show_home" class="form-check-label">{$LANG->getModule('show_home')}</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="config_show_icon" id="config_show_icon" value="1"{if not empty($DATA.show_icon)} checked{/if}>
            <label for="config_show_icon" class="form-check-label">{$LANG->getModule('show_icon')}</label>
        </div>
    </div>
</div>
