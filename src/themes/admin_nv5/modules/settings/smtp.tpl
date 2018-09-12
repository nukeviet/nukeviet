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
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('mail_config')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="mailer_mode" value="smtp"{if $DATA['mailer_mode'] eq 'smtp'} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('type_smtp')}</span>
                    </label>
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="mailer_mode" value="sendmail"{if $DATA['mailer_mode'] eq 'sendmail'} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('type_linux')}</span>
                    </label>
                    <label class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="mailer_mode" value=""{if $DATA['mailer_mode'] eq ''} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('type_phpmail')}</span>
                    </label>
                </div>
            </div>
            <div id="mailer_mode_smtp"{if $DATA['mailer_mode'] neq 'smtp'} class="d-none"{/if}>
                <div class="row">
                    <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                        <h4 class="mb-0">{$LANG->get('smtp_server')}</h4>
                    </div>
                </div>
                <div class="card-divider"></div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="smtp_host">{$LANG->get('outgoing')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="text" class="form-control form-control-sm" id="smtp_host" name="smtp_host" value="{$DATA['smtp_host']}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="smtp_port">{$LANG->get('outgoing_port')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="text" class="form-control form-control-sm" id="smtp_port" name="smtp_port" value="{$DATA['smtp_port']}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="smtp_ssl">{$LANG->get('incoming_ssl')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <select class="form-control form-control-sm" id="smtp_ssl" name="smtp_ssl">
                            {foreach from=$SMTP_ENCRYPTED key=key item=row}
                            <option value="{$key}"{if $key eq $GLOBAL_CONFIG['smtp_ssl']} selected="selected"{/if}>{$row}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group row pt-1 pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('verify_peer_ssl')}</label>
                    <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                        <label class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="verify_peer_ssl" value="1"{if $DATA['verify_peer_ssl']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('verify_peer_ssl_yes')}</span>
                        </label>
                        <label class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="verify_peer_ssl" value="0"{if not $DATA['verify_peer_ssl']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('verify_peer_ssl_no')}</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row pt-1 pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('verify_peer_name_ssl')}</label>
                    <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                        <label class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="verify_peer_name_ssl" value="1"{if $DATA['verify_peer_name_ssl']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('verify_peer_ssl_yes')}</span>
                        </label>
                        <label class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="verify_peer_name_ssl" value="0"{if not $DATA['verify_peer_name_ssl']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('verify_peer_ssl_no')}</span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                        <h4 class="mb-0">{$LANG->get('smtp_username')}</h4>
                    </div>
                </div>
                <div class="card-divider"></div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="smtp_username">{$LANG->get('smtp_login')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="text" class="form-control form-control-sm" id="smtp_username" name="smtp_username" value="{$DATA['smtp_username']}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="smtp_password">{$LANG->get('smtp_pass')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="password" class="form-control form-control-sm" id="smtp_password" name="smtp_password" value="{$DATA['smtp_password']}">
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
