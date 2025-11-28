{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR}</div>
{/if}
<form id="form-page-content" method="post" action="{$FORM_ACTION}" novalidate class="confirm-reload">
    <div class="row g-3">
        <div class="col-lg-8 col-xxl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="idtitle" class="form-label">{$LANG->getModule('title')} <span class="text-danger">(*)</span>:</label>
                        <div class="position-relative">
                            <input type="text" class="form-control required" id="idtitle" name="title" value="{$DATA.title}" maxlength="250">
                            <div class="invalid-tooltip">{$LANG->getModule('empty_title')}</div>
                        </div>
                        <div class="form-text">{$LANG->getGlobal('length_characters')}: <span id="titlelength" class="fw-bold text-danger">0</span>. {$LANG->getGlobal('title_suggest_max')}.</div>
                    </div>
                    <div class="mb-3">
                        <label for="idalias" class="form-label">{$LANG->getModule('alias')}:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="idalias" name="alias" value="{$DATA.alias}" maxlength="250">
                            <button class="btn btn-secondary" type="button" aria-label="{$LANG->getModule('alias')}" data-toggle="getaliaspage" data-auto-alias="{empty($DATA.alias) ? '1' : '0'}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('alias')}"><i class="fa-solid fa-rotate"></i></button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="image" class="form-label">{$LANG->getModule('image')}:</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="image" id="image" value="{$DATA.image}">
                                    <button type="button" class="btn btn-secondary" aria-label="{$LANG->getGlobal('browse_image')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="image" data-path="{$UPLOADS_DIR_USER}" data-type="image" data-alt="imagealt"><i class="fa-solid fa-file-image"></i></button>
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
                        <label for="{$MODULE_NAME}_bodytext" class="form-label">{$LANG->getModule('bodytext')} <span class="text-danger">(*)</span>:</label>
                        <div class="position-relative">
                            <div data-toggle="container-bodytext">
                                {if $HAS_EDITOR}
                                {editor('bodytext', '100%', '400px', $DATA.bodytext, '', $UPLOADS_DIR_USER, $UPLOAD_CURRENT)}
                                {else}
                                <textarea class="form-control required" id="{$MODULE_NAME}_bodytext" name="bodytext" rows="15">{$DATA.bodytext}</textarea>
                                {/if}
                            </div>
                            <div class="invalid-tooltip">{$LANG->getModule('empty_bodytext')}</div>
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
                        <label class="form-check-label" for="hot_post">{$LANG->getModule('hot_post')}</label>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('keywords')}
                </div>
                <div class="card-body">
                    <input type="text" class="form-control" name="keywords" id="keywords" value="{$DATA.keywords}">
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('socialbutton')}
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="socialbutton" value="1" id="socialbutton"{if not empty($DATA.socialbutton)} checked{/if}>
                        <label class="form-check-label" for="socialbutton">{$LANG->getModule('socialbuttonnote')}</label>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('layout_func')}
                </div>
                <div class="card-body">
                    <select name="layout_func" id="layout_func" class="form-select">
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
                    <div class="position-relative maxh-250 overflow-hidden" data-nv-toggle="scroll">
                        {foreach from=$GROUPS_LIST key=key item=value}
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" value="{$key}" name="activecomm[]" id="activecomm_{$key}"{if in_array($key, $ACTIVECOMM)} checked{/if}>
                            <label class="form-check-label" for="activecomm_{$key}">{$value}</label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('schema_type')}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <select class="form-select" name="schema_type" id="content_schema_type">
                            {foreach from=$SCHEMA_TYPES key=key item=value}
                            <option value="{$key}"{if $key eq $DATA.schema_type} selected{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="mb-0{if $DATA.schema_type neq 'webpage'} d-none{/if}" id="schema_about_container">
                        <label for="schema_about" class="form-label">{$LANG->getModule('schema_about')}:</label>
                        <input class="form-control" type="text" value="{$DATA.schema_about}" name="schema_about" id="schema_about" maxlength="50">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="1" name="save">
    <input type="hidden" value="{$ISCOPY}" name="copy">
    <div class="hstack gap-2 flex-wrap justify-content-center mt-3">
        <button class="btn btn-primary" type="submit">{$LANG->getModule('save')}</button>
    </div>
</form>
<script type="text/javascript">
$(function() {
    // Character count for title
    $("#titlelength").html($("#idtitle").val().length);
    $("#idtitle").on('keyup paste', function() {
        $("#titlelength").html($(this).val().length);
    });

    // Character count for description
    $("#descriptionlength").html($("#description").val().length);
    $("#description").on('keyup paste', function() {
        $("#descriptionlength").html($(this).val().length);
    });

    // Auto get alias when title changes (if alias is empty)
    {if empty($DATA.alias)}
    $('#idtitle').change(function() {
        $('[data-toggle="getaliaspage"]').trigger('click');
    });
    {/if}

    // Get alias button click handler
    $('[data-toggle="getaliaspage"]').on('click', function() {
        var title = $('#idtitle').val();
        if (title.length > 0) {
            $.post(
                script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={$MODULE_NAME}&' + nv_fc_variable + '=alias',
                'title=' + encodeURIComponent(title) + '&type=page',
                function(res) {
                    if (res) {
                        $('#idalias').val(res);
                    }
                }
            );
        }
    });

    // Toggle schema_about visibility based on schema_type selection
    $('#content_schema_type').on('change', function() {
        if ($(this).val() === 'webpage') {
            $('#schema_about_container').removeClass('d-none');
        } else {
            $('#schema_about_container').addClass('d-none');
        }
    });
});
</script>
