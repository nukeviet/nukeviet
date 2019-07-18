{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{else}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get("delete_sendmail_info", $ROW_USER.username)}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$ACTION}" autocomplete="off">
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="sendmail" value="1"{if $DATA.sendmail} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('admin_del_sendmail')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="adminpass_iavim">{$LANG->get('admin_password')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="password" autocomplete="new-password" class="form-control form-control-sm" id="adminpass_iavim" name="adminpass_iavim" value="{$DATA.adminpass}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="del_reason">{$LANG->get('admin_del_reason')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="del_reason" name="reason" value="{$DATA.reason}">
                </div>
            </div>
            <div class="form-group row pt-1 pb-1">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('action_account')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    {foreach from=$ARRAY_ACTION_ACCOUNT key=key item=value}
                    <label class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" name="action_account" value="{$key}"{if $key eq $DATA.action_account} checked="checked"{/if}><span class="custom-control-label">{$value}</span>
                    </label>
                    {/foreach}
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input name="ok" type="hidden" value="{$CHECKSS}">
                    <button class="btn btn-space btn-primary" type="submit" name="go_del">{$LANG->get('nv_admin_del')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
