<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div id="module_show_list">
    {if $CAT_TITLE}
    <ol class="breadcrumb breadcrumb-catnav mb-3">
        {foreach from=$CAT_TITLE item=cat}
        {if $cat.active}
        <li class="breadcrumb-item active">{$cat.title}</li>
        {else}
        <li class="breadcrumb-item"><a href="{$cat.link nofilter}">{$cat.title}</a></li>
        {/if}
        {/foreach}
    </ol>
    {/if}

    {if $ROWS}
    <div class="card mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('weight')}</th>
                            <th class="text-nowrap" style="width:30%">{$LANG->getModule('name')}</th>
                            <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('numlinks')}</th>
                            <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('newday')}</th>
                            <th class="text-nowrap" style="width:25%">{$LANG->getModule('viewcat_page')}</th>
                            <th class="text-nowrap" style="width:25%">{$LANG->getModule('status')}</th>
                            <th class="text-center text-nowrap" style="width:5%">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$ROWS item=row}
                        <tr>
                            <td class="text-center">
                                {if $row.weight_can_change}
                                <button id="cat_weight_{$row.catid}"
                                        data-toggle="changecatnum"
                                        data-mod="weight"
                                        data-min="1"
                                        data-num="{$MAX_WEIGHT}"
                                        data-id="{$row.catid}"
                                        data-checkss="{$row.checkss}"
                                        data-current="{$row.weight}"
                                        type="button"
                                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1 justify-content-between btn-dropdown-tool fw-75 mx-auto">
                                    <span class="text">{$row.weight}</span><i class="fa-solid fa-caret-down"></i>
                                </button>
                                {else}
                                {$row.weight}
                                {/if}
                            </td>
                            <td>
                                <a href="{$row.link nofilter}"><strong>{$row.title}</strong>
                                {if $row.numsubcat}
                                <span class="text-danger ms-1">({$row.numsubcat})</span>
                                {/if}
                                </a>
                            </td>
                            <td class="text-center">
                                {if $row.numlinks_can_change}
                                <button id="cat_numlinks_{$row.catid}"
                                        data-toggle="changecatnum"
                                        data-mod="numlinks"
                                        data-min="0"
                                        data-num="20"
                                        data-id="{$row.catid}"
                                        data-checkss="{$row.checkss}"
                                        data-current="{$row.numlinks}"
                                        type="button"
                                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1 justify-content-between btn-dropdown-tool fw-75 mx-auto">
                                    <span class="text">{$row.numlinks}</span><i class="fa-solid fa-caret-down"></i>
                                </button>
                                {else}
                                {$row.numlinks}
                                {/if}
                            </td>
                            <td class="text-center">
                                {if $row.newday_can_change}
                                <button id="cat_newday_{$row.catid}"
                                        data-toggle="changecatnum"
                                        data-mod="newday"
                                        data-min="0"
                                        data-num="10"
                                        data-id="{$row.catid}"
                                        data-checkss="{$row.checkss}"
                                        data-current="{$row.newday}"
                                        type="button"
                                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1 justify-content-between btn-dropdown-tool fw-75 mx-auto">
                                    <span class="text">{$row.newday}</span><i class="fa-solid fa-caret-down"></i>
                                </button>
                                {else}
                                {$row.newday}
                                {/if}
                            </td>
                            <td>
                                {if $row.viewcat_can_change}
                                <a href="#" id="cat_viewcat_{$row.catid}"
                                        data-toggle="changecatnum"
                                        data-mod="viewcat"
                                        data-id="{$row.catid}"
                                        data-checkss="{$row.checkss}"
                                        data-current="{$row.viewcat_val}"
                                        data-source="cat_list_{$row.viewcat_mode}"
                                        type="button">
                                    <span class="text">{$row.viewcat_text}</span> <i class="fa-solid fa-caret-down"></i>
                                </a>
                                {else}
                                {$row.viewcat_text}
                                {/if}
                            </td>
                            <td>
                                {if $row.status_can_change}
                                <a href="#" id="cat_status_{$row.catid}"
                                        data-toggle="changecatnum"
                                        data-mod="status"
                                        data-id="{$row.catid}"
                                        data-checkss="{$row.checkss}"
                                        data-current="{$row.status_val}"
                                        data-source="cat_list_status"
                                        data-msgconfirm="{$LANG->getModule('cat_status_0_confirm')}"
                                        type="button">
                                    <span class="text">{$row.status_text}</span> <i class="fa-solid fa-caret-down"></i>
                                </a>
                                {else}
                                {$row.status_text}
                                {/if}
                            </td>
                            <td class="text-center text-nowrap">
                                {if isset($row.adminfuncs.add)}
                                <a href="{$row.adminfuncs.add}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="{$LANG->getModule('content_add')}" aria-label="{$LANG->getModule('content_add')}"><i class="fa-solid fa-plus"></i></a>
                                {/if}
                                {if isset($row.adminfuncs.edit)}
                                <a href="{$row.adminfuncs.edit}" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}"><i class="fa-solid fa-pencil"></i></a>
                                {/if}
                                {if isset($row.adminfuncs.delete)}
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="delete-cat" data-id="{$row.catid}" data-checkss="{$row.checkss}" data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i></button>
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}
</div>

