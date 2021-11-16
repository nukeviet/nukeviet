<!-- BEGIN: main -->
<div class="form-inline m-bottom">
    <!-- BEGIN: isTags -->
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">{LANG.filter_by_tag}:</span>
            <select class="form-control" data-toggle="filter_by_tag" data-url="{FORM_ACTION}">
                <option value="">{LANG.no_filter}</option>
                <!-- BEGIN: tag -->
                <option value="{TAG.alias}" {TAG.sel}>{TAG.name}</option>
                <!-- END: tag -->
            </select>
        </div>
    </div>
    <!-- END: isTags -->
    <div class="form-group">
        <button type="button" class="btn btn-default" data-toggle="getfollowers" data-mess="{LANG.getfollowers_note}" data-url="{GETFOLLOWERS_LINK}">{LANG.getfollowers}</button>
    </div>
</div>
<!-- BEGIN: isFollowers -->
<div class="table-responsive">
    <table class="table table-striped table-bordered followers" data-form-action="{FORM_ACTION}">
        <thead>
            <tr>
                <th>{LANG.user_id}</th>
                <th>{LANG.user_id_by_app}</th>
                <th>{LANG.display_name}</th>
                <th class="text-nowrap text-center">{LANG.user_gender}</th>
                <th class="text-nowrap text-center">{LANG.updatetime}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: follower -->
            <tr class="follower" data-user-id="{FOLLOWER.user_id}">
                <td class="text-nowrap" style="width:5%"><a class="btn btn-default btn-block" href="{FORM_ACTION}&amp;user_id={FOLLOWER.user_id}">{FOLLOWER.user_id}</span></td>
                <td class="user_id_by_app">{FOLLOWER.user_id_by_app}</td>
                <td class="display_name">{FOLLOWER.display_name}</td>
                <td class="text-nowrap text-right user_gender" style="width:5%">{FOLLOWER.user_gender}</td>
                <td class="text-nowrap text-right updatetime_format" style="width:5%">{FOLLOWER.updatetime_format}</td>
                <td class="text-nowrap text-right" style="width:5%">
                    <button type="button" class="btn btn-primary" data-toggle="follower_getprofile">{LANG.getprofile}</button>
                </td>
            </tr>
            <!-- END: follower -->
        </tbody>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    {GENERATE_PAGE}
                </td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: isFollowers -->
<div class="m-bottom">
    
    <a href="{UNFOLLOWERS_LINK}" class="btn btn-default m-bottom">{LANG.unfollowers}</a>
</div>
<!-- END: main -->

<!-- BEGIN: wait_getfollowers -->
<meta http-equiv="refresh" content="3;url={GETFOLLOWERS_LINK}" />
<p class="text-center"><img border="0" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/load_bar.gif" /><br /><br />{LANG.wait_update_info}</p>
<!-- END: wait_getfollowers -->

