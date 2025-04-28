<div class="alert alert-info" role="alert">
    <p class="mb-1">{$LANG->getModule('thumb_note')}</p>
    <p class="mb-1">- {$LANG->getModule('thumb_default_size_note')}</p>
    <p class="mb-0">- {$LANG->getModule('thumb_dir_size_note')}</p>
</div>
<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead class="text-muted">
                        <tr class="text-center">
                            <th>{$LANG->getModule('thumb_dir')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('thumb_type')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('thumb_width_height')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('thumb_quality')}</th>
                            <th style="width: 1%;">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$CONFIGURED_DIRS item=dir}
                        <tr class="text-center item" data-did="d{$dir.did}">
                            <td class="text-start text-break"><strong>{$dir.dirname}</strong></td>
                            <td>
                                <select name="thumb_type[{$dir.did}]" class="form-select fw-250">
                                    {for $i=1 to 5}
                                    <option value="{$i}"{if $i eq $dir.thumb_type} selected{/if}>{$LANG->getModule("thumb_type_`$i`")}</option>
                                    {/for}
                                </select>
                            </td>
                            <td>
                                <div class="hstack gap-1 align-items-center">
                                    <input class="form-control text-center fw-75" type="number" min="1" max="1000" value="{$dir.thumb_width}" name="thumb_width[{$dir.did}]">
                                    <div>x</div>
                                    <input class="form-control text-center fw-75" type="number" min="1" max="1000" value="{$dir.thumb_height}" name="thumb_height[{$dir.did}]">
                                </div>
                            </td>
                            <td>
                                <select name="thumb_quality[{$dir.did}]" class="form-select fw-75">
                                    {for $i=4 to 20}
                                    {assign var='y' value=($i * 5) nocache}
                                    <option value="{$y}"{if $y eq $dir.thumb_quality} selected{/if}>{$y}</option>
                                    {/for}
                                </select>
                            </td>
                            <td class="text-start">
                                <div class="hstack gap-2">
                                    <button type="button" data-toggle="thumbCfgViewEx" data-did="{$dir.did}" data-errmsg="{$LANG->getModule('prViewExampleError')}" class="btn btn-secondary text-nowrap"><i class="fa-solid fa-magnifying-glass" data-icon="fa-magnifying-glass"></i> {$LANG->getModule('prViewExample')}</button>
                                    {if not empty($dir.did)}
                                    <button type="button" class="btn btn-secondary text-nowrap" data-toggle="remove_config" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark"></i></button>
                                    {/if}
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                        <tr class="text-center item">
                            <td>
                                <select name="other_dir" class="form-select">
                                    <option value=""> ---- </option>
                                    {foreach from=$OTHER_DIRS item=dir}
                                    <option value="{$dir.did}">{$dir.dirname}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td>
                                <select name="other_type" class="form-select fw-250">
                                    {for $i=1 to 5}
                                    <option value="{$i}"{if $i eq 3} selected{/if}>{$LANG->getModule("thumb_type_`$i`")}</option>
                                    {/for}
                                </select>
                            </td>
                            <td>
                                <div class="hstack gap-1 align-items-center">
                                    <input class="form-control text-center fw-75" type="number" min="1" max="1000" type="number" value="300" name="other_thumb_width">
                                    <div>x</div>
                                    <input class="form-control text-center fw-75" type="number" min="1" max="1000" type="number" value="300" name="other_thumb_height">
                                </div>
                            </td>
                            <td>
                                <select name="other_thumb_quality" class="form-select fw-75">
                                    {for $i=4 to 20}
                                    {assign var='y' value=($i * 5) nocache}
                                    <option value="{$y}"{if $y eq 90} selected{/if}>{$y}</option>
                                    {/for}
                                </select>
                            </td>
                            <td class="text-start">
                                <button type="button" data-toggle="thumbCfgViewEx" data-did="-1" data-errmsg="{$LANG->getModule('prViewExampleError')}" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass" data-icon="fa-magnifying-glass"></i> {$LANG->getModule('prViewExample')}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
            <button type="submit" class="btn btn-primary">{$LANG->getModule('pubdate')}</button>
        </div>
    </div>
</form>
<div class="modal fade" id="thumbnail-preview" tabindex="-1" aria-labelledby="thumbnail-preview-lbl" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-md-down modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="thumbnail-preview-lbl">{$LANG->getModule('prViewExample')}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6 text-md-end">
                        <div class="fw-medium fs-5 mb-2">{$LANG->getModule('original_image')}</div>
                        <img src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" class="img-fluid imgorg">
                    </div>
                    <div class="col-md-6">
                        <div class="fw-medium fs-5 mb-2">{$LANG->getModule('thumb_image')}</div>
                        <img src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" class="img-fluid imgthumb">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