<ul id="cat_list_full" class="d-none">
    {foreach from=$VIEWCAT_FULL key=k item=v}
    <li><a href="#" data-value="{$k}">{$v}</a></li>
    {/foreach}
</ul>
<ul id="cat_list_nosub" class="d-none">
    {foreach from=$VIEWCAT_NOSUB key=k item=v}
    <li><a href="#" data-value="{$k}">{$v}</a></li>
    {/foreach}
</ul>
<ul id="cat_list_status" class="d-none">
    {foreach from=$STATUS_LIST item=s}
    <li><a href="#" data-value="{$s.key}">{$s.value}</a></li>
    {/foreach}
</ul>

{if $HAS_CAT_LIST}
<form id="cat-form" method="post" class="ajax-submit" novalidate{if $IS_EDIT} data-is-edit="1"{/if}
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{$CAPTION}</h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="idtitle" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('name')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5" id="idtitle_parent">
                    <input type="text" class="form-control required" id="idtitle" name="title"
                           value="{$TITLE}" maxlength="250" autocomplete="off">
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
                               value="{$ALIAS}" maxlength="250" autocomplete="off">
                        <button type="button" class="btn btn-secondary"
                                data-toggle="refresh-alias"
                                data-catid="{$CATID}"
                                title="{$LANG->getGlobal('refresh')}"
                                aria-label="{$LANG->getGlobal('refresh')}">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="titlesite" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('titlesite')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="titlesite" name="titlesite"
                           value="{$TITLESITE}" maxlength="250" autocomplete="off">
                    <small class="text-muted">{$LANG->getGlobal('length_characters')}: <span id="titlesitelength">0</span>. {$LANG->getGlobal('title_suggest_max')}</small>
                </div>
            </div>
            <div class="row mb-3">
                <label for="parentid" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('cat_sub')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" name="parentid" id="parentid">
                        {foreach from=$CAT_LISTSUB item=item}
                        <option value="{$item.value}" {if $item.value == $PARENTID}selected{/if}>{$item.title nofilter}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="cat-keywords" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('keywords')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="cat-keywords" name="keywords"
                           value="{$KEYWORDS}" maxlength="250" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="description" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('description')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" id="description" name="description" rows="5">{$DESCRIPTION}</textarea>
                    <small class="text-muted">{$LANG->getGlobal('length_characters')}: <span id="descriptionlength">0</span>. {$LANG->getGlobal('description_suggest_max')}</small>
                </div>
            </div>
            <div class="row mb-3">
                <label for="cat-image" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('content_homeimg')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="cat-image" name="image"
                               value="{$IMAGE}" autocomplete="off">
                        <button type="button" class="btn btn-info"
                                data-toggle="selectfile"
                                data-target="cat-image"
                                data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}"
                                data-currentpath="{$UPLOAD_CURRENT}"
                                data-type="image"
                                title="{$LANG->getGlobal('browse_image')}"
                                aria-label="{$LANG->getGlobal('browse_image')}">
                            <i class="fa-solid fa-folder-open"></i>
                        </button>
                    </div>
                    {if $IMAGE}
                    <div class="mt-2">
                        <img src="{$IMAGE}" class="img-thumbnail" style="max-height:80px" alt="">
                    </div>
                    {/if}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 text-sm-end col-form-label">
                    <div class="form-label">{$LANG->getModule('viewcat_detail')}</div>
                </div>
                <div class="col-sm-8 col-lg-6 col-xxl-5 pt-sm-2">
                    {foreach from=$GROUPS_VIEWS item=item}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="groups_view[]"
                               id="gv_{$item.value}" value="{$item.value}"
                               {if $item.is_checked}checked{/if}>
                        <label class="form-check-label" for="gv_{$item.value}">{$item.title}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 text-sm-end col-form-label">
                    <div class="form-label">{$LANG->getModule('content_bodytext')}</div>
                </div>
                <div class="col-sm-9">
                    {$DESCRIPTIONHTML nofilter}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 text-sm-end col-form-label">
                    <div class="form-label">{$LANG->getModule('viewdescription')}</div>
                </div>
                <div class="col-sm-8 col-lg-7 pt-sm-2">
                    {foreach from=$VIEWDESCRIPTION_OPTIONS item=item}
                    <div class="form-check form-check-inline me-4">
                        <input class="form-check-input" type="radio" name="viewdescription"
                               id="vd_{$item.value}" value="{$item.value}"
                               {if $VIEWDESCRIPTION == $item.value}checked{/if}>
                        <label class="form-check-label" for="vd_{$item.value}">{$item.title}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            {if $IS_EDIT}
            <div class="row mb-3">
                <label for="featured" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('featured')}
                </label>
                <div class="col-sm-8 col-lg-5 col-xxl-4">
                    <select class="form-select" name="featured" id="featured">
                        <option value="0">{$LANG->getModule('not_featured')}</option>
                        {foreach from=$FEATURED_NEWS item=row}
                        <option value="{$row.id}" {if $FEATURED == $row.id}selected{/if}>{$row.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <div class="col-sm-3 text-sm-end col-form-label">
                    <div class="form-label">{$LANG->getModule('ad_block_show')}</div>
                </div>
                <div class="col-sm-8 col-lg-6 col-xxl-5 pt-sm-2">
                    {foreach from=$AD_BLOCK_CATS item=item}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ad_block_cat[]"
                               id="abl_{$item.value}" value="{$item.value}"
                               {if $item.is_checked}checked{/if}>
                        <label class="form-check-label" for="abl_{$item.value}">{$item.title}</label>
                    </div>
                    {/foreach}
                    {if $AD_BLOCK_NOTE}
                    <small class="text-muted">{$LANG->getModule('ad_block_note')}</small>
                    {/if}
                </div>
            </div>
            <div class="row">
                <label for="layout_func" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('cat_layout')}
                </label>
                <div class="col-sm-8 col-lg-4 col-xxl-3">
                    <select class="form-select" name="layout_func" id="layout_func">
                        <option value="">{$LANG->getModule('default_layout')}</option>
                        {foreach from=$LAYOUT_OPTS item=key}
                        <option value="{$key}" {if $LAYOUT_FUNC == $key}selected{/if}>{$key}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="savecat" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <input type="hidden" name="catid" value="{$CATID}">
            <input type="hidden" name="parentid_old" value="{$PARENTID}">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</form>
{/if}

<div class="modal fade" id="mdDelCat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{$LANG->getGlobal('delete')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
