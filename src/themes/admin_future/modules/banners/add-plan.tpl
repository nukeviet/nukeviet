<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <label for="title" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('title')} <span class="text-danger">(*)</span></label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" name="title" id="title" value="{$ITEM.title}" maxlength="255" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('size')} <span class="text-danger">(*)</span></div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" class="form-control" name="width" id="width"
                               value="{$ITEM.width}" min="50" style="width:100px"
                               placeholder="{$LANG->getModule('width')}" autocomplete="off">
                        <span>&times;</span>
                        <input type="number" class="form-control" name="height" id="height"
                               value="{$ITEM.height}" min="50" style="width:100px"
                               placeholder="{$LANG->getModule('height')}" autocomplete="off">
                        <span>px</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="blang" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('blang')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select name="blang" id="blang" class="form-select">
                        <option value="">{$LANG->getModule('blang_all')}</option>
                        {foreach from=$ALLOW_LANGS key=lang_key item=lang_data}
                        <option value="{$lang_key}"{if $ITEM.blang == $lang_key} selected{/if}>{$lang_data.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end pt-0">{$LANG->getModule('form')}</div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$FORMS_LIST item=form_row}
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="form"
                               id="form_{$form_row.key}" value="{$form_row.key}"
                               {if $ITEM.form == $form_row.key} checked{/if}>
                        <label class="form-check-label" for="form_{$form_row.key}">{$form_row.title}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end pt-0">{$LANG->getModule('require_image')}</div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="require_image" id="require_image1" value="1"{if $ITEM.require_image == 1} checked{/if}>
                        <label class="form-check-label" for="require_image1">{$LANG->getModule('require_image1')}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="require_image" id="require_image0" value="0"{if $ITEM.require_image == 0} checked{/if}>
                        <label class="form-check-label" for="require_image0">{$LANG->getModule('require_image0')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end pt-0">{$LANG->getModule('uploadtype')}</div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$ARRAY_UPLOADTYPE item=uploadtype_item}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="uploadtype[]"
                               id="uploadtype_{$uploadtype_item}" value="{$uploadtype_item}"
                               {if in_array($uploadtype_item, $ITEM.uploadtype)} checked{/if}>
                        <label class="form-check-label" for="uploadtype_{$uploadtype_item}">{$uploadtype_item}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            {if not empty($UPLOADGROUP_LIST)}
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end pt-0">{$LANG->getModule('plan_uploadgroup')}</div>
                <div class="col-12 col-sm-8 col-lg-9">
                    <div class="row mt-1">
                        {foreach from=$UPLOADGROUP_LIST item=group_row}
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="uploadgroup[]"
                                       id="uploadgroup_{$group_row.id}" value="{$group_row.id}"
                                       {if in_array($group_row.id, $ITEM.uploadgroup)} checked{/if}>
                                <label class="form-check-label" for="uploadgroup_{$group_row.id}">{$group_row.title}</label>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <label for="plan_exp_time" class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('plan_exp_time')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select name="exp_time" id="plan_exp_time" class="form-select">
                        {foreach from=$EXP_TIME_LIST item=expt}
                        <option value="{$expt.key}"{if $ITEM.exp_time == $expt.key} selected{/if}>{$expt.title}</option>
                        {/foreach}
                    </select>
                    <div id="plan_exp_time_custom" class="mt-2{if $ITEM.exp_time != -1} d-none{/if}">
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="exp_time_custom" class="form-control"
                                   style="width:150px" min="0" step="0.1"
                                   value="{$ITEM.exp_time_custom}" autocomplete="off">
                            <span>{$LANG->getGlobal('day')}</span>
                        </div>
                    </div>
                    <div class="form-text">{$LANG->getModule('plan_exp_time_note')}</div>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-12 col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('description')}</div>
                <div class="col-12 col-sm-8">
                    {$DESCRIPTION nofilter}
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</form>
