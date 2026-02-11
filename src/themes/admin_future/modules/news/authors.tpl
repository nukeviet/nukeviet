<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>

{if not empty($AUTHORS_LIST)}
<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap">{$LANG->getModule('author_pseudonym')}</th>
                        <th class="text-nowrap">{$LANG->getModule('author_uid')}</th>
                        <th class="text-nowrap text-center">{$LANG->getModule('author_add_time')}</th>
                        <th class="text-nowrap text-center">{$LANG->getModule('author_numnews')}</th>
                        <th class="text-nowrap text-center">{$LANG->getModule('author_status')}</th>
                        <th class="text-nowrap text-center">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$AUTHORS_LIST item=row}
                    <tr>
                        <td>
                            {if $row.numnews}
                            <a href="{$row.newslist_link}">{$row.pseudonym} ({$row.alias})</a>
                            {else}
                            {$row.pseudonym} ({$row.alias})
                            {/if}
                        </td>
                        <td>
                            <a href="{$row.account_link}" target="_blank">{$row.account} ({$row.email})</a>
                        </td>
                        <td class="text-center">{$row.add_time_format}</td>
                        <td class="text-center">{$row.numnews|nformat}</td>
                        <td class="text-center">
                            <select class="form-select form-select-sm w-auto" data-toggle="changeStatus" data-id="{$row.id}" name="status_{$row.id}">
                                <option value="0"{if not $row.active} selected{/if}>{$LANG->getModule('author_status_0')}</option>
                                <option value="1"{if $row.active} selected{/if}>{$LANG->getModule('author_status_1')}</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center flex-nowrap">
                                <a href="{$row.url_edit}" class="btn btn-sm btn-secondary" aria-label="{$LANG->getGlobal('edit')}"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                                {if $row.can_delete}
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="delAuthor" data-id="{$row.id}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if not empty($PAGINATION)}
    <div class="card-footer border-top">
        <div class="pagination-wrap">
            {$PAGINATION}
        </div>
    </div>
    {/if}
</div>
{/if}

<div class="card"{if $SCROLL_TO_EDIT} data-toggle="autoScroll"{/if}>
    <div class="card-header text-bg-primary rounded-top-2">
        <div class="fw-medium"><i class="fa-solid fa-user-pen"></i> {$DATA.title}</div>
    </div>
    <div class="card-body">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" class="ajax-submit">
            <input type="hidden" name="aid" value="{$DATA.aid}">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
            
            <div class="row mb-3">
                <label for="element_pseudonym" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('author_pseudonym')} <span class="text-danger">(*)</span></label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_pseudonym" name="pseudonym" value="{$DATA.pseudonym}" maxlength="100" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            
            {if not $IS_MY_AUTHOR}
            <div class="row mb-3">
                <label for="element_uid" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('author_uid')} <span class="text-danger">(*)</span></label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" name="uid" id="element_uid" data-placeholder="{$LANG->getModule('author_select_account')}">
                        {if not empty($DATA.uid)}
                        <option value="{$DATA.uid}" selected>{$DATA.u_account}</option>
                        {/if}
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            {else}
            <input type="hidden" name="uid" value="{$DATA.uid}">
            {/if}
            
            <div class="row mb-3">
                <label for="element_image" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('author_image')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" name="image" id="element_image" value="{$DATA.image}" autocomplete="off">
                        <button type="button" class="btn btn-secondary" data-toggle="selectfile" data-target="element_image" data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/authors" data-type="image" aria-label="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                    </div>
                    {if not empty($DATA.image)}
                    <div class="mt-2">
                        <img src="{$DATA.image}" width="100" class="img-thumbnail" alt="{$DATA.pseudonym}">
                    </div>
                    {/if}
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="element_description" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('author_description')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" id="element_description" name="description" rows="5" autocomplete="off">{$DATA.description}</textarea>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
