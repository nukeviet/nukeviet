<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cookie_prefix">{$LANG->get('cookie_prefix')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="cookie_prefix" name="cookie_prefix" value="{$DATA['cookie_prefix']}">
                    <span class="form-text text-muted">{$LANG->get('rule_alphanumeric')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="session_prefix">{$LANG->get('session_prefix')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="session_prefix" name="session_prefix" value="{$DATA['session_prefix']}">
                    <span class="form-text text-muted">{$LANG->get('rule_alphanumeric')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="nv_live_cookie_time">{$LANG->get('live_cookie_time')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" id="nv_live_cookie_time" name="nv_live_cookie_time" value="{$NV_LIVE_COOKIE_TIME}">
                        <div class="input-group-append">
                            <span class="input-group-text">{$LANG->get('day')}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="nv_live_session_time">{$LANG->get('live_session_time')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" id="nv_live_session_time" name="nv_live_session_time" value="{$NV_LIVE_SESSION_TIME}">
                        <div class="input-group-append">
                            <span class="input-group-text">{$LANG->get('min')}</span>
                        </div>
                    </div>
                    <span class="form-text text-muted">{$LANG->get('live_session_time0')}</span>
                </div>
            </div>
            <div class="form-group row pb-0 mb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cookie_secure">{$LANG->get('cookie_secure')}</label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <label class="nv-checkbox custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="cookie_secure" id="cookie_secure" value="1"{if $DATA['cookie_secure'] eq 1} checked="checked"{/if}><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row pb-0 mb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cookie_httponly">{$LANG->get('cookie_httponly')}</label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <label class="nv-checkbox custom-control custom-checkbox custom-control-inline">
                        <input class="custom-control-input" type="checkbox" name="cookie_httponly" id="cookie_httponly" value="1"{if $DATA['cookie_httponly'] eq 1} checked="checked"{/if}><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
