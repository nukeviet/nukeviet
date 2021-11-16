<!-- BEGIN: main -->
<div<!-- BEGIN: popup --> style="padding:10px 15px;background-color:#fff"<!-- END: popup -->>
    <!-- BEGIN: if_not_popup -->
    <div class="m-bottom form-inline">
        <a href="{ADD_LINK}" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> {LANG.article_add}</a>
    </div>
    <div class="m-bottom form-inline">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">{LANG.filter_by_type}:</span>
                <select class="form-control" data-toggle="filter_by_type" data-url="{FORM_ACTION}">
                    <option value="">{LANG.no_filter}</option>
                    <!-- BEGIN: type -->
                    <option value="{TYPE.key}" {TYPE.sel}>{TYPE.name}</option>
                    <!-- END: type -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="button" data-toggle="get_article_list" data-mess="{LANG.articles_filter_mess}">{LANG.getlist}</button>
        </div>
    </div>
    <!-- END: if_not_popup -->
    <!-- BEGIN: isArticles -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered articles-list" data-url="{FORM_ACTION}" data-del-confirm="{LANG.delete_confirm}" data-idfield="{IDFIELD}">
            <thead>
                <tr>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.article_type}</th>
                    <th class="text-nowrap" style="width:1%">{LANG.zalo_id}</th>
                    <th>{LANG.title}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.addtime}</th>
                    <th style="width:1%">
                        <!-- BEGIN: if_not_popup -->{LANG.operation}<!-- END: if_not_popup -->
                    </th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="5">
                        {GENERATE_PAGE}
                    </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
            <!-- BEGIN: article -->
            <tr>
                <td class="text-nowrap text-center" style="width:1%">
                    <!-- BEGIN: type_video --><i class="fa fa-video-camera fa-lg" title="{ARTICLE.type_format}"></i><!-- END: type_video -->
                    <!-- BEGIN: type_normal --><i class="fa fa-file-text-o fa-lg" title="{ARTICLE.type_format}"></i><!-- END: type_normal -->
                </td>
                <td class="text-nowrap" style="width:1%">
                    <!-- BEGIN: zalo_id --><button type="button" class="btn btn-block btn-sm<!-- BEGIN: view --> btn-primary" data-toggle="view_article" data-url="{ARTICLE.zalo_url}<!-- END: view -->">{ARTICLE.zalo_id}</button><!-- END: zalo_id -->
                    <!-- BEGIN: get_zalo_id --><button type="button" class="btn btn-default btn-block btn-sm" data-toggle="get_zalo_id" data-id="{ARTICLE.id}">{LANG.get_zalo_id}</button><!-- END: get_zalo_id -->
                    <!-- BEGIN: not_defined -->{LANG.not_defined}<!-- END: not_defined -->
                </td>
                <td>{ARTICLE.title}</td>
                <td class="text-nowrap text-center" style="width:1%">{ARTICLE.create_date_format}</td>
                <td style="width:1%">
                    <!-- BEGIN: if_not_popup -->
                    <select class="form-control" style="width: auto;" data-toggle="article_action_change" data-id="{ARTICLE.id}">
                        <option value=""></option>
                        <option value="edit">{GLANG.edit}</option>
                        <option value="sync">{LANG.sync}</option>
                        <option value="delete">{GLANG.delete}</option>
                    </select>
                    <!-- END: if_not_popup -->
                    <!-- BEGIN: if_popup -->
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="get_related_article" data-zalo-id="{ARTICLE.zalo_id}" data-title="{ARTICLE.title}">{LANG.article_select}</button>
                    <!-- END: if_popup -->
                </td>
            </tr>
            <!-- END: article -->
            </tbody>
        </table>
    </div>
    <!-- BEGIN: get_zalo_id_Modal -->
    <div class="modal fade articles-list" id="get_zalo_id_Modal" tabindex="-1" role="dialog" aria-labelledby="get_zalo_id_Label" data-url="{FORM_ACTION}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="get_zalo_id_Label">{LANG.get_zalo_id_title}</h4>
        </div>
        <div class="modal-body">{GET_ZALO_ID.note}</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-toggle="get_zalo_id" data-id="{GET_ZALO_ID.id}">{LANG.get_zalo_id}</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    <script>
    $(function() {
        $('#get_zalo_id_Modal').modal({
            backdrop: false
        }).modal('show')
    })
    </script>
    <!-- END: get_zalo_id_Modal -->
    <!-- END: isArticles -->
