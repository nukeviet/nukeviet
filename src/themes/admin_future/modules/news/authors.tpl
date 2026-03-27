<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:24%">{$LANG->getModule('author_pseudonym')}</th>
                        <th class="text-nowrap" style="width:30%">{$LANG->getModule('author_uid')}</th>
                        <th class="text-center text-nowrap" style="width:14%">{$LANG->getModule('author_add_time')}</th>
                        <th class="text-center text-nowrap" style="width:10%">{$LANG->getModule('author_numnews')}</th>
                        <th class="text-center text-nowrap" style="width:8%">{$LANG->getModule('author_status')}</th>
                        <th class="text-center text-nowrap" style="width:14%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROWS item=row}
                    <tr>
                        <td>
                            {if $row.has_news}
                            <a href="{$row.newslist_link}"><strong>{$row.pseudonym}</strong> ({$row.alias})</a>
                            {else}
                            <strong>{$row.pseudonym}</strong> ({$row.alias})
                            {/if}
                        </td>
                        <td>
                            {if $row.account_link}
                            <a href="{$row.account_link}" target="_blank" rel="noopener noreferrer">{$row.account} ({$row.email})</a>
                            {elseif $row.account}
                            {$row.account}{if $row.email} ({$row.email}){/if}
                            {else}
                            {$LANG->getGlobal('unknown')}
                            {/if}
                        </td>
                        <td class="text-center text-nowrap">{$row.add_time_format}</td>
                        <td class="text-center text-nowrap fw-medium">{$row.numnews}</td>
                        <td class="text-center text-nowrap">
                            <div class="form-check form-switch d-inline-block m-0">
                                <input type="checkbox" class="form-check-input"
                                       role="switch"
                                       data-toggle="change-author-status"
                                       data-id="{$row.id}"
                                       data-tokend="{$CHECKSS}"
                                       aria-label="{$row.pseudonym}"
                                       {if $row.is_active}checked{/if}>
                            </div>
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{$row.url_edit}" class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            {if $row.can_delete}
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-author"
                                    data-id="{$row.id}"
                                    data-tokend="{$CHECKSS}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                            {/if}
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

<form id="author-form" method="post" class="ajax-submit" novalidate{if $IS_EDIT} data-is-edit="1"{/if}
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {if $IS_EDIT}{$LANG->getModule('edit_author')}{else}{$LANG->getModule('add_author')}{/if}
            </h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="author_pseudonym" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('author_pseudonym')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" id="author_pseudonym" name="pseudonym"
                           value="{$ITEM.pseudonym}" maxlength="100" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            {if $CAN_CHANGE_UID}
            <div class="row mb-3">
                <label for="author_uid" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('author_uid')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" name="uid" id="author_uid" data-placeholder="{$LANG->getModule('author_select_account')}">
                        {if $ITEM.uid}
                        <option value="{$ITEM.uid}" selected>{$ITEM.u_account}</option>
                        {/if}
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            {else}
            <div class="row mb-3">
                <div class="col-sm-3 text-sm-end col-form-label">
                    <div class="form-label">{$LANG->getModule('author_uid')}</div>
                </div>
                <div class="col-sm-8 col-lg-6 col-xxl-5 pt-sm-2">
                    {if $ITEM.u_account}{$ITEM.u_account}{else}{$LANG->getGlobal('unknown')}{/if}
                    <input type="hidden" name="uid" value="{$ITEM.uid}">
                </div>
            </div>
            {/if}

            <div class="row mb-3">
                <label for="author_image" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('author_image')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="author_image" name="image"
                               value="{$ITEM.image}" autocomplete="off">
                        <button type="button" class="btn btn-info"
                                data-toggle="selectfile"
                                data-target="author_image"
                                data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/authors"
                                data-currentpath="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/authors"
                                data-type="image"
                                title="{$LANG->getGlobal('browse_image')}"
                                aria-label="{$LANG->getGlobal('browse_image')}">
                            <i class="fa-solid fa-folder-open"></i>
                        </button>
                    </div>
                    {if $ITEM.image}
                    <div class="mt-2">
                        <img src="{$ITEM.image}" class="img-thumbnail" style="max-height:100px" alt="{$ITEM.pseudonym}">
                    </div>
                    {/if}
                </div>
            </div>

            <div class="row mb-3">
                <label for="author_description" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('author_description')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" id="author_description" name="description" rows="5">{$ITEM.description}</textarea>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="save" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="aid" value="{$ITEM.aid}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
