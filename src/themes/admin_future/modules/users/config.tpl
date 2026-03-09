<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    {if $smarty.const.NV_IS_GODADMIN and empty($GCONFIG.idsite)}
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('access_caption')}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead class="text-muted">
                        <th>{$LANG->getModule('access_admin')}</th>
                        <th class="text-center">{$LANG->getModule('access_viewlist')}</th>
                        <th class="text-center">{$LANG->getModule('access_addus')}</th>
                        <th class="text-center">{$LANG->getModule('access_waiting')}</th>
                        <th class="text-center">{$LANG->getModule('editcensor')}</th>
                        <th class="text-center">{$LANG->getModule('access_editus')}</th>
                        <th class="text-center">{$LANG->getModule('access_delus')}</th>
                        <th class="text-center">{$LANG->getModule('access_passus')}</th>
                        <th class="text-center">{$LANG->getModule('access_groups')}</th>
                    </thead>
                    <tbody>
                        {for $level=1 to 3}
                        <tr>
                            <td class="fw-medium">{$LANG->getGlobal("level`$level`")}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_viewlist[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_viewlist[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_addus[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_addus[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_waiting[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_waiting[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_editcensor[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_editcensor[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_editus[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_editus[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_delus[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_delus[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_passus[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_passus[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="access_groups[{$level}]" value="1"{if not empty($ACCESS_ADMIN.access_groups[$level])} checked{/if}>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {/for}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
        </div>
    </div>
    {/if}
    <div class="row g-4 mb-4">
        <div class="col-xxl-6">
            <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
                <div class="card-header fs-5 fw-medium">{$LANG->getModule('access_register')}</div>
                <div class="card-body pt-4">
                    <div class="row mb-3">
                        <label for="element_allowuserreg" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('type_reg')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select" id="element_allowuserreg" name="allowuserreg">
                                {foreach from=$REGISTER_TYPES key=key item=value}
                                <option value="{$key}"{if $key eq $DATA.allowuserreg} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_nv_unickmin" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('nv_unick')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="d-flex align-items-center">
                                <div>
                                    <select class="form-select fw-75" id="element_nv_unickmin" name="nv_unickmin">
                                        {for $value=3 to 20}
                                        <option value="{$value}"{if $value eq  $GCONFIG.nv_unickmin} selected{/if}>{$value}</option>
                                        {/for}
                                    </select>
                                </div>
                                <div class="mx-1">{$LANG->getModule('to')}</div>
                                <div>
                                    <select class="form-select fw-75" name="nv_unickmax">
                                        {for $value=9 to 100}
                                        <option value="{$value}"{if $value eq  $GCONFIG.nv_unickmax} selected{/if}>{$value}</option>
                                        {/for}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_nv_unick_type" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('nv_unick_type')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select" id="element_nv_unick_type" name="nv_unick_type">
                                {for $value=0 to 4}
                                <option value="{$value}"{if $value eq $GCONFIG.nv_unick_type} selected{/if}>{$LANG->getGlobal("unick_type_`$value`")}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_nv_upassmin" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('nv_upass')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="d-flex align-items-center">
                                <div>
                                    <select class="form-select fw-75" id="element_nv_upassmin" name="nv_upassmin">
                                        {for $value=5 to 20}
                                        <option value="{$value}"{if $value eq  $GCONFIG.nv_upassmin} selected{/if}>{$value}</option>
                                        {/for}
                                    </select>
                                </div>
                                <div class="mx-1">{$LANG->getModule('to')}</div>
                                <div>
                                    <select class="form-select fw-75" name="nv_upassmax">
                                        {for $value=20 to 250}
                                        <option value="{$value}"{if $value eq  $GCONFIG.nv_upassmax} selected{/if}>{$value}</option>
                                        {/for}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_nv_upass_type" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('nv_upass_type')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select" id="element_nv_upass_type" name="nv_upass_type">
                                {for $value=0 to 4}
                                <option value="{$value}"{if $value eq $GCONFIG.nv_upass_type} selected{/if}>{$LANG->getGlobal("upass_type_`$value`")}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="email_dot_equivalent" value="1"{if not empty($DATA.email_dot_equivalent)} checked="checked"{/if} role="switch" id="element_email_dot_equivalent">
                                <label class="form-check-label" for="element_email_dot_equivalent">{$LANG->getModule('email_dot_equivalent')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="email_plus_equivalent" value="1"{if not empty($DATA.email_plus_equivalent)} checked="checked"{/if} role="switch" id="element_email_plus_equivalent">
                                <label class="form-check-label" for="element_email_plus_equivalent">{$LANG->getModule('email_plus_equivalent')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="auto_login_after_reg" value="1"{if not empty($DATA.auto_login_after_reg)} checked="checked"{/if} role="switch" id="element_auto_login_after_reg">
                                <label class="form-check-label" for="element_auto_login_after_reg">{$LANG->getModule('auto_login_after_reg')}</label>
                            </div>
                        </div>
                    </div>
                    {if $smarty.const.NV_IS_GODADMIN and empty($GCONFIG.idsite)}
                    <div class="row mb-3">
                        <label for="element_register_active_time" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('register_active_time')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="input-group w-auto d-inline-flex">
                                <input type="number" class="form-control fw-75" id="element_register_active_time" name="register_active_time" value="{$DATA.register_active_time}" max="9999" min="0" aria-describedby="element_register_active_time_text">
                                <span class="input-group-text" id="element_register_active_time_text">{$LANG->getModule('hours')}</span>
                            </div>
                            <div class="form-text">{$LANG->getModule('register_active_time_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_active_group_newusers" class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('active_group_newusers')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active_group_newusers" value="1"{if not empty($DATA.active_group_newusers)} checked="checked"{/if} role="switch" id="element_active_group_newusers">
                                <label class="form-check-label" for="element_active_group_newusers">{$LANG->getModule('active_group_newusers_note')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_active_editinfo_censor" class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('active_editinfo_censor')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active_editinfo_censor" value="1"{if not empty($DATA.active_editinfo_censor)} checked="checked"{/if} role="switch" id="element_active_editinfo_censor">
                                <label class="form-check-label" for="element_active_editinfo_censor">{$LANG->getModule('active_editinfo_censor_note')} {$LANG->getModule('active_editinfo_censor_note1', "`$smarty.const.NV_BASE_ADMINURL`index.php?`$smarty.const.NV_LANG_VARIABLE`=`$smarty.const.NV_LANG_DATA`&amp;`$smarty.const.NV_NAME_VARIABLE`=`$MODULE_NAME`&amp;`$smarty.const.NV_OP_VARIABLE`=editcensor")}</label>
                            </div>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <label for="element_auto_assign_oauthuser" class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('auto_assign_oauthuser')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="auto_assign_oauthuser" value="1"{if not empty($DATA.auto_assign_oauthuser)} checked="checked"{/if} role="switch" id="element_auto_assign_oauthuser">
                                <label class="form-check-label" for="element_auto_assign_oauthuser">{$LANG->getModule('auto_assign_oauthuser_note')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </div>
            </div>
            {if $smarty.const.NV_IS_GODADMIN and empty($GCONFIG.idsite)}
            <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mt-4">
                <div class="card-header fs-5 fw-medium">{$LANG->getModule('config_deny')}</div>
                <div class="card-body pt-4">
                    <div class="row mb-3">
                        <label for="element_deny_email" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('deny_email')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <textarea id="element_deny_email" name="deny_email" rows="3" class="form-control">{$DATA.deny_email}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_deny_name" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('deny_name')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <textarea id="element_deny_name" name="deny_name" rows="3" class="form-control">{$DATA.deny_name}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_password_simple" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('password_simple')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <textarea id="element_password_simple" name="password_simple" rows="7" class="form-control">{$DATA.password_simple}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </div>
            </div>
            {/if}
        </div>
        <div class="col-xxl-6">
            <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
                <div class="card-header fs-5 fw-medium">{$LANG->getModule('access_other')}</div>
                <div class="card-body pt-4">
                    <div class="row mb-3">
                        <label for="element_name_show" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('name_show')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select" id="element_name_show" name="name_show">
                                {foreach from=$NAMES_SHOW key=key item=value}
                                <option value="{$key}"{if $key eq $GCONFIG.name_show} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {if $smarty.const.NV_IS_GODADMIN and empty($GCONFIG.idsite)}
                    <div class="row mb-3">
                        <label for="element_avatar_width" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('avatar_size')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="d-flex align-items-center">
                                <div>
                                    <input type="number" class="form-control fw-75" id="element_avatar_width" name="avatar_width" value="{$DATA.avatar_width}">
                                </div>
                                <div class="mx-1">x</div>
                                <div>
                                    <input type="number" class="form-control fw-75" name="avatar_height" value="{$DATA.avatar_height}">
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                    {if $USER_FORUM_SHOW}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_user_forum" value="1"{if not empty($DATA.is_user_forum)} checked="checked"{/if} role="switch" id="element_is_user_forum">
                                <label class="form-check-label" for="element_is_user_forum">{$LANG->getModule('is_user_forum')}</label>
                            </div>
                        </div>
                    </div>
                    {/if}
                    {if not empty($DIRS_FORUM)}
                    <div class="row mb-3">
                        <label for="element_dir_forum" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('dir_forum')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select" id="element_dir_forum" name="dir_forum">
                                {foreach from=$DIRS_FORUM item=value}
                                <option value="{$value}"{if $value eq $GCONFIG.dir_forum} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('whoviewlistuser')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="show-list-ugroup" data-nv-toggle="scroll">
                                <div>
                                    {foreach from=$GROUPS_LIST key=group_id item=group_name}
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="whoviewuser[]" value="{$group_id}" id="whoviewuser{$group_id}"{if in_array($group_id, $DATA.whoviewuser)} checked{/if}>
                                        <label class="form-check-label" for="whoviewuser{$group_id}">{$group_name}</label>
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="send_pass" value="1"{if not empty($DATA.send_pass)} checked="checked"{/if} role="switch" id="element_send_pass">
                                <label class="form-check-label" for="element_send_pass">{$LANG->getModule('send_password')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_user_check_pass_time" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('user_check_pass_time0')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="input-group w-auto d-inline-flex">
                                <input type="number" class="form-control fw-75" id="element_user_check_pass_time" name="user_check_pass_time" value="{round($GCONFIG.user_check_pass_time / 60)}" max="999" min="0" aria-describedby="element_user_check_pass_time_text">
                                <span class="input-group-text" id="element_user_check_pass_time_text">{$LANG->getGlobal('min')}</span>
                            </div>
                            <div class="form-text">{$LANG->getModule('user_check_pass_time')}. {$LANG->getModule('user_check_pass_time_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_pass_timeout" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('pass_timeout')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="element_pass_timeout" name="pass_timeout">
                                <option value="0">0</option>
                                {for $to=6 to 73}
                                {assign var="day" value=($to * 5) nocache}
                                <option value="{$day}"{if $day eq $DATA.pass_timeout} selected{/if}>{$day}</option>
                                {/for}
                            </select>
                            <div class="form-text">{$LANG->getModule('days')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_oldpass_num" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('oldpass_num')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="element_oldpass_num" name="oldpass_num">
                                {for $value=1 to 20}
                                <option value="{$value}"{if $value eq $DATA.oldpass_num} selected{/if}>{$value}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allowuserloginmulti" value="1"{if not empty($DATA.allowuserloginmulti)} checked="checked"{/if} role="switch" id="element_allowuserloginmulti">
                                <label class="form-check-label" for="element_allowuserloginmulti">{$LANG->getModule('allowuserloginmulti')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allowuserlogin" value="1"{if not empty($DATA.allowuserlogin)} checked="checked"{/if} role="switch" id="element_allowuserlogin">
                                <label class="form-check-label" for="element_allowuserlogin">{$LANG->getModule('allow_login')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_login_name_type" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('login_name_type')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="element_login_name_type" name="login_name_type">
                                {foreach from=$LOGIN_NAME_TYPES item=value}
                                <option value="{$value}"{if $value eq $GCONFIG.login_name_type} selected{/if}>{$LANG->getGlobal("login_name_type_`$value`")}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_min_old_user" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('min_old_user')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <input class="form-control w-auto mw-100" type="number" id="element_min_old_user" name="min_old_user" value="{$DATA.min_old_user}" min="0" max="150">
                        </div>
                    </div>
                    {if $smarty.const.NV_IS_GODADMIN and empty($GCONFIG.idsite)}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active_user_logs" value="1"{if not empty($DATA.active_user_logs)} checked="checked"{/if} role="switch" id="element_active_user_logs">
                                <label class="form-check-label" for="element_active_user_logs">{$LANG->getModule('active_user_logs')}</label>
                            </div>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allowuserpublic" value="1"{if not empty($DATA.allowuserpublic)} checked="checked"{/if} role="switch" id="element_allowuserpublic">
                                <label class="form-check-label" for="element_allowuserpublic">{$LANG->getModule('allow_public')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allowquestion" value="1"{if not empty($DATA.allowquestion)} checked="checked"{/if} role="switch" id="element_allowquestion">
                                <label class="form-check-label" for="element_allowquestion">{$LANG->getModule('allow_question')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allowloginchange" value="1"{if not empty($DATA.allowloginchange)} checked="checked"{/if} role="switch" id="element_allowloginchange">
                                <label class="form-check-label" for="element_allowloginchange">{$LANG->getModule('allow_change_login')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="allowmailchange" value="1"{if not empty($DATA.allowmailchange)} checked="checked"{/if} role="switch" id="element_allowmailchange">
                                <label class="form-check-label" for="element_allowmailchange">{$LANG->getModule('allow_change_email')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('openid_servers')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            {foreach from=$OPENID_SERVERS item=server}
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="openid_servers[]" value="{$server.name}" id="openid_servers_{$server.name}"{if $server.disabled} disabled{/if}{if in_array($server.name, $DATA.openid_servers)} checked{/if}>
                                <label class="form-check-label opacity-100" for="openid_servers_{$server.name}">
                                    {if $server.config}
                                    <a href="{$server.link}" title="{$server.note}">{$server.title}</a>
                                    {else}
                                    {$server.title}
                                    {/if}
                                </label>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('openid_processing')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            {foreach from=$OPENID_PROCESSING key=key item=value}
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="openid_processing[]" value="{$key}" id="openid_processing_{$key}"{if in_array($key, $DATA.openid_processing)} checked{/if}>
                                <label class="form-check-label opacity-100" for="openid_processing_{$key}">{$value}</label>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="remove_2step_allow" value="1"{if not empty($DATA.remove_2step_allow)} checked="checked"{/if} role="switch" id="element_remove_2step_allow">
                                <label class="form-check-label" for="element_remove_2step_allow">{$LANG->getModule('remove_2step_allow')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="remove_2step_method" value="1"{if not empty($DATA.remove_2step_method)} checked="checked"{/if} role="switch" id="element_remove_2step_method">
                                <label class="form-check-label" for="element_remove_2step_method">{$LANG->getModule('remove_2step_method')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_admin_email" class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('user_config_admin_email')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="admin_email" value="1"{if not empty($DATA.admin_email)} checked="checked"{/if} role="switch" id="element_admin_email">
                                <label class="form-check-label" for="element_admin_email">{$LANG->getModule('user_config_admin_email1')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_hold_deleted_username" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('hold_deleted_username')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            <input class="form-control w-auto mw-100" type="number" id="element_hold_deleted_username" name="hold_deleted_username" value="{$DATA.hold_deleted_username}" min="0" max="1000">
                            <div class="form-text">{$LANG->getModule('hold_deleted_username_note')}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="checkss" value="{$DATA.checkss}">
    <input type="hidden" name="save" value="1">
</form>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="card">
    <div class="card-header bg-primary text-white rounded-top-2 fs-5 fw-medium">
        {$LANG->getModule('redirecturi_helper_title')}
    </div>
    <div class="card-body">
        <p>
            {$LANG->getModule('redirecturi_helper_text')}.
        </p>
        <h6>{$LANG->getModule('redirecturi_helper_fb1')}</h6>
        <div class="row g-3 mb-3">
            {foreach from=$FACEBOOK_REDIRECTURI key=key item=uri}
            <div class="col-12 col-xxl-6">
                <div class="input-group">
                    <input type="text" class="form-control" value="{$uri}" readonly aria-label="{$LANG->getModule('redirecturi_helper_fb1')}" id="fb_redirecturi_{$key}">
                    <button class="btn btn-secondary copy-btn" type="button" data-success="{$LANG->getGlobal('copy_to_clipboard')}" data-clipboard-target="#fb_redirecturi_{$key}"><i class="fa-solid fa-copy"></i> {$LANG->getGlobal('copy')}</button>
                </div>
            </div>
            {/foreach}
        </div>
        <hr>
        <h6>{$LANG->getModule('redirecturi_helper_gg')}</h6>
        <div class="row g-3 mb-3">
            {foreach from=$GOOGLE_REDIRECTURI key=key item=uri}
            <div class="col-12 col-xxl-6">
                <div class="input-group">
                    <input type="text" class="form-control" value="{$uri}" readonly aria-label="{$LANG->getModule('redirecturi_helper_gg')}" id="gg_redirecturi_{$key}">
                    <button class="btn btn-secondary copy-btn" type="button" data-success="{$LANG->getGlobal('copy_to_clipboard')}" data-clipboard-target="#gg_redirecturi_{$key}"><i class="fa-solid fa-copy"></i> {$LANG->getGlobal('copy')}</button>
                </div>
            </div>
            {/foreach}
        </div>
        <hr>
        <h6>{$LANG->getModule('redirecturi_helper_fb2')}</h6>
        <div class="row g-3">
            {foreach from=$FACEBOOK_DATADELETIONURL key=key item=uri}
            <div class="col-12 col-xxl-6">
                <div class="input-group">
                    <input type="text" class="form-control" value="{$uri}" readonly aria-label="{$LANG->getModule('redirecturi_helper_fb2')}" id="fb_datadeletionurl_{$key}">
                    <button class="btn btn-secondary copy-btn" type="button" data-success="{$LANG->getGlobal('copy_to_clipboard')}" data-clipboard-target="#fb_datadeletionurl_{$key}"><i class="fa-solid fa-copy"></i> {$LANG->getGlobal('copy')}</button>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