</div>
<!-- END: main -->

<!-- BEGIN: add -->
<div class="m-bottom form-inline">
    <a href="{LIST_LINK}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {LANG.article_list}</a>
</div>
<!-- BEGIN: if_add -->
<div class="m-bottom form-inline">
    <div class="input-group">
        <span class="input-group-addon">{LANG.article_type}:</span>
        <select class="form-control" data-toggle="add_article_change_type">
            <!-- BEGIN: type -->
            <option value="{TYPE.key}" data-url="{TYPE.url}" {TYPE.sel}>{TYPE.name}</option>
            <!-- END: type -->
        </select>
    </div>
</div>
<!-- END: if_add -->
<form class="row" method="POST" action="{FORM_ACTION}" data-toggle="article_form_submit" autocomplete="off" novalidate>
    <div class="col-md-12">
        <table class="table table-striped table-bordered">
            <colgroup>
                <col style="width: 150px;"/>
            </colgroup>
            <thead>
                <tr class="bg-primary">
                    <th colspan="2">{LANG.general_info}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{LANG.title}</td>
                    <td><input type="text" class="form-control required" name="title" value="{ARTICLE.title}" maxlength="150" data-pattern="/^(.){3,}$/" data-mess="{LANG.title_error}"/></td>
                </tr>
                <tr>
                    <td>{LANG.description}</td>
                    <td><textarea name="description" class="form-control keypress_submit non-resize min-width-100 required" maxlength="300" data-pattern="/^(.){3,}$/" data-mess="{LANG.description_empty}">{ARTICLE.description}</textarea></td>
                </tr>
                <!-- BEGIN: if_article_normal -->
                <tr>
                    <td>{LANG.author}</td>
                    <td><input type="text" class="form-control required" name="author" value="{ARTICLE.author}" maxlength="50" data-pattern="/^(.){3,}$/" data-mess="{LANG.author_empty}"/></td>
                </tr>
                <tr>
                    <td>{LANG.cover_type}</td>
                    <td>
                        <select name="cover_type" class="form-control" data-toggle="cover_type_change">
                            <!-- BEGIN: cover_type -->
                            <option value="{COVER_TYPE.key}" {COVER_TYPE.sel}>{COVER_TYPE.name}</option>
                            <!-- END: cover_type -->
                        </select>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: cover_photo_url_hide --> hidden<!-- END: cover_photo_url_hide -->">
                    <td>{LANG.cover_photo_url}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="cover_photo_url" id="cover_photo_url" value="{ARTICLE.cover_photo_url}" maxlength="250" data-pattern="/^(.){3,}$/" data-mess="{LANG.cover_photo_url_empty}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="cover_photo_url" data-area="cover_photo_url" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: cover_video_id_hide --> hidden<!-- END: cover_video_id_hide -->">
                    <td>{LANG.cover_video_id}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="cover_video_id" id="cover_video_id" value="{ARTICLE.cover_video_id}" maxlength="50" data-pattern="/^(.){10,}$/" data-mess="{LANG.cover_video_id_empty}"/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="cover_video_get" data-url="{COVER_VIDEO_GET_URL}"><i class="fa fa-video-camera"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: cover_view_hide --> hidden<!-- END: cover_view_hide -->">
                    <td>{LANG.cover_view}</td>
                    <td>
                        <select name="cover_view" id="cover_view" class="form-control">
                            <!-- BEGIN: cover_view -->
                            <option value="{COVER_VIEW.key}" {COVER_VIEW.sel}>{COVER_VIEW.name}</option>
                            <!-- END: cover_view -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.cover_status}</td>
                    <td>
                        <!-- BEGIN: cover_status_0 -->
                        <div class="form-inline">
                            <label style="margin-right: 15px"><input type="radio" name="cover_status" value="show"> {LANG.yes}</label>
                            <label><input type="radio" name="cover_status" value="hide" checked="checked"> {LANG.no}</label>
                        </div>
                        <!-- END: cover_status_0 -->
                        <!-- BEGIN: cover_status_1 -->
                        <div class="form-inline">
                            <label style="margin-right: 15px"><input type="radio" name="cover_status" value="show" checked="checked"> {LANG.yes}</label>
                            <label><input type="radio" name="cover_status" value="hide"> {LANG.no}</label>
                        </div>
                        <!-- END: cover_status_1 -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.related_medias}</td>
                    <td>
                        <ul class="list-group m-bottom" id="related_medias">
                            <!-- BEGIN: related_article -->
                            <li class="list-group-item related_article">
                                <button type="button" class="close" data-toggle="related_article_remove"><span aria-hidden="true">&times;</span></button>
                                <input type="hidden" name="related_article[]" value="{RELATED_ARTICLE.zalo_id}">
                                {RELATED_ARTICLE.title}
                            </li>
                            <!-- END: related_article -->
                        </ul>
                        <div class="text-right">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="add_related_article" data-url="{RELATED_ARTICLE_LINK}" data-zalo-id="{ARTICLE.zalo_id}">{LANG.add_related_article}</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.tracking_link}</td>
                    <td><input type="text" class="form-control" name="tracking_link" value="{ARTICLE.tracking_link}" maxlength="250" /></td>
                </tr>
                <!-- END: if_article_normal -->
                <tr>
                    <td>{LANG.article_status}</td>
                    <td>
                        <!-- BEGIN: article_status_hide -->
                        <div class="form-inline">
                            <label style="margin-right: 15px"><input type="radio" name="status" value="show"> {LANG.show}</label>
                            <label><input type="radio" name="status" value="hide" checked="checked"> {LANG.hide}</label>
                        </div>
                        <!-- END: article_status_hide -->
                        <!-- BEGIN: article_status_show -->
                        <div class="form-inline">
                            <label style="margin-right: 15px"><input type="radio" name="status" value="show" checked="checked"> {LANG.show}</label>
                            <label><input type="radio" name="status" value="hide"> {LANG.hide}</label>
                        </div>
                        <!-- END: article_status_show -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.article_comment}</td>
                    <td>
                        <!-- BEGIN: article_comment_hide -->
                        <div class="form-inline">
                            <label style="margin-right: 15px"><input type="radio" name="comment" value="show"> {LANG.show}</label>
                            <label><input type="radio" name="comment" value="hide" checked="checked"> {LANG.hide}</label>
                        </div>
                        <!-- END: article_comment_hide -->
                        <!-- BEGIN: article_comment_show -->
                        <div class="form-inline">
                            <label style="margin-right: 15px"><input type="radio" name="comment" value="show" checked="checked"> {LANG.show}</label>
                            <label><input type="radio" name="comment" value="hide"> {LANG.hide}</label>
                        </div>
                        <!-- END: article_comment_show -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <table class="table table-bordered body_list">
            <colgroup>
                <col style="width: 150px;"/>
            </colgroup>
            <thead>
                <tr class="bg-primary">
                    <th colspan="2">{LANG.content}</th>
                </tr>
            </thead>
            <!-- BEGIN: if_article_normal2 -->
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right">
                        <button type="button" class="btn btn-default btn-sm active" data-toggle="add_body">{LANG.add_body}</button>
                    </td>
                </tr>
            </tfoot>
            <!-- BEGIN: body -->
            <tbody class="body" id="body-{BODY.key}">
                <input type="hidden" name="body_id[]" value="body-{BODY.key}"/>
                <tr>
                    <td>{LANG.body_type}</td>
                    <td>
                        <div class="input-group">
                            <select name="body_type[]" class="form-control" data-toggle="body_type_change">
                                <!-- BEGIN: body_type -->
                                <option value="{BODY_TYPE.key}"{BODY_TYPE.sel}>{BODY_TYPE.name}</option>
                                <!-- END: body_type -->
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="delete_body">{LANG.delete_body}</button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: body_content_hide --> hidden<!-- END: body_content_hide -->">
                    <td>{LANG.body_content}</td>
                    <td>
                        <div style="margin-bottom:5px">
                            <textarea name="body_content[]" class="form-control keypress_submit non-resize min-width-100 required" style="height:150px" maxlength="1000" data-pattern="/^(.){3,}$/" data-mess="{LANG.body_content_empty}">{BODY.body_content}</textarea>
                        </div>
                        <small><em>({LANG.body_content_note})</em></small>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: body_photo_url_hide --> hidden<!-- END: body_photo_url_hide -->">
                    <td>{LANG.body_photo_url}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="body_photo_url[]" id="body_photo_url{BODY.key}" value="{BODY.body_photo_url}" maxlength="250" data-pattern="/^(.){3,}$/" data-mess="{LANG.body_photo_url_empty}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="body_photo_url" data-area="body_photo_url{BODY.key}" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: body_caption_hide --> hidden<!-- END: body_caption_hide -->">
                    <td>{LANG.body_caption}</td>
                    <td>
                        <input type="text" class="form-control" name="body_caption[]" value="{BODY.body_caption}" maxlength="250">
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: body_video_content_hide --> hidden<!-- END: body_video_content_hide -->">
                    <td>
                        <select name="body_video_type[]" class="form-control" data-toggle="body_video_type_change">
                            <!-- BEGIN: body_video_type -->
                            <option value="{BODY_VIDEO_TYPE.key}"{BODY_VIDEO_TYPE.sel}>{BODY_VIDEO_TYPE.name}</option>
                            <!-- END: body_video_type -->
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="body_video_content[]" id="body_video_content{BODY.key}" value="{BODY.body_video_content}" maxlength="50" data-pattern="/^(.){3,}$/" data-mess="{LANG.body_video_content_empty}"/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="body_video_get" data-url="{BODY_VIDEO_GET_URL}" data-idfield="body_video_content{BODY.key}" data-thumbfield="body_thumb{BODY.key}"<!-- BEGIN: body_video_get_disabled --> disabled="disabled"<!-- END: body_video_get_disabled -->><i class="fa fa-video-camera"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: body_thumb_hide --> hidden<!-- END: body_thumb_hide -->">
                    <td>{LANG.body_thumb}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="body_thumb[]" id="body_thumb{BODY.key}" value="{BODY.body_thumb}" maxlength="250" data-pattern="/^(.){3,}$/" data-mess="{LANG.body_thumb_empty}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="body_thumb" data-area="body_thumb{BODY.key}" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="field<!-- BEGIN: body_product_id_hide --> hidden<!-- END: body_product_id_hide -->">
                    <td>{LANG.body_product_id}</td>
                    <td>
                        <input type="text" class="form-control required" name="body_product_id[]" value="{BODY.body_product_id}" maxlength="100" data-pattern="/^(.){3,}$/" data-mess="{LANG.body_product_id_empty}"/>
                    </td>
                </tr>
            </tbody>
            <!-- END: body -->
            <!-- END: if_article_normal2 -->
            <!-- BEGIN: if_article_video -->
            <tbody class="body">
                <tr>
                    <td>{LANG.video_id}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="video_id" id="video_id" value="{ARTICLE.video_id}" maxlength="50" data-pattern="/^(.){10,}$/" data-mess="{LANG.video_id_empty}"/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="article_video_get" data-url="{ARTICLE_VIDEO_GET_URL}"><i class="fa fa-video-camera"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.video_thumb}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control required" name="video_avatar" id="video_avatar" value="{ARTICLE.video_avatar}" maxlength="250" data-pattern="/^(.){3,}$/" data-mess="{LANG.video_avatar_empty}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="video_avatar" data-area="video_avatar" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
            <!-- END: if_article_video -->
        </table>
        <div class="text-center">
            <input type="hidden" name="save" value="1">
            <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
        </div>
    </div>
</form>
<!-- END: add -->
<!-- BEGIN: wait_getlist -->
<meta http-equiv="refresh" content="3;url={GETLIST_LINK}" />
<p class="text-center"><img border="0" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/load_bar.gif" /><br /><br />{LANG.wait_update_info}</p>
<!-- END: wait_getlist -->