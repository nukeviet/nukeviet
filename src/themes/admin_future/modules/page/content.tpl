{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR}</div>
{/if}
<form action="{$FORM_ACTION}" method="post" class="confirm-reload">
    <input name="save" type="hidden" value="1">
    <input type="hidden" value="{$ISCOPY}" name="copy">
    <div class="row g-3">
        <div class="col-lg-8 col-xxl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="idtitle" class="form-label">{$LANG->getModule('title')} <span class="text-danger">(*)</span>:</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="idtitle" name="title" value="{$DATA.title}" maxlength="250">
                        </div>
                        <div class="form-text">{$LANG->getGlobal('length_characters')}: <span id="titlelength" class="fw-bold text-danger">0</span>. {$LANG->getGlobal('title_suggest_max')}.</div>
                    </div>
                    <div class="mb-3">
                        <label for="idalias" class="form-label">{$LANG->getModule('alias')}:</label>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="idalias" name="alias" value="{$DATA.alias}" maxlength="250">
                                    <button class="btn btn-secondary" role="button" type="button" aria-label="{$LANG->getModule('alias')}" onclick="get_alias(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('alias')}"><i class="fa-solid fa-rotate"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="image" class="form-label">{$LANG->getModule('image')}:</label>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="image" id="image" value="{$DATA.image}" maxlength="250">
                                            <button type="button" class="btn btn-secondary" aria-label="{$LANG->getGlobal('browse_image')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="image" data-path="{$UPLOADS_DIR_USER}" data-type="image" data-alt="imagealt"><i class="fa-solid fa-file-image"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="imageposition" class="form-label">{$LANG->getModule('imgposition')}:</label>
                                <select class="form-select" name="imageposition" id="imageposition">
                                    {foreach from=$ARRAY_IMGPOSITION key=key item=value}
                                    <option value="{$key}"{if $key eq $DATA.imageposition} selected{/if}>{$value}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imagealt" class="form-label">{$LANG->getModule('imagealt')}:</label>
                        <input type="text" class="form-control" id="imagealt" name="imagealt" value="{$DATA.imagealt}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{$LANG->getModule('description')}:</label>
                        <textarea class="form-control" id="description" name="description" rows="5">{$DATA.description}</textarea>
                        <div class="form-text">{$LANG->getGlobal('length_characters')}: <span id="descriptionlength" class="fw-bold text-danger">0</span>. {$LANG->getGlobal('description_suggest_max')}.</div>
                    </div>
                    <div class="mb-0">
                        <label for="bodytext" class="form-label">{$LANG->getModule('bodytext')} <span class="text-danger">(*)</span>:</label>
                        <div class="position-relative">
                            {$BODYTEXT}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('group_post')}
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="hot_post" value="1" id="hot_post"{if not empty($DATA.hot_post)} checked{/if}>
                        <label class="form-check-label" for="hot_post">
                            {$LANG->getModule('hot_post')}
                        </label>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <label for="keywords_input" class="form-label fw-medium">
                        {$LANG->getModule('keywords')}
                    </label>
                    <input class="form-control" type="text" value="{$DATA.keywords}" name="keywords" id="keywords_input">
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('socialbutton')}
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="socialbutton" value="1" id="socialbutton"{if not empty($DATA.socialbutton)} checked{/if}>
                        <label class="form-check-label" for="socialbutton">
                            {$LANG->getModule('socialbuttonnote')}
                        </label>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <label for="layout_func_select" class="form-label fw-medium">
                        {$LANG->getModule('layout_func')}
                    </label>
                    <select name="layout_func" class="form-select" id="layout_func_select">
                        <option value="">{$LANG->getModule('layout_default')}</option>
                        {foreach from=$LAYOUT_ARRAY item=layout}
                        <option value="{$layout}"{if $layout eq $DATA.layout_func} selected{/if}>{$layout}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('activecomm')}
                </div>
                <div class="card-body">
                    {foreach from=$ACTIVECOMM_LIST key=group_id item=group_title}
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="activecomm[]" value="{$group_id}" id="activecomm_{$group_id}"{if in_array($group_id, $DATA.activecomm_array)} checked{/if}>
                        <label class="form-check-label" for="activecomm_{$group_id}">
                            {$group_title}
                        </label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('schema_type')}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <select name="schema_type" id="content_schema_type" class="form-select">
                            {foreach from=$SCHEMA_TYPES key=key item=value}
                            <option value="{$key}"{if $key eq $DATA.schema_type} selected{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="mb-0{if $DATA.schema_type neq 'webpage'} d-none{/if}" data-toggle="content_schema_about">
                        <label for="schema_about" class="form-label">{$LANG->getModule('schema_about')}:</label>
                        <input class="form-control" type="text" value="{$DATA.schema_about}" name="schema_about" id="schema_about" maxlength="50">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hstack gap-2 flex-wrap justify-content-center">
        <button type="submit" role="button" class="btn btn-primary">{$LANG->getModule('save')}</button>
    </div>
</form>
<script>
    $(function() {
        // Đếm ký tự
        $("#titlelength").html($("#idtitle").val().length);
        $("#idtitle").on('keyup paste', function() {
            $("#titlelength").html($(this).val().length);
        });

        $("#descriptionlength").html($("#description").val().length);
        $("#description").on('keyup paste', function() {
            $("#descriptionlength").html($(this).val().length);
        });
    });
</script>

