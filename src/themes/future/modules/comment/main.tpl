<div class="mt-3 comment-form-container border-top pt-4 pt-xl-5" id="idcomment"
    data-module="{$MODULE_COMM}"
    data-content="{$MODULE_DATA}_commentcontent"
    data-area="{$AREA_COMM}"
    data-id="{$ID_COMM}"
    data-allowed="{$ALLOWED_COMM}"
    data-checkss="{$CHECKSS_COMM}"
>
    <div class="d-flex align-items-center gap-3 justify-content-between mb-3 border-bottom pb-2">
        <div class="h3 mb-0">
            <i class="fa-regular fa-message me-1"></i> {$LANG->getModule('comment')}
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-input" data-toggle="commListShow" data-obj="#showcomment" title="{$LANG->getModule('comment_hide_show')}" aria-label="{$LANG->getModule('comment_hide_show')}">
                <i class="fa-regular fa-eye"></i>
            </button>
            <select class="form-select form-select-sm flex-shrink-0 fw-125" data-toggle="nv_comment_sort_change" name="nv_comment_sort_change">
                {for $i = 0 to 2}
                <option value="{$i}"{$i eq $SORTCOMM ? ' selected' : ''}>{$LANG->getModule("sortcomm_`$i`")}</option>
                {/for}
            </select>
        </div>
    </div>
    <div id="showcomment" class="mb-3">{$COMMENTCONTENT}</div>
    <div id="formcomment">
        {if $ALLOWED_COMM_BOOL}
        {* Form bình luận *}
        <form method="post" role="form" target="submitcommentarea"
            action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=comment&amp;{$smarty.const.NV_OP_VARIABLE}=post"
            data-precheck="commFormSubmit" autocomplete="off" novalidate
            data-gfxnum="{GFX_NUM}"
            data-editor="{(empty($MCONFIG.alloweditorcomm) or not $HEADER) ? 0 : 1}"
            {if not empty($MCONFIG.allowattachcomm)} enctype="multipart/form-data"{/if}
            {if $CAPTCHA_VALUE eq 'captcha'} data-captcha="code"{/if}
            {if $CAPTCHA_VALUE eq 'recaptcha2'} data-recaptcha2="1"{/if}
            {if $CAPTCHA_VALUE eq 'recaptcha3'} data-recaptcha3="1"{/if}
            {if $CAPTCHA_VALUE eq 'turnstile'} data-turnstile="1"{/if}
        >
            <input type="hidden" name="module" value="{$MODULE_COMM}">
            <input type="hidden" name="area" value="{$AREA_COMM}">
            <input type="hidden" name="id" value="{$ID_COMM}">
            <input type="hidden" name="pid" value="0">
            <input type="hidden" name="allowed" value="{$ALLOWED_COMM}">
            <input type="hidden" name="checkss" value="{$CHECKSS_COMM}">
            {assign var="DISABLED" value=($smarty.const.NV_IS_USER ? ' disabled' : '')}
            <div class="mb-3 row g-3">
                <div class="col-lg-6">
                    <input type="text" name="name" value="{$NAME}"{$DISABLED} class="form-control" placeholder="{$LANG->getModule('comment_name')}" autocomplete="name">
                </div>
                <div class="col-lg-6">
                    <input type="email" name="email" value="{$EMAIL}"{$DISABLED} class="form-control" placeholder="{$LANG->getModule('comment_email')}" autocomplete="email">
                </div>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="content" id="commentcontent" rows="5"></textarea>
                {if not empty($MCONFIG.alloweditorcomm) and $HEADER}
                <link rel="stylesheet" href="{$smarty.const.NV_STATIC_URL}{$smarty.const.NV_EDITORSDIR}/ckeditor5-classic/ckeditor.css?t={$GCONFIG.timestamp}">
                <script type="text/javascript" src="{$smarty.const.NV_STATIC_URL}{$smarty.const.NV_EDITORSDIR}/ckeditor5-classic/ckeditor.js?t={$GCONFIG.timestamp}"></script>
                <script type="text/javascript" src="{$smarty.const.NV_STATIC_URL}{$smarty.const.NV_EDITORSDIR}/ckeditor5-classic/language/{$smarty.const.NV_LANG_INTERFACE}.js?t={$GCONFIG.timestamp}"></script>
                <script type="text/javascript">
                (async () => {
                    await ClassicEditor
                    .create(document.getElementById("commentcontent"), {
                        language: '{$smarty.const.NV_LANG_INTERFACE}',
                        removePlugins: ["NVBox"],
                        image: { insert: { integrations: ["url"] } },
                        nvmedia: { insert: { integrations: ["url"] } },
                        toolbar: {
                            items: [
                                'undo',
                                'redo',
                                'selectAll',
                                '|',
                                'link',
                                'bookmark',
                                'imageInsert',
                                'nvmediaInsert',
                                'insertTable',
                                'nviframeInsert',
                                'nvdocsInsert',
                                'code',
                                'codeBlock',
                                'horizontalLine',
                                'specialCharacters',
                                'pageBreak',
                                '|',
                                'findAndReplace',
                                'showBlocks',
                                '|',
                                'bulletedList',
                                'numberedList',
                                'outdent',
                                'indent',
                                'blockQuote',
                                'heading',
                                'fontSize',
                                'fontFamily',
                                'fontColor',
                                'fontBackgroundColor',
                                'highlight',
                                'alignment',
                                '|',
                                'bold',
                                'italic',
                                'underline',
                                'emoji',
                                'strikethrough',
                                'subscript',
                                'superscript',
                                '|',
                                'sourceEditing',
                                'nvtools',
                                'removeFormat',
                                'fullscreen'
                            ],
                            shouldNotGroupWhenFull: false
                        },
                        nukeviet: {
                            editorId: 'commentcontent',
                            initCallback: 'cmtEditorCallback'
                        }
                    }).then(editor => {
                        editor.editing.view.document.on('keydown', (event, data) => {
                            if (data.ctrlKey && data.keyCode == 13) {
                                $('#formcomment form').submit();
                            }
                        });
                    }).catch(error => {
                        console.error(error);
                    });
                })();
                </script>
                {/if}
            </div>

            {if not empty($MCONFIG.allowattachcomm)}
            <div class="mb-3">
                <label for="commentFileAttach" class="form-label">{$LANG->getModule('attach')}:</label>
                <input class="form-control" type="file" name="fileattach" id="commentFileAttach">
            </div>
            {/if}

            {if not empty($GCONFIG.data_warning) or not empty($GCONFIG.antispam_warning)}
            <div class="collapse" id="commentWarnings">
                <div class="pb-3">
                    <div class="alert alert-info mb-0 vstack gap-3">
                        {if not empty($GCONFIG.data_warning)}
                        <div class="d-flex gap-2">
                            <input type="checkbox" class="form-check-input mt-2" id="data_permission_confirm" name="data_permission_confirm" value="1" data-error="{$LANG->getGlobal('data_warning_error')}">
                            <label for="data_permission_confirm">
                                <small>{$GCONFIG.data_warning_content ?: $LANG->getGlobal('data_warning_content')}</small>
                            </label>
                        </div>
                        {/if}
                        {if not empty($GCONFIG.antispam_warning)}
                        <div class="d-flex gap-2">
                            <input type="checkbox" class="form-check-input mt-2" id="antispam_confirm" name="antispam_confirm" value="1" data-error="{$LANG->getGlobal('antispam_warning_error')}">
                            <label for="antispam_confirm">
                                <small>{$GCONFIG.antispam_warning_content ?: $LANG->getGlobal('antispam_warning_content')}</small>
                            </label>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
            {/if}

            <div class="hstack gap-2 justify-content-center">
                <input type="submit" value="{$LANG->getModule('comment_submit')}" class="btn btn-primary">
                <input type="button" value="{$LANG->getGlobal('reset')}" class="btn btn-outline-secondary" data-toggle="commReset">
            </div>
        </form>
        <iframe class="d-none" id="submitcommentarea" name="submitcommentarea"></iframe>
        {elseif $FORM_LOGIN.display}
        {* Chưa có quyền bình luận nhưng có thể check quyền để bình luận *}
        <div class="alert alert-danger">
            {if $FORM_LOGIN.mode eq 'direct'}
            {* Thành viên đăng nhập trực tiếp *}
            <a title="{$LANG->getGlobal('loginsubmit')}" href="#" data-toggle="loginForm">{$LANG->getModule('comment_login', $FORM_LOGIN.groups.0)}</a>
            {else}
            {* Tham gia nhóm để bình luận *}
            {$LANG->getModule('comment_register_groups', $FORM_LOGIN.groups|join:', ', $FORM_LOGIN.link)}
            {/if}
        </div>
        {/if}
    </div>
</div>
