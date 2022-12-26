<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}themes/admin_default/js/push_action.js"></script>
<form method="post" action="{PAGE_URL}" class="role-{DATA.sender_role} receiver-grs-{DATA.is_receiver_grs} form-horizontal push-action-form" id="push-action-form">
    <input type="hidden" name="action" value="push_action" />
    <input type="hidden" name="id" value="{DATA.id}" />
    <input type="hidden" name="save" value="1" />
    <!-- BEGIN: is_sender_not_select -->
    <input type="hidden" name="sender_role" value="admin" />
    <input type="hidden" name="sender_group" value="0" />
    <input type="hidden" name="sender_admin" value="{DATA.sender_admin}" />
    <!-- END: is_sender_not_select -->
    <!-- BEGIN: is_sender_select -->
    <div class="form-group field">
        <label class="col-md-5 control-label">{LANG.sender}</label>
        <div class="col-md-19">
            <div class="row">
                <div class="col-xs-8">
                    <select name="sender_role" class="form-control">
                        <!-- BEGIN: sender_role -->
                        <option value="{ROLE.key}" {ROLE.sel}>{ROLE.name}</option>
                        <!-- END: sender_role -->
                    </select>
                </div>
                <div class="col-xs-16 sender_group">
                    <select name="sender_group" class="form-control" data-default="{DATA.sender_group}" style="width: 100%" data-error="{LANG.please_select_group}" {DATA.sender_group_disabled}>
                        <option value="0">{LANG.select_group}</option>
                        <!-- BEGIN: sender_group -->
                        <option value="{GROUP.key}" {GROUP.sel}>{GROUP.name} ({LANG.id} #{GROUP.key})</option>
                        <!-- END: sender_group -->
                    </select>
                </div>
                <div class="col-xs-16 sender_admin">
                    <select name="sender_admin" class="form-control" data-default="{DATA.sender_admin}" style="width: 100%" data-error="{LANG.please_select_admin}" {DATA.sender_admin_disabled}>
                        <option value="0">{LANG.select_admin}</option>
                        <!-- BEGIN: sender_admin -->
                        <option value="{ADMIN.key}" {ADMIN.sel}>{ADMIN.name} ({LANG.id} #{ADMIN.key})</option>
                        <!-- END: sender_admin -->
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- END: is_sender_select -->

    <div class="form-group field">
        <label class="col-md-5 control-label">{LANG.receiver}</label>
        <div class="col-md-19">
            <div class="row">
                <div class="col-xs-8">
                    <select name="receiver_type" class="form-control" data-from-group-title="{LANG.to_members}" data-from-system-title="{LANG.to_users}">
                        <!-- BEGIN: receiver_type -->
                        <option value="{TYPE.key}" {TYPE.sel} {TYPE.disabled}>{TYPE.name}</option>
                        <!-- END: receiver_type -->
                    </select>
                </div>
                <div class="col-xs-16 receiver_grs">
                    <select name="receiver_grs[]" id="receiver_grs" class="form-control" multiple="multiple" data-placeholder="{LANG.select_group}" style="width: 100%" {DATA.receiver_grs_disabled}>
                        <!-- BEGIN: receiver_grs -->
                        <option value="{RECEIVER_GROUP.key}" {RECEIVER_GROUP.sel}>{RECEIVER_GROUP.name} ({LANG.id} #{RECEIVER_GROUP.key})</option>
                        <!-- END: receiver_grs -->
                    </select>
                </div>
                <div class="col-xs-16 receiver_ids">
                    <select name="receiver_ids[]" id="receiver_ids" class="form-control" multiple="multiple" data-placeholder="{LANG.to_all}" data-input-too-short="{LANG.please_enter}" style="width: 100%" {DATA.receiver_ids_disabled}>
                        <!-- BEGIN: receiver_ids -->
                        <option value="{RECEIVER_ID.key}" selected="selected">{RECEIVER_ID.name}</option>
                        <!-- END: receiver_ids -->
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group field">
        <label class="col-md-5 control-label">{LANG.content}</label>
        <div class="col-md-19">
            <textarea name="message" class="form-control message" maxlength="2000">{DATA.message}</textarea>
        </div>
    </div>

    <div class="form-group field">
        <label class="col-md-5 control-label">{LANG.push_link}</label>
        <div class="col-md-19">
            <input type="text" name="link" value="{DATA.link}" class="form-control" maxlength="500">
        </div>
    </div>

    <div class="form-group field time">
        <label class="col-md-5 control-label">{LANG.add_time}</label>
        <div class="col-md-19">
            <input type="text" name="add_time" value="{DATA.add_time_format}" class="form-control" maxlength="10">
            <select name="add_hour" class="form-control">
                <!-- BEGIN: add_hour -->
                <option value="{HOUR.val}"{HOUR.sel}>{HOUR.name}</option>
                <!-- END: add_hour -->
            </select>
            <select name="add_min" class="form-control">
                <!-- BEGIN: add_min -->
                <option value="{MIN.val}"{MIN.sel}>{MIN.name}</option>
                <!-- END: add_min -->
            </select>
        </div>
    </div>

    <div class="form-group field time">
        <label class="col-md-5 control-label">{LANG.exp_time}</label>
        <div class="col-md-19">
            <input type="text" name="exp_time" value="{DATA.exp_time_format}" class="form-control" maxlength="10">
            <select name="exp_hour" class="form-control">
                <option value="-1"></option>
                <!-- BEGIN: exp_hour -->
                <option value="{EXP_HOUR.val}"{EXP_HOUR.sel}>{EXP_HOUR.name}</option>
                <!-- END: exp_hour -->
            </select>
            <select name="exp_min" class="form-control">
                <option value="-1"></option>
                <!-- BEGIN: exp_min -->
                <option value="{EXP_MIN.val}"{EXP_MIN.sel}>{EXP_MIN.name}</option>
                <!-- END: exp_min -->
            </select>
            <br/>
            <span>{LANG.empty_is_unlimited}</span>
        </div>
    </div>

    <div class="field time text-center">
        <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
        <button type="button" class="btn btn-default" data-toggle="push_action_canceled">{GLANG.cancel}</button>
    </div>
</form>
<!-- END: main -->