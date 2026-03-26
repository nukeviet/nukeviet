<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('weight')}</th>
                        <th class="text-center text-nowrap" style="width:5%">ID</th>
                        <th class="text-nowrap" style="width:25%">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap" style="width:20%">{$LANG->getModule('adddefaultblock')}</th>
                        <th class="text-nowrap" style="width:10%">{$LANG->getModule('numlinks')}</th>
                        <th class="text-center text-nowrap" style="width:15%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$GROUPS item=row}
                    <tr>
                        <td class="text-center">
                            {if $NUM_GROUPS > 1}
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-toggle="change-group-weight"
                                    data-bid="{$row.bid}"
                                    data-current-weight="{$row.weight}"
                                    data-tokend="{$CHECKSS}"
                                    data-bs-title="{$LANG->getModule('change_weight')}">
                                {$row.weight}
                            </button>
                            {else}
                            {$row.weight}
                            {/if}
                        </td>
                        <td class="text-center"><strong>{$row.bid}</strong></td>
                        <td>
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=block&amp;bid={$row.bid}">
                                <strong>{$row.title}</strong>
                            </a>
                            <small class="text-muted">({$row.numnews} {$LANG->getModule('topic_num_news')})</small>
                        </td>
                        <td class="text-center">
                            <select class="form-select form-select-sm fw-100"
                                    name="adddefault"
                                    data-toggle="change-group-adddefault"
                                    data-bid="{$row.bid}"
                                    data-tokend="{$CHECKSS}">
                                <option value="0" {if $row.adddefault == 0}selected{/if}>{$LANG->getGlobal('no')}</option>
                                <option value="1" {if $row.adddefault == 1}selected{/if}>{$LANG->getGlobal('yes')}</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <select class="form-select form-select-sm fw-75"
                                    name="numbers"
                                    data-toggle="change-group-numlinks"
                                    data-bid="{$row.bid}"
                                    data-tokend="{$CHECKSS}">
                                {foreach from=$NUMLINKS_OPTIONS item=n}
                                <option value="{$n}" {if $row.numbers == $n}selected{/if}>{$n}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;bid={$row.bid}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-group"
                                    data-id="{$row.bid}"
                                    data-tokend="{$CHECKSS}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="group-form" method="post" class="ajax-submit" novalidate{if $IS_EDIT} data-is-edit="1"{/if}
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {if $IS_EDIT}{$LANG->getModule('edit_block_cat')}{else}{$LANG->getModule('add_block_cat')}{/if}
            </h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="idtitle" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('name')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" id="idtitle" name="title"
                           value="{$ITEM.title}" maxlength="250" autocomplete="off">
                    <div class="invalid-feedback"></div>
                    <small class="text-muted">{$LANG->getGlobal('length_characters')}: <span id="titlelength">0</span>. {$LANG->getGlobal('title_suggest_max')}</small>
                </div>
            </div>
            <div class="row mb-3">
                <label for="idalias" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('alias')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="idalias" name="alias"
                               value="{$ITEM.alias}" maxlength="250" autocomplete="off">
                        <button type="button" class="btn btn-secondary"
                                data-toggle="refresh-alias"
                                data-bid="{$ITEM.bid}"
                                title="{$LANG->getGlobal('refresh')}"
                                aria-label="{$LANG->getGlobal('refresh')}">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="group-keywords" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('keywords')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="group-keywords" name="keywords"
                           value="{$ITEM.keywords}" maxlength="255" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="group-description" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('description')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" id="group-description" name="description"
                              rows="4">{$ITEM.description}</textarea>
                    <small class="text-muted">{$LANG->getGlobal('length_characters')}: <span id="descriptionlength">0</span>. {$LANG->getGlobal('description_suggest_max')}</small>
                </div>
            </div>
            <div class="row mb-3">
                <label for="group-image" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('content_homeimg')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="group-image" name="image"
                               value="{$ITEM.image}" autocomplete="off">
                        <button type="button" class="btn btn-info"
                                data-toggle="selectfile"
                                data-target="group-image"
                                data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}"
                                data-currentpath="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}"
                                data-type="image"
                                title="{$LANG->getGlobal('browse_image')}"
                                aria-label="{$LANG->getGlobal('browse_image')}">
                            <i class="fa-solid fa-folder-open"></i>
                        </button>
                    </div>
                    {if $ITEM.image}
                    <div class="mt-2">
                        <img src="{$ITEM.image}" class="img-thumbnail" style="max-height:80px" alt="">
                    </div>
                    {/if}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="savecat" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="bid" value="{$ITEM.bid}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{if $NUM_GROUPS > 1}
<div id="group-weight-tpl" class="d-none">
    <div style="width:220px">
        <div class="input-group input-group-sm group-weight-item">
            <input type="number" class="form-control group-new-weight" min="1" max="{$NUM_GROUPS}" value="" name="newweight">
            <button type="button" class="btn btn-secondary group-weight-down" tabindex="-1"><i class="fa-solid fa-angle-down"></i></button>
            <button type="button" class="btn btn-secondary group-weight-up" tabindex="-1"><i class="fa-solid fa-angle-up"></i></button>
            <button type="button" class="btn btn-primary group-weight-ok" data-bid="" data-current-weight="">OK</button>
        </div>
        <div class="form-text mt-1">{$LANG->getModule('type_new_weight')} {$NUM_GROUPS}</div>
    </div>
</div>
{/if}
