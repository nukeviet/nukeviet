<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('googleplusNote1')}</div>
</div>
{if $NUMGOOGLEPLUS}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('googleplus_module')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;" class="text-nowrap">{$LANG->get('weight')}</th>
                        <th style="width: 30%;" class="text-nowrap">{$LANG->get('module')}</th>
                        <th style="width: 30%;" class="text-nowrap">{$LANG->get('custom_title')}</th>
                        <th style="width: 30%;" class="text-right text-nowrap">{$LANG->get('googleplus_title')}</th>
                    </tr>
                </thead>
                <tbody>
                    {assign var="stt" value="1"}
                    {foreach from=$SITE_MODS key=modname item=modinfo}
                    <tr>
                        <td>{$stt++}</td>
                        <td>{$modname}</td>
                        <td>{$modinfo.custom_title}</td>
                        <td class="text-right">
                            <select class="form-control form-control-xs mw200" id="id_mod_{$modname}" onchange="nv_mod_googleplus('{$modname}');">
                                <option value="">&nbsp;</option>
                                {foreach from=$GOOGLEPLUS key=gid item=row}
                                <option value="{$row.gid}"{if $modinfo.gid eq $row.gid} selected="selected"{/if}>{$row.title}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('googleplus_list')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-nowrap">{$LANG->get('weight')}</th>
                        <th style="width: 45%;" class="text-nowrap">{$LANG->get('googleplus_idprofile')}</th>
                        <th style="width: 45%;" class="text-nowrap">{$LANG->get('googleplus_title')}</th>
                        <th style="width: 5%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$GOOGLEPLUS key=gid item=row}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs w70" id="id_weight_{$row.gid}" onchange="nv_chang_googleplus({$row.gid});">
                                {for $key=1 to $NUMGOOGLEPLUS}
                                <option value="{$key}"{if $key eq $row.weight} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </td>
                        <td>{$row.idprofile}</td>
                        <td>
                            <input name="hidden_{$row.gid}" id="hidden_{$row.gid}" type="hidden" value="{$row.title}">
                            <div class="d-flex  align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control form-control-xs" id="title_{$row.gid}" name="title_{$row.gid}" value="{$row.title}" maxlength="255">
                                </div>
                                <div class="flex-grow-0 ml-2">
                                    <input type="button" onclick="nv_save_title({$row.gid});" value="{$LANG->get('save')}" class="btn btn-secondary">
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-danger" onclick="nv_del_googleplus({$row.gid});"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right text-nowrap">
                            {$LANG->get('googleplus_add')}
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" id="new_profile" name="new_profile" value="" placeholder="{$LANG->get('googleplus_idprofile')}" maxlength="255">
                        </td>
                        <td colspan="2">
                            <div class="d-flex  align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control form-control-sm" id="new_title" name="new_title" value="" placeholder="{$LANG->get('googleplus_title')}" maxlength="255">
                                </div>
                                <div class="flex-grow-0 ml-2">
                                    <input name="Button1" class="btn btn-primary btn-input-sm" type="button" value="{$LANG->get('submit')}" onclick="nv_add_googleplus();" />
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div role="alert" class="alert alert-primary alert-icon alert-icon-colored alert-dismissible">
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        {$LANG->get('googleplusNote2')}
    </div>
</div>
