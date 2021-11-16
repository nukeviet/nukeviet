<!-- BEGIN: main -->
<div<!-- BEGIN: popup --> style="padding:10px 15px;background-color:#fff"<!-- END: popup -->>
    <!-- BEGIN: change_template_type -->
    <div class="form-inline m-bottom">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">{LANG.template}:</span>
                <select class="form-control" data-toggle="change_template_type">
                    <option value="" data-url="{MAIN_PAGE}"></option>
                    <!-- BEGIN: loop -->
                    <option value="{LOOP.key}" data-url="{LOOP.url}"{LOOP.sel}>{LOOP.name}</option>
                    <!-- END: loop -->
                </select>
            </div>
        </div>
    </div>
    <!-- END: change_template_type -->
    <div class="m-bottom form-inline">
        <a href="{ADD_LINK}" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> {LANG.template_add}</a>
    </div>
    <div class="alert alert-danger">{LANG.btnlist_note}</div>
    <div class="row">
        <!-- BEGIN: template -->
        <!-- BEGIN: element3 -->
        </div>
        <div class="row">
        <!-- END: element3 -->
        <div class="col-sm-8">
            <div class="panel panel-primary list" data-idfield="{IDFIELD}" data-clfield="{CLFIELD}">
                <div class="panel-body">
                    <h3><strong>{TEXT}</strong></h3>
                    <!-- BEGIN: btn -->
                    <div class="form-group">
                        <button type="button" class="btn btn-default btn-block active" data-toggle="action_open_modal" data-title="{BTN.action_title}" data-content="{BTN.action_content}">{BTN.title}</button>
                    </div>
                    <!-- END: btn -->
                </div>
                <div class="panel-footer d-flex">
                    <div>
                        <a href="{EDIT_LINK}{ID}" class="btn btn-primary btn-sm">{LANG.template_edit}</a>
                        <a href="{DEL_LINK}" class="btn btn-default btn-sm" data-toggle="list_del" data-confirm="{LANG.delete_confirm}" data-id="{ID}">{GLANG.delete}</a>
                    </div>
                    <!-- BEGIN: select -->
                    <div class="ml-auto">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="list_select" data-id="{ID}">{LANG.list_select}</button>
                    </div>
                    <!-- END: select -->
                </div>
            </div>
        </div>
        <!-- END: template -->
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="action-title"></div>
            </div>
            <div class="modal-body action-content"></div>
        </div>
    </div>
</div>
<!-- END: main -->
<!-- BEGIN: add -->
<div<!-- BEGIN: popup --> style="padding:10px 15px;background-color:#fff"<!-- END: popup -->>
    <div class="m-bottom form-inline">
        <a href="{LIST_LINK}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {LANG.template_list}</a>
        <!-- BEGIN: add_bt --><a href="{ADD_LINK}" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> {LANG.template_add}</a><!-- END: add_bt -->
    </div>
    <div class="alert alert-danger">{LANG.btnlist_note}</div>
    <form method="POST" action="{FORM_ACTION}" data-toggle="list_form_submit">
        <input type="hidden" name="save" value="1" />
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <label>{LANG.text_list}</label>
                    <input type="text" class="form-control" name="text" value="{TEXT}" maxlength="100">
                </div>
            </div>
        </div>
        <div class="row">
            <!-- BEGIN: btn -->
            <!-- BEGIN: btn3 -->
            </div>
            <div class="row">
            <!-- END: btn3 -->
            <div class="col-sm-8 element">
                <div class="panel panel-primary">
                    <div class="panel-heading">{LANG.btn} {BTN.num_format}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>{LANG.title}</label>
                            <input type="text" class="form-control" name="title[]" value="{BTN.title}" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label>{LANG.default_action}</label>
                            <select name="type[]" class="form-control" data-toggle="action_change">
                                <!-- BEGIN: action -->
                                <option value="{ACTION.key}"{ACTION.sel}>{ACTION.name}</option>
                                <!-- END: action -->
                            </select>
                        </div>
                        <div class="form-group action<!-- BEGIN: url_hide --> hidden<!-- END: url_hide -->">
                            <label>{LANG.url}</label>
                            <input type="text" class="form-control" name="url[]" value="{BTN.url}" maxlength="250">
                        </div>
                        <div class="form-group action<!-- BEGIN: content_hide --> hidden<!-- END: content_hide -->">
                            <label>{LANG.content}</label>
                            <input type="text" class="form-control" name="content[]" value="{BTN.content}" maxlength="250">
                        </div>
                        <div class="form-group action<!-- BEGIN: keyword_hide --> hidden<!-- END: keyword_hide -->">
                            <label>{LANG.command_keyword}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword[]" id="keyword{BTN.num_format}" value="{BTN.keyword}" maxlength="50" readonly="readonly">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="list_keywords" data-url="{KEYWORDS_URL}" data-idfield="keyword{BTN.num_format}"><i class="fa fa-location-arrow"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-horizontal action<!-- BEGIN: phone_hide --> hidden<!-- END: phone_hide -->">
                            <label>{LANG.phone}</label>
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <select name="phone_code[]" class="form-control">
                                        <!-- BEGIN: phone_code -->
                                        <option value="{PHONE_CODE.key}"{PHONE_CODE.sel}>{PHONE_CODE.name}</option>
                                        <!-- END: phone_code -->
                                    </select>
                                </div>
                                <div class="col-xs-16">
                                    <input type="text" class="form-control" name="phone_number[]" value="{BTN.phone_number}" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: btn -->
        </div>
        <div class="">
            <button type="submit" class="btn btn-primary btn-sm">{LANG.submit}</button>
        </div>
    </form>
</div>
<!-- END: add -->