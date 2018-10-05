{if isset($IS_ACTIVELANG)}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message">{$LANG->get('nv_setting_save')}</div>
</div>
<script>
setTimeout(function() {
    window.location = '{$URL_REDIRECT}';
}, 5000);
</script>
{/if}
{if isset($CONTENTS_SETUP)}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message">{$LANG->get('nv_data_setup_ok')}</div>
</div>
<script>
setTimeout(function() {
    window.location = '{$URL_REDIRECT}';
}, 5000);
</script>
{/if}
{if not empty($LANG_INSTALLED) and not isset($IS_ACTIVELANG) and not isset($CONTENTS_SETUP)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('lang_installed')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;">{$LANG->get('order')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('nv_lang_key')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('nv_lang_name')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('nv_lang_slsite')}</th>
                        <th style="width: 20%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$LANG_INSTALLED item=row}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs w70" id="change_weight_{$row.keylang}" onchange="nv_change_weight('{$row.keylang}');">
                                {for $key=1 to $LANG_INSTALLED_NUM}
                                <option value="{$key}"{if $key eq $row.weight} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </td>
                        <td class="text-nowrap">{$row.keylang}</td>
                        <td class="text-nowrap">{$row.name}</td>
                        <td class="text-nowrap">
                            {if $row.display lt 0}
                            {$LANG->get('site_lang')}
                            {else}
                            <select class="form-control form-control-xs mw100" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
                                <option value="{$row.url_yes}"{if $row.display eq 1} selected="selected"{/if}>{$LANG->get('yes')}</option>
                                <option value="{$row.url_no}"{if $row.display eq 0} selected="selected"{/if}>{$LANG->get('no')}</option>
                            </select>
                            {/if}
                        </td>
                        <td class="text-right text-nowrap">
                            {if not empty($row.delete)}
                            <a href="{$row.delete}" onclick="return confirm(nv_is_del_confirm[0]);" class="btn btn-sm btn-danger"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('nv_setup_delete')}</a>
                            {else}
                            {$LANG->get('nv_setup')}
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{if not empty($CAN_INSTALL) and not isset($CONTENTS_SETUP) and not isset($IS_ACTIVELANG)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('lang_can_install')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 30%;" class="text-nowrap">{$LANG->get('nv_lang_key')}</th>
                        <th style="width: 50%;" class="text-nowrap">{$LANG->get('nv_lang_name')}</th>
                        <th style="width: 20%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$CAN_INSTALL item=row}
                    <tr>
                        <td class="text-nowrap">{$row.keylang}</td>
                        <td class="text-nowrap">{$row.name}</td>
                        <td class="text-right text-nowrap">
                            {if not empty($row.setup)}
                            <a href="{$row.setup}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-cog"></i> {$LANG->get('nv_setup_new')}</a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
