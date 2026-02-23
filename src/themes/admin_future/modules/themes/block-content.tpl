<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.NV_STATIC_URL}themes/{$TEMPLATE}/js/nv.block.content.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<form class="p-1 ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;selectthemes={$SELECTTHEMES}{if not empty($BLOCKREDIRECT)}&amp;blockredirect={$BLOCKREDIRECT}{/if}" id="block-content-form" data-page-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" data-selectthemes="{$SELECTTHEMES}" data-callback="nvBlockCtCallback" novalidate>
    <div class="card border-primary border-1 mb-3">
        <div class="card-header fs-5 fw-medium py-2 text-bg-primary">
            {$LANG->getModule('theme', "<span class=\"text-capitalize\">`$SELECTTHEMES`</span>")}
            <i class="fa-solid fa-angle-right mx-2"></i>
            {if empty($ROW.bid)}{$LANG->getModule('block_add')}{else}{$LANG->getModule('block_edit')}{/if}
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('of_module')}">{$LANG->getModule('of_module')}:</div>
                <div class="col-sm-9">
                    <div class="row g-2">
                        <div class="col-6">
                            <select name="module_type" class="form-select" aria-label="{$LANG->getModule('of_module')}">
                                <option value="">{$LANG->getModule('block_select_type')}</option>
                                <option value="theme"{if $ROW.module eq 'theme'} selected{/if}>{$LANG->getModule('block_type_theme')}</option>
                                {foreach from=$LIST_MODULES item=module}
                                <option value="{$module.value}"{if $module.value eq $ROW.module} selected{/if}>{$module.title}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="file_name" class="form-select" aria-label="{$LANG->getModule('block_select')}" data-default="{$LANG->getModule('block_select')}">
                                {$BLOCKLIST}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="block_config" class="d-none"></div>
            <div class="row mb-3">
                <label for="element_title" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('block_title')}">{$LANG->getModule('block_title')}:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="element_title" name="title" value="{$ROW.title}" maxlength="250">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_link" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('block_link')}">{$LANG->getModule('block_link')}:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="element_link" name="link" value="{$ROW.link}" maxlength="255">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_template" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('block_tpl')}">{$LANG->getModule('block_tpl')}:</label>
                <div class="col-9 col-sm-5">
                    <select class="form-select" id="element_template" name="template">
                        <option value="">{$LANG->getModule('block_default')}</option>
                        {foreach from=$LIST_TEMPLATES item=template}
                        {if not empty($template) and $template neq 'default'}
                        <option value="{$template}"{if $template eq $ROW.template} selected{/if}>{$template}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_position" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('block_pos')}">{$LANG->getModule('block_pos')}:</label>
                <div class="col-9 col-sm-5">
                    <select class="form-select select2" id="element_position" name="position">
                        {foreach from=$POSITIONS item=position}
                        <option value="{$position.tag}"{if $position.tag eq $ROW.position} selected{/if}>
                            {if empty($position.module)}
                                {$position.name}
                            {else}
                                {if isset($SITE_MODS[$position.module])}
                                    {$SITE_MODS[$position.module].custom_title}: {$position.name}
                                {else}
                                    {$position.module}: {$position.name}
                                {/if}
                            {/if}
                        </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_dtime_type" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('dtime_type')}">{$LANG->getModule('dtime_type')}:</label>
                <div class="col-9 col-sm-5">
                    <select class="form-select" id="element_dtime_type" name="dtime_type" data-current="{$ROW.dtime_type}">
                        {foreach from=$DTIME_TYPES item=key}
                        <option value="{$key}"{if $key eq $ROW.dtime_type} selected{/if}>{$LANG->getModule("dtime_type_`$key`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div id="dtime_details">{$DTIME_DETAILS}</div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('show_device')}">{$LANG->getModule('show_device')}:</div>
                <div class="col-sm-9">
                    {for $device=1 to 4}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="element_active_device_{$device}" name="active_device[]" value="{$device}"{if in_array($device, $ROW.active_device, true)} checked{/if}>
                        <label class="form-check-label" for="element_active_device_{$device}">{$LANG->getModule("show_device_`$device`")}</label>
                    </div>
                    {/for}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_bot_visible" name="bot_visible" value="1" role="switch"{if $ROW.bot_visible} checked{/if}>
                        <label class="form-check-label" for="switch_bot_visible">{$LANG->getModule('dtime_allow_bot')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('groups_view')}">{$LANG->getGlobal('groups_view')}:</div>
                <div class="col-9 col-sm-5">
                    <div data-nv-toggle="scroll" class="show-list-ugroup">
                        {foreach from=$GROUPS_LIST key=group_id item=group_title}
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="element_groups_view_{$group_id}" name="groups_view[]" value="{$group_id}"{if in_array($group_id, $ROW.groups_view)} checked{/if}>
                            <label class="form-check-label" for="element_groups_view_{$group_id}">{$group_title}</label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {if not empty($ROW.bid)}
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <div class="me-3"><i class="fa-solid fa-bell fa-3x"></i></div>
                <div>{$LANG->getModule('block_group_notice')}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-9 offset-sm-3">
                    <div>{$LANG->getModule('block_groupbl', $ROW.bid, $BLOCKS_NUM)}</div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="element_leavegroup" name="leavegroup" value="1" role="switch">
                        <label class="form-check-label" for="element_leavegroup">{$LANG->getModule('block_leavegroup')}</label>
                    </div>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium py-0" title="{$LANG->getModule('add_block_module')}">{$LANG->getModule('add_block_module')}:</div>
                <div class="col-sm-9">
                    <div class="form-check mb-sm-0 form-check-inline{if not $ROW.block_global} d-none{/if}" id="check_all_func_1">
                        <input class="form-check-input" type="radio" id="element_all_func_1" name="all_func" value="1"{if $ROW.all_func eq 1} checked{/if}>
                        <label class="form-check-label" for="element_all_func_1">{$LANG->getModule('add_block_all_module')}</label>
                    </div>
                    <div class="form-check mb-sm-0 form-check-inline" id="check_all_func_0">
                        <input class="form-check-input" type="radio" id="element_all_func_0" name="all_func" value="0"{if $ROW.all_func eq 0} checked{/if}>
                        <label class="form-check-label" for="element_all_func_0">{$LANG->getModule('add_block_select_module')}</label>
                    </div>
                </div>
            </div>
            <div id="shows_all_func"{if $ROW.all_func} class="d-none"{/if}>
                <table class="table table-bordered table-striped">
                    <thead class="align-middle">
                        <tr>
                            <th class="text-nowrap text-bg-secondary fw-125">
                                {$LANG->getModule('block_select_module')}
                            </th>
                            <th class="text-bg-secondary">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-truncate me-2">{$LANG->getModule('block_select_function')}</div>
                                    <button type="button" name="checkallmod" class="btn btn-secondary btn-sm text-nowrap"><i class="fa-solid fa-check-double"></i> {$LANG->getModule('block_checkall')}</button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$MOD_FUNCS key=m_title item=m_data}
                        <tr class="funclist" id="idmodule_{$m_title}">
                            <td>
                                <div class="form-check m-0">
                                    <input{if count($m_data.funcs) <= $m_data.func_checked} checked{/if} class="form-check-input checkmodule" type="checkbox" id="element_checkmodule_{$m_title}">
                                    <label class="form-check-label text-truncate-3" title="{$m_data.title}" for="element_checkmodule_{$m_title}">{$m_data.title}</label>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    {foreach from=$m_data.funcs key=func_id item=func_data}
                                    <div class="col-6 col-sm-3">
                                        <div class="form-check m-0">
                                            <input{if $func_data.checked} checked{/if} class="form-check-input" type="checkbox" id="element_func_id_{$m_title}_{$func_id}" name="func_id[]" value="{$func_id}">
                                            <label class="form-check-label d-block text-truncate" title="{$func_data.name}" for="element_func_id_{$m_title}_{$func_id}">{$func_data.name}</label>
                                        </div>
                                    </div>
                                    {/foreach}
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium py-0" title="{$LANG->getModule('status')}">{$LANG->getModule('status')}:</div>
                <div class="col-sm-9">
                    <div class="form-check mb-sm-0 form-check-inline">
                        <input class="form-check-input" type="radio" id="element_act_1" name="act" value="1"{if $ROW.act} checked{/if}>
                        <label class="form-check-label" for="element_act_1">{$LANG->getModule('act_1')}</label>
                    </div>
                    <div class="form-check mb-sm-0 form-check-inline">
                        <input class="form-check-input" type="radio" id="element_act_0" name="act" value="0"{if not $ROW.act} checked{/if}>
                        <label class="form-check-label" for="element_act_0">{$LANG->getModule('act_0')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_heading" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('block_heading')}">{$LANG->getModule('block_heading')}:</label>
                <div class="col-sm-9">
                    <select class="form-select w-auto mw-100" name="heading" id="element_heading">
                        {for $i=0 to 6}
                        <option value="{$i}"{if $ROW.heading eq $i} selected{/if}>{$LANG->getModule("block_heading_`$i`")}</option>
                        {/for}
                    </select>
                    <div class="form-text">{$LANG->getModule('block_heading_note')}.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-3 text-center">
            <input type="hidden" name="bid" value="{$ROW.bid}">
            <input type="hidden" name="checkss" value="{$ROW.checkss}">
            <button type="submit" name="confirm" value="1" class="btn btn-primary">{$LANG->getModule('block_confirm')}</button>
            <button data-toggle="closeWindow" type="button" class="btn btn-secondary">{$LANG->getModule('back')}</button>
        </div>
    </div>
</form>
