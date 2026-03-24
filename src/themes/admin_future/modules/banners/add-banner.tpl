<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

{if $UPLOAD_BLOCKED_MSG}
<div class="alert alert-danger">{$UPLOAD_BLOCKED_MSG}</div>
{else}
<form method="post" class="ajax-submit" novalidate enctype="multipart/form-data"
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <label for="title" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('title')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" name="title" id="title"
                           value="{$ITEM.title}" maxlength="255" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="pid" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('in_plan')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select name="pid" id="pid" class="form-select" data-toggle="change-plan">
                        {foreach from=$PLANS item=plan}
                        <option value="{$plan.key}"
                                data-require-image="{if $plan.require_image}true{else}false{/if}"
                                {if $ITEM.pid == $plan.key} selected{/if}>{$plan.title}</option>
                        {/foreach}
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('assign_to_user')}
                </div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="autosearch-user position-relative"
                         data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}"
                         data-checkss="{$CHECKSS_AJAX_USER}">
                        <div class="autosearch-input-wrap">
                            <input type="text" class="form-control" name="assign_user" id="assign_user"
                                   value="{$ITEM.assign_user}" autocomplete="off">
                            <span class="input-group-text d-none" id="autosearch-spinner">
                                <i class="fa-solid fa-spinner fa-spin-pulse"></i>
                            </span>
                        </div>
                        <div class="autosearch-result list-group position-absolute w-100 z-3 d-none"></div>
                    </div>
                    <div class="form-text">{$LANG->getModule('assign_to_user_tip')}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('upload', $FILE_ALLOWED_EXT)}
                    <span class="text-danger d-none" id="require_image_mark">(*)</span>
                </div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="file" class="form-control" name="banner" id="banner" accept="image/*">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="imageforswf" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('imageforswf')}
                </label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="file" class="form-control" name="imageforswf" id="imageforswf" accept="image/*">
                </div>
            </div>

            <div class="row mb-3">
                <label for="file_alt" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('file_alt')}
                </label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" name="file_alt" id="file_alt"
                           value="{$ITEM.file_alt}" maxlength="255" autocomplete="off">
                </div>
            </div>

            <div class="row mb-3">
                <label for="click_url" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('click_url')}
                </label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="url" class="form-control" name="click_url" id="click_url"
                           value="{$ITEM.click_url}" maxlength="255" autocomplete="url">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="target" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('target')}
                </label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select name="target" id="target" class="form-select">
                        {foreach from=$TARGETS key=tkey item=ttitle}
                        <option value="{$tkey}"{if $ITEM.target == $tkey} selected{/if}>{$ttitle}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('publ_date')}
                </div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="input-group flex-nowrap" style="width:auto">
                            <input type="text" name="publ_date" id="publ_date"
                                   value="{$ITEM.publ_date}" class="form-control datepicker"
                                   style="width:110px" readonly autocomplete="off">
                            <button class="btn btn-secondary" type="button" id="publ_date_btn">
                                <i class="fa-regular fa-calendar"></i>
                            </button>
                        </div>
                        <select class="form-select" name="publ_date_h" id="publ_date_h" style="width:80px">
                            {foreach from=$HOUR_OPTIONS item=h}
                            <option value="{$h.key}"{if $ITEM.publ_date_h == $h.key} selected{/if}>{$h.title}</option>
                            {/foreach}
                        </select>
                        <span>:</span>
                        <select class="form-select" name="publ_date_m" id="publ_date_m" style="width:80px">
                            {foreach from=$MIN_OPTIONS item=m}
                            <option value="{$m.key}"{if $ITEM.publ_date_m == $m.key} selected{/if}>{$m.title}</option>
                            {/foreach}
                        </select>
                        <a href="javascript:void(0)" class="text-muted"
                           data-toggle="delval"
                           data-target="#publ_date"
                           data-select="#publ_date_h,#publ_date_m">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    </div>
                    <div class="form-text">{$LANG->getModule('publ_time_info')}</div>
                </div>
            </div>

            <div class="row mb-3" id="exp_date_manual">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('exp_date')}
                </div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="input-group flex-nowrap" style="width:auto">
                            <input type="text" name="exp_date" id="exp_date"
                                   value="{$ITEM.exp_date}" class="form-control datepicker"
                                   style="width:110px" readonly autocomplete="off">
                            <button class="btn btn-secondary" type="button" id="exp_date_btn">
                                <i class="fa-regular fa-calendar"></i>
                            </button>
                        </div>
                        <select class="form-select" name="exp_date_h" id="exp_date_h" style="width:80px">
                            {foreach from=$HOUR_OPTIONS item=h}
                            <option value="{$h.key}"{if $ITEM.exp_date_h == $h.key} selected{/if}>{$h.title}</option>
                            {/foreach}
                        </select>
                        <span>:</span>
                        <select class="form-select" name="exp_date_m" id="exp_date_m" style="width:80px">
                            {foreach from=$MIN_OPTIONS item=m}
                            <option value="{$m.key}"{if $ITEM.exp_date_m == $m.key} selected{/if}>{$m.title}</option>
                            {/foreach}
                        </select>
                        <a href="javascript:void(0)" class="text-muted"
                           data-toggle="delval"
                           data-target="#exp_date"
                           data-select="#exp_date_h,#exp_date_m">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    </div>
                    <div class="form-text">{$LANG->getModule('exp_date_nochoose')}</div>
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('bannerhtml')}
                </div>
                <div class="col-12 col-sm-8">
                    {$BANNERHTML nofilter}
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</form>
{/if}
