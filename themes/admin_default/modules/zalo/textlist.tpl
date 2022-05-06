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
    <div class="row">
        <!-- BEGIN: template -->
        <!-- BEGIN: element3 -->
        </div>
        <div class="row">
        <!-- END: element3 -->
        <div class="col-sm-8">
            <div class="panel panel-primary list" data-idfield="{IDFIELD}" data-clfield="{CLFIELD}">
                <div class="panel-image image-9-16"<!-- BEGIN: element0_action --> data-toggle="action_open_modal" data-title="{ELEMENT0.default_action_title}" data-content="{ELEMENT0.default_action_content}" style="cursor: pointer;"<!-- END: element0_action -->>
                    <img class="panel-image" src="{ASSETS_STATIC_URL}/images/pix.svg" alt="" style="background-image: url({ELEMENT0.image_url});" />
                </div>
                <div class="panel-body"<!-- BEGIN: element0_action2 --> data-toggle="action_open_modal" data-title="{ELEMENT0.default_action_title}" data-content="{ELEMENT0.default_action_content}" style="cursor: pointer;"<!-- END: element0_action2 -->>
                    <p><strong>{ELEMENT0.title}</strong></p>
                    <div>{ELEMENT0.subtitle}</div>
                </div>
                <div class="list-group">
                    <!-- BEGIN: other -->
                    <a href="#" class="list-group-item" data-toggle="action_open_modal" data-title="{OTHER.default_action_title}" data-content="{OTHER.default_action_content}" style="cursor: pointer;">
                        <span class="d-flex">
                            <span class="flex-shrink-1" style="margin-right:5px; width:70px">
                                <span class="image-3-4">
                                    <img class="panel-image" src="{ASSETS_STATIC_URL}/images/pix.svg" alt="" style="background-image: url({OTHER.image_url});" />
                                </span>
                            </span>
                            <span class="align-self-center" style="width:100%">
                                <strong>{OTHER.title}</strong><!-- BEGIN: subtitle --><br/>{OTHER.subtitle}<!-- END: subtitle -->
                            </span>
                        </span>
                    </a>
                    <!-- END: other -->
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
    <form method="POST" action="{FORM_ACTION}" data-toggle="list_form_submit">
        <input type="hidden" name="save" value="1" />
        <div class="row">
            <!-- BEGIN: element -->
            <!-- BEGIN: element3 -->
            </div>
            <div class="row">
            <!-- END: element3 -->
            <div class="col-sm-8 element">
                <div class="panel panel-primary">
                    <div class="panel-heading">{LANG.element} {ELEMENT.num_format}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>{LANG.title}</label>
                            <input type="text" class="form-control" name="title[]" value="{ELEMENT.title}" maxlength="100">
                        </div>
                        <!-- BEGIN: subtitle0 -->
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-xs" data-toggle="subtitle_box"><!-- BEGIN: subtitle_hide --><span class="fa fa-caret-down"></span><!-- END: subtitle_hide --><!-- BEGIN: subtitle_show --><span class="fa fa-caret-up"></span><!-- END: subtitle_show --> {LANG.subtitle}</button>
                        </div>
                        <!-- END: subtitle0 -->
                        <!-- BEGIN: subtitle1 -->
                        <label>{LANG.subtitle}</label>
                        <!-- END: subtitle1 -->
                        <input type="hidden" name="issubtitle[]" value="{ELEMENT.issubtitle}">
                        <div class="form-group<!-- BEGIN: subtitle_hide --> hidden<!-- END: subtitle_hide -->">
                            <input type="text" class="form-control non-resize keypress_submit" name="subtitle[]" value="{ELEMENT.subtitle}" maxlength="500">
                        </div>
                        <div class="form-group">
                            <label>{LANG.image_url}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="image_url[]" id="image_url{ELEMENT.num_format}" value="{ELEMENT.image_url}" maxlength="250">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="list_image" data-area="image_url{ELEMENT.num_format}" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{LANG.default_action}</label>
                            <select name="default_action[]" class="form-control" data-toggle="action_change">
                                <!-- BEGIN: action -->
                                <option value="{ACTION.key}"{ACTION.sel}>{ACTION.name}</option>
                                <!-- END: action -->
                            </select>
                        </div>
                        <div class="form-group action<!-- BEGIN: url_hide --> hidden<!-- END: url_hide -->">
                            <label>{LANG.url}</label>
                            <input type="text" class="form-control" name="url[]" value="{ELEMENT.url}" maxlength="250">
                        </div>
                        <div class="form-group action<!-- BEGIN: content_hide --> hidden<!-- END: content_hide -->">
                            <label>{LANG.content}</label>
                            <input type="text" class="form-control" name="content[]" value="{ELEMENT.content}" maxlength="250">
                        </div>
                        <div class="form-group action<!-- BEGIN: keyword_hide --> hidden<!-- END: keyword_hide -->">
                            <label>{LANG.command_keyword}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword[]" id="keyword{ELEMENT.num_format}" value="{ELEMENT.keyword}" maxlength="50" readonly="readonly">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="list_keywords" data-url="{KEYWORDS_URL}" data-idfield="keyword{ELEMENT.num_format}"><i class="fa fa-location-arrow"></i></button>
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
                                    <input type="text" class="form-control" name="phone_number[]" value="{ELEMENT.phone_number}" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: element -->
        </div>
        <div class="">
            <button type="submit" class="btn btn-primary btn-sm">{LANG.submit}</button>
        </div>
    </form>
</div>
<!-- END: add -->