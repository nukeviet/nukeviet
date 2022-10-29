<!-- BEGIN: main -->
<form  class="form-inline" role="form" action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <!-- BEGIN: access -->
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.access_caption} </caption>
            <thead>
                <tr class="text-center">
                    <th>{LANG.access_admin}</th>
                    <th class="text-center">{LANG.access_viewlist}</th>
                    <th class="text-center">{LANG.access_addus}</th>
                    <th class="text-center">{LANG.access_waiting}</th>
                    <th class="text-center">{LANG.editcensor}</th>
                    <th class="text-center">{LANG.access_editus}</th>
                    <th class="text-center">{LANG.access_delus}</th>
                    <th class="text-center">{LANG.access_passus}</th>
                    <th class="text-center">{LANG.access_groups}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td><strong>{ACCESS.title}</strong></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_viewlist} value="1" name="access_viewlist[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_addus} value="1" name="access_addus[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_waiting} value="1" name="access_waiting[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_editcensor} value="1" name="access_editcensor[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_editus} value="1" name="access_editus[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_delus} value="1" name="access_delus[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_passus} value="1" name="access_passus[{ACCESS.id}]"></td>
                    <td class="text-center"><input type="checkbox" {ACCESS.checked_groups} value="1" name="access_groups[{ACCESS.id}]"></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
        <!-- END: access -->
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.access_register} </caption>
            <colgroup>
                <col style="width: 320px;" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td>{LANG.type_reg}</td>
                    <td>
                    <select class="form-control" name="allowuserreg">
                        <!-- BEGIN: registertype -->
                        <option value="{REGISTERTYPE.id}"{REGISTERTYPE.select}> {REGISTERTYPE.value}</option>
                        <!-- END: registertype -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.nv_unick}</td>
                    <td>
                    <select name="nv_unickmin" class="form-control text-right">
                        <!-- BEGIN: nv_unickmin -->
                        <option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
                        <!-- END: nv_unickmin -->
                    </select>
                    <span class="text-middle"> -> </span>
                    <select name="nv_unickmax" class="form-control text-right">
                        <!-- BEGIN: nv_unickmax -->
                        <option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
                        <!-- END: nv_unickmax -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.nv_unick_type}</td>
                    <td>
                    <select class="form-control" name="nv_unick_type">
                        <!-- BEGIN: nv_unick_type -->
                        <option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
                        <!-- END: nv_unick_type -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.nv_upass}</td>
                    <td>
                    <select name="nv_upassmin" class="form-control text-right">
                        <!-- BEGIN: nv_upassmin -->
                        <option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
                        <!-- END: nv_upassmin -->
                    </select>
                    <span class="text-middle"> -> </span>
                    <select name="nv_upassmax" class="form-control text-right">
                        <!-- BEGIN: nv_upassmax -->
                        <option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
                        <!-- END: nv_upassmax -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.nv_upass_type}</td>
                    <td>
                    <select class="form-control" name="nv_upass_type">
                        <!-- BEGIN: nv_upass_type -->
                        <option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
                        <!-- END: nv_upass_type -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.auto_login_after_reg}</td>
                    <td><input type="checkbox" value="1" name="auto_login_after_reg"{DATA.auto_login_after_reg}/></td>
                </tr>
                <!-- BEGIN: active_group_newusers -->
                <tr>
                    <td>{LANG.active_group_newusers}</td>
                    <td>
                        <label class="mb-0">
                            <input type="checkbox" value="1" name="active_group_newusers"{DATA.active_group_newusers}/> <small>{LANG.active_group_newusers_note}</small>
                        </label>
                    </td>
                </tr>
                <!-- END: active_group_newusers -->
                <!-- BEGIN: active_editinfo_censor -->
                <tr>
                    <td>{LANG.active_editinfo_censor}</td>
                    <td>
                        <label class="mb-0">
                            <input type="checkbox" value="1" name="active_editinfo_censor"{DATA.active_editinfo_censor}/> <small>{LANG.active_editinfo_censor_note} {LINK_EDITCENSOR}</small>
                        </label>
                    </td>
                </tr>
                <!-- END: active_editinfo_censor -->
                <tr>
                    <td>{LANG.auto_assign_oauthuser}</td>
                    <td>
                        <label class="mb-0">
                            <input type="checkbox" value="1" name="auto_assign_oauthuser"{DATA.auto_assign_oauthuser}/> <small>{LANG.auto_assign_oauthuser_note}</small>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.access_other} </caption>
            <colgroup>
                <col style="width: 320px;" />
                <col />
            </colgroup>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input type="hidden" name="save" value="1"><input class="btn btn-primary w100" type="submit" value="{LANG.save}"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td> {LANG.name_show} </td>
                    <td>
                        <select class="form-control" name="name_show">
                            <!-- BEGIN: name_show -->
                            <option value="{NAME_SHOW.id}"{NAME_SHOW.select}>{NAME_SHOW.value}</option>
                            <!-- END: name_show -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: avatar_size -->
                <tr>
                    <td>{LANG.avatar_size}</td>
                    <td>
                        <label>
                            <input type="text" class="form-control txt-half" name="avatar_width" value="{DATA.avatar_width}" style="width: 50px"/> x
                            <input type="text" class="form-control txt-half" name="avatar_height" value="{DATA.avatar_height}" style="width: 50px"/>
                        </label>
                    </td>
                </tr>
                <!-- END: avatar_size -->
                <!-- BEGIN: user_forum -->
                <tr>
                    <td>{LANG.is_user_forum}</td>
                    <td><input name="is_user_forum" value="1" type="checkbox"{DATA.is_user_forum} /></td>
                </tr>
                <!-- END: user_forum -->
                <!-- BEGIN: dir_forum -->
                <tr>
                    <td>{LANG.dir_forum}</td>
                    <td>
                        <select class="form-control w200" name="dir_forum">
                            <option value="">&nbsp;</option>
                            <!-- BEGIN: loop -->
                            <option value="{DIR_FORUM.id}"{DIR_FORUM.select}> {DIR_FORUM.value}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: dir_forum -->

                <tr>
                    <td>{LANG.whoviewlistuser}</td>
                    <td>
                        <div class="clearfix group-allow-area">
                            <!-- BEGIN: whoviewlistuser -->
                            <p>
                                <input type="checkbox" id="whoviewuser{WHOVIEW.key}" name="whoviewuser[]" value="{WHOVIEW.key}"{WHOVIEW.checked}/>
                                <label for="whoviewuser{WHOVIEW.key}">{WHOVIEW.title}</label>
                            </p>
                            <!-- END: whoviewlistuser -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.user_check_pass_time}</td>
                    <td><input class="form-control pull-left" style="width:50px;" type="text" value="{USER_CHECK_PASS_TIME}" name="user_check_pass_time" maxlength="3"/>({GLANG.min})</td>
                </tr>
                <tr>
                    <td>{LANG.allowuserloginmulti}</td>
                    <td><input name="allowuserloginmulti" value="1" type="checkbox" {DATA.allowuserloginmulti} /></td>
                </tr>
                <tr>
                    <td>{LANG.allow_login}</td>
                    <td><input name="allowuserlogin" value="1" type="checkbox"{DATA.allowuserlogin} /></td>
                </tr>
                <tr>
                    <td>{LANG.min_old_user}</td>
                    <td><input class="form-control pull-left"  name="min_old_user" value="{DATA.min_old_user}" type="text" style="width: 50px" /></td>
                </tr>
                <!-- BEGIN: active_user_logs -->
                <tr>
                    <td>{LANG.active_user_logs}</td>
                    <td><input name="active_user_logs" value="1" type="checkbox"{DATA.active_user_logs} /></td>
                </tr>
                <!-- END: active_user_logs -->
                <tr>
                    <td>{LANG.allow_public}</td>
                    <td><input name="allowuserpublic" value="1" type="checkbox"{DATA.allowuserpublic} /></td>
                </tr>
                <tr>
                    <td>{LANG.allow_question}</td>
                    <td><input name="allowquestion" value="1" type="checkbox"{DATA.allowquestion} /></td>
                </tr>
                <tr>
                    <td>{LANG.allow_change_login}</td>
                    <td><input name="allowloginchange" value="1" type="checkbox"{DATA.allowloginchange} /></td>
                </tr>
                <tr>
                    <td>{LANG.allow_change_email}</td>
                    <td><input name="allowmailchange" value="1" type="checkbox"{DATA.allowmailchange} /></td>
                </tr>
                <tr>
                    <td>{LANG.openid_servers}</td>
                    <td>
                    <!-- BEGIN: openid_servers -->
                    <input name="openid_servers[]" {OPENID.disabled} value="{OPENID.name}" type="checkbox"{OPENID.checked} />
                    <!-- BEGIN: config -->
                        <a href="{OPENID.link_config}" title="{OPENID.note}">{OPENID.title}</a>
                    <!-- END: config -->
                    <!-- BEGIN: noconfig -->
                        {OPENID.title}
                    <!-- END: noconfig -->
                    <br />
                    <!-- END: openid_servers -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.openid_processing}</td>
                    <td>
                        <!-- BEGIN: openid_processing -->
                        <input name="openid_processing[]" value="{OPENID_PROCESSING.key}" type="checkbox"{OPENID_PROCESSING.checked} /> {OPENID_PROCESSING.name}<br/>
                        <!-- END: openid_processing -->
                    </td>
                </tr>
                <!-- BEGIN: deny_config -->
                <tr>
                    <td>{LANG.deny_email}</td>
                    <td><textarea name="deny_email" rows="7" cols="70" class="form-control">{DATA.deny_email}</textarea></td>
                </tr>
                <tr>
                    <td>{LANG.deny_name}</td>
                    <td><textarea name="deny_name" rows="7" cols="70" class="form-control">{DATA.deny_name}</textarea></td>
                </tr>
                <tr>
                    <td>{LANG.password_simple}</td>
                    <td><textarea name="password_simple" rows="7" cols="70" class="form-control">{DATA.password_simple}</textarea></td>
                </tr>
                <!-- END: deny_config -->
                <tr>
                    <td>{LANG.user_config_admin_email}</td>
                    <td>
                        <label class="mb-0">
                            <input type="checkbox" value="1" name="admin_email"{DATA.admin_email}/> <small>{LANG.user_config_admin_email1}</small>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->
