{if $SUBSTEP eq 1}
{* Bước con 1: Sao lưu CSDL và code trước khi nâng cấp *}
{if $DATA.data_message}
<div class="alert alert-success">{$DATA.data_message}</div>
{/if}
{if $DATA.is_data_backup}
<div class="alert alert-warning text-center">
    <p>{$LANG->getModule('update_info_dump')}</p>
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
        <a class="btn btn-sm btn-primary" href="{$DATA.url_dump_db}&amp;type=sql" data-toggle="updateDump" data-target="#dump-db-result"><i class="fa-solid fa-database"></i> {$LANG->getModule('update_dump')} sql</a>
        <span>{$LANG->getModule('update_or')}</span>
        <a class="btn btn-sm btn-primary" href="{$DATA.url_dump_db}&amp;type=gz" data-toggle="updateDump" data-target="#dump-db-result"><i class="fa-solid fa-file-zipper"></i> {$LANG->getModule('update_dump')} gz</a>
    </div>
    <div id="dump-db-result" class="mt-2"></div>
</div>
{else}
<div class="alert alert-danger">{$LANG->getModule('update_data_not_allow')}</div>
{/if}
{if $DATA.file_message}
<div class="alert alert-success">{$DATA.file_message}</div>
{/if}
{if $DATA.is_file_backup}
<div class="alert alert-warning text-center">
    <p>{$LANG->getModule('update_file_backup_info')}</p>
    <a class="btn btn-sm btn-primary" href="{$DATA.url_dump_file}" data-toggle="updateDump" data-target="#dump-file-result"><i class="fa-solid fa-file-zipper"></i> {$LANG->getModule('update_file_backup')}</a>
    <div id="dump-file-result" class="mt-2"></div>
</div>
{/if}
<div class="alert alert-info mb-0">{$LANG->getModule('update_refuse_dump')}</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$UPDATE_URL}?step=1"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    <span class="next_step"><a class="btn btn-primary" href="{$UPDATE_URL}?step=2&amp;substep=2">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
</div>
{elseif $SUBSTEP eq 2}
{* Bước con 2: Danh sách công việc sẽ thực hiện *}
{if $CONFIG.update_auto_type eq 0}
<div class="alert alert-warning">{$LANG->getModule('update_manual')}.</div>
<div class="install-license">{$DATA.guide}</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$UPDATE_URL}?step=2&amp;substep=1"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    <span class="next_step"><a class="btn btn-primary" href="{$UPDATE_URL}?step=3">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
</div>
{else}
{if $DATA.is_move_file}
<div class="alert alert-warning">{$LANG->getModule('update_semiautomatic')}.</div>
{elseif $CONFIG.update_auto_type eq 1}
<div class="alert alert-warning">{$LANG->getModule('update_automatic')}.</div>
{else}
<div class="alert alert-info">{$LANG->getModule('update_info_list_prosess')}.</div>
{/if}
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-medium">{$LANG->getModule('update_data_work')}</div>
            {if not empty($DATA.data_list)}
            <ul class="list-group list-group-flush update-list">
                {foreach from=$DATA.data_list item=row}
                <li class="list-group-item">{$row.title}</li>
                {/foreach}
            </ul>
            {else}
            <div class="card-body text-center text-body-secondary">{$LANG->getModule('update_empty_work')}</div>
            {/if}
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-medium">{$LANG->getModule('update_file_work')}</div>
            {if not empty($DATA.file_list)}
            <ul class="list-group list-group-flush update-list">
                {foreach from=$DATA.file_list item=row}
                <li class="list-group-item"><code class="text-body">{$row}</code></li>
                {/foreach}
            </ul>
            {else}
            <div class="card-body text-center text-body-secondary">{$LANG->getModule('update_empty_work')}</div>
            {/if}
        </div>
    </div>
</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$UPDATE_URL}?step=2&amp;substep=1"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    <span class="next_step"><a class="btn btn-primary" href="{$UPDATE_URL}?step=2&amp;substep=3">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
</div>
{/if}
{elseif $SUBSTEP eq 3}
{* Bước con 3: Chạy lần lượt các tác vụ cập nhật CSDL *}
{if $DATA.errorStepMoveFile}
<div class="alert alert-danger mb-0">
    <p>{$LANG->getModule('update_substep3_error_file')}</p>
    <a class="btn btn-sm btn-primary" href="{$UPDATE_URL}?step=2&amp;substep=3"><i class="fa-solid fa-rotate"></i> {$LANG->getModule('update_substep3_moved')}</a>
