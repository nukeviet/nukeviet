<div class="card mb-4">
    <div class="card-body">
        <div class="row g-1">
            <div class="col-auto">
                <a class="btn btn-{(!$COMPLETE and !$INCOMPLETE) ? 'primary' : 'secondary'}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">{$LANG->getModule('tags_all_link')}</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-{$COMPLETE ? 'primary' : 'secondary'}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;complete=1">{$LANG->getModule('tags_complete_link')}</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-{$INCOMPLETE ? 'primary' : 'secondary'}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;incomplete=1">{$LANG->getModule('tags_incomplete_link')}</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary" href="#" data-toggle="add_tags" data-fc="addTag" data-mtitle="{$LANG->getModule('add_tags')}" data-tid="0">{$LANG->getModule('add_tags')}</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#mdTagMulti">{$LANG->getModule('add_multiple_tags')}</a>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <input type="hidden" name="incomplete" value="{$INCOMPLETE}">
            <input type="hidden" name="complete" value="{$COMPLETE}">
            <div class="d-flex align-items-end flex-wrap justify-content-between g-3">
                <div>
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="{$Q}" maxlength="64" placeholder="{$LANG->getModule('search_key')}" aria-label="{$LANG->getModule('search_key')}">
                        <button type="submit" class="btn btn-primary text-nowrap"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('search')}</button>
                    </div>
                </div>
                <div>
                    {if $COMPLETE}{$LANG->getModule('tags_complete_link')}{elseif $INCOMPLETE}{$LANG->getModule('tags_incomplete_link')}{else}{$LANG->getModule('tags_all_link')}{/if}: <strong class="text-danger">{$NUM_ITEMS|nv_number_format}</strong>
                </div>
            </div>
        </form>
    </div>
    {if not empty($DATA)}
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-news-items">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" data-toggle="checkAll" data-type="tag" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width: 38%;">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('description')}</th>
                        <th class="text-nowrap" style="width: 40%;">{$LANG->getModule('keywords')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA item=row}
                    <tr>
                        <td>
                            <input type="checkbox" data-toggle="checkSingle" data-type="tag" value="{$row.tid}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td>
                            <a href="{$row.link}" target="_blank">{$row.title}</a>
                        </td>
                        <td class="text-center">
                            {if empty($row.description)}
                            <i class="fa-solid fa-triangle-exclamation text-danger" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('tags_no_description')}"></i>
                            {else}
                            <i class="fa-solid fa-check text-success"></i>
                            {/if}
                        </td>
                        <td>{$row.keywords}</td>
                        <td>
                            <div class="row g-1 flex-nowrap">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary btn-sm"{if empty($row.numnews)} disabled{/if} data-toggle="link_tags" data-tid="{$row.tid}"><i class="fa-solid fa-tags" data-icon="fa-tags"></i> {$LANG->getModule('tag_links')}: <strong>{$row.numnews|nv_number_format}</strong></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="add_tags" data-fc="editTag" data-mtitle="{$LANG->getModule('edit_tags')}" data-tid="{$row.tid}"><i class="fa-solid fa-pen" data-icon="fa-pen"></i> {$LANG->getGlobal('edit')}</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="nv_del_tag" data-tid="{$row.tid}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkAll" data-type="tag" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <div class="input-group me-1 my-1">
                    <button type="button" class="btn btn-danger" data-toggle="nv_del_check_tags"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                </div>
            </div>
            <div class="pagination-wrap">
                {$PAGINATION}
            </div>
        </div>
    </div>
    {/if}
</div>

<div class="modal fade" id="mdTagMulti" tabindex="-1" aria-labelledby="mdTagMultiLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="mdTagMultiLabel">{$LANG->getModule('add_multiple_tags')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" class="ajax-submit">
                    <input name="savetag" type="hidden" value="1">
                    <div class="mb-3">
                        <label for="element_mtag_mtitle" class="form-label">{$LANG->getModule('note_tags')}:</label>
                        <textarea class="form-control" name="mtitle" id="element_mtag_mtitle" rows="5" maxlength="2000"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk"></i> {$LANG->getModule('save')}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdTagSingle" tabindex="-1" aria-labelledby="mdTagSingleLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="mdTagSingleLabel"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" class="ajax-submit">
                    <input name="savecat" type="hidden" value="1">
                    <input name="tid" type="hidden" value="0">
                    <div class="row mb-3">
                        <label for="element_stag_keywords" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('keywords')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8">
                            <input type="text" class="form-control" id="element_stag_keywords" name="keywords" value="" maxlength="250">
                            <div class="invalid-feedback">{$LANG->getModule('error_tag_keywords')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_stag_title" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('name')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8">
                            <input type="text" class="form-control" id="element_stag_title" name="title" value="" maxlength="65">
                            <div class="invalid-feedback">{$LANG->getModule('error_tag_title')}</div>
                            <div class="form-text">{$LANG->getGlobal('length_characters')}: <span data-toggle="titlelength" class="text-danger">0</span>. {$LANG->getGlobal('title_suggest_max')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_stag_description" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('description')}</label>
                        <div class="col-12 col-sm-8">
                            <textarea class="form-control" id="element_stag_description" name="description" rows="5"></textarea>
                            <div class="form-text">{$LANG->getGlobal('length_characters')}: <span data-toggle="descriptionlength" class="text-danger">0</span>. {$LANG->getGlobal('description_suggest_max')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_stag_image" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('content_homeimg')}</label>
                        <div class="col-12 col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="element_stag_image" name="image" value="" aria-describedby="element_stag_image_btn">
                                <button type="button" class="btn btn-secondary" id="element_stag_image_btn" aria-label="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="element_stag_image" data-path="{$UPLOAD_PATH}" data-currentpath="{$UPLOAD_PATH}" data-type="image" title="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdTagLinks" tabindex="-1" aria-labelledby="mdTagLinksLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="mdTagLinksLabel">{$LANG->getModule('tag_links')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body p-0">
            </div>
            <div class="modal-footer justify-content-start">
                <div class="tag-tools">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <input type="checkbox" data-toggle="checkAll" data-type="link" class="form-check-input m-0 d-block" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </div>
                        <div>
                            <button type="button" class="btn btn-danger" data-toggle="tags_id_check_del" data-tid="0"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
