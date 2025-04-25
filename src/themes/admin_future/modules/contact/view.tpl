<div class="row page" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" data-id="{$DATA.id}" data-checkss="{$CHECKSS}">
    <div class="col-lg-8 col-xxl-7">
        <div class="card">
            <div class="card-header .h2">
                {if !$DATA.is_processed}
                <span class="fa-solid fa-spinner fa-spin-pulse"></span>
                {else}
                <span class="fa-solid fa-check"></span>
                {/if}
                <strong>{$DATA.title}</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card m-1">
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
                                                    <a href="javascript:void(0)" class="view_user" data-bs-toggle="modal" data-bs-target="#view-user" data-userid="{$DATA.sender_id}">{$DATA.sender_name}</a>
                                                    {else}
                                                    <span>{$DATA.sender_name}</span>
                                                    {/if}
                                                </td>
                                                <td>&nbsp;&nbsp;&lt;{$DATA.sender_email}&gt;</td>
                                            </tr>
                                            {if !empty($DATA.sender_phone)}
                                            <tr>
                                                <td class="text-right">{$LANG->getGlobal('phonenumber')}:</td>
                                                <td>&nbsp;&nbsp;{$DATA.sender_phone}</td>
                                            </tr>
                                            {/if}
                                            {if !empty($DATA.sender_address)}
                                            <tr>
                                                <td class="text-right">{$LANG->getGlobal('address')}:</td>
                                                <td>&nbsp;&nbsp;{$DATA.sender_address}</td>
                                            </tr>
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
                                    <a href="javascript:void(0)" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=department&amp;id={$DATA.cid}" class="department-view">{$DEPARTMENTS[$DATA.cid].full_name}</a>
                                    {else}
                                    <span>{$LANG->getModule('department_empty')}</span>
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
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        {if !empty($DATA.auto_forward)}
        <div class="card mt-3 mt-lg-0">
            <div class="card-header">
                <strong>{$LANG->getModule('auto_forward_to')}:</strong>
            </div>
            <div class="card-body">
                {$DATA.auto_forward}
            </div>
        </div>
        {/if}

        {if !empty($smarty.const.NV_IS_SPADMIN)}
        <div class="card mt-3">
            <div class="card-header">
                <strong>{$LANG->getModule('has_been_read')}:</strong>
            </div>
            <div class="card-body">
                {$DATA.read_admins}
            </div>
        </div>
        {/if}

        {if $DATA.is_processed}
        <div class="card mt-3 bg-success text-white">
            <div class="card-header">
                <strong>{$LANG->getModule('has_been_processed')}</strong>
            </div>
            <div class="card-body">
                {if !empty($ADMINS[$DATA.processed_by])}
                {$LANG->getModule('processed_by')}: <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=authors&amp;id={$DATA.processed_by}">{$ADMINS[$DATA.processed_by]}</a>&nbsp;&nbsp;
                {/if}
                {$LANG->getModule('processed_time')}: {$DATA.processed_time|ddatetime:1}
            </div>
        </div>
        {/if}
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-xxl-7">
        <div class="card mt-3">
            <div class="card-body text-center row row-cols-auto justify-content-center g-2">
                {if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
                <div class="col">
                    <button type="button" class="btn btn-secondary feedback-reply" data-bs-toggle="modal" data-bs-target="#feedback-reply"><i class="fa-solid fa-reply"></i> {$LANG->getModule('send_title')}</button>
                </div>
                {/if}
                {if ($CONTACT_ALLOWED.exec[$DATA.cid])|isset}
                <div class="col">
                    <button type="button" class="btn btn-secondary feedback_del"><i class="fa-solid fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-secondary feedback_mark_single" data-mark="unread"><i class="fa-solid fa-bookmark"></i> {$LANG->getModule('mark_as_unread')}</button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-secondary feedback_mark_single" data-mark="{if $DATA.is_processed}unprocess{else}processed{/if}">{if $DATA.is_processed}<i class="fa-regular fa-circle"></i> {$LANG->getModule('mark_as_unprocess')}{else}<i class="fa-regular fa-circle-check"></i> {$LANG->getModule('mark_as_processed')}{/if}</button>
                </div>
                {/if}
                {if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
                <div class="col">
                    <button type="button" class="btn btn-secondary feedback-forward" data-bs-toggle="modal" data-bs-target="#feedback-forward"><i class="fa-solid fa-share"></i> {$LANG->getModule('mark_as_forward')}</button>
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-8 col-xxl-7">
    <div class="accordion" id="accordion-reply-list" role="tablist" aria-multiselectable="true">
        {foreach $REPLYLIST as $REPLY}
        <div class="accordion-item">
            <div class="accordion-header">
                <button type="button" role="tab" id="reply-list-heading{$REPLY.rid}" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#reply-list-collapse{$REPLY.rid}" aria-expanded="false" aria-controls="reply-list-collapse{$REPLY.rid}">
                    <span style="flex-grow:1"><i class="fa-solid {$REPLY.icon}" aria-hidden="true"></i> {$REPLY.type}</span>
                    <span class="pull-right">{$REPLY.time}&nbsp;</span>
                </button>
            </div>
            <div id="reply-list-collapse{$REPLY.rid}" class="accordion-collapse collapse" role="tabpanel" aria-labelledby="reply-list-heading{$REPLY.rid}" data-bs-parent="#accordion-reply-list">
                <div class="card m-1">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            {$LANG->getModule('sender')}: <a href="{$REPLY.sender_url}">{$REP_ADMINS[$REPLY.reply_aid]}</a>
                        </li>
                        <li class="list-group-item">
                            {$LANG->getModule('receiver')}: {$REPLY.reply_recipient}
                        </li>
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
                    </ul>
                    <div class="card-footer">
                        <div class="card">
                            <div class="card-body" style="white-space: normal !important;">
                                {$REPLY.reply_content}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
</div>

{if !empty($DATA.sender_id)}
<div class="modal fade" id="view-user" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{$LANG->getModule('user_info')}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td class="text-nowrap" style="width:1%">{$LANG->getModule('user_fullname')}</td>
                            <td>{$USER.full_name}</td>
                            <td rowspan="3" style="width:80px">
                                <img src="{$USER.photo}" style="width:80px;height:80px" alt="">
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

{if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
<div class="modal fade" id="feedback-reply" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{$LANG->getModule('send_title')}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label text-sm-end">{$LANG->getModule('title_send_title')}</div>
                                <div class="col-sm-9">
                                    <input name="title" type="text" value="Re:{$DATA.title}" class="form-control" disabled="true">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label text-sm-end">{$LANG->getModule('email')}</div>
                                <div class="col-sm-9">
                                    <input name="email" type="email" value="{$DATA.sender_email}" class="form-control" disabled="true">
                                </div>
                            </div>
                            <div class="row">
                                {$MESS_CONTENT}
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('bt_send_row_title')}</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                        </div>
                    </div>
                    <input type="hidden" name="reply" value="{$DATA.id}">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="feedback-forward" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{$LANG->getModule('mark_as_forward')}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label text-sm-end">{$LANG->getModule('title_send_title')}</div>
                                <div class="col-sm-9">
                                    <input name="title" type="text" value="Fwd:{$DATA.title}" class="form-control" disabled="true">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-2 col-form-label text-sm-end">{$LANG->getModule('email')}</div>
                                <div class="col-sm-9">
                                    <input name="email" type="email" value="" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                {$FORWARD_CONTENT}
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('bt_send_row_title')}</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                        </div>
                    </div>
                    <input type="hidden" name="forward" value="{$DATA.id}">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                </form>
            </div>
        </div>
    </div>
</div>
{/if}
