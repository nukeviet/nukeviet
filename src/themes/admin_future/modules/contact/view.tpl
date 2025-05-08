<div class="row page" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" data-id="{$DATA.id}" data-checkss="{$CHECKSS}">
    <div class="col-lg-8 col-xxl-7">
        <div class="card">
            <div class="card-header fs-5">
                {if !$DATA.is_processed}
                <span class="fa-solid fa-spinner fa-spin-pulse"></span>
                {else}
                <span class="fa-solid fa-check"></span>
                {/if}
                <span class="fs-medium">{$DATA.title}</span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>{$LANG->getModule('infor_user_send_title')}:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {if !empty($DATA.sender_id)}
                            <a href="javascript:void(0)" class="view_user" data-bs-toggle="modal" data-bs-target="#view-user" data-userid="{$DATA.sender_id}">{$DATA.sender_name}</a>
                            {else}
                            <span>{$DATA.sender_name}</span>
                            {/if}
                            <span>&nbsp;&nbsp;&lt;{$DATA.sender_email}&gt;</span>
                        </div>
                    </div>
                </li>
                {if !empty($DATA.sender_phone)}
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>{$LANG->getGlobal('phonenumber')}:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {$DATA.sender_phone}                           
                        </div>
                    </div>
                </li>
                {/if}
                {if !empty($DATA.sender_address)}
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>{$LANG->getGlobal('address')}:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {$DATA.sender_address}                           
                        </div>
                    </div>
                </li>
                {/if}
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>IP:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {$DATA.sender_ip}                          
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>{$LANG->getModule('send_time')}:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {$DATA.send_time}                          
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>{$LANG->getModule('to_department')}:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {if !empty($DEPARTMENTS[$DATA.cid])}
                            <a href="javascript:void(0)" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=department&amp;id={$DATA.cid}" class="department-view">{$DEPARTMENTS[$DATA.cid].full_name}</a>
                            {else}
                            <span>{$LANG->getModule('department_empty')}</span>
                            {/if}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-3">
                        <div class="col-5 col-sm-4 col-lg-3 col-xxl-2 text-end">
                            <strong>{$LANG->getModule('cat')}:</strong>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-9 col-xxl-10">
                            {$DATA.cat}                          
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div style="min-height:150px">
                        {$DATA.content}
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="vstack gap-3">
            {if !empty($DATA.auto_forward)}
            <div class="card p-2">
                <div class="card-header">
                    <strong>{$LANG->getModule('auto_forward_to')}:</strong>
                </div>
                <div class="card-body">
                    {$DATA.auto_forward}
                </div>
            </div>
            {/if}

            {if !empty($smarty.const.NV_IS_SPADMIN)}
            <div class="card p-2">
                <div class="card-header">
                    <strong>{$LANG->getModule('has_been_read')}:</strong>
                </div>
                <div class="card-body">
                    {$DATA.read_admins}
                </div>
            </div>
            {/if}

            {if $DATA.is_processed}
            <div class="card p-2 bg-success text-white">
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
                    <button type="button" class="btn btn-secondary feedback_del"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getGlobal('delete')}</button>
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
                        <span class="flex-grow-1"><i class="fa-solid {$REPLY.icon}" aria-hidden="true"></i> {$REPLY.type}</span>
                        <span class="text-end">{$REPLY.time}&nbsp;</span>
                    </button>
                </div>
                <div id="reply-list-collapse{$REPLY.rid}" class="accordion-collapse collapse" role="tabpanel" aria-labelledby="reply-list-heading{$REPLY.rid}" data-bs-parent="#accordion-reply-list">
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
                        <li class="list-group-item">
                            {$REPLY.reply_content}
                        </li>
                    </ul>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>

{if !empty($DATA.sender_id)}
<div class="modal fade" id="view-user" tabindex="-1" role="dialog" aria-labelledby="userModalTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userModalTitle">{$LANG->getModule('user_info')}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
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
</div>
{/if}

{if ($CONTACT_ALLOWED.reply[$DATA.cid])|isset}
<div class="modal fade" id="feedback-reply" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="replyModalTitle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="replyModalTitle">{$LANG->getModule('send_title')}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="modal-body">
                    <div class="row mb-3">
                        <label class="col-xl-2 col-form-label text-xl-end" for="title-reply">{$LANG->getModule('title_send_title')}</label>
                        <div class="col-xl-9">
                            <input name="title" type="text" id="title-reply" value="Re:{$DATA.title}" class="form-control" disabled="true">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-xl-2 col-form-label text-xl-end" for="email-reply">{$LANG->getModule('email')}</label>
                        <div class="col-xl-9">
                            <input name="email" type="email" id="email-reply" value="{$DATA.sender_email}" class="form-control" disabled="true">
                        </div>
                    </div>
                    <div class="row mb-3">
                        {$MESS_CONTENT}
                    </div>
                    <div class="row row-cols-auto justify-content-center g-2">
                        <div class="col">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> {$LANG->getModule('bt_send_row_title')}</button>
                        </div>
                        <div class="col">
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

<div class="modal fade" id="feedback-forward" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="forwardModalTitle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="forwardModalTitle">{$LANG->getModule('mark_as_forward')}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="modal-body">
                    <div class="row mb-3">
                        <label class="col-xl-2 col-form-label text-xl-end" for="title-forward">{$LANG->getModule('title_send_title')}</label>
                        <div class="col-xl-9">
                            <input name="title" id="title-forward" type="text" value="Fwd:{$DATA.title}" class="form-control" disabled="true">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-xl-2 col-form-label text-xl-end" for="email-forward">{$LANG->getModule('email')}</label>
                        <div class="col-xl-9">
                            <input name="email" type="email" id="email-forward" value="" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        {$FORWARD_CONTENT}
                    </div>
                    <div class="row row-cols-auto justify-content-center g-2">
                        <div class="col">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> {$LANG->getModule('bt_send_row_title')}</button>
                        </div>
                        <div class="col">
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
