<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">
                {if $EDIT_CATID}{$LANG->getModule('cat_edit')}{else}{$LANG->getModule('cat_add')}{/if}
            </h5>
            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=cat" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-times"></i> {$LANG->getGlobal('cancel')}
            </a>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- FORM THÊM/SỬA CHUYÊN MỤC -->
    <div class="col-lg-5">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-body">
                <form id="form-cat-content" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}{if $EDIT_CATID}&amp;catid={$EDIT_CATID}{/if}" novalidate class="ajax-submit">
                    <div class="mb-3">
                        <label for="cat_title" class="form-label">{$LANG->getModule('cat_title')} <span class="text-danger">(*)</span>:</label>
                        <div class="position-relative">
                            <input type="text" class="form-control required" id="cat_title" name="title" value="{if $EDIT_DATA}{$EDIT_DATA.title}{/if}" maxlength="250" data-catid="{$EDIT_CATID}" data-checkss="{$CHECKSS}">
                            <div class="invalid-tooltip">{$LANG->getModule('cat_empty_title')}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cat_alias" class="form-label">{$LANG->getModule('alias')}:</label>
                        <input type="text" class="form-control" id="cat_alias" name="alias" value="{if $EDIT_DATA}{$EDIT_DATA.alias}{/if}" maxlength="250">
                    </div>
                    <div class="mb-3">
                        <label for="cat_description" class="form-label">{$LANG->getModule('description')}:</label>
                        <textarea class="form-control" id="cat_description" name="description" rows="4">{if $EDIT_DATA}{$EDIT_DATA.description}{/if}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cat_keywords" class="form-label">{$LANG->getModule('keywords')}:</label>
                        <input type="text" class="form-control" id="cat_keywords" name="keywords" value="{if $EDIT_DATA}{$EDIT_DATA.keywords}{/if}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{$LANG->getModule('status')}:</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" id="cat_status"{if !$EDIT_DATA or $EDIT_DATA.status} checked{/if}>
                            <label class="form-check-label" for="cat_status">{$LANG->getModule('active')}</label>
                        </div>
                    </div>
                    <input type="hidden" name="save" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <div class="hstack gap-2 justify-content-center mt-3">
                        <button class="btn btn-primary" type="submit">{$LANG->getModule('cat_save')}</button>
                        {if $EDIT_CATID}
                        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=cat" class="btn btn-outline-secondary">{$LANG->getGlobal('cancel')}</a>
                        {/if}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DANH SÁCH CHUYÊN MỤC -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                {if $NUM_CATS > 0}
                <div class="table-responsive-lg table-card pb-1">
                    <table class="table table-striped align-middle table-sticky mb-0">
                        <thead>
                            <tr>
                                <th class="text-nowrap" style="width: 8%;">{$LANG->getModule('order')}</th>
                                <th class="text-nowrap">{$LANG->getModule('cat_title')}</th>
                                <th class="text-nowrap" style="width: 8%;">{$LANG->getModule('active')}</th>
                                <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('feature')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$DATA key=key item=cat}
                            <tr>
                                <td>
                                    <select aria-label="{$LANG->getModule('order')}" data-toggle="changeWeiCat" data-checkss="{$cat.checkss}" data-catid="{$cat.catid}" name="change_weight_{$cat.catid}" id="change_weight_cat_{$cat.catid}" class="form-select form-select-sm fw-75">
                                        {for $weight=1 to count($DATA)}
                                        <option value="{$weight}"{if $weight eq $cat.weight} selected{/if}>{$weight}</option>
                                        {/for}
                                    </select>
                                </td>
                                <td>
                                    <a href="{$cat.url_edit}">{$cat.title}</a>
                                    {if $cat.description}
                                    <div class="small text-muted mt-1">{$cat.description|truncate:80:'...'}</div>
                                    {/if}
                                </td>
                                <td class="text-center form-switch">
                                    <div class="d-inline-flex">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getModule('active')}" data-toggle="changeCatActive" data-checkss="{$cat.checkss}" data-catid="{$cat.catid}" name="change_status_cat_{$cat.catid}" id="change_status_cat_{$cat.catid}" {if $cat.status == 1} checked{/if}/>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="hstack gap-1">
                                        <a href="{$cat.url_edit}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></a>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="nv_del_cat" data-checkss="{$cat.checkss}" data-catid="{$cat.catid}"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                {else}
                <p class="text-muted text-center my-4">{$LANG->getGlobal('no_data')}</p>
                {/if}
            </div>
        </div>
    </div>
</div>

