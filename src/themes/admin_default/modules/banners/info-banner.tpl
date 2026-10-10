{* Nút hành động *}
<div class="d-flex flex-wrap gap-2 mb-3 justify-content-end">
    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=banner-content&amp;id={$BANNER_ID}" class="btn btn-secondary">
        <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
    </a>
    <button type="button" class="btn btn-warning"
            data-toggle="change-act-banner"
            data-id="{$BANNER_ID}"
            data-checkss="{$CHECKSS}"
            {if $ROW.act eq 0}data-msgconfirm="{$LANG->getModule('banner_confirm_act0')}"{/if}
            {if $ROW.act eq 2}data-msgconfirm="{$LANG->getModule('banner_confirm_act2')}"{/if}>
        <i class="fa-solid fa-toggle-on" data-icon="fa-toggle-on"></i> {$LANG->getModule('change_act')}
    </button>
    <button type="button" class="btn btn-danger"
            data-toggle="del-banner"
            data-id="{$BANNER_ID}"
            data-checkss="{$CHECKSS}"
            data-msgconfirm="{$LANG->getModule('file_del_confirm')}">
        <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
    </button>
</div>

{* Thông tin chi tiết banner *}
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('info_banner_caption', $ROW.title)}
        </h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">ID</div>
                <div class="col-sm-9"><span class="badge text-bg-primary">{$ROW.id}</span></div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('title')}</div>
                <div class="col-sm-9">{$ROW.title}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('in_plan')}</div>
                <div class="col-sm-9">
                    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=info-plan&amp;id={$PLAN.id}">{$PLAN.title} ({$PLAN.blang_name})</a>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('of_user')}</div>
                <div class="col-sm-9">
                    {if not empty($CL_USER)}
                        {if not empty($CL_USER.link)}
                        <a href="{$CL_USER.link}">{$CL_USER.username}</a>
                        {else}
                        {$CL_USER.username}
                        {/if}
                    {/if}
                </div>
            </div>
        </li>
        {if $ROW.file_ext neq 'no_image'}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('file_name')}</div>
                <div class="col-sm-9">
                    <a href="javascript:void(0)"
                       data-src="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_UPLOADS_DIR}/{$smarty.const.NV_BANNER_DIR}/{$ROW.file_name}"
                       class="open-modal-image">
                        {$LANG->getModule('click_show_img')}
                    </a>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('img_info1')}</div>
                <div class="col-sm-9">{$IMG_INFO}</div>
            </div>
        </li>
        {/if}
        {if not empty($ROW.imageforswf)}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('imageforswf')}</div>
                <div class="col-sm-9">
                    <a href="javascript:void(0)"
                       data-src="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_UPLOADS_DIR}/{$smarty.const.NV_BANNER_DIR}/{$ROW.imageforswf}"
                       class="open-modal-image">
                        {$LANG->getModule('click_show_img')}
                    </a>
                </div>
            </div>
        </li>
        {/if}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('file_alt')}</div>
                <div class="col-sm-9">{$ROW.file_alt}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('click_url')}</div>
                <div class="col-sm-9">
                    {if not empty($ROW.click_url)}
                    <a href="{$ROW.click_url}" target="_blank" rel="noopener noreferrer">{$ROW.click_url}</a>
                    {/if}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('target')}</div>
                <div class="col-sm-9">{$TARGETS[$ROW.target]}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('add_date')}</div>
                <div class="col-sm-9">{$ROW.add_time|ddatetime}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('publ_date')}</div>
                <div class="col-sm-9">{$ROW.publ_time|ddatetime}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('exp_date')}</div>
                <div class="col-sm-9">
                    {if $ROW.exp_time}
                    {$ROW.exp_time|ddatetime}
                    {else}
                    {$LANG->getModule('unlimited')}
                    {/if}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getGlobal('status')}</div>
                <div class="col-sm-9">{$LANG->getModule("act{$ROW.act}")}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3 fw-semibold text-muted">{$LANG->getModule('hits_total')}</div>
                <div class="col-sm-9">{$ROW.hits_total}</div>
            </div>
        </li>
    </ul>
</div>

{* Thống kê chi tiết *}
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">{$LANG->getModule('info_stat_caption')}</h5>
    </div>
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-sm-auto">
                <div class="form-label mb-0">{$LANG->getModule('please_select_month')}:</div>
            </div>
            <div class="col-sm-3">
                <select name="select_month" id="select_month" class="form-select">
                    {foreach from=$BYMONTH key=k item=v}
                    <option value="{$k}">{$v}</option>
                    {/foreach}
                </select>
            </div>
            <div class="col-sm-3">
                <select name="select_ext" id="select_ext" class="form-select">
                    {foreach from=$EXTS key=k item=v}
                    <option value="{$k}">{$v}</option>
                    {/foreach}
                </select>
            </div>
            <div class="col-sm-auto">
                <button type="button" id="btn-show-stat"
                        class="btn btn-primary"
                        data-toggle="show-banner-stat"
                        data-id="{$BANNER_ID}">
                    {$LANG->getModule('select')}
                </button>
            </div>
        </div>
    </div>
</div>

<div id="statistic" class="mb-3"></div>

{* Modal xem ảnh banner *}
<div class="modal fade" id="imagemodal" tabindex="-1" aria-labelledby="imagemodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagemodalLabel">{$LANG->getModule('file_name')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="imagemodal-body"></div>
        </div>
    </div>
</div>
