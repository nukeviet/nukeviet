<!-- BEGIN: main -->
<!-- BEGIN: header -->
<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE_JS}/js/comment.js"></script>
<link rel="StyleSheet" href="{NV_STATIC_URL}themes/{TEMPLATE_CSS}/css/comment.css" type="text/css" />
<!-- END: header -->
<div id="idcomment" class="nv-fullbg" data-module="{MODULE_COMM}" data-content="{MODULE_DATA}_commentcontent" data-area="{AREA_COMM}" data-id="{ID_COMM}" data-allowed="{ALLOWED_COMM}" data-checkss="{CHECKSS_COMM}">
    <div class="row clearfix margin-bottom-lg">
        <div class="col-xs-12 text-left">
            <button type="button" class="btn btn-default btn-sm pull-right" data-toggle="commListShow" data-obj="#showcomment" title="{LANG.comment_hide_show}">
                <em class="fa fa-eye-slash"></em>
            </button>
            <p class="comment-title">
                <em class="fa fa-comments">&nbsp;</em> {LANG.comment}
            </p>
        </div>
        <div class="col-xs-12 text-right">
            <select class="form-control" data-toggle="nv_comment_sort_change">
                <!-- BEGIN: sortcomm -->
                <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                <!-- END: sortcomm -->
            </select>
        </div>
    </div>
    <div id="showcomment" class="margin-bottom-lg">{COMMENTCONTENT}</div>
    <div id="formcomment" class="comment-form">
        <!-- BEGIN: allowed_comm -->
        <form method="post" role="form" target="submitcommentarea" action="{FORM_ACTION}" autocomplete="off" novalidate data-gfxnum="{GFX_NUM}" data-editor="{EDITOR_COMM}" {ENCTYPE}<!-- BEGIN: captcha --> data-captcha="code"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
            <input type="hidden" name="module" value="{MODULE_COMM}" />
            <input type="hidden" name="area" value="{AREA_COMM}" />
            <input type="hidden" name="id" value="{ID_COMM}" />
            <input type="hidden" name="pid" value="0" />
            <input type="hidden" name="allowed" value="{ALLOWED_COMM}" />
            <input type="hidden" name="checkss" value="{CHECKSS_COMM}" />
            <div class="form-group clearfix">
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" name="name" value="{NAME}" {DISABLED} class="form-control" placeholder="{LANG.comment_name}" />
                    </div>
                    <div class="col-xs-12">
                        <input type="email" name="email" value="{EMAIL}" {DISABLED} class="form-control" placeholder="{LANG.comment_email}" />
                    </div>
                </div>
            </div>
            <div class="form-group clearfix">
                <textarea class="form-control" style="width: 100%" name="content" id="commentcontent" cols="20" rows="5"></textarea>
                <!-- BEGIN: editor -->
                <script src="{NV_STATIC_URL}{NV_EDITORSDIR}/ckeditor/ckeditor.js?t={TIMESTAMP}"></script>
                <script>
                    CKEDITOR.replace('commentcontent', {{REPLACES}});
                </script>
                <!-- END: editor -->
            </div>
            <!-- BEGIN: attach -->
            <div class="form-group">
                <div class="row">
                    <label class="col-sm-8 col-md-6 control-label">{LANG.attach}</label>
                    <div class="col-sm-16 col-md-18">
                        <input type="file" name="fileattach" />
                    </div>
                </div>
            </div>
            <!-- END: attach -->

            <!-- BEGIN: confirm -->
            <div class="alert alert-info confirm" style="padding:0 10px;display:none">
                <!-- BEGIN: data_sending -->
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="form-control" style="margin-top:2px" name="data_permission_confirm" value="1" data-error="{GLANG.data_warning_error}"> <small>{DATA_USAGE_CONFIRM}</small>
                    </label>
                </div>
                <!-- END: data_sending -->

                <!-- BEGIN: antispam -->
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="form-control" style="margin-top:2px" name="antispam_confirm" value="1" data-error="{GLANG.antispam_warning_error}"> <small>{ANTISPAM_CONFIRM}</small>
                    </label>
                </div>
                <!-- END: antispam -->
            </div>
            <!-- END: confirm -->

            <div class="form-group text-center">
                <input type="button" value="{GLANG.reset}" class="reset btn btn-default" data-toggle="commReset" />
                <input type="submit" value="{LANG.comment_submit}" class="btn btn-primary" />
            </div>
        </form>
        <iframe class="hidden" id="submitcommentarea" name="submitcommentarea"></iframe>
        <!-- END: allowed_comm -->
        <!-- BEGIN: form_login-->
        <div class="alert alert-danger fade in">
            <!-- BEGIN: message_login -->
            <a title="{GLANG.loginsubmit}" href="#" data-toggle="loginForm">{LOGIN_MESSAGE}</a>
            <!-- END: message_login -->
            <!-- BEGIN: message_register_group -->
            {LANG_REG_GROUPS}
            <!-- END: message_register_group -->
        </div>
        <!-- END: form_login -->
    </div>
</div>
<!-- END: main -->