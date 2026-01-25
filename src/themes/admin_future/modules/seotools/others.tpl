<div class="card">
    <div class="card-body">
        <div class="card-title fs-5 fw-medium">
            {$LANG->getModule('strdata')}
            <a href="https://developers.google.com/search/docs/appearance/structured-data/search-gallery" target="_blank" title="{$LANG->getModule('more_information')}" aria-label="{$LANG->getModule('more_information')}"><i class="fa-solid fa-circle-question"></i></a>
        </div>
        <form class="mt-4" id="strdata" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input autosubmit" id="element_sitelinks_search_box_schema" type="checkbox" role="switch" name="sitelinks_search_box_schema" value="1"{if not empty($GCONFIG.sitelinks_search_box_schema)} checked{/if}>
                <label class="form-check-label" for="element_sitelinks_search_box_schema">
                    {$LANG->getModule('add_sitelinks_search_box_schema')}
                    <a href="https://developers.google.com/search/docs/appearance/structured-data/sitelinks-searchbox" target="_blank" title="{$LANG->getModule('more_information')}" aria-label="{$LANG->getModule('more_information')}"><i class="fa-solid fa-circle-question"></i></a>
                </label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input autosubmit" id="element_breadcrumblist" type="checkbox" role="switch" name="breadcrumblist" value="1"{if not empty($GCONFIG.breadcrumblist)} checked{/if}>
                <label class="form-check-label" for="element_breadcrumblist">
                    {$LANG->getModule('strdata_breadcrumblist')}
                    <a href="https://developers.google.com/search/docs/appearance/structured-data/breadcrumb" target="_blank" title="{$LANG->getModule('more_information')}" aria-label="{$LANG->getModule('more_information')}"><i class="fa-solid fa-circle-question"></i></a>
                </label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input autosubmit" id="element_localbusiness" type="checkbox" role="switch" name="localbusiness" value="1"{if not empty($GCONFIG.localbusiness) and file_exists("{$smarty.const.NV_ROOTDIR}/{$smarty.const.NV_DATADIR}/localbusiness.json")} checked{/if}>
                <label class="form-check-label" for="element_localbusiness">
                    {$LANG->getModule('strdata_localbusiness')}
                    <a href="https://developers.google.com/search/docs/appearance/structured-data/local-business" target="_blank" title="{$LANG->getModule('more_information')}" aria-label="{$LANG->getModule('more_information')}"><i class="fa-solid fa-circle-question"></i></a>
                    (<a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;localbusiness_information=1">{$LANG->getModule('localbusiness_information')}</a>)
                </label>
            </div>
            <div>
                {$LANG->getModule('strdata_organization')}:
                <a href="https://developers.google.com/search/docs/appearance/structured-data/logo" target="_blank" title="{$LANG->getModule('more_information')}" aria-label="{$LANG->getModule('more_information')}"><i class="fa-solid fa-circle-question"></i></a>
                <div class="form-text">{$LANG->getModule('strdata_organization_logo_guidelines')}</div>
                <div class="mt-3">
                    <img src="{if not empty($GCONFIG.organization_logo)}{$smarty.const.NV_BASE_SITEURL}{$GCONFIG.organization_logo}{else}{$smarty.const.ASSETS_STATIC_URL}/images/no-photo.svg{/if}" data-default="{$smarty.const.ASSETS_STATIC_URL}/images/no-photo.svg" class="p-1 border rounded-2 bg-body-tertiary" id="organization_logo" alt="{$LANG->getModule('strdata_organization')}" role="button" width="112" height="112">
                </div>
                {if not empty($GCONFIG.organization_logo)}
                <div class="mt-3">
                    <button type="button" id="organization_logo_del" class="btn btn-danger"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                </div>
                {/if}
            </div>
            <input type="hidden" name="checkss" value="{$CHECKSS}">
        </form>
    </div>
</div>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/cropper/cropper.min.js"></script>
<script src="{$smarty.const.NV_STATIC_URL}themes/{$TEMPLATE}/js/seotools.logo.js"></script>
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/cropper/cropper.min.css">
<script>
    UAV.config.maxsize = {$smarty.const.NV_UPLOAD_MAX_FILESIZE};
    UAV.config.img_width = 112;
    UAV.config.img_height = 112;
    UAV.lang.bigsize = '{$LANG->getModule('bigsize')}';
    UAV.lang.smallsize = '{$LANG->getModule('smallsize')}';
    UAV.lang.filetype = '{$LANG->getModule('allowed_type')}';
    UAV.lang.bigfile = '{$LANG->getModule('bigfile')}';
    UAV.lang.upload = '{$LANG->getModule('change_logo_upload')}';
</script>
<div class="modal fade" id="mdUploadLogo" tabindex="-1" aria-labelledby="mdUploadLogoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="mdUploadLogoLabel">{$LANG->getModule('strdata_organization')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <form class="modal-body" id="upload-form" target="upload-form-listener" method="post" enctype="multipart/form-data" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;logoupload=1">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div id="organlogo-picker">
                            <div class="inner-picker position-absolute bg-body-tertiary rounded-1 p-3 d-flex align-items-center justify-content-center">
                                <a id="upload_icon" href="#" class="rounded-circle d-inline-flex align-items-center justify-content-center text-bg-primary">
                                    <i class="fa-solid fa-upload fa-3x"></i>
                                </a>
                            </div>
                        </div>
                        <img id="preview" class="d-none w-100">
                    </div>
                    <div class="col-lg-4">
                        <h5>{$LANG->getModule('change_logo')}</h5>
                        <div class="p-2 bg-info-subtle rounded-2" id="guide">
                            <div class="mb-2"><strong>{$LANG->getModule('change_logo_guide')}:</strong></div>
                            <div>- {$LANG->getModule('change_logo_chosen')}</div>
                            <div>- {$LANG->getModule('change_logo_upload')}</div>
                        </div>
                        <div class="d-none" id="uploadInfo">
                            <div>- {$LANG->getModule('filesize')}: <span id="image-size"></span></div>
                            <div>- {$LANG->getModule('filetype')}: <span id="image-type"></span></div>
                            <div>- {$LANG->getModule('filedimension')}: <span id="original-dimension"></span></div>
                            <div>- {$LANG->getModule('displaydimension')}: <span id="display-dimension"></span></div>
                            <div class="mt-3">
                                <button id="btn-submit" type="submit" class="btn btn-primary btn-sm">{$LANG->getModule('crop')}</button>
                                <button id="btn-reset" type="button" class="btn btn-secondary btn-sm">{$LANG->getModule('chosen_other')}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="crop_x" name="crop_x">
                <input type="hidden" id="crop_y" name="crop_y">
                <input type="hidden" id="crop_width" name="crop_width">
                <input type="hidden" id="crop_height" name="crop_height">
                <input type="file" name="image_file" id="image_file" class="d-none" accept=".jpg,.jpeg,.png,.webp">
            </form>
            <iframe id="upload-form-listener" name="upload-form-listener" class="d-none"></iframe>
        </div>
    </div>
</div>
