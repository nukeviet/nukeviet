{* Template: editcensor.tpl
 * Giao diện admin_future cho khu vực Kiểm duyệt thông tin thành viên
 *}

{if $IS_FORUM}
<div class="alert alert-warning">{$LANG->getModule('modforum')}</div>
{/if}

<div class="mb-3">
    <form class="d-flex flex-wrap gap-2 align-items-center"
          action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"
          method="post" role="search">
        <span class="fw-bold">{$LANG->getModule('search_type')}:</span>
        <select class="form-select" style="width: auto;" name="method" id="f_method" aria-label="{$LANG->getModule('search_type')}">
            <option value="">---</option>
            {foreach from=$METHODS item=m}
            <option value="{$m.key}"{if $m.selected} selected{/if}>{$m.value}</option>
            {/foreach}
        </select>
        <input class="form-control" style="width: auto;" type="text" name="value" id="f_value" value="{$SEARCH_VALUE}" maxlength="64" autocomplete="off">
        <button type="submit" name="search" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('submit')}</button>
        <div class="form-text w-100">{$LANG->getModule('search_note')}</div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{$TABLE_CAPTION}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 5%;"><a href="{$HEAD_TDS.userid.href}">{$HEAD_TDS.userid.title}</a></th>
                        <th class="text-nowrap" style="width: 15%;"><a href="{$HEAD_TDS.username.href}">{$HEAD_TDS.username.title}</a></th>
                        <th class="text-nowrap" style="width: 20%;"><a href="{$HEAD_TDS.full_name.href}">{$HEAD_TDS.full_name.title}</a></th>
                        <th class="text-nowrap" style="width: 25%;"><a href="{$HEAD_TDS.email.href}">{$HEAD_TDS.email.title}</a></th>
                        <th class="text-nowrap" style="width: 15%;"><a href="{$HEAD_TDS.lastedit.href}">{$HEAD_TDS.lastedit.title}</a></th>
                        <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$USERS_LIST item=u}
                    <tr>
                        <td>{$u.userid}</td>
                        <td>
                            {if $u.allow}
                            <a href="{$u.view_link}"><strong>{$u.username}</strong></a>
                            {else}
                            <strong>{$u.username}</strong>
                            {/if}
                        </td>
                        <td>{$u.full_name}</td>
                        <td><a href="mailto:{$u.email}">{$u.email}</a></td>
                        <td class="text-nowrap">{$u.lastedit}</td>
                        <td class="text-center text-nowrap">
                            {if $u.allow}
                            <a href="{$u.view_link}" class="btn btn-sm btn-info" aria-label="{$LANG->getModule('check')}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-success"
                                    aria-label="{$LANG->getModule('approved')}"
                                    data-toggle="approve-censor"
                                    data-userid="{$u.userid}"
                                    data-tokend="{$u.checkss}"
                                    data-msgconfirm="{$LANG->getModule('editcensor_confirm_approval')}"
                                    data-icon="fa-check">
                                <i class="fa-solid fa-check" data-icon="fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger"
                                    aria-label="{$LANG->getModule('denied')}"
                                    data-toggle="deny-censor"
                                    data-userid="{$u.userid}"
                                    data-tokend="{$u.checkss}"
                                    data-msgconfirm="{$LANG->getModule('editcensor_confirm_denied')}"
                                    data-icon="fa-trash">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                            </button>
                            {/if}
                        </td>
                    </tr>
                    {foreachelse}
                    <tr><td colspan="6" class="text-center text-muted py-4">{$LANG->getModule('editcensor')}: 0</td></tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $GENERATE_PAGE}
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div></div>
            <div class="pagination-wrap">{$GENERATE_PAGE}</div>
        </div>
    </div>
    {/if}
</div>
