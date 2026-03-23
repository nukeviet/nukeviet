{if empty($ROW.active2step)}
<div class="alert alert-info">{$LANG->getModule('user_2step_off')}</div>
{else}
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">{$LANG->getModule('user_2step_turnoff')}</h5>
    </div>
    <div class="card-body">
        {if !empty($GCONFIG.two_step_verification)}
        <div class="alert alert-info">{$LANG->getModule('user_2step_turnoff_info')}</div>
        {/if}
        <div class="text-center">
            <button type="button" class="btn btn-danger"
                    data-toggle="turnoff2step"
                    data-userid="{$ROW.userid}"
                    data-checkss="{$CHECKSS}"
                    data-msgconfirm="{$LANG->getModule('user_2step_turnoff')}">
                <i class="fa-solid fa-shield-halved" data-icon="fa-shield-halved"></i>
                {$LANG->getModule('user_2step_turnoff')}
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{$LANG->getModule('user_2step_codes')}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:20%">{$LANG->getModule('user_2step_codes')}</th>
                        <th class="text-nowrap" style="width:40%">{$LANG->getModule('status')}</th>
                        <th class="text-nowrap" style="width:20%">{$LANG->getModule('user_2step_codes_timecreat')}</th>
                        <th class="text-nowrap" style="width:20%">{$LANG->getModule('user_2step_codes_timeuse')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$CODES item=code}
                    <tr>
                        <td><code>{$code.code}</code></td>
                        <td>
                            {if $code.is_used}
                            <span class="badge bg-secondary">{$code.status_label}</span>
                            {else}
                            <span class="badge bg-success">{$code.status_label}</span>
                            {/if}
                        </td>
                        <td>{if $code.time_creat}{$code.time_creat|ddatetime:1}{/if}</td>
                        <td>{if $code.time_used}{$code.time_used|ddatetime:1}{/if}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" value="1" name="sendmail" id="sendmail_check">
                <label class="form-check-label" for="sendmail_check">{$LANG->getModule('user_2step_sendmail')}</label>
            </div>
            <button type="button" class="btn btn-danger"
                    data-toggle="resetbackupcodes"
                    data-userid="{$ROW.userid}"
                    data-checkss="{$CHECKSS}"
                    data-msgconfirm="{$LANG->getModule('user_2step_reset')}">
                <i class="fa-solid fa-rotate" data-icon="fa-rotate"></i>
                {$LANG->getModule('user_2step_reset')}
            </button>
        </div>
    </div>
</div>
{/if}
