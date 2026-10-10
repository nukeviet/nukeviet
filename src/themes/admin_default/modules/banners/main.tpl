{* Form tìm kiếm *}
<form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php" class="row g-2 align-items-end mb-3">
    <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
    <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
    <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
    <div class="col-sm-4 col-lg-3">
        <input type="text" class="form-control" name="q" value="{$ARRAY_SEARCH.keyword}" placeholder="{$LANG->getModule('enter_keyword')}" autocomplete="off">
    </div>
    <div class="col-sm-4 col-lg-3">
        <select name="pid" class="form-select">
            <option value="0">{$LANG->getModule('all_plan')}</option>
            {foreach from=$ARRAY_PLANS item=plan}
            <option value="{$plan.id}"{if $plan.id eq $ARRAY_SEARCH.pid} selected{/if}>{$plan.title} ({$plan.blang_name})</option>
            {/foreach}
        </select>
    </div>
    <div class="col-sm-4 col-lg-3">
        <select name="act" class="form-select">
            <option value="-1">{$LANG->getModule('all_act')}</option>
            {foreach item=card from=$STATUS_CARDS}
            <option value="{$card.act}"{if $card.act eq $ARRAY_SEARCH.act} selected{/if}>{$card.title}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-sm-auto">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i> {$LANG->getGlobal('search')}
            </button>
            {if $ARRAY_SEARCH.is_filtered}
            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" class="btn btn-outline-secondary" aria-label="{$LANG->getGlobal('clear')}">
                <i class="fa-solid fa-xmark"></i>
            </a>
            {/if}
            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=banner-content" class="btn btn-success">
                <i class="fa-solid fa-plus-circle"></i> {$LANG->getGlobal('add')}
            </a>
        </div>
    </div>
</form>

{* 5 ô đếm trạng thái *}
<div class="row row-cols-2 row-cols-md-5 g-2 mb-3">
    {foreach item=card from=$STATUS_CARDS}
    <div class="col">
        <a href="{$card.url}" class="card text-decoration-none text-bg-{$card.color}{if $card.act eq $ARRAY_SEARCH.act} border border-3 border-dark border-opacity-25{/if}">
            <div class="card-body text-center py-2 px-1">
                <div class="h4 fw-bold mb-0">{$card.count}</div>
                <div class="small text-truncate">{$card.title}</div>
            </div>
        </a>
    </div>
    {/foreach}
</div>

{* Bảng danh sách *}
<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:25%;">{$LANG->getModule('title')}</th>
                        <th class="text-nowrap" style="width:18%;">{$LANG->getModule('in_plan')}</th>
                        <th class="text-nowrap" style="width:12%;">{$LANG->getModule('of_user')}</th>
                        <th class="text-nowrap" style="width:10%;">{$LANG->getModule('publ_date')}</th>
                        <th class="text-nowrap" style="width:10%;">{$LANG->getModule('exp_date')}</th>
                        <th class="text-nowrap text-center" style="width:10%;">{$LANG->getModule('is_act')}</th>
                        <th class="text-nowrap text-center" style="width:15%;">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>
                            <a href="{$row.view_url}">{$row.title}</a>
                            <div class="mt-1 small text-{$row.act_color}">{$row.act_label}</div>
                        </td>
                        <td>
                            <a href="{$row.pid_url}">{$row.pid_title}</a>
                        </td>
                        <td>
                            {if not empty($row.user)}
                                {if not empty($row.user.link)}
                                <a href="{$row.user.link}">{$row.user.username}</a>
                                {else}
                                {$row.user.username}
                                {/if}
                            {/if}
                        </td>
                        <td>{$row.publ_date}</td>
                        <td>{$row.exp_date}</td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox"
                                       id="banact_{$row.id}"
                                       {if $row.act eq 1} checked{/if}
                                       data-toggle="toggle-banner-act"
                                       data-id="{$row.id}"
                                       data-act="{$row.act}"
                                       data-checkss="{$CHECKSS}"
                                       {if $row.act eq 0}data-msgconfirm="{$LANG->getModule('banner_confirm_act0')}"{/if}
                                       {if $row.act eq 2}data-msgconfirm="{$LANG->getModule('banner_confirm_act2')}"{/if}
                                       aria-label="{$row.title}">
                            </div>
                        </td>
                        <td class="text-center text-nowrap">
                            <div class="hstack gap-1 justify-content-center">
                                <a href="{$row.edit_url}" class="btn btn-sm btn-secondary"
                                   aria-label="{$LANG->getGlobal('edit')}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                        aria-label="{$LANG->getGlobal('delete')}"
                                        data-toggle="del-banner"
                                        data-id="{$row.id}"
                                        data-checkss="{$CHECKSS}"
                                        data-msgconfirm="{$LANG->getModule('file_del_confirm')}">
                                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">{$LANG->getModule('banners_list_empty')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $PAGINATION}
    <div class="card-footer border-top">
        <div class="pagination-wrap">{$PAGINATION}</div>
    </div>
    {/if}
</div>
