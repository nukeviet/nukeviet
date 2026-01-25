<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="row g-4">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header fw-medium fs-5 py-2">Cookie</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="element_cookie_prefix" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('cookie_prefix')} <span class="text-danger">(*)</span></label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <input type="text" class="form-control alphanumeric" id="element_cookie_prefix" name="cookie_prefix" value="{$DATA.cookie_prefix}" maxlength="255">
                            <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_nv_live_cookie_time" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('live_cookie_time')} <span class="text-danger">(*)</span></label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <div class="input-group">
                                <input type="number" class="form-control" id="element_nv_live_cookie_time" name="nv_live_cookie_time" value="{$NV_LIVE_COOKIE_TIME}" min="0" max="9999" aria-label="{$LANG->getModule('live_cookie_time')}" aria-describedby="live_cookie_time_des">
                                <span class="input-group-text" id="live_cookie_time_des">{$LANG->getGlobal('day')}</span>
                            </div>
                            <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_cookie_secure" class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('cookie_secure')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="cookie_secure" value="1"{if not empty($DATA.cookie_secure)} checked{/if} role="switch" id="element_cookie_secure">
                                <label class="form-check-label" for="element_cookie_secure">{$LANG->getModule('cookie_secure_note')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_cookie_httponly" class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('cookie_httponly')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="cookie_httponly" value="1"{if not empty($DATA.cookie_httponly)} checked{/if} role="switch" id="element_cookie_httponly">
                                <label class="form-check-label" for="element_cookie_httponly">{$LANG->getModule('cookie_httponly_note')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('cookie_SameSite')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            {foreach from=$SAMESITE_ARRAY key=val item=note}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cookie_SameSite" value="{$val}" id="element_cookie_SameSite_{$val}"{if $val eq ($DATA.cookie_SameSite ?: 'Empty')} checked{/if}>
                                <label class="form-check-label" for="element_cookie_SameSite_{$val}"><code>{$val}</code>: {$note}</label>
                            </div>
                            {/foreach}
                            <div class="form-text">{$LANG->getModule('cookie_SameSite_note')}. {$LANG->getModule('cookie_SameSite_note2')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="element_cookie_share" name="cookie_share" value="1" role="switch"{if not empty($DATA.cookie_share)} checked{/if}>
                                <label class="form-check-label" for="element_cookie_share">{$LANG->getModule('cookie_share')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="element_cookie_notice_popup" name="cookie_notice_popup" value="1" role="switch"{if not empty($DATA.cookie_notice_popup)} checked{/if}>
                                <label class="form-check-label" for="element_cookie_notice_popup">{$LANG->getModule('cookie_notice_popup')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header fw-medium fs-5 py-2">Session</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="element_session_prefix" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('session_prefix')} <span class="text-danger">(*)</span></label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <input type="text" class="form-control alphanumeric" id="element_session_prefix" name="session_prefix" value="{$DATA.session_prefix}" maxlength="255">
                            <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_nv_live_session_time" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('live_session_time')} <span class="text-danger">(*)</span></label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <div class="input-group">
                                <input type="number" class="form-control" id="element_nv_live_session_time" name="nv_live_session_time" value="{$NV_LIVE_SESSION_TIME}" min="0" max="9999" aria-label="{$LANG->getModule('live_session_time')}" aria-describedby="nv_live_session_time_des">
                                <span class="input-group-text" id="nv_live_session_time_des">{$LANG->getGlobal('min')}</span>
                            </div>
                            <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                            <div class="form-text">{$LANG->getModule('live_session_time0')}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="checkss" value="{$DATA.checkss}">
</form>