</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$UPDATE_URL}?step=2&amp;substep=2"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
</div>
{else}
<div class="row g-3" id="update-tasks"
    data-update-url="{$UPDATE_URL}"
    data-next-step-url="{$DATA.NextStepUrl}"
    data-next-funcs="{$DATA.nextfunction}"
    data-next-funcs-name="{$DATA.nextftitle|escape}"
    data-lang-nav-confirm="{$LANG->getModule('update_nav_confirm')|escape}"
    data-lang-taskiload="{$LANG->getModule('update_taskiload')|escape}"
    data-lang-taskierror="{$LANG->getModule('update_taskierror')|escape}"
    data-lang-taskiwarn="{$LANG->getModule('update_taskiwarn')|escape}"
    data-lang-taskiok="{$LANG->getModule('update_taskiok')|escape}"
    data-lang-do1-error="{$LANG->getModule('update_task_do1_error')|escape}"
    data-lang-do2-error="{$LANG->getModule('update_task_do2_error', $CONFIG.support_website)|escape}"
    data-lang-all-complete="{$LANG->getModule('update_task_all_complete')|escape}"
    data-lang-all-complete-alert="{$LANG->getModule('update_task_all_complete_alert')|escape}"
    data-lang-task-load="{$LANG->getModule('update_task_load')|escape}"
    data-lang-task-load-message="{$LANG->getModule('update_task_load_message')|escape}"
    data-lang-next-step="{$LANG->getModule('next_step')|escape}">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header fw-medium">{$LANG->getModule('update_all_work')}</div>
            <ul class="list-group list-group-flush update-list">
                {foreach from=$DATA.task item=row}
                <li class="list-group-item update-task{$row.class}" id="{$row.id}" title="{$row.status}"><i class="fa-solid task-icon"></i> {$row.title}</li>
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header fw-medium">{$LANG->getModule('update_current_work')}</div>
            <div class="card-body">
                {if not empty($DATA.stopprocess)}
                <div class="alert alert-danger mb-0">{$DATA.error_message}</div>
                {elseif $DATA.AllPassed}
                <div class="alert alert-success mb-0">{$LANG->getModule('update_task_all_complete')}.</div>
                {else}
                <div id="nv-message" class="text-center">
                    <p>{$LANG->getModule('update_task_next')} <strong>&quot;{$DATA.nextftitle}&quot;</strong></p>
                    <button type="button" class="btn btn-primary" data-toggle="updateTaskStart"><i class="fa-solid fa-play"></i> {$LANG->getModule('update_task_start')}</button>
                </div>
                <div id="nv-loading" class="text-center" hidden></div>
                {/if}
            </div>
        </div>
    </div>
</div>
<div class="install-nav" id="control_t">
    <a class="btn btn-outline-secondary back_step" href="{$UPDATE_URL}?step=2&amp;substep=2"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    {if $DATA.AllPassed and empty($DATA.stopprocess)}
    <span class="next_step"><a class="btn btn-primary" href="{$DATA.NextStepUrl}">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
    {/if}
</div>
{/if}
{elseif $SUBSTEP eq 4}
{* Bước con 4: Di chuyển các file của gói nâng cấp *}
{if not $DATA.getcomplete and not empty($DATA.file_list)}
{if $DATA.is_win}
<div class="alert alert-warning">
    <p>{$LANG->getModule('update_file_info_win')}</p>
    <a class="btn btn-sm btn-outline-secondary" href="{$UPDATE_URL}?step=2&amp;substep=4&amp;complete=1">{$LANG->getModule('update_file_info_win_manual')}</a>
</div>
{/if}
{if $DATA.FTP_nosupport}
<div class="alert alert-warning" id="ftp_nosupport">
    <p>{$LANG->getModule('update_ftp_nosupport')}</p>
    <a class="btn btn-sm btn-outline-secondary" href="{$UPDATE_URL}?step=2&amp;substep=4&amp;complete=1">{$LANG->getModule('update_file_info_win_manual')}</a>
