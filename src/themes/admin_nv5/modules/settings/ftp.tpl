{if not $SYS_INFO['ftp_support']}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('ftp_error_support')}</div>
</div>
{else}
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_server">{$LANG->get('server')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="ftp_server" name="ftp_server" value="{$DATA['ftp_server']}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pr-2 pl-2">{$LANG->get('port')}</div>
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="ftp_port" name="ftp_port" value="{$DATA['ftp_port']}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_user_name">{$LANG->get('username')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="ftp_user_name" name="ftp_user_name" value="{$DATA['ftp_user_name']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_user_pass">{$LANG->get('password')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="password" class="form-control form-control-sm" id="ftp_user_pass" name="ftp_user_pass" value="{$DATA['ftp_user_pass']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_path">{$LANG->get('ftp_path')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="ftp_path" name="ftp_path" value="{$DATA['ftp_path']}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" id="autodetectftp" data-errormsg="{$LANG->get('ftp_error_full')}"><i class="icon icon-left fas fa-bolt"></i> {$LANG->get('ftp_auto_detect_root')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{/if}
