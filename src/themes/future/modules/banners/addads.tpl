{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li class="nav-item"><a class="nav-link active" href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
<form id="form-addads" action="{$FORM_ACTION}" method="post" enctype="multipart/form-data" role="form" data-toggle="ajax-form" data-precheck="nv_precheck_form" novalidate{$CAPTCHA_ATTRS}>
    <div class="mb-3 row">
        <label for="banner_plan" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('plan_title')} <span class="text-danger">(*)</span>:</label>
        <div class="col-md-9">
            <select name="block" id="banner_plan" class="form-select">
                {foreach from=$PLANS item=blockitem}
                <option value="{$blockitem.id}" data-image="{$blockitem.typeimage}" data-uploadtype="{$blockitem.uploadtype}"{if $CURRENT_PLAN.id == $blockitem.id} selected{/if}>{$blockitem.title}</option>
                {/foreach}
            </select>
            <div class="invalid-feedback">{$LANG->getModule('plan_wrong_selected')}</div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="title" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('addads_title')} <span class="text-danger">(*)</span>:</label>
        <div class="col-md-9 position-relative">
            <input class="form-control" type="text" name="title" id="title" value="" minlength="3" maxlength="240" data-valid>
            <div class="invalid-feedback">{$LANG->getModule('title_empty')}</div>
        </div>
    </div>
    <div id="banner_uploadimage" data-area="banner-upload-box"{if !$CURRENT_PLAN.typeimage} class="d-none"{/if}>
        <div class="mb-3 row">
            <label for="image" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('addads_adsdata')}<span class="text-danger{if !$CURRENT_PLAN.typeimage} d-none{/if}" data-area="required-file"> (*)</span>:</label>
            <div class="col-md-9">
                <input type="file" name="image" id="image" class="form-control file" data-area="image-input"{if $CURRENT_PLAN.typeimage} data-valid{/if}>
                <div class="invalid-feedback">{$LANG->getModule('file_upload_empty')}</div>
                <div id="banner_uploadtype" class="form-text">{$LANG->getModule('banner_uploadtype', {$smarty.const.NV_MAX_WIDTH}, {$smarty.const.NV_MAX_HEIGHT})}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="description" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('addads_description')}:</label>
            <div class="col-md-9">
                <input type="text" name="description" id="description" class="form-control" maxlength="240">
            </div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="url" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('addads_url')}<span class="text-danger {if $CURRENT_PLAN.typeimage} d-none{/if}" data-area="required-url"> (*)</span>:</label>
        <div class="col-md-9 position-relative">
            <input class="url form-control" type="text" name="url" id="url" value="" data-area="url-input" minlength="10" maxlength="240"{if !$CURRENT_PLAN.typeimage} data-valid{/if}>
            <div class="invalid-feedback">{$LANG->getModule('click_url_invalid')}</div>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-md-9 offset-md-3">
            <input type="hidden" name="confirm" value="1">
            <button type="submit" class="btn btn-primary">{$LANG->getModule('add_banner')}</button>
        </div>
    </div>
</form>