</div>
{elseif $DATA.check_FTP}
<div id="check_ftp">
    <div class="alert alert-info">{$LANG->getModule('update_ftp_config_info')}</div>
    <form action="{$DATA.action_form}" method="post" id="ftpconfigform" class="card mb-4" data-error-empty="{$LANG->getModule('ftp_error_empty')|escape}">
        <div class="card-body">
            {if not empty($DATA.ftpdata.error) and $DATA.ftpdata.show_ftp_error}
            <div class="alert alert-danger">{$DATA.ftpdata.error}</div>
            {/if}
            <div class="row g-3">
                <div class="col-sm-9">
                    <label for="ftp_server" class="form-label">{$LANG->getModule('ftp_server')}</label>
                    <input type="text" class="form-control" id="ftp_server" name="ftp_server" value="{$DATA.ftpdata.ftp_server}">
                    <div class="form-text">{$LANG->getModule('ftp_server_note')}</div>
                </div>
                <div class="col-sm-3">
                    <label for="ftp_port" class="form-label">{$LANG->getModule('ftp_port')}</label>
                    <input type="text" class="form-control" id="ftp_port" name="ftp_port" value="{$DATA.ftpdata.ftp_port}">
                </div>
                <div class="col-sm-6">
                    <label for="ftp_user_name" class="form-label">{$LANG->getModule('ftp_user')}</label>
                    <input type="text" class="form-control" id="ftp_user_name" name="ftp_user_name" value="{$DATA.ftpdata.ftp_user_name}">
                    <div class="form-text">{$LANG->getModule('ftp_user_note')}</div>
                </div>
                <div class="col-sm-6">
                    <label for="ftp_user_pass" class="form-label">{$LANG->getModule('ftp_pass')}</label>
                    <input type="password" class="form-control" id="ftp_user_pass" name="ftp_user_pass" value="{$DATA.ftpdata.ftp_user_pass}" autocomplete="off">
                    <div class="form-text">{$LANG->getModule('ftp_pass_note')}</div>
                </div>
                <div class="col-12">
                    <label for="ftp_path" class="form-label">{$LANG->getModule('ftp_path')}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="ftp_path" name="ftp_path" value="{$DATA.ftpdata.ftp_path}">
                        <button class="btn btn-outline-secondary" type="button" data-toggle="findFtpPath">{$LANG->getModule('ftp_path_find')}</button>
                    </div>
                    <div class="form-text">{$LANG->getModule('ftp_path_note')}</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="hidden" name="modftp" value="1">
            <button class="btn btn-primary" type="submit">{$LANG->getModule('refesh')}</button>
        </div>
    </form>
</div>
{/if}
{/if}
<div class="row g-3" id="update-move"
    data-update-url="{$UPDATE_URL}"
    data-next-step-url="{$DATA.NextStepUrl}"
    data-ok-message="{$DATA.ok_message|escape}"
    data-lang-nav-confirm="{$LANG->getModule('update_nav_confirm')|escape}"
    data-lang-load-waiting="{$LANG->getModule('update_load_waiting')|escape}"
    data-lang-move-redo="{$LANG->getModule('update_move_redo')|escape}"
    data-lang-move-redo-message="{$LANG->getModule('update_move_redo_message')|escape}"
    data-lang-move-redo-manual="{$LANG->getModule('update_move_redo_manual')|escape}"
    data-lang-next-step="{$LANG->getModule('next_step')|escape}">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header fw-medium">{$LANG->getModule('update_file_list')}</div>
            <ul class="list-group list-group-flush update-list">
                {foreach from=$DATA.all_files item=row}
                <li class="list-group-item update-task {$row.status}" id="file-{$row.id}"><i class="fa-solid task-icon"></i> <code class="text-body">{$row.name}</code></li>
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header fw-medium">{$LANG->getModule('update_file_info')}</div>
            <div class="card-body">
                {if empty($DATA.file_list)}
                <div class="alert alert-success mb-0">{$DATA.ok_message}</div>
                {else}
                <div id="nv-toolmove" class="alert alert-info">
                    <p>{$DATA.process_message}</p>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="updateMoveStart"><i class="fa-solid fa-play"></i> {$LANG->getModule('update_move_start')}</button>
                </div>
                <div id="nv-message" hidden></div>
                {if $DATA.note_message}
                <div class="alert alert-danger mb-0">{$DATA.note_message}</div>
                {/if}
                {/if}
            </div>
        </div>
    </div>
</div>
<div class="install-nav" id="control_t">
    <a class="btn btn-outline-secondary back_step" href="{$DATA.BackStepUrl}"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    {if empty($DATA.file_list)}
    <span class="next_step"><a class="btn btn-primary" href="{$DATA.NextStepUrl}">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
    {/if}
</div>
{elseif $SUBSTEP eq 5}
{* Bước con 5: Hướng dẫn nâng cấp thủ công *}
{if $DATA.error}
<div class="alert alert-danger mb-0">{$LANG->getModule('update_step5_info_error')}</div>
{else}
<div class="alert alert-info">{$LANG->getModule('update_step5_info')}</div>
<div class="install-license">{$DATA.guide}</div>
{/if}
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$DATA.BackStepUrl}"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    <span class="next_step"><a class="btn btn-primary" href="{$UPDATE_URL}?step=3">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
</div>
{/if}
