<!-- BEGIN: main -->
<form id="variables-settings" class="form-horizontal ajax-submit" action="{FORM_ACTION}" method="post">
    <div class="panel-group" id="accordion-settings" role="tablist" aria-multiselectable="true">
        <div class="panel panel-primary">
            <a role="tab" id="headingOne" class="panel-heading" style="display: block;text-decoration:none" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong>Cookie</strong>
            </a>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <ul class="list-group type2n1">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.cookie_prefix}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <input class="w200 required alphanumeric form-control" type="text" name="cookie_prefix" value="{DATA.cookie_prefix}" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.live_cookie_time}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <div class="input-group w150">
                                    <input class="required form-control number" type="text" name="nv_live_cookie_time" value="{NV_LIVE_COOKIE_TIME}" />
                                    <span class="input-group-addon">{GLANG.day}</span>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label hidden-xs">
                                <strong>{LANG.cookie_secure}</strong><br />
                                <span class="help-block">{LANG.cookie_secure_note}</span>
                            </label>
                            <div class="col-sm-16 col-md-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="cookie_secure" {CHECKBOX_COOKIE_SECURE} />
                                    <strong class="visible-xs-inline-block">{LANG.cookie_secure}</strong>
                                </label>
                                <div class="help-block visible-xs-inline-block">{LANG.cookie_secure_note}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label hidden-xs">
                                <strong>{LANG.cookie_httponly}</strong><br />
                                <span class="help-block">{LANG.cookie_httponly_note}</span>
                            </label>
                            <div class="col-sm-16 col-md-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="cookie_httponly" {CHECKBOX_COOKIE_HTTPONLY} />
                                    <strong class="visible-xs-inline-block">{LANG.cookie_httponly}</strong>
                                </label>
                                <div class="help-block visible-xs-inline-block">{LANG.cookie_httponly_note}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label">
                                <strong>{LANG.cookie_SameSite}</strong><br />
                                <span class="help-block">{LANG.cookie_SameSite_note}. {LANG.cookie_SameSite_note2}</span>
                            </label>
                            <div class="col-sm-16 col-md-14">
                                <!-- BEGIN: SameSite -->
                                <div>
                                    <label class="pointer"><input type="radio" name="cookie_SameSite" value="{SAMESITE.val}" {SAMESITE.checked} /> <code>{SAMESITE.val}</code>: {SAMESITE.note}</label>
                                </div>
                                <!-- END: SameSite -->
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label hidden-xs">
                                <strong>{LANG.cookie_share}</strong>
                            </label>
                            <div class="col-sm-16 col-md-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="cookie_share" {CHECKBOX_COOKIE_SHARE} />
                                    <strong class="visible-xs-inline-block">{LANG.cookie_share}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label hidden-xs">
                                <strong>{LANG.cookie_notice_popup}</strong>
                            </label>
                            <div class="col-sm-16 col-md-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="cookie_notice_popup" {CHECKED_COOKIE_NOTICE_POPUP} />
                                    <strong class="visible-xs-inline-block">{LANG.cookie_notice_popup}</strong>
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <a role="tab" id="headingTwo" class="panel-heading" style="display: block;text-decoration:none" data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <strong>Session</strong>
            </a>
            <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                <ul class="list-group type2n1">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.session_prefix}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <input class="w200 required alphanumeric form-control" type="text" name="session_prefix" value="{DATA.session_prefix}" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.live_session_time}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <div class="input-group w150">
                                    <input class="required form-control number" type="text" name="nv_live_session_time" value="{NV_LIVE_SESSION_TIME}" />
                                    <span class="input-group-addon">{GLANG.min}</span>
                                </div>
                                <div class="help-block">{LANG.live_session_time0}</div>
                            </div>
                        </div>
                    </li>


                </ul>
            </div>
        </div>
    </div>
    <div class="text-center">
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
    </div>
</form>
<!-- END: main -->