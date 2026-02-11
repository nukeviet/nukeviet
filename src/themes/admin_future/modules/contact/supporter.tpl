{if not empty($DEPARTMENT_LIST)}
<div class="table-responsive">
    <table class="table table-striped table-bordered list" data-url="{$OP_URL}" data-checkss="{$smarty.const.NV_CHECK_SESSION}">
        <thead>
            <tr>
                <th colspan="2" class="text-center">{$LANG->getModule('full_name')}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getGlobal('phonenumber')}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getGlobal('email')}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('active')}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getGlobal('actions')}</th>
            </tr>
        </thead>
        {foreach from=$DEPARTMENT_LIST item=department}
        <tbody>
            <tr>
                <td colspan="6" class="table-primary">
                    <i class="fa-regular fa-folder-open"></i>
                    {if $department.has_link}
                    <a href="{$department.href}"><strong>{$department.full_name}</strong></a>
                    {else}
                    <strong>{$department.full_name}</strong>
                    {/if}
                </td>
            </tr>
            {foreach from=$department.supporters item=supporter}
            <tr class="item" data-id="{$supporter.id}">
                <td class="text-nowrap align-middle" style="width: 80px;">
                    <select name="weight_{$supporter.id}" class="form-select form-select-sm supporter_cweight" data-default="{$supporter.weight}">
                        {for $i=1 to $department.max_weight}
                        <option value="{$i}"{if $supporter.weight == $i} selected{/if}>{$i|string_format:"%02d"}</option>
                        {/for}
                    </select>
                </td>
                <td class="align-middle">{$supporter.full_name}</td>
                <td class="text-nowrap align-middle" style="width: 1%;">{$supporter.phone}</td>
                <td class="text-nowrap align-middle" style="width: 1%;">{$supporter.email}</td>
                <td class="text-nowrap text-center align-middle" style="width: 1%;">
                    <div class="form-check form-switch d-inline-block">
                        <input type="checkbox" name="act_{$supporter.id}" class="form-check-input supporter_act" value="1"{if $supporter.is_active} checked{/if} />
                    </div>
                </td>
                <td class="text-nowrap text-center align-middle" style="width: 1%;">
                    <button type="button" class="btn btn-sm btn-secondary supporter_edit" title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}"><i class="fa-regular fa-pen-to-square"></i></button>
                    <button type="button" class="btn btn-sm btn-danger supporter_del" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-regular fa-trash-can"></i></button>
                </td>
            </tr>
            {/foreach}
        </tbody>
        {/foreach}
    </table>
</div>
{/if}
<div class="text-center">
    <button type="button" data-url="{$OP_URL}" class="btn btn-primary supporter_add{if $SHOW_FORM} auto{/if}">{$LANG->getModule('supporter_add')}</button>
</div>

<div class="modal fade" id="content" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
