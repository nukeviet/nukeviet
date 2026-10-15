<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{assign var="formAction" value="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"}
{function name=menuGroupItem group=[] idx=0}
<li class="list-group-item menu-group-item">
    <div class="row g-2">
        <div class="col-md-6 col-xl-3">
            <label for="menu-group-title-{$idx}" class="form-label fw-medium">{$LANG->getModule('menu_groups_title')}:</label>
            <input type="text" class="form-control required" id="menu-group-title-{$idx}" name="group_title[]" value="{$group.title}" maxlength="100">
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <label for="menu-group-icon-{$idx}" class="form-label fw-medium">{$LANG->getModule('menu_groups_icon')}:</label>
            <select class="form-select" id="menu-group-icon-{$idx}" name="group_icon[]" data-toggle="groupIcon" data-placeholder="{$LANG->getModule('menu_groups_icon_placeholder')}">
                <option value=""></option>
                {if not empty($group.icon)}
                <option value="{$group.icon}" selected>{$ICON_PACKS[$group.icon] ?? $group.icon}</option>
                {/if}
            </select>
        </div>
        <div class="col-xl-6">
            <label for="menu-group-mods-{$idx}" class="form-label fw-medium">{$LANG->getModule('menu_groups_mods')}:</label>
            <div class="hstack gap-1 align-items-start">
                <div class="flex-grow-1" style="min-width: 0;">
                    <select class="form-select" id="menu-group-mods-{$idx}" multiple data-toggle="groupMods" data-placeholder="{$LANG->getModule('menu_groups_mods_placeholder')}">
                        {foreach from=$MODS key=mod item=modtitle}
                        <option value="{$mod}"{if in_array($mod, $group.mods)} selected{/if}>{$modtitle}</option>
                        {/foreach}
                    </select>
                </div>
                <input type="hidden" name="group_mods[]" value="{$group.mods|join:','}">
                <button type="button" class="btn btn-secondary" data-toggle="moveMenuGroup" data-dir="up" title="{$LANG->getModule('menu_groups_move_up')}" aria-label="{$LANG->getModule('menu_groups_move_up')}"><i class="fa-solid fa-arrow-up"></i></button>
                <button type="button" class="btn btn-secondary" data-toggle="moveMenuGroup" data-dir="down" title="{$LANG->getModule('menu_groups_move_down')}" aria-label="{$LANG->getModule('menu_groups_move_down')}"><i class="fa-solid fa-arrow-down"></i></button>
                <button type="button" class="btn btn-secondary" data-toggle="delMenuGroup" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark text-danger"></i></button>
            </div>
        </div>
    </div>
</li>
{/function}
{if $smarty.const.NV_IS_SPADMIN}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link{if $SCOPE eq 'personal'} active" aria-current="page{/if}" href="{$formAction}">{$LANG->getModule('menu_groups_personal')}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{if $SCOPE eq 'default'} active" aria-current="page{/if}" href="{$formAction}&amp;scope=default">{$LANG->getModule('menu_groups_default')}</a>
    </li>
</ul>
{/if}
<div class="alert alert-info">
    {if $SCOPE eq 'default'}
    {$LANG->getModule('menu_groups_note_default', $LANG_DATA_NAME)}
    {else}
    {$LANG->getModule('menu_groups_note_personal', $LANG_DATA_NAME)}
    {if not $HAS_CONFIG}<div class="mt-1 fw-medium">{$LANG->getModule('menu_groups_using_default')}</div>{/if}
    {/if}
</div>
<form method="post" class="ajax-submit" id="form-menu-groups" action="{$formAction}&amp;scope={$SCOPE}" data-checkss="{$CHECKSS}" novalidate>
    <div class="card">
        <ul class="list-group list-group-flush" id="menu-groups-list">
            {foreach from=$GROUPS item=group}
            {call name=menuGroupItem group=$group idx=$group@index}
            {/foreach}
        </ul>
        <div class="card-body text-center text-muted" id="menu-groups-empty"{if not empty($GROUPS)} hidden{/if}>{$LANG->getModule('menu_groups_empty')}</div>
        <div class="card-footer border-top">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <input type="hidden" name="checkss" value="{$CHECKSS}">
                <button type="button" class="btn btn-secondary" data-toggle="addMenuGroup"><i class="fa-solid fa-plus"></i> {$LANG->getModule('menu_groups_add')}</button>
                <button type="submit" class="btn btn-primary">{$LANG->getModule('save')}</button>
                {if $HAS_CONFIG}
                <button type="button" class="btn btn-outline-danger" data-toggle="resetMenuGroups" data-url="{$formAction}&amp;scope={$SCOPE}" data-checkss="{$CHECKSS}" data-confirm="{$LANG->getModule($SCOPE == 'default' ? 'menu_groups_reset_default_confirm' : 'menu_groups_reset_personal_confirm')}"><i class="fa-solid fa-rotate-left"></i> {$LANG->getModule($SCOPE == 'default' ? 'menu_groups_reset_default' : 'menu_groups_reset_personal')}</button>
                {/if}
            </div>
        </div>
    </div>
</form>
<template id="menu-group-tpl">
    {call name=menuGroupItem group=['title' => '', 'icon' => '', 'mods' => []] idx='__IDX__'}
</template>
