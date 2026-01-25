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
    <div class="alert alert-danger">{LANG.feature_for_verified_OA}</div>
    <div class="row">
        <!-- BEGIN: request -->
        <div class="col-sm-8">
            <form method="POST" action="{FORM_ACTION}" data-toggle="request_form_submit" data-idfield="{IDFIELD}" data-clfield="{CLFIELD}">
                <input type="hidden" name="id" value="{REQUEST.id}" />
                <input type="hidden" name="action" value="update" />
                <div class="panel panel-primary request_form">
                    <div class="panel-image image-9-16"><img class="panel-image" src="{ASSETS_STATIC_URL}/images/pix.svg" alt="" style="background-image: url({REQUEST.image_url});" /></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="image_url" id="request_image{REQUEST.id}" value="{REQUEST.image_url}" placeholder="{LANG.image_url}" maxlength="250">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="request_image" data-area="request_image{REQUEST.id}" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" value="{REQUEST.title}" placeholder="{LANG.title}" max="100">
                        </div>
                        <div class="form-group m-bottom-none">
                            <textarea type="text" class="form-control non-resize keypress_submit" name="subtitle" placeholder="{LANG.subtitle}" maxlength="500">{REQUEST.subtitle}</textarea>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="d-flex">
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm">{LANG.update}</button>
                                <button type="button" class="btn btn-default btn-sm" data-toggle="request_delete" data-confirm="{LANG.delete_confirm}">{LANG.delete}</button>
                            </div>
                            <!-- BEGIN: isPopup -->
                            <div class="ml-auto">
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="request_select">{LANG.request_select}</button>
                            </div>
                            <!-- END: isPopup -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: request -->
        <div class="col-sm-8">
            <form method="POST" action="{FORM_ACTION}" data-toggle="request_form_submit">
                <input type="hidden" name="action" value="add" />
                <div class="panel panel-primary">
                    <div class="panel-image image-9-16"><img class="panel-image" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" alt="" /><div class="desc">{LANG.add_info_request}</div></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="image_url" id="request_image" value="" placeholder="{LANG.image_url}" maxlength="250">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="request_image" data-area="request_image" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" value="" placeholder="{LANG.title}" maxlength="100">
                        </div>
                        <div class="form-group m-bottom-none">
                            <textarea type="text" class="form-control non-resize keypress_submit" name="subtitle" placeholder="{LANG.subtitle}" maxlength="500"></textarea>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="submit" class="btn btn-primary btn-sm">{LANG.submit}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: main -->