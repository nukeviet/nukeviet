<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-body pt-4">
        <form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="admfirewall" value="1"{if $GCONFIG.admfirewall} checked="checked"{/if} role="switch" id="element_admfirewall">
                        <label class="form-check-label" for="element_admfirewall">{$LANG->getModule('admfirewall')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="block_admin_ip" value="1"{if $GCONFIG.block_admin_ip} checked="checked"{/if} role="switch" id="element_block_admin_ip">
                        <label class="form-check-label" for="element_block_admin_ip">{$LANG->getModule('block_admin_ip')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="authors_detail_main" value="1"{if $GCONFIG.authors_detail_main} checked="checked"{/if} role="switch" id="element_authors_detail_main">
                        <label class="form-check-label" for="element_authors_detail_main">{$LANG->getModule('authors_detail_main')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="spadmin_add_admin" value="1"{if $GCONFIG.spadmin_add_admin} checked="checked"{/if} role="switch" id="element_spadmin_add_admin">
                        <label class="form-check-label" for="element_spadmin_add_admin">{$LANG->getModule('spadmin_add_admin')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-form-label text-sm-end">{$LANG->getModule('admin_login_duration')}</div>
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5">
                    <div class="input-group d-inline-flex w-auto">
                        <input type="number" class="form-control" value="{if not empty($GCONFIG.admin_login_duration)}{round($GCONFIG.admin_login_duration / 60)}{/if}" name="admin_login_duration" aria-label="{$LANG->getGlobal('admin_login_duration')}" maxlength="4" min="0">
                        <span class="input-group-text">{$LANG->getGlobal('min')}</span>
                    </div>
                    <div class="form-text">{$LANG->getModule('admin_login_duration_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-4 col-form-label text-sm-end">{$LANG->getModule('admin_check_pass_time')}</div>
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5">
                    <div class="input-group d-inline-flex w-auto">
                        <input type="number" class="form-control" value="{if not empty($GCONFIG.admin_check_pass_time)}{round($GCONFIG.admin_check_pass_time / 60)}{/if}" name="admin_check_pass_time" aria-label="{$LANG->getGlobal('admin_check_pass_time')}" maxlength="3" min="0">
                        <span class="input-group-text">{$LANG->getGlobal('min')}</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="admin_user_logout" value="1"{if $GCONFIG.admin_user_logout} checked="checked"{/if} role="switch" id="element_admin_user_logout">
                        <label class="form-check-label" for="element_admin_user_logout">{$LANG->getModule('admin_user_logout')}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-4">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" value="1" name="savesetting">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-xxl-6">
        {if not empty($FIREWALLS)}
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title fs-4 fw-medium mb-4">{$LANG->getModule('title_username')}</div>
                <div class="table-responsive-lg table-card pt-1 pb-1">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th class="text-nowrap">{$LANG->getGlobal('username')}</th>
                                <th class="text-nowrap">{$LANG->getModule('adminip_timeban')}</th>
                                <th class="text-nowrap">{$LANG->getModule('adminip_timeendban')}</th>
                                <th class="text-nowrap text-center">{$LANG->getModule('funcs')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$FIREWALLS item=row}
                            <tr>
                                <td>{$row.keyname}</td>
                                <td>{$row.dbbegintime}</td>
                                <td>{$row.dbendtime}</td>
                                <td class="text-nowrap text-center">
                                    <a href="{$row.url_edit}" class="btn btn-sm btn-secondary text-nowrap"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                                    <a href="#" data-toggle="delFwUser" data-id="{$row.uid}" data-checkss="{$row.checkss}" data-message="{$LANG->getModule('nicknam_delete_confirm')}" class="btn btn-sm btn-danger text-nowrap"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {/if}
        <div class="fw-medium fs-4 mb-2">{$LANG->getModule('username_add')}</div>
        {if not empty($ERROR_USER)}
        <div role="alert" class="alert alert-danger" data-toggle="autoScroll">
            {$ERROR_USER}
        </div>
        {/if}
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4 mb-xxl-0"{if $FIREWALLDATA.uid and !$ERROR_USER} data-toggle="autoScroll"{/if}>
            <div class="card-body pt-4">
                <form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
                    <div class="row mb-3">
                        <label for="elementfw_username" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('username')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" class="form-control" id="elementfw_username" name="username" value="{$FIREWALLDATA.username}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="elementfw_password" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('password')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="password" class="form-control" id="elementfw_password" name="password" value="{$FIREWALLDATA.password}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="elementfw_password2" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('password2')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="password" class="form-control" id="elementfw_password2" name="password2" value="{$FIREWALLDATA.password2}">
                            {if $FIREWALLDATA.uid}<div class="form-text">{$LANG->getModule('nochangepass')}</div>{/if}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="elementfw_begintime1" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_begintime')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="input-group d-inline-flex w-auto flex-nowrap">
                                <input type="text" name="begintime1" id="elementfw_begintime1" value="{$FIREWALLDATA.begintime1}" class="form-control datepicker-post" autocomplete="off">
                                <button data-toggle="focusDate" class="btn btn-secondary" type="button" aria-hidden="true"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="elementfw_endtime1" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_endtime')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="input-group d-inline-flex w-auto flex-nowrap">
                                <input type="text" name="endtime1" id="elementfw_endtime1" value="{$FIREWALLDATA.endtime1}" class="form-control datepicker-post" autocomplete="off">
                                <button data-toggle="focusDate" class="btn btn-secondary" type="button" aria-hidden="true"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                            <input type="hidden" name="checkss" value="{$CHECKSS}">
                            <input type="hidden" name="submituser" value="1">
                            <input type="hidden" name="uid" value="{$FIREWALLDATA.uid}">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        {if not empty($IPACCESS)}
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title fs-4 fw-medium mb-4">{$LANG->getModule('adminip')}</div>
                <div class="table-responsive-lg table-card pt-1 pb-1">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th class="text-nowrap">{$LANG->getModule('adminip_ip')}</th>
                                <th class="text-nowrap">{$LANG->getModule('adminip_mask')}</th>
                                <th class="text-nowrap">{$LANG->getModule('suspend_start')}</th>
                                <th class="text-nowrap">{$LANG->getModule('suspend_end')}</th>
                                <th class="text-nowrap text-center">{$LANG->getModule('funcs')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$IPACCESS item=row}
                            <tr>
                                <td class="text-break">{$row.keyname}</td>
                                <td class="text-break">{$row.mask_text_array}</td>
                                <td>{$row.dbbegintime}</td>
                                <td>{$row.dbendtime}</td>
                                <td class="text-nowrap text-center">
                                    <a href="{$row.url_edit}" class="btn btn-sm btn-secondary text-nowrap"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                                    <a href="#" data-toggle="delFwUser" data-id="{$row.id}" data-checkss="{$row.checkss}" data-message="{$LANG->getModule('adminip_delete_confirm')}" class="btn btn-sm btn-danger text-nowrap"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {/if}
        <div class="fw-medium fs-4 mb-2">{$LANG->getModule('adminip_add')}</div>
        {if not empty($ERROR_IP)}
        <div role="alert" class="alert alert-danger" data-toggle="autoScroll">
            {$ERROR_IP}
        </div>
        {/if}
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0"{if $IPDATA.cid and !$ERROR_IP} data-toggle="autoScroll"{/if}>
            <div class="card-body pt-4">
                <form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
                    <div class="row mb-3">
                        <label for="ipt_ip_version" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ip_version')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select w-auto" id="ipt_ip_version" name="ip_version">
                                {foreach from=$IPTYPES key=key item=value}
                                <option value="{$key}"{if $key eq $IPDATA.ip_version} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ipt_keyname" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_address')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" class="form-control" id="ipt_keyname" name="keyname" value="{$IPDATA.keyname}">
                        </div>
                    </div>
                    <div class="row mb-3{if $IPDATA.ip_version neq 4} d-none{/if}" id="ip4_mask">
                        <label for="ipt_mask" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_mask')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select w-auto" id="ipt_mask" name="mask">
                                <option value="0">{$MASK_TEXT_ARRAY.0}</option>
                                <option value="3"{if 3 eq $IPDATA.mask} selected{/if}>{$MASK_TEXT_ARRAY.3}</option>
                                <option value="2"{if 2 eq $IPDATA.mask} selected{/if}>{$MASK_TEXT_ARRAY.2}</option>
                                <option value="1"{if 1 eq $IPDATA.mask} selected{/if}>{$MASK_TEXT_ARRAY.1}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3{if $IPDATA.ip_version neq 6} d-none{/if}" id="ip6_mask">
                        <label for="ipt_maskv6" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_mask')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select w-auto" id="ipt_maskv6" name="mask6">
                                {for $mask=1 to 128}
                                <option value="{$mask}"{if $mask eq $IPDATA.mask6} selected{/if}>/{$mask}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="elementfw_begintime" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_begintime')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="input-group d-inline-flex w-auto flex-nowrap">
                                <input type="text" name="begintime" id="elementfw_begintime" value="{$IPDATA.begintime}" class="form-control datepicker-post" autocomplete="off">
                                <button data-toggle="focusDate" class="btn btn-secondary" type="button" aria-hidden="true"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="elementfw_endtime" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_endtime')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="input-group d-inline-flex w-auto flex-nowrap">
                                <input type="text" name="endtime" id="elementfw_endtime" value="{$IPDATA.endtime}" class="form-control datepicker-post" autocomplete="off">
                                <button data-toggle="focusDate" class="btn btn-secondary" type="button" aria-hidden="true"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ipt_notice" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('adminip_notice')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <textarea rows="5" class="form-control" name="notice" id="ipt_notice">{$IPDATA.notice}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <input type="hidden" name="checkss" value="{$CHECKSS}">
                            <input type="hidden" name="cid" value="{$IPDATA.cid}">
                            <input type="hidden" name="submitip" value="1">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
