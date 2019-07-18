{if empty($ARRAY_SUSPENDED)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('suspend_info_empty', $ADMIN.username)}</div>
</div>
{else}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('suspend_info_yes', $ADMIN.username)}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 25%;">{$LANG->get('suspend_start')}</th>
                        <th style="width: 25%;">{$LANG->get('suspend_end')}</th>
                        <th style="width: 50%;">{$LANG->get('suspend_reason')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_SUSPENDED item=value}
                    <tr>
                        <td>{$value.0}</td>
                        <td>{$value.1}</td>
                        <td>{$value.2}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{if $ALLOW_CHANGE}
{if not empty($DATA.error)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$DATA.error}</div>
</div>
{else}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get("chg_is_suspend`$IS_NEW_SUSPEND`")}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$DATA.new_suspend_action}" autocomplete="off">
            {if isset($DATA.new_reason)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="new_reason">{$LANG->get('suspend_reason')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="new_reason" name="new_reason" value="{$DATA.new_reason}">
                </div>
            </div>
            {/if}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="sendmail" value="1"{if $DATA.sendmail} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('suspend_sendmail')}</span>
                    </label>
                </div>
            </div>
            {if isset($DATA.clean_history)}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline my-0">
                        <input class="custom-control-input" type="checkbox" name="sendmail" value="1"{if $DATA.clean_history} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('clean_history')}</span>
                    </label>
                </div>
            </div>
            {/if}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input name="save" type="hidden" value="1">
                    <button class="btn btn-space btn-primary" type="submit" name="go_change">{$LANG->get("suspend`$IS_NEW_SUSPEND`")}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{/if}