<!-- BEGIN: follower_profile -->
<link href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/perfect-scrollbar/style.css" rel="stylesheet" />
<script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/perfect-scrollbar/min.js" charset="utf-8"></script>
<script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/amr-player/amrnb.js" charset="utf-8"></script>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">{LANG.follower_info}</div>
            <table class="table table-striped table-bordered">
                <tbody>
                    <!-- BEGIN: follower -->
                    <tr>
                        <td class="text-nowrap" style="width: 30%">{FOLLOWER.key}</td>
                        <td>
                            <!-- BEGIN: normal -->
                            {FOLLOWER.val}
                            <!-- END: normal -->
                            <!-- BEGIN: id -->
                            <span class="btn btn-default">{FOLLOWER.val}</span>
                            <!-- END: id -->
                            <!-- BEGIN: avatar120 -->
                            <img src="{FOLLOWER.val}" width="120" height="120" class="img-thumbnail" alt="" />
                            <!-- END: avatar120 -->
                            <!-- BEGIN: avatar240 -->
                            <img src="{FOLLOWER.val}" width="240" height="240" class="img-thumbnail" alt="" />
                            <!-- END: avatar240 -->
                        </td>
                    </tr>
                    <!-- END: follower -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="panel-group" id="fi_actions" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="follower_getprofile2" data-url="{FORM_ACTION}" data-user-id="{OTHER.user_id}">
                            {LANG.getprofile2}
                        </a>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="edit_fi_title">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#fi_actions" href="#edit_fi" aria-expanded="false" aria-controls="edit_fi">
                            {LANG.follower_info_in_db}
                        </a>
                    </h4>
                </div>
                <form id="edit_fi" class="panel-collapse collapse" role="tabpanel" aria-labelledby="edit_fi_title" method="POST" action="{FORM_ACTION}" data-location="{LOCATION}&amp;action=edit_fi">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <td class="text-nowrap" style="width: 30%">{LANG.name}</td>
                                <td><input type="text" class="form-control" name="name" value="{OTHER.name}" maxlength="100" /></td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" style="width: 30%">{LANG.phone}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <select name="phone_code" class="form-control">
                                                <!-- BEGIN: phone_code -->
                                                <option value="{PHONE_CODE.key}" {PHONE_CODE.sel}>{PHONE_CODE.name}</option>
                                                <!-- END: phone_code -->
                                            </select>
                                        </div>
                                        <div class="col-xs-16">
                                            <input type="text" class="form-control" name="phone_number" value="{OTHER.phone_number}" maxlength="20" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" style="width: 30%">{LANG.city_id}</td>
                                <td>
                                    <select name="city_id" class="form-control" data-toggle="change_city">
                                        <option value=""></option>
                                        <!-- BEGIN: city_id -->
                                        <option value="{CITY.id}" {CITY.sel}>{CITY.name}</option>
                                        <!-- END: city_id -->
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" style="width: 30%">{LANG.district_id}</td>
                                <td>
                                    <select name="district_id" class="form-control" data-default="{OTHER.district_id}">
                                        <option value=""></option>
                                        <!-- BEGIN: district_id -->
                                        <option value="{DISTRICT.id}" {DISTRICT.sel}>{DISTRICT.name}</option>
                                        <!-- END: district_id -->
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" style="width: 30%">{LANG.address}</td>
                                <td><input type="text" class="form-control" name="address" value="{OTHER.address}" maxlength="100" /></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <input type="hidden" name="change_profile" value="1" />
                                    <input type="hidden" name="user_id" value="{OTHER.user_id}" />
                                    <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="edit_ftags_title">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#fi_actions" href="#edit_ftags" aria-expanded="false" aria-controls="edit_ftags">
                            {LANG.followertag_management}
                        </a>
                    </h4>
                </div>
                <div id="edit_ftags" class="panel-collapse collapse" role="tabpanel" aria-labelledby="edit_ftags_title" data-location="{LOCATION}&amp;action=edit_ftags">
                    <div class="panel-body">
                        <!-- BEGIN: follower_tag -->
                        <span class="tag"><a class="view" href="{TAG_LINK}&amp;tag={FOLLOWER_TAG.alias}">{FOLLOWER_TAG.name}</a><a class="remove" href="{FORM_ACTION}" title="{LANG.click_to_remove}" data-toggle="remove_ftag" data-tag-alias="{FOLLOWER_TAG.alias}" data-user-id="{OTHER.user_id}"><span aria-hidden="true">&times;</span></a></span>
                        <!-- END: follower_tag -->
                        <!-- BEGIN: no_tags_assigned -->
                        <div class="text-center">{LANG.no_tags_assigned}</div>
                        <!-- END: no_tags_assigned -->
                    </div>
                    <div class="panel-footer">
                        <form method="POST" action="{FORM_ACTION}" class="row" data-toggle="add_follower_tag">
                            <div class="col-xs-8">
                                <select name="add_tag" class="form-control">
                                    <option value="">{LANG.select_tag}</option>
                                    <!-- BEGIN: tag_list -->
                                    <option value="{TAG.alias}">{TAG.name}</option>
                                    <!-- END: tag_list -->
                                </select>
                            </div>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="add_newtag" value="" placeholder="{LANG.or_new_tag}" maxlength="50" />
                            </div>
                            <div class="col-xs-7">
                                <input type="hidden" name="add_follower_tag" value="1" />
                                <input type="hidden" name="user_id" value="{OTHER.user_id}" />
                                <input type="submit" class="btn btn-primary btn-block" value="{LANG.add_follower_tag}" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="last_conversation_title">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#fi_actions" href="#last_conversation" aria-expanded="false" aria-controls="last_conversation">
                            {LANG.conversation}
                        </a>
                    </h4>
                </div>
                <div id="last_conversation" class="chat panel-collapse collapse" role="tabpanel" aria-labelledby="last_conversation_title" data-loaded="false" data-url="{CONVERSATION_LINK}" data-user-id="{OTHER.user_id}" data-location="{LOCATION}&amp;action=last_conversation">
                    <div class="message-box">
                        <div class="panel-body">
                            <div class="text-center hidden" id="loading"><img border="0" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/load_bar.gif" /></div>
                            <div class="text-center" id="conversation-content"></div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row m-bottom">
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-primary" data-toggle="conversation_refresh" data-url="{CONVERSATION_LINK}" data-user-id="{OTHER.user_id}">{LANG.refresh}</button>
                            </div>
                            <div class="col-xs-12 text-right">
                                <button type="button" class="btn btn-default" data-toggle="conversation_gobottom"><i class="fa fa-chevron-down"></i></button><button type="button" class="btn btn-default" data-toggle="conversation_gotop"><i class="fa fa-chevron-up"></i></button>
                            </div>
                        </div>
                        <form method="POST" action="{FORM_ACTION}" id="chat-box">
                            <div class="row m-bottom">
                                <div class="col-xs-8">
                                    <select name="attachment_type" class="form-control" data-toggle="change_attachment_type">
                                        <option value="plaintext">{LANG.no_attachment}</option>
                                        <option value="pltext" data-url="{POPUP_PLAINTEXT_URL}">{LANG.add_plaintext_from_templates}</option>
                                        <option value="site">{LANG.site_attachment}</option>
                                        <option value="internet">{LANG.internet_attachment}</option>
                                        <option value="zalo" data-url="{POPUP_URL}">{LANG.zalo_attachment}</option>
                                        <option value="file" data-url="{POPUP_FILE_URL}">{LANG.file_attachment}</option>
                                        <option value="textlist" data-url="{POPUP_TEXTLIST_URL}">{LANG.textlist_attachment}</option>
                                        <option value="btnlist" data-url="{POPUP_BTNLIST_URL}">{LANG.btnlist_attachment}</option>
                                        <!-- BEGIN: for_verified_OA --><option value="request" data-url="{POPUP_REQUEST_URL}">{LANG.request_attachment}</option><!-- END: for_verified_OA -->
                                    </select>
                                </div>
                                <div class="col-xs-16">
                                    <div class="input-group">
                                        <input type="text" name="attachment" id="attachment" class="form-control" readonly/>
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default" data-toggle="del_attachment"><span aria-hidden="true">&times;</span></button>
                                            <button type="button" class="btn btn-default" data-toggle="add_attachment" data-upload-dir="{NV_UPLOADS_DIR}" title="{LANG.attach}" disabled><i class="fa fa-paperclip"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="reply-mess form-inline m-bottom hidden" id="reply-mess">
                                <label>{LANG.mess_reply}:</label>
                                <button id="message_id_text" type="button" class="btn btn-default btn-sm" data-toggle="goto_reply_mess"></button>
                                <a href="#" class="reply-remove" title="{LANG.reply_remove}" data-toggle="reply_remove"><i class="fa fa-times"></i></a>
                            </div>
                            <div class="chat-box">
                                <div class="chat-content">
                                    <textarea class="form-control keypress_submit non-resize" name="chat_text" id="chat_text" maxlength="2000"></textarea>
                                </div>
                                <input type="hidden" name="user_id" value="{OTHER.user_id}"/>
                                <input type="hidden" name="message_id" id="message_id" value=""/>
                                <input type="hidden" name="send_text" value="1"/>
                                <button class="chat-send btn btn-primary" id="chat_submit" type="submit"><i class="fa fa-chevron-right fa-lg"></i></button>
                            </div>
                        </form>
                    </div>
                    <button type="button" class="chat-close"><span class="fa fa-times"></span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{FORM_ACTION}" id="update-to-oa">
    <input type="hidden" name="updatefollowerinfo" value="1" />
    <input type="hidden" name="user_id" value="" />
</form>

<!-- Modal -->
<div class="modal fade" id="viewimg" tabindex="-1" role="dialog" aria-labelledby="viewimgModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="viewimgModalLabel">{LANG.viewimg}</h4>
            </div>
            <div class="modal-body viewimg"><img border="0" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" alt="" /></div>
        </div>
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
<script>
    $(function() {
        var ps = new PerfectScrollbar('.message-box');
        <!-- BEGIN: action -->
        $('a[data-toggle="collapse"][aria-controls="{ACTION}"]').trigger('click')
        <!-- END: action -->
    })
</script>
<!-- END: follower_profile -->