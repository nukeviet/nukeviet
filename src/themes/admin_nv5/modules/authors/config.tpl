<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/locales/bootstrap-datepicker.{$NV_LANG_INTERFACE}.min.js"></script>

{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}

<div class="card card-border-color card-border-color-primary">
    <div class="card-header card-header-divider">
        {$LANG->get('config')}
    </div>
    <div class="card-body">
        <form method="post" action="{$NV_BASE_ADMINURL}index.php" autocomplete="off">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-0">
                        <input class="custom-control-input" type="checkbox" name="admfirewall" value="1"{if $CONFIG.admfirewall} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('admfirewall')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-0">
                        <input class="custom-control-input" type="checkbox" name="block_admin_ip" value="1"{if $CONFIG.block_admin_ip} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('block_admin_ip')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-0">
                        <input class="custom-control-input" type="checkbox" name="authors_detail_main" value="1"{if $CONFIG.authors_detail_main} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('authors_detail_main')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-0">
                        <input class="custom-control-input" type="checkbox" name="spadmin_add_admin" value="1"{if $CONFIG.spadmin_add_admin} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('spadmin_add_admin')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_check_pass_time">{$LANG->get('admin_check_pass_time')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="admin_check_pass_time" name="admin_check_pass_time" value="{$ADMIN_CHECK_PASS_TIME}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">({$LANG->get('min')})</div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" value="1" name="savesetting">
                    <button class="btn btn-space btn-primary" type="submit" name="Submit1">{$LANG->get('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{if not empty($ARRAY_FIREWALL_USERS)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('title_username')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 25%;">{$LANG->get('username')}</th>
                        <th style="width: 25%;">{$LANG->get('adminip_timeban')}</th>
                        <th style="width: 25%;">{$LANG->get('adminip_timeendban')}</th>
                        <th style="width: 25%;" class="text-right">{$LANG->get('adminip_funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_FIREWALL_USERS item=user}
                    <tr>
                        <td>{$user.keyname}</td>
                        <td>{$user.dbbegintime}</td>
                        <td>{$user.dbendtime}</td>
                        <td class="text-right">
                            <a href="{$user.url_edit}" class="btn btn-sm btn-hspace btn-secondary edit"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="{$user.url_delete}" class="btn btn-sm btn-danger deleteuser"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}

<div class="card card-border-color card-border-color-primary" id="iduser">
    <div class="card-header card-header-divider">
        {$LANG->get('username_add')}
    </div>
    <div class="card-body">
        <form method="post" action="{$NV_BASE_ADMINURL}index.php" autocomplete="off">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <input type="hidden" name="uid" value="{$UID}">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="username">{$LANG->get('username')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="username" name="username" value="{$FIREWALLDATA.username}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="password">{$LANG->get('password')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="password" class="form-control form-control-sm" id="password" name="password" value="{$FIREWALLDATA.password}" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="password2">{$LANG->get('password2')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="password" class="form-control form-control-sm" id="password2" name="password2" value="{$FIREWALLDATA.password2}" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('adminip_begintime')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <div class="input-group input-group-sm date bsdatepicker">
                        <input class="form-control" type="text" name="begintime1" value="{$FIREWALLDATA.begintime1}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('adminip_endtime')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <div class="input-group input-group-sm date bsdatepicker">
                        <input class="form-control" type="text" name="endtime1" value="{$FIREWALLDATA.endtime1}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submituser">{$LANG->get('save')}</button>
                    {if not empty($UID)}
                    <div class="mt-2">
                        <i>{$LANG->get('nochangepass')}</i>
                    </div>
                    {/if}
                </div>
            </div>
        </form>
    </div>
</div>

{if not empty($ARRAY_IPACCESS)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('adminip')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 20%;">{$LANG->get('adminip_ip')}</th>
                        <th style="width: 20%;">{$LANG->get('adminip_mask')}</th>
                        <th style="width: 20%;">{$LANG->get('adminip_timeban')}</th>
                        <th style="width: 20%;">{$LANG->get('adminip_timeendban')}</th>
                        <th style="width: 20%;" class="text-right">{$LANG->get('adminip_funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_IPACCESS item=ipaccess}
                    <tr>
                        <td>{$ipaccess.keyname}</td>
                        <td>{$ipaccess.mask_text_array}</td>
                        <td>{$ipaccess.dbbegintime}</td>
                        <td>{$ipaccess.dbendtime}</td>
                        <td class="text-right">
                            <a href="{$ipaccess.url_edit}" class="btn btn-sm btn-hspace btn-secondary edit"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="{$ipaccess.url_delete}" class="btn btn-sm btn-danger deleteone"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}

<div class="card card-border-color card-border-color-primary" id="idip">
    <div class="card-header card-header-divider">
        {$LANG->get('adminip_add')}
    </div>
    <div class="card-body">
        <form method="post" action="{$NV_BASE_ADMINURL}index.php" autocomplete="off">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <input type="hidden" name="cid" value="{$CID}">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="keyname">{$LANG->get('adminip_address')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="keyname" name="keyname" value="{$IPDATA.keyname}">
                    <div class="form-text text-muted">(xxx.xxx.xxx.xxx)</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ip_mask">{$LANG->get('adminip_mask')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select id="ip_mask" name="mask" class="form-control form-control-sm">
                        <option value="0">{$MASK_TEXT_ARRAY.0}</option>
                        <option value="3"{if $IPDATA.mask eq 3} selected="selected"{/if}>{$MASK_TEXT_ARRAY.3}</option>
                        <option value="2"{if $IPDATA.mask eq 2} selected="selected"{/if}>{$MASK_TEXT_ARRAY.2}</option>
                        <option value="1"{if $IPDATA.mask eq 1} selected="selected"{/if}>{$MASK_TEXT_ARRAY.1}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('adminip_begintime')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <div class="input-group input-group-sm date bsdatepicker">
                        <input class="form-control" type="text" name="begintime" value="{$IPDATA.begintime}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('adminip_endtime')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <div class="input-group input-group-sm date bsdatepicker">
                        <input class="form-control" type="text" name="endtime" value="{$IPDATA.endtime}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button"><i class="icon-th far fa-calendar-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ip_notice">{$LANG->get('adminip_notice')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <textarea rows="2" name="notice" id="ip_notice" class="form-control">{$IPDATA.notice}</textarea>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submitip">{$LANG->get('save')}</button>
                    <div class="mt-2">
                        <i>{$LANG->get('adminip_note')}</i>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(".bsdatepicker").datepicker({
        autoclose: 1,
        templates: {
            rightArrow: '<i class="fas fa-chevron-right"></i>',
            leftArrow: '<i class="fas fa-chevron-left"></i>'
        },
        language: '{$NV_LANG_INTERFACE}',
        orientation: 'auto bottom',
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });

    $('input[name=submitip]').click(function() {
        var ip = $('input[name=keyname]').val();
        $('input[name=keyname]').focus();
        if (ip == '') {
            alert('{$LANG->get('adminip_error_ip')}');
            return false;
        }
    });

    $('input[name=submituser]').click(function() {
        var username = $('input[name=username]').val();
        var nv_rule = /^([a-zA-Z0-9_-])+$/;
        if (username == '') {
            $('input[name=username]').focus();
            alert('{$LANG->get('username_empty')}');
            return false;
        } else if (!nv_rule.test(username)) {
            $('input[name=username]').focus();
            alert('{$LANG->get('rule_user')}');
            return false;
        }
        var password = $('input[name=password]').val();
        if (password == '' && $('input[name=uid]').val() == '0') {
            $('input[name=password]').focus();
            alert('{$LANG->get('password_empty')}');
            return false;
        }
        if (password != $('input[name=password2]').val()) {
            $('input[name=password2]').focus();
            alert('{$LANG->get('passwordsincorrect')}');
            return false;
        } else if (password != '' && !nv_rule.test(password)) {
            $('input[name=password]').focus();
            alert('{$LANG->get('rule_pass')}');
            return false;
        }
    });

    $('a.deleteone').click(function() {
        if (confirm('{$LANG->get('adminip_delete_confirm')}')) {
            var url = $(this).attr('href');
            $.ajax({
                type : 'POST',
                url : url,
                data : '',
                success : function(data) {
                    alert('{$LANG->get('adminip_del_success')}');
                    location.reload();
                }
            });
        }
        return false;
    });

    $('a.deleteuser').click(function() {
        if (confirm('{$LANG->get('nicknam_delete_confirm')}')) {
            var url = $(this).attr('href');
            $.ajax({
                type : 'POST',
                url : url,
                data : '',
                success : function(data) {
                    alert('{$LANG->get('adminip_del_success')}');
                    location.reload();
                }
            });
        }
        return false;
    });
});
</script>
