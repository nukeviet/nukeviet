<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap" style="width:40%">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap">{$LANG->getModule('description')}</th>
                        <th class="text-center text-nowrap" style="width:15%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$TOPICS item=row}
                    <tr>
                        <td class="text-center">
                            {if $NUM_TOPICS > 1}
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-toggle="change-topic-weight"
                                    data-topicid="{$row.topicid}"
                                    data-current-weight="{$row.weight}"
                                    data-tokend="{$CHECKSS}"
                                    data-bs-title="{$LANG->getModule('change_weight')}">
                                {$row.weight}
                            </button>
                            {else}
                            {$row.weight}
                            {/if}
                        </td>
                        <td>
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=topicsnews&amp;topicid={$row.topicid}">
                                <strong>{$row.title}</strong>
                            </a>
                            <small class="text-muted">({$row.numnews} {$LANG->getModule('topic_num_news')})</small>
                        </td>
                        <td>{$row.description}</td>
                        <td class="text-center text-nowrap">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;topicid={$row.topicid}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-topic"
                                    data-id="{$row.topicid}"
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

<form id="topic-form" method="post" class="ajax-submit" novalidate{if $IS_EDIT} data-is-edit="1"{/if}
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {if $IS_EDIT}{$LANG->getModule('edit_topic')}{else}{$LANG->getModule('add_topic')}{/if}
            </h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="idtitle" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('name')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" id="idtitle" name="title"
                           value="{$ITEM.title}" maxlength="255" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="idalias" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('alias')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="idalias" name="alias"
                               value="{$ITEM.alias}" maxlength="255" autocomplete="off">
                        <button type="button" class="btn btn-secondary"
                                data-toggle="refresh-alias"
                                data-topicid="{$ITEM.topicid}"
                                title="{$LANG->getGlobal('refresh')}"
                                aria-label="{$LANG->getGlobal('refresh')}">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="homeimg" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('content_homeimg')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="homeimg" name="homeimg"
                               value="{$ITEM.image}" autocomplete="off">
                        <button type="button" class="btn btn-info"
                                data-toggle="selectfile"
                                data-target="homeimg"
                                data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/topics"
                                data-currentpath="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/topics"
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
            <div class="row mb-3">
                <label for="topic_keywords" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('keywords')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="topic_keywords" name="keywords"
                           value="{$ITEM.keywords}" maxlength="255" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="topic_description" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('description')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" id="topic_description" name="description"
                              rows="4">{$ITEM.description}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="savecat" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="topicid" value="{$ITEM.topicid}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{if $NUM_TOPICS > 1}
<div id="topic-weight-tpl" class="d-none">
    <div style="width:220px">
        <div class="input-group input-group-sm topic-weight-item">
            <input type="number" class="form-control topic-new-weight" min="1" max="{$NUM_TOPICS}" value="" name="newweight">
            <button type="button" class="btn btn-secondary topic-weight-down" tabindex="-1"><i class="fa-solid fa-angle-down"></i></button>
            <button type="button" class="btn btn-secondary topic-weight-up" tabindex="-1"><i class="fa-solid fa-angle-up"></i></button>
            <button type="button" class="btn btn-primary topic-weight-ok" data-topicid="" data-current-weight="">OK</button>
        </div>
        <div class="form-text mt-1">{$LANG->getModule('type_new_weight')} {$NUM_TOPICS}</div>
    </div>
</div>
{/if}
