{if $IS_WIN}
<div class="alert alert-info">
    {$LANG->getModule('s2_winhost_info')}
    {if $NEXTSTEP}
    {$LANG->getModule('s2_winhost_info1')} <span class="text-success fw-medium">OK</span> {$LANG->getModule('s2_winhost_info2')}.
    {else}
    {$LANG->getModule('s2_winhost_info3')} <a href="{$STEP_URL}2">{$LANG->getModule('s2_winhost_info4')}</a> {$LANG->getModule('s2_winhost_info5')}.
    {/if}
</div>
{/if}
{if $SHOW_FTP}
<form action="{$ACTIONFORM}" method="post" id="ftpconfig" class="card mb-4" data-toggle="ftpForm" data-error-empty="{$LANG->getModule('ftp_error_empty')|escape}">
    <div class="card-header fw-medium">{$LANG->getModule('ftpconfig_note')}</div>
    <div class="card-body">
        {if not empty($FTPDATA.error)}
        <div class="alert alert-danger">{$FTPDATA.error}</div>
        {/if}
        <div class="row g-3">
            <div class="col-sm-9">
                <label for="ftp_server" class="form-label">{$LANG->getModule('ftp_server')}</label>
                <input type="text" class="form-control" id="ftp_server" name="ftp_server" value="{$FTPDATA.ftp_server}">
                <div class="form-text">{$LANG->getModule('ftp_server_note')}</div>
            </div>
            <div class="col-sm-3">
                <label for="ftp_port" class="form-label">{$LANG->getModule('ftp_port')}</label>
                <input type="text" class="form-control" id="ftp_port" name="ftp_port" value="{$FTPDATA.ftp_port}">
            </div>
            <div class="col-sm-6">
                <label for="ftp_user_name" class="form-label">{$LANG->getModule('ftp_user')}</label>
                <input type="text" class="form-control" id="ftp_user_name" name="ftp_user_name" value="{$FTPDATA.ftp_user_name}">
                <div class="form-text">{$LANG->getModule('ftp_user_note')}</div>
            </div>
            <div class="col-sm-6">
                <label for="ftp_user_pass" class="form-label">{$LANG->getModule('ftp_pass')}</label>
                <input type="password" class="form-control" id="ftp_user_pass" name="ftp_user_pass" value="{$FTPDATA.ftp_user_pass}" autocomplete="off">
                <div class="form-text">{$LANG->getModule('ftp_pass_note')}</div>
            </div>
            <div class="col-12">
                <label for="ftp_path" class="form-label">{$LANG->getModule('ftp_path')}</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="ftp_path" name="ftp_path" value="{$FTPDATA.ftp_path}">
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
{/if}
<p>{$LANG->getModule('if_chmod')} <span class="text-danger">{$LANG->getModule('not_compatible')}</span>. {$LANG->getModule('please_chmod')}.</p>
<div class="table-responsive">
    <table id="checkchmod" class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>{$LANG->getModule('listchmod')}</th>
                <th class="text-end">{$LANG->getModule('result')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$DIRS item=row}
            <tr>
                <td><code class="text-body">{$row.dir}</code></td>
                <td class="text-end"><span class="badge {if $row.ok}text-bg-success{else}text-bg-danger{/if}">{$row.check}</span></td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$STEP_URL}1"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    {if $NEXTSTEP}
    <span class="next_step"><a class="btn btn-primary" href="{$STEP_URL}3">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
    {/if}
</div>
