{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
{if isset($MANUAL_WRITE_MESSAGE)}
<div class="card card-border-color card-border-color-danger">
    <div class="card-header">
        {$LANG->get('banip_error_write_title')}
        <div class="card-subtitle">{$MANUAL_WRITE_MESSAGE}</div>
    </div>
    <div class="card-body">
        <pre><code>{$MANUAL_WRITE_CODE}</code></pre>
    </div>
</div>
{/if}
<div class="card">
    <div class="tab-container">
        <ul role="tablist" class="nav nav-tabs pt-1" id="settingTabs">
            <li class="nav-item"><a aria-offsets="0" href="#settingBasic" data-toggle="tab" role="tab" class="nav-link{if $SELECTEDTAB eq 0} active{/if}">{$LANG->get('security')}</a></li>
            <li class="nav-item"><a aria-offsets="1" href="#settingFlood" data-toggle="tab" role="tab" class="nav-link{if $SELECTEDTAB eq 1} active{/if}">{$LANG->get('flood_blocker')}</a></li>
            <li class="nav-item"><a aria-offsets="2" href="#settingCaptcha" data-toggle="tab" role="tab" class="nav-link{if $SELECTEDTAB eq 2} active{/if}">{$LANG->get('captcha')}</a></li>
            <li class="nav-item"><a aria-offsets="3" href="#settingIp" data-toggle="tab" role="tab" class="nav-link{if $SELECTEDTAB eq 3} active{/if}">{$LANG->get('banip')}</a></li>
            <li class="nav-item"><a aria-offsets="4" href="#settingCORS" data-toggle="tab" role="tab" class="nav-link{if $SELECTEDTAB eq 4} active{/if}">{$LANG->get('cors')}</a></li>
        </ul>
        <div class="tab-content">
            <div id="settingBasic" role="tabpanel" class="tab-pane{if $SELECTEDTAB eq 0} active{/if}">
                <form method="post" action="{$FORM_ACTION}" autocomplete="off">
                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}"/>
                    <div class="form-group row pt-1 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="is_login_blocker" value="1"{if $CONFIG_GLOBAL['is_login_blocker']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('is_login_blocker')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="login_number_tracking">{$LANG->get('login_number_tracking_label')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="login_number_tracking" name="login_number_tracking" value="{$CONFIG_GLOBAL['login_number_tracking']}">
                                </div>
                            </div>
                            <span class="form-text text-muted">{$LANG->get('login_number_tracking')}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="login_time_tracking">{$LANG->get('login_time_tracking')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 flex-shrink-1">
                                            <input type="text" class="form-control form-control-sm" id="login_time_tracking" name="login_time_tracking" value="{$CONFIG_GLOBAL['login_time_tracking']}">
                                        </div>
                                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                                            {$LANG->get('min')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="login_time_ban">{$LANG->get('login_time_ban')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 flex-shrink-1">
                                            <input type="text" class="form-control form-control-sm" id="login_time_ban" name="login_time_ban" value="{$CONFIG_GLOBAL['login_time_ban']}">
                                        </div>
                                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                                            {$LANG->get('min')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="two_step_verification">{$LANG->get('two_step_verification')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-control form-control-sm" id="two_step_verification" name="two_step_verification">
                                {for $key=0 to 3}
                                <option value="{$key}"{if $key eq $CONFIG_GLOBAL['two_step_verification']} selected="selected"{/if}>{$LANG->get("two_step_verification`$key`")}</option>
                                {/for}
                            </select>
                            <span class="form-text text-muted">{$LANG->get('two_step_verification_note')}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('admin_2step_opt')}</label>
                        <div class="col-12 col-sm-8 col-lg-6 mt-1">
                            {foreach from=$TWOSTEP_ARRAY item=$twostep}
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="admin_2step_opt[]" value="{$twostep}"{if in_array($twostep, $CONFIG_GLOBAL.admin_2step_opt)} checked="checked"{/if}><span class="custom-control-label">{$LANG->get("admin_2step_opt_`$twostep`")}{if $twostep eq 'facebook' or $twostep eq 'google'} (<a href="{$LINK_CONFIG_OAUTH}{$twostep}" target="_blank">{$LANG->get('admin_2step_appconfig')}</a>){/if}</span>
                            </label>
                            {/foreach}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_2step_default">{$LANG->get('admin_2step_default')}</label>
                        <div class="col-12 col-sm-8 col-lg-6 form-inline">
                            <select class="form-control form-control-sm" id="admin_2step_default" name="admin_2step_default">
                                {foreach from=$TWOSTEP_ARRAY item=$twostep}
                                <option value="{$twostep}"{if $twostep eq $CONFIG_GLOBAL.admin_2step_default} selected="selected"{/if}>{$LANG->get("admin_2step_opt_`$twostep`")}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row pt-1 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="nv_anti_agent" value="1"{if $CONFIG_DEFINE['nv_anti_agent']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('nv_anti_agent')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="proxy_blocker">{$LANG->get('proxy_blocker')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-control form-control-sm" id="proxy_blocker" name="proxy_blocker">
                                {foreach from=$PROXY_BLOCKER key=key item=$row}
                                <option value="{$key}"{if $key eq $CONFIG_GLOBAL['proxy_blocker']} selected="selected"{/if}>{$row}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row pt-1 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="str_referer_blocker" value="1"{if $CONFIG_GLOBAL['str_referer_blocker']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('str_referer_blocker')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row pt-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="nv_anti_iframe" value="1"{if $CONFIG_DEFINE['nv_anti_iframe']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('nv_anti_iframe')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="nv_allowed_html_tags">{$LANG->get('nv_allowed_html_tags')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <textarea rows="4" class="form-control" id="nv_allowed_html_tags" name="nv_allowed_html_tags">{$CONFIG_DEFINE['nv_allowed_html_tags']}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-primary" type="submit" name="submitbasic">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="settingFlood" role="tabpanel" class="tab-pane{if $SELECTEDTAB eq 1} active{/if}">
                <form method="post" action="{$FORM_ACTION}" autocomplete="off">
                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}"/>
                    <div class="form-group row pt-1 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="is_flood_blocker" value="1"{if $CONFIG_FLOOD['is_flood_blocker']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('is_flood_blocker')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="max_requests_60">{$LANG->get('max_requests_60')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="max_requests_60" name="max_requests_60" value="{$CONFIG_FLOOD['max_requests_60']}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="max_requests_300">{$LANG->get('max_requests_300')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="max_requests_300" name="max_requests_300" value="{$CONFIG_FLOOD['max_requests_300']}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-primary" type="submit" name="submitflood">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </form>
                {if not empty($NOFLIPS)}
                <h4 class="mb-0">{$LANG->get('noflood_ip_list')}</h4>
                <div class="card-divider"></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-nowrap" style="width: 24%;">{$LANG->get('banip_ip')}</th>
                                <th class="text-nowrap" style="width: 24%;">{$LANG->get('banip_mask')}</th>
                                <th style="width: 25%;">{$LANG->get('banip_timeban')}</th>
                                <th style="width: 25%;">{$LANG->get('banip_timeendban')}</th>
                                <th class="text-right text-nowrap" style="width: 1%;">{$LANG->get('banip_funcs')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$NOFLIPS item=$row}
                            <tr>
                                <td class="text-nowrap">{$row['ip']}</td>
                                <td class="text-nowrap">{$MASK_ARRAY[$row['mask']]}</td>
                                <td>{if not empty($row['begintime'])}{"d/m/Y"|date:$row['begintime']}{/if}</td>
                                <td>{if not empty($row['endtime'])}{"d/m/Y"|date:$row['endtime']}{else}{$LANG->get('banip_nolimit')}{/if}</td>
                                <td class="text-right text-nowrap">
                                    <a href="{$FORM_ACTION}&amp;selectedtab=1&amp;flid={$row.id}" class="btn btn-sm btn-hspace btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('banip_edit')}</a>
                                    <a href="{$FORM_ACTION}&amp;selectedtab=1&amp;fldel=1&amp;flid={$row.id}" class="btn btn-sm btn-danger deleteone-ip" data-msgc="{$LANG->get('banip_delete_confirm')}" data-msgs="{$LANG->get('banip_del_success')}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                {/if}
                <h4 class="mb-0">{$NOFLOODIP_TITLE}</h4>
                <div class="card-divider"></div>
                <form method="post" action="{$FORM_ACTION}" autocomplete="off" id="no-flood-form">
                    <input type="hidden" name="flid" value="{$FLID}" />
                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}"/>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="add_flip">{$LANG->get('banip_address')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" class="form-control form-control-sm" id="add_flip" name="flip" value="{$FLDATA['flip']}" placeholder="xxx.xxx.xxx.xxx">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="flmask">{$LANG->get('banip_mask')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-control form-control-sm" id="flmask" name="flmask">
                                <option value="0">{$MASK_ARRAY[0]}</option>
                                <option value="3"{if 3 eq $FLDATA['flmask']} selected="selected"{/if}>{$MASK_ARRAY[3]}</option>
                                <option value="2"{if 2 eq $FLDATA['flmask']} selected="selected"{/if}>{$MASK_ARRAY[2]}</option>
                                <option value="1"{if 1 eq $FLDATA['flmask']} selected="selected"{/if}>{$MASK_ARRAY[1]}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('banip_begintime')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <div class="input-group input-group-sm date bsdatepicker">
                                <input placeholder="dd/mm/yyyy" class="form-control" type="text" name="flbegintime" value="{if not empty($FLDATA['flbegintime'])}{"d/m/Y"|date:$FLDATA.flbegintime}{/if}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('banip_endtime')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <div class="input-group input-group-sm date bsdatepicker">
                                <input placeholder="dd/mm/yyyy" class="form-control" type="text" name="flendtime" value="{if not empty($FLDATA['flendtime'])}{"d/m/Y"|date:$FLDATA['flendtime']}{/if}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="flnotice">{$LANG->get('banip_notice')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <textarea rows="2" class="form-control" id="flnotice" name="flnotice">{$FLDATA['flnotice']}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-primary" type="submit" name="submitfloodip">{$LANG->get('banip_confirm')}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="settingCaptcha" role="tabpanel" class="tab-pane{if $SELECTEDTAB eq 2} active{/if}">
                <form method="post" action="{$FORM_ACTION}" autocomplete="off">
                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}"/>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="gfx_chk">{$LANG->get('captcha')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <select class="form-control form-control-sm" id="gfx_chk" name="gfx_chk">
                                {foreach from=$CAPTCHA_ARRAY key=key item=row}
                                <option value="{$key}"{if $key eq $CONFIG_CAPTCHA['gfx_chk']} selected="selected"{/if}>{$row}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="captcha_type">{$LANG->get('captcha_type')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <select class="form-control form-control-sm" id="captcha_type" name="captcha_type" data-toggle="ctcaptcha">
                                {foreach from=$CAPTCHA_TYPE key=key item=row}
                                <option value="{$key}"{if $key eq $CONFIG_CAPTCHA['captcha_type']} selected="selected"{/if}>{$row}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {* Các thiết lập cho captcha thường *}
                    <div class="form-group row{if $CONFIG_CAPTCHA['captcha_type'] eq 2} d-none{/if}" data-captcha="typebasic">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="nv_gfx_num">{$LANG->get('captcha_num')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <select class="form-control form-control-sm" id="nv_gfx_num" name="nv_gfx_num">
                                {for $key=2 to 9}
                                <option value="{$key}"{if $key eq $DEFINE_CAPTCHA['nv_gfx_num']} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row{if $CONFIG_CAPTCHA['captcha_type'] eq 2} d-none{/if}" data-captcha="typebasic">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('captcha_size')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control form-control-sm" id="nv_gfx_width" name="nv_gfx_width" value="{$DEFINE_CAPTCHA['nv_gfx_width']}" maxlength="3">
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 pr-1 pl-1">x</div>
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control form-control-sm" id="nv_gfx_height" name="nv_gfx_height" value="{$DEFINE_CAPTCHA['nv_gfx_height']}" maxlength="3">
                                </div>
                            </div>
                        </div>
                    </div>
                    {* Các thiết lập cho recaptcha *}
                    <div class="form-group row{if $CONFIG_CAPTCHA['captcha_type'] neq 2} d-none{/if}" data-captcha="typerecaptcha">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="recaptcha_sitekey">{$LANG->get('recaptcha_sitekey')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" class="form-control form-control-sm" id="recaptcha_sitekey" name="recaptcha_sitekey" value="{$CONFIG_CAPTCHA['recaptcha_sitekey']}">
                            <span class="form-text text-muted"><a href="https://www.google.com/recaptcha/admin" target="_blank">{$LANG->get('recaptcha_guide')}</a></span>
                        </div>
                    </div>
                    <div class="form-group row{if $CONFIG_CAPTCHA['captcha_type'] neq 2} d-none{/if}" data-captcha="typerecaptcha">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="recaptcha_secretkey">{$LANG->get('recaptcha_secretkey')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" class="form-control form-control-sm" id="recaptcha_secretkey" name="recaptcha_secretkey" value="{$CONFIG_CAPTCHA['recaptcha_secretkey']}">
                            <span class="form-text text-muted"><a href="https://www.google.com/recaptcha/admin" target="_blank">{$LANG->get('recaptcha_guide')}</a></span>
                        </div>
                    </div>
                    <div class="form-group row{if $CONFIG_CAPTCHA['captcha_type'] neq 2} d-none{/if}" data-captcha="typerecaptcha">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="recaptcha_type">{$LANG->get('recaptcha_type')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <select class="form-control form-control-sm" id="recaptcha_type" name="recaptcha_type">
                                {foreach from=$RECAPTCHA_TYPE key=key item=row}
                                <option value="{$key}"{if $key eq $CONFIG_CAPTCHA['recaptcha_type']} selected="selected"{/if}>{$row}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-primary" type="submit" name="submitcaptcha">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="settingIp" role="tabpanel" class="tab-pane{if $SELECTEDTAB eq 3} active{/if}">
                {if not empty($LISTIP)}
                <h4 class="mb-0">{$LANG->get('banip')}</h4>
                <div class="card-divider"></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-nowrap" style="width: 24%;">{$LANG->get('banip_ip')}</th>
                                <th class="text-nowrap" style="width: 24%;">{$LANG->get('banip_mask')}</th>
                                <th style="width: 25%;">{$LANG->get('banip_timeban')}</th>
                                <th style="width: 25%;">{$LANG->get('banip_timeendban')}</th>
                                <th class="text-right text-nowrap" style="width: 1%;">{$LANG->get('banip_funcs')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$LISTIP item=$row}
                            <tr>
                                <td class="text-nowrap">{$row['ip']}</td>
                                <td class="text-nowrap">{$MASK_ARRAY[$row['mask']]}</td>
                                <td>{if not empty($row['begintime'])}{"d/m/Y"|date:$row['begintime']}{/if}</td>
                                <td>{if not empty($row['endtime'])}{"d/m/Y"|date:$row['endtime']}{else}{$LANG->get('banip_nolimit')}{/if}</td>
                                <td class="text-right text-nowrap">
                                    <a href="{$FORM_ACTION}&amp;selectedtab=3&amp;id={$row.id}" class="btn btn-sm btn-hspace btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('banip_edit')}</a>
                                    <a href="{$FORM_ACTION}&amp;selectedtab=3&amp;del=1&amp;id={$row.id}" class="btn btn-sm btn-danger deleteone-ip" data-msgc="{$LANG->get('banip_delete_confirm')}" data-msgs="{$LANG->get('banip_del_success')}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('banip_delete')}</a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                {/if}
                <h4 class="mb-0">{$BANIP_TITLE}</h4>
                <div class="card-divider"></div>
                <form method="post" action="{$FORM_ACTION}" autocomplete="off" id="banip-form">
                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}"/>
                    <input type="hidden" name="cid" value="{$DATA.cid}"/>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="banip_ip">{$LANG->get('banip_address')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" class="form-control form-control-sm" id="banip_ip" name="ip" value="{$DATA['ip']}" placeholder="xxx.xxx.xxx.xxx">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="banip_mask">{$LANG->get('banip_mask')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-control form-control-sm" id="banip_mask" name="mask">
                                <option value="0">{$MASK_ARRAY[0]}</option>
                                <option value="3"{if 3 eq $DATA['mask']} selected="selected"{/if}>{$MASK_ARRAY[3]}</option>
                                <option value="2"{if 2 eq $DATA['mask']} selected="selected"{/if}>{$MASK_ARRAY[2]}</option>
                                <option value="1"{if 1 eq $DATA['mask']} selected="selected"{/if}>{$MASK_ARRAY[1]}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="banip_area">{$LANG->get('banip_area')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-control form-control-sm" id="banip_area" name="area">
                                <option value="0">{$AREA_ARRAY[0]}</option>
                                <option value="1"{if 1 eq $DATA['area']} selected="selected"{/if}>{$AREA_ARRAY[1]}</option>
                                <option value="2"{if 2 eq $DATA['area']} selected="selected"{/if}>{$AREA_ARRAY[2]}</option>
                                <option value="3"{if 3 eq $DATA['area']} selected="selected"{/if}>{$AREA_ARRAY[3]}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('banip_begintime')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <div class="input-group input-group-sm date bsdatepicker">
                                <input placeholder="dd/mm/yyyy" class="form-control" type="text" name="begintime" value="{if not empty($DATA['begintime'])}{"d/m/Y"|date:$DATA.begintime}{/if}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('banip_endtime')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <div class="input-group input-group-sm date bsdatepicker">
                                <input placeholder="dd/mm/yyyy" class="form-control" type="text" name="endtime" value="{if not empty($DATA['endtime'])}{"d/m/Y"|date:$DATA['endtime']}{/if}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="banip_notice">{$LANG->get('banip_notice')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <textarea rows="2" class="form-control" id="banip_notice" name="notice">{$DATA['notice']}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('banip_confirm')}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="settingCORS" role="tabpanel" class="tab-pane{if $SELECTEDTAB eq 4} active{/if}">
                <form method="post" action="{$FORM_ACTION}" autocomplete="off">
                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}"/>
                    <div class="form-group row pt-1 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cors_restrict_domains">{$LANG->get('cors_restrict_domains')}</label>
                        <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" id="cors_restrict_domains" name="cors_restrict_domains" value="1"{if $CONFIG_SITE['cors_restrict_domains']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cors_help')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cors_valid_domains">{$LANG->get('cors_valid_domains')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <textarea rows="5" class="form-control" id="cors_valid_domains" name="cors_valid_domains">{$CONFIG_SITE['cors_valid_domains']}</textarea>
                            <span class="form-text text-muted">{$LANG->get('cors_valid_domains_help')}</span>
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-primary" type="submit" name="submitcors">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="gselectedtab" value="{$SELECTEDTAB}"/>

<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/locales/bootstrap-datepicker.{$NV_LANG_INTERFACE}.min.js"></script>

<script>
$(document).ready(function() {
    $(".bsdatepicker").datepicker({
        autoclose: 1,
        templates: {
            rightArrow: '<i class="fas fa-chevron-right"></i>',
            leftArrow: '<i class="fas fa-chevron-left"></i>'
        },
        language: '{$NV_LANG_INTERFACE}',
        orientation: 'auto',
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });
    $('#settingTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
        $('[name="gselectedtab"]').val($(this).attr('aria-offsets'));
    });
    {if $FLID and not isset($MANUAL_WRITE_MESSAGE)}
    $('html, body').animate({
        scrollTop: ($('#no-flood-form').offset().top - 30)
    }, 200);
    {/if}
    {if $DATA.cid and not isset($MANUAL_WRITE_MESSAGE)}
    $('html, body').animate({
        scrollTop: ($('#banip-form').offset().top - 30)
    }, 200);
    {/if}
});
</script>
