<!-- BEGIN: main -->
<div class="row page" data-url="{PAGE_URL}" data-id="{DATA.id}">
    <div class="col-md-14">
        <h2>
            <!-- BEGIN: process --><span class="fa fa-spinner fa-spin"></span><!-- END: process -->
            <!-- BEGIN: processed --><span class="fa fa-check"></span><!-- END: processed -->
            <strong>{DATA.title}</strong>
        </h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="text-nowrap" style="vertical-align:top;width:1%"><strong>{LANG.infor_user_send_title}</strong></td>
                        <td>
                            <table style="width: fit-content;">
                                <tbody>
                                    <tr>
                                        <td class="text-right">
                                            <!-- BEGIN: is_user --><a href="#" class="view_user" data-userid="{DATA.sender_id}">{DATA.sender_name}</a><!-- END: is_user -->
                                            <!-- BEGIN: is_guest --><span>{DATA.sender_name}</span><!-- END: is_guest -->
                                        </td>
                                        <td>&nbsp;&nbsp;&lt;{DATA.sender_email}&gt;</td>
                                    </tr>
                                    <!-- BEGIN: sender_phone -->
                                    <tr>
                                        <td class="text-right">{GLANG.phonenumber}:</td>
                                        <td>&nbsp;&nbsp;{DATA.sender_phone}</td>
                                    </tr>
                                    <!-- END: sender_phone -->
                                    <!-- BEGIN: sender_address -->
                                    <tr>
                                        <td class="text-right">{GLANG.address}:</td>
                                        <td>&nbsp;&nbsp;{DATA.sender_address}</td>
                                    </tr>
                                    <!-- END: sender_address -->
                                    <tr>
                                        <td class="text-right">IP:</td>
                                        <td>&nbsp;&nbsp;{DATA.sender_ip}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">{LANG.send_time}:</td>
                                        <td>&nbsp;&nbsp;{DATA.send_time}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap" style="width:1%"><strong>{LANG.to_department}</strong></td>
                        <td>
                            <!-- BEGIN: department_url --><a href="#" data-url="{DATA.department_url}" class="department-view">{DATA.department}</a><!-- END: department_url -->
                            <!-- BEGIN: department --><span>{DATA.department}</span><!-- END: department -->
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap" style="width:1%"><strong>{LANG.cat}</strong></td>
                        <td>{DATA.cat}</td>
                    </tr>
                    <tr class="active">
                        <td colspan="2">
                            <div class="panel panel-primary m-bottom-none">
                                <div class="panel-body" style="white-space: normal !important;min-height:150px">
                                    {DATA.content}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BEGIN: auto_forward -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <p><strong>{LANG.auto_forward_to}:</strong></p>
                {DATA.auto_forward}
            </div>
        </div>
        <!-- END: auto_forward -->

        <!-- BEGIN: read_admins -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <p><strong>{LANG.has_been_read}:</strong></p>
                {DATA.read_admins}
            </div>
        </div>
        <!-- END: read_admins -->

        <!-- BEGIN: is_processed -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <p class="alert-title"><strong>{LANG.has_been_processed}</strong></p>
                <!-- BEGIN: processed_person -->
                {LANG.processed_by}: <a href="{PROCESSED.url}">{PROCESSED.name}</a>&nbsp;&nbsp;
                <!-- END: processed_person -->
                {LANG.processed_time}: {PROCESSED.time}
            </div>
        </div>
        <!-- END: is_processed -->

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr class="active">
                        <td class="text-center">
                            <!-- BEGIN: reply -->
                            <button type="button" class="btn btn-default feedback-reply">{LANG.send_title}</button>&nbsp;
                            <!-- END: reply -->
                            <!-- BEGIN: exec -->
                            <button type="button" class="btn btn-default feedback_del">{GLANG.delete}</button>&nbsp;
                            <button type="button" class="btn btn-default feedback_mark_single" data-mark="unread">{LANG.mark_as_unread}</button>&nbsp;
                            <button type="button" class="btn btn-default feedback_mark_single" data-mark="{DATA.mark_process}">{DATA.mark_process_title}{MARK_PROCESS}</button>&nbsp;
                            <!-- END: exec -->
                            <!-- BEGIN: forward -->
                            <button type="button" class="btn btn-default feedback-forward"><em class="fa fa-share">&nbsp;</em> {LANG.mark_as_forward}</button>
                            <!-- END: forward -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BEGIN: data_reply -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption><em class="fa fa-file-text-o">&nbsp;</em>Re: {DATA.title}</caption>
                <col class="w150" />
                <col />
                <tbody>
                    <tr>
                        <td style="vertical-align:top">{LANG.infor_user_send_title}</td>
                        <td> {REPLY.reply_name} &lt;{REPLY.admin_email}&gt;
                            <br />
                            {REPLY.time}
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.reply_user_send_title}</td>
                        <td>{REPLY.reply_time}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{REPLY.reply_content}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- END: data_reply -->

        <!-- BEGIN: is_user_modal -->
        <div class="modal fade" id="view-user" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{LANG.user_info}</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_fullname}</td>
                                    <td>{USER.full_name}</td>
                                    <td rowspan="3" style="width:80px">
                                        <img src="{USER.photo}" style="width:80px;height:80px" alt="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_username}</td>
                                    <td>{USER.username}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_email}</td>
                                    <td>{USER.email}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_gender}</td>
                                    <td colspan="2">{USER.gender}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_birthday}</td>
                                    <td colspan="2">{USER.birthday}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_regdate}</td>
                                    <td colspan="2">{USER.regdate}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{LANG.user_last_login}</td>
                                    <td colspan="2">{USER.last_login}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: is_user_modal -->

        <!-- BEGIN: reply_form -->
        <div class="modal fade" id="feedback-reply" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{LANG.send_title}</h4>
                    </div>
                    <form method="post" class="modal-body">
                        <input type="hidden" name="reply" value="{DATA.id}" />
                        <table class="table table-striped table-bordered">
                            <tfoot>
                                <tr>
                                    <td class="text-center" colspan="2">
                                        <button type="submit" class="btn btn-primary">{LANG.bt_send_row_title}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>{LANG.title_send_title}</td>
                                    <td class="text-center"><input name="title" type="text" value="{POST.title}" class="form-control" disabled="true" /></td>
                                </tr>
                                <tr>
                                    <td>{GLANG.email}</td>
                                    <td class="text-center"><input name="email" type="email" value="{POST.sender_email}" class="form-control" disabled="true" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">{POST.content}</td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: reply_form -->

        <!-- BEGIN: forward_form -->
        <div class="modal fade" id="feedback-forward" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{LANG.mark_as_forward}</h4>
                    </div>
                    <form method="post" class="modal-body">
                        <input type="hidden" name="forward" value="{DATA.id}" />
                        <table class="table table-striped table-bordered">
                            <tfoot>
                                <tr>
                                    <td class="text-center" colspan="2">
                                        <button type="submit" class="btn btn-primary">{LANG.bt_send_row_title}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>{LANG.title_send_title}</td>
                                    <td class="text-center"><input name="title" type="text" value="{FORWARD.title}" class="form-control" disabled="true" /></td>
                                </tr>
                                <tr>
                                    <td>{GLANG.email}</td>
                                    <td class="text-center"><input name="email" type="email" value="" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">{FORWARD.content}</td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: forward_form -->
    </div>
    <div class="col-md-10">
        <div class="panel-group" id="reply_list" role="tablist" aria-multiselectable="true">
            <!-- BEGIN: reply_loop -->
            <div class="panel panel-info">
                <a class="panel-heading collapsed" style="display: flex" data-toggle="collapse" data-parent="#reply_list" href="#collapse-{REPLY.rid}" aria-expanded="false" aria-controls="collapse-{REPLY.rid}">
                    <span style="flex-grow:1"><i class="fa {REPLY.icon}" aria-hidden="true"></i> {REPLY.type}</span>
                    <span class="pull-right">{REPLY.time}</span>
                </a>
                <div id="collapse-{REPLY.rid}" class="panel-collapse collapse" role="tabpanel">
                    <ul class="list-group">
                        <li class="list-group-item">
                            {LANG.sender}: <a href="{REPLY.sender_url}">{REPLY.sender}</a>
                        </li>
                        <li class="list-group-item">
                            {LANG.receiver}: {REPLY.reply_recipient}
                        </li>
                        <!-- BEGIN: reply_cc -->
                        <li class="list-group-item">
                            {LANG.cc}: {REPLY.reply_cc}
                        </li>
                        <!-- END: reply_cc -->
                    </ul>
                    <div class="panel-footer">
                        <div class="panel panel-primary m-bottom-none">
                            <div class="panel-body" style="white-space: normal !important;">
                                {REPLY.reply_content}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: reply_loop -->
        </div>
    </div>
</div>
<!-- END: main -->