<div class="accordion" id="accordion-settings">
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-settings" aria-expanded="true" aria-controls="collapse-settings">
                <span class="fs-5 fw-medium">{$LANG->getModule('general_settings')}</span>
            </button>
        </div>
        <div id="collapse-settings" class="accordion-collapse collapse show" data-bs-parent="#accordion-settings">
            <div class="accordion-body">
                <div class="alert alert-info" role="alert">{$LANG->getModule('not_apply_to_localhost')}</div>
                <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate data-callback="country_cdn_list_load">
                    <div class="row mb-3">
                        <label for="element_nv_static_url" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('static_url')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_nv_static_url" name="nv_static_url" value="{$DATA.nv_static_url}">
                            <div class="form-text">{$LANG->getModule('static_url_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('cdn_url')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <div class="cdn-list vstack gap-2">
                                {foreach from=$CDN_URLS key=key item=cdn}
                                <div class="item">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" data-toggle="cdn_default" id="element_toggle_cdn_default_{$key}"{if $cdn.is_default} checked{/if}>
                                                <label class="form-check-label" for="element_toggle_cdn_default_{$key}" data-toggle="cdn_default_lbl">{$LANG->getModule('default')}</label>
                                            </div>
                                        </span>
                                        <input type="text" name="cdn_url[]" value="{$cdn.val}" class="form-control" placeholder="{$LANG->getModule('url')}" aria-label="{$LANG->getModule('url')}">
                                        <button class="btn btn-secondary" type="button" data-toggle="add_cdn" title="{$LANG->getModule('add_cdn')}" aria-label="{$LANG->getModule('add_cdn')}"><i class="fa-solid fa-plus"></i></button>
                                        <button class="btn btn-secondary" type="button" data-toggle="remove_cdn" title="{$LANG->getModule('remove_cdn')}" aria-label="{$LANG->getModule('remove_cdn')}"><i class="fa-solid fa-xmark text-danger"></i></button>
                                    </div>
                                    <input type="hidden" name="cdn_countries[]" value="{$cdn.countries}">
                                    <input type="hidden" name="cdn_is_default[]" value="{$cdn.is_default}">
                                </div>
                                {/foreach}
                            </div>
                            <div class="form-text">{$LANG->getModule('cdn_notes')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="assets_cdn" value="1"{if $DATA.assets_cdn} checked{/if} id="element_assets_cdn">
                                <label class="form-check-label" for="element_assets_cdn">{$LANG->getModule('assets_cdn')}</label>
                            </div>
                            <div class="form-text">{$DATA.assets_cdn_note}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <input type="hidden" name="checkss" value="{$CHECKSS}">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-country-cdn" aria-expanded="false" aria-controls="collapse-country-cdn">
                <span class="fs-5 fw-medium">{$LANG->getModule('bycountry')}</span>
            </button>
        </div>
        <div id="collapse-country-cdn" class="accordion-collapse collapse" data-bs-parent="#accordion-settings" data-loaded="false" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;by_country=1">
            <div class="accordion-body">
                <i class="fa-solid fa-spinner fa-spin-pulse"></i> {$LANG->getGlobal('wait_page_load')}
            </div>
        </div>
    </div>
</div>
