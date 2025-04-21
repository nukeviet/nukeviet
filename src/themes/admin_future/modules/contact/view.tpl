<!-- BEGIN: main -->
<div class="row page" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" data-id="{$DATA.id}">
    <div class="col-md-14">
        <h2>
            {if !$DATA.is_processed}
            <!-- BEGIN: process --><span class="fa-solid fa-spinner fa-spin-pulse"></span><!-- END: process -->
            {else}
            <!-- BEGIN: processed --><span class="fa-solid fa-check"></span><!-- END: processed -->
            {/if}
            <strong>{$DATA.title}</strong>
        </h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="text-nowrap" style="vertical-align:top;width:1%"><strong>{$LANG->getModule('infor_user_send_title')}</strong></td>
                        <td>
                            <table style="width: fit-content;">
                                <tbody>
                                    <tr>
                                        <td class="text-right">
                                            {if !empty($DATA.sender_id)}
                                            <!-- BEGIN: is_user --><a href="javascript:void(0)" class="view_user" data-userid="{$DATA.sender_id}">{$DATA.sender_name}</a><!-- END: is_user -->
                                            {else}
                                            <!-- BEGIN: is_guest --><span>{$DATA.sender_name}</span><!-- END: is_guest -->
                                            {/if}
                                        </td>
                                        <td>&nbsp;&nbsp;&lt;{$DATA.sender_email}&gt;</td>
                                    </tr>
                                    <!-- BEGIN: sender_phone -->
                                    {if !empty($DATA.sender_phone)}
                                    <tr>
                                        <td class="text-right">{$LANG->getGlobal('phonenumber')}:</td>
                                        <td>&nbsp;&nbsp;{$DATA.sender_phone}</td>
                                    </tr>
                                    {/if}
                                    <!-- END: sender_phone -->
                                    <!-- BEGIN: sender_address -->
                                    {if !empty($DATA.sender_address)}
                                    <tr>
                                        <td class="text-right">{$LANG->getGlobal('address')}:</td>
                                        <td>&nbsp;&nbsp;{$DATA.sender_address}</td>
                                    </tr>
                                    <!-- END: sender_address -->
                                    {/if}
                                    <tr>
                                        <td class="text-right">IP:</td>
                                        <td>&nbsp;&nbsp;{$DATA.sender_ip}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">{$LANG->getModule('send_time')}:</td>
                                        <td>&nbsp;&nbsp;{$DATA.send_time}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap" style="width:1%"><strong>{$LANG->getModule('to_department')}</strong></td>
                        <td>
                            {if !empty($DEPARTMENTS[$DATA.cid])}
                            <!-- BEGIN: department_url --><a href="javascript:void(0)" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=department&amp;id={$DATA.cid}" class="department-view">{$DEPARTMENTS[$DATA.cid].full_name}</a><!-- END: department_url -->
                            {else}
                            <!-- BEGIN: department --><span>{$LANG->getModule('department_empty')}</span><!-- END: department -->
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-nowrap" style="width:1%"><strong>{$LANG->getModule('cat')}</strong></td>
                        <td>{$DATA.cat}</td>
                    </tr>
                    <tr class="active">
                        <td colspan="2">
                            <div class="panel panel-primary m-bottom-none">
                                <div class="panel-body" style="white-space: normal !important;min-height:150px">
                                    {$DATA.content}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {if !empty($DATA.auto_forward)}
        <div class="panel panel-default">
            <div class="panel-heading">
                <p><strong>{$LANG->getModule('auto_forward_to')}:</strong></p>
                {$DATA.auto_forward}
            </div>
        </div>
        {/if}
        <!-- END: auto_forward -->

        <!-- BEGIN: read_admins -->
        {if !empty($smarty.const.NV_IS_SPADMIN)}
        <div class="panel panel-default">
            <div class="panel-heading">
                <p><strong>{$LANG->getModule('has_been_read')}:</strong></p>
                {$DATA.read_admins}
            </div>
        </div>
        {/if}
        <!-- END: read_admins -->

        <!-- BEGIN: is_processed -->
        {if $DATA.is_processed}
        <div class="panel panel-success">
            <div class="panel-heading">
                <p class="alert-title"><strong>{$LANG->getModule('has_been_processed')}</strong></p>
                <!-- BEGIN: processed_person -->
                {if !empty($ADMINS[$DATA.processed_by])}
                {$LANG->getModule('processed_by')}: <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=authors&amp;id={$DATA.processed_by}">{$ADMINS[$DATA.processed_by]}</a>&nbsp;&nbsp;
                {/if}
                <!-- END: processed_person -->
                {$LANG->getModule('processed_time')}: {$DATA.processed_time|ddatetime:1}
            </div>
        </div>
        {/if}
        <!-- END: is_processed -->

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr class="active">
                        <td class="text-center">
                            {if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
                            <!-- BEGIN: reply -->
                            <button type="button" class="btn btn-default feedback-reply">{$LANG->getModule('send_title')}</button>&nbsp;
                            <!-- END: reply -->
                            {/if}
                            {if ($CONTACT_ALLOWED.exec[$DATA.cid])|isset}
                            <!-- BEGIN: exec -->
                            <button type="button" class="btn btn-default feedback_del">{$LANG->getGlobal('delete')}</button>&nbsp;
                            <button type="button" class="btn btn-default feedback_mark_single" data-mark="unread">{$LANG->getModule('mark_as_unread')}</button>&nbsp;
                            <button type="button" class="btn btn-default feedback_mark_single" data-mark="{if $DATA.is_processed}unprocess{else}processed{/if}">{if $DATA.is_processed}{$LANG->getModule('mark_as_unprocess')}{else}{$LANG->getModule('mark_as_processed')}{/if}</button>&nbsp;
                            <!-- END: exec -->
                            {/if}
                            {if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
                            <!-- BEGIN: forward -->
                            <button type="button" class="btn btn-default feedback-forward"><em class="fa fa-share">&nbsp;</em> {$LANG->getModule('mark_as_forward')}</button>
                            <!-- END: forward -->
                            {/if}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BEGIN: data_reply -->
        {* <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption><em class="fa fa-file-text-o">&nbsp;</em>Re: {$DATA.title}</caption>
                <col class="w150" />
                <col />
                <tbody>
                    <tr>
                        <td style="vertical-align:top">{$LANG->getModule('infor_user_send_title')}</td>
                        <td> {$REPLY.reply_name} &lt;{$REPLY.admin_email}&gt;
                            <br />
                            {$REPLY.time}
                        </td>
                    </tr>
                    <tr>
                        <td>{$LANG->getModule('reply_user_send_title')}</td>
                        <td>{$REPLY.reply_time}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{$REPLY.reply_content}</td>
                    </tr>
                </tbody>
            </table>
        </div> *}
        <!-- END: data_reply -->

        <!-- BEGIN: is_user_modal -->
        {if !empty($DATA.sender_id)}
        <div class="modal fade" id="view-user" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{$LANG->getModule('user_info')}</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_fullname')}</td>
                                    <td>{$USER.full_name}</td>
                                    <td rowspan="3" style="width:80px">
                                        <img src="{$USER.photo}" style="width:80px;height:80px" alt="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_username')}</td>
                                    <td>{$USER.username}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_email')}</td>
                                    <td>{$USER.email}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_gender')}</td>
                                    <td colspan="2">{$USER.gender}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_birthday')}</td>
                                    <td colspan="2">{$USER.birthday}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_regdate')}</td>
                                    <td colspan="2">{$USER.regdate}</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_last_login')}</td>
                                    <td colspan="2">{$USER.last_login}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {/if}
        <!-- END: is_user_modal -->

        <!-- BEGIN: reply_form -->
        {if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
        <div class="modal fade" id="feedback-reply" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{$LANG->getModule('send_title')}</h4>
                    </div>
                    <form method="post" class="modal-body">
                        <input type="hidden" name="reply" value="{$DATA.id}" />
                        <table class="table table-striped table-bordered">
                            <tfoot>
                                <tr>
                                    <td class="text-center" colspan="2">
                                        <button type="submit" class="btn btn-primary">{$LANG->getModule('bt_send_row_title')}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{$LANG->getGlobal('close')}</button>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>{$LANG->getModule('title_send_title')}</td>
                                    <td class="text-center"><input name="title" type="text" value="Re:{$DATA.title}" class="form-control" disabled="true"></td>
                                </tr>
                                <tr>
                                    <td>{$LANG->getGlobal('email')}</td>
                                    <td class="text-center"><input name="email" type="email" value="{$DATA.sender_email}" class="form-control" disabled="true"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">{$MESS_CONTENT}</td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        {/if}
        <!-- END: reply_form -->

        <!-- BEGIN: forward_form -->
        {if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
        <div class="modal fade" id="feedback-forward" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{$LANG->getModule('mark_as_forward')}</h4>
                    </div>
                    <form method="post" class="modal-body">
                        <input type="hidden" name="forward" value="{$DATA.id}" />
                        <table class="table table-striped table-bordered">
                            <tfoot>
                                <tr>
                                    <td class="text-center" colspan="2">
                                        <button type="submit" class="btn btn-primary">{$LANG->getModule('bt_send_row_title')}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{$LANG->getGlobal('close')}</button>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>{$LANG->getModule('title_send_title')}</td>
                                    <td class="text-center"><input name="title" type="text" value="Fwd:{$DATA.title}" class="form-control" disabled="true"></td>
                                </tr>
                                <tr>
                                    <td>{$LANG->getGlobal('email')}</td>
                                    <td class="text-center"><input name="email" type="email" value="" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">{$FORWARD_CONTENT}</td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        {/if}
        <!-- END: forward_form -->
    </div>

    <div class="col-md-10">
        <div class="panel-group" id="reply_list" role="tablist" aria-multiselectable="true">
            {foreach $REPLYLIST as $REPLY}
            <!-- BEGIN: reply_loop -->
            <div class="panel panel-info">
                <a class="panel-heading collapsed" style="display: flex" data-toggle="collapse" data-parent="#reply_list" href="#collapse-{$REPLY.rid}" aria-expanded="false" aria-controls="collapse-{$REPLY.rid}">
                    <span style="flex-grow:1"><i class="fa {$REPLY.icon}" aria-hidden="true"></i> {$REPLY.type}</span>
                    <span class="pull-right">{$REPLY.time}</span>
                </a>
                <div id="collapse-{$REPLY.rid}" class="panel-collapse collapse" role="tabpanel">
                    <ul class="list-group">
                        <li class="list-group-item">
                            {$LANG->getModule('sender')}: <a href="{$REPLY.sender_url}">{$REP_ADMINS[$REPLY.reply_aid]}</a>
                        </li>
                        <li class="list-group-item">
                            {$LANG->getModule('receiver')}: {$REPLY.reply_recipient}
                        </li>
                        <!-- BEGIN: reply_cc -->
                        {if !empty($REPLY.reply_cc)}
                        <li class="list-group-item">
                            {$LANG->getModule('cc')}: {$REPLY.reply_cc}
                            {assign var='COUNT' value=0}
                            {foreach $REPLY.reply_cc as $CC}
                            {assign var='COUNT' value=$COUNT+1}
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=authors&amp;id={$CC}">{$REP_ADMINS.$CC}</a>
                            {if $COUNT < $REPLY.reply_cc|count}
                            ,&nbsp;
                            {/if}
                            {/foreach}
                        </li>
                        {/if}
                        <!-- END: reply_cc -->
                    </ul>
                    <div class="panel-footer">
                        <div class="panel panel-primary m-bottom-none">
                            <div class="panel-body" style="white-space: normal !important;">
                                {$REPLY.reply_content}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: reply_loop -->
            {/foreach}
        </div>
    </div>
</div>
<!-- END: main -->
