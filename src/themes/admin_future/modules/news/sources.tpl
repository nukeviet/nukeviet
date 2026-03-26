<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap" style="width:40%">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap" style="width:40%">{$LANG->getModule('link')}</th>
                        <th class="text-center text-nowrap" style="width:15%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$SOURCES item=row}
                    <tr>
                        <td class="text-center">
                            <select name="weight_{$row.sourceid}" class="form-select form-select-sm fw-75"
                                    data-toggle="change-source-weight"
                                    data-id="{$row.sourceid}"
                                    data-tokend="{$CHECKSS}">
                                {for $w=1 to $NUM_SOURCES}
                                <option value="{$w}"{if $w == $row.weight} selected{/if}>{$w}</option>
                                {/for}
                            </select>
                        </td>
                        <td><strong>{$row.title}</strong></td>
                        <td>{$row.link}</td>
                        <td class="text-center text-nowrap">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;sourceid={$row.sourceid}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-source"
                                    data-id="{$row.sourceid}"
                                    data-tokend="{$CHECKSS}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $PAGINATION}
    <div class="card-footer border-top">
        <div class="d-flex justify-content-end">
            <div class="pagination-wrap">
                {$PAGINATION}
            </div>
        </div>
    </div>
    {/if}
</div>

<form id="source-form" method="post" class="ajax-submit" novalidate{if $IS_EDIT} data-is-edit="1"{/if}
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {if $IS_EDIT}{$LANG->getModule('edit_sources')}{else}{$LANG->getModule('add_sources')}{/if}
            </h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="source_title" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('name')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" id="source_title" name="title"
                           value="{$ITEM.title}" maxlength="250" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="source_link" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('link')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="url" class="form-control" id="source_link" name="link"
                           value="{$ITEM.link}" maxlength="255" autocomplete="url">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="source_logo" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('source_logo')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="source_logo" name="logo"
                               value="{$ITEM.logo}" autocomplete="off">
                        <button type="button" class="btn btn-info"
                                data-toggle="selectfile"
                                data-target="source_logo"
                                data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/source"
                                data-currentpath="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/source"
                                data-type="image"
                                title="{$LANG->getGlobal('browse_image')}"
                                aria-label="{$LANG->getGlobal('browse_image')}">
                            <i class="fa-solid fa-folder-open"></i>
                        </button>
                    </div>
                    {if $ITEM.logo}
                    <div class="mt-2">
                        <img src="{$ITEM.logo}" class="img-thumbnail" style="max-height:80px" alt="">
                    </div>
                    {/if}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="savecat" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="sourceid" value="{$ITEM.sourceid}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
