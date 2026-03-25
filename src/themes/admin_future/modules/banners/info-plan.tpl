{* Card thông tin khối banner *}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fa-solid fa-circle-info"></i> {$ROW.caption}
        </h5>
        <div class="hstack gap-2">
            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=plan-content&amp;id={$ROW.id}"
               class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
            </a>
            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=banner-content&amp;pid={$ROW.id}"
               class="btn btn-sm btn-success">
                <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('admin_add_banner')}
            </a>
            <button type="button" class="btn btn-sm btn-warning"
                    data-toggle="change-act-plan"
                    data-id="{$ROW.id}"
                    data-tokend="{$CHECKSS}">
                <i class="fa-solid fa-toggle-on" data-icon="fa-toggle-on"></i> {$LANG->getModule('change_act')}
            </button>
            <button type="button" class="btn btn-sm btn-danger"
                    data-toggle="del-plan"
                    data-id="{$ROW.id}"
                    data-tokend="{$CHECKSS}"
                    data-redirect="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=plans-list"
                    data-msgconfirm="{$LANG->getModule('file_del_confirm')}">
                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
            </button>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('title')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.title}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('blang')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.blang_format}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('form')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.form_format}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('size')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.width} x {$ROW.height}px</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('is_act')}</div>
                <div class="col-sm-8 col-lg-9">
                    {if $ROW.act}
                        <span class="badge bg-success">{$LANG->getGlobal('yes')}</span>
                    {else}
                        <span class="badge bg-secondary">{$LANG->getGlobal('no')}</span>
                    {/if}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('require_image')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.require_image}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('uploadtype')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.uploadtype}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('plan_uploadgroup')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.uploadgroup}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('plan_exp_time')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.plan_exp_time}</div>
            </div>
        </li>
        {if not empty($ROW.description)}
        <li class="list-group-item">
            <div class="row g-0">
                <div class="col-sm-4 col-lg-3 text-muted fw-semibold">{$LANG->getModule('description')}</div>
                <div class="col-sm-8 col-lg-9">{$ROW.description}</div>
            </div>
        </li>
        {/if}
    </ul>
</div>

{* 5 ô trạng thái banner — click chuyển sang trang main với filter pid + act *}
<div class="mb-2 fw-semibold text-muted small text-uppercase">{$LANG->getModule('info_plan_banner_stat')}</div>
<div class="row row-cols-2 row-cols-md-5 g-2">
    {foreach item=card from=$STATUS_CARDS}
    <div class="col">
        <a href="{$card.url}" class="card text-decoration-none text-bg-{$card.color}">
            <div class="card-body text-center py-2 px-1">
                <div class="h4 fw-bold mb-0">{$card.count}</div>
                <div class="small text-truncate">{$card.title}</div>
            </div>
        </a>
    </div>
    {/foreach}
</div>
