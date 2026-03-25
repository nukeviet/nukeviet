<div class="card supporter-list" data-checkss="{$CHECKSS}" data-url="{$OP_URL}" data-auto-open="{if empty($DEPARTMENT_GROUPS)}1{else}0{/if}" data-department-url="{$DEPARTMENT_OP_URL}" data-department-checkss="{$DEPARTMENT_CHECKSS}">
    <div class="card-body">
        {if empty($DEPARTMENT_GROUPS)}
        <div class="alert alert-info mb-0">{$LANG->getModule('supporter_contact_add')}</div>
        {else}
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap text-center" style="width:10%">{$LANG->getModule('number')}</th>
                        <th class="text-nowrap" style="width:34%">{$LANG->getModule('full_name')}</th>
                        <th class="text-nowrap" style="width:18%">{$LANG->getGlobal('phonenumber')}</th>
                        <th class="text-nowrap" style="width:20%">{$LANG->getGlobal('email')}</th>
                        <th class="text-nowrap text-center" style="width:8%">{$LANG->getModule('active')}</th>
                        <th class="text-nowrap text-center" style="width:10%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                {foreach from=$DEPARTMENT_GROUPS item=department}
                <tbody>
                    <tr>
                        <td colspan="6" class="fw-semibold bg-light-subtle">
                            <i class="fa-solid fa-folder-open me-1"></i>
                            {if !empty($department.id)}
                            <a href="#" class="supporter_department_edit" data-id="{$department.id}">{$department.full_name}</a>
                            {else}
                            {$department.full_name}
                            {/if}
                        </td>
                    </tr>
                    {foreach from=$department.supporters item=row}
                    <tr class="item" data-id="{$row.id}">
                        <td class="text-center align-middle">
                            <select class="form-select form-select-sm supporter_cweight fw-75" name="weight" data-default="{$row.weight}" aria-label="{$LANG->getModule('number')}">
                                {foreach from=$row.weight_options item=option}
                                <option value="{$option.value}"{if $option.value == $row.weight} selected{/if}>{$option.title}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="align-middle">{$row.full_name}</td>
                        <td class="align-middle text-nowrap">{$row.phone}</td>
                        <td class="align-middle text-nowrap">{$row.email}</td>
                        <td class="text-center align-middle">
                            <div class="form-check form-switch d-inline-block m-0">
                                <input class="form-check-input supporter_act" type="checkbox" role="switch" value="1"{if $row.act} checked{/if} aria-label="{$LANG->getModule('active')}">
                            </div>
                        </td>
                        <td class="text-center align-middle text-nowrap">
                            <button type="button" class="btn btn-secondary btn-sm supporter_edit">
                                <i class="fa-solid fa-pencil" data-icon="fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </button>
                            <button type="button" class="btn btn-danger btn-sm supporter_del">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
                {/foreach}
            </table>
        </div>
        {/if}
    </div>
</div>

<div class="text-center mt-3">
    <button type="button" data-url="{$OP_URL}" class="btn btn-primary supporter_add{if empty($DEPARTMENT_GROUPS)} auto{/if}">
        <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('supporter_add')}
    </button>
</div>

<div class="modal fade" id="content" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="supporter_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-bg-primary">
                <h4 class="modal-title" id="supporter_modal_title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
