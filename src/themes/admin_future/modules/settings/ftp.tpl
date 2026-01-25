<div class="card">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate data-error="{$LANG->getModule('ftp_error_full')}">
            <div class="row mb-3">
                <label for="element_ftp_server" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('server')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="element_ftp_server" name="ftp_server" value="{$DATA.ftp_server}">
                        <span class="input-group-text" id="ftp_port_des">{$LANG->getModule('port')}</span>
                        <input type="number" min="1" max="99999" value="{$DATA.ftp_port}" class="form-control text-center fw-75 flex-grow-0" name="ftp_port" aria-describedby="ftp_port_des">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_ftp_user_name" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('username')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_ftp_user_name" name="ftp_user_name" value="{$DATA.ftp_user_name}">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_ftp_user_pass" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('password')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="password" autocomplete="off" class="form-control" id="element_ftp_user_pass" name="ftp_user_pass" value="{$DATA.ftp_user_pass}">
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_ftp_path" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ftp_path')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="element_ftp_path" name="ftp_path" value="{$DATA.ftp_path}">
                        <button type="button" id="autodetectftp" class="btn btn-secondary" title="{$LANG->getModule('ftp_auto_detect_root')}" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('ftp_auto_detect_root')}" data-bs-trigger="hover" aria-label="{$LANG->getModule('ftp_auto_detect_root')}"><i class="fa-solid fa-retweet" data-icon="fa-retweet"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
