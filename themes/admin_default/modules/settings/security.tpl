<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: error_save -->
<div class="alert alert-danger">{ERROR_SAVE}</div>
<!-- END: error_save -->

<!-- BEGIN: manual_save -->
<div class="alert alert-danger">{MESSAGE}</div>
<pre><code>{CODE}</code></pre>
<!-- END: manual_save -->

<div class="clearfix">
    <ul class="nav nav-tabs setting-tabnav" role="tablist" id="settingTabs">
        <li role="presentation" class="{TAB0_ACTIVE}"><a href="#settingBasic" aria-controls="settingBasic" aria-offsets="0" role="tab" data-toggle="tab">{LANG.security}</a></li>
        <li role="presentation" class="{TAB1_ACTIVE}"><a href="#settingFlood" aria-controls="settingFlood" aria-offsets="1" role="tab" data-toggle="tab">{LANG.flood_blocker}</a></li>
        <li role="presentation" class="{TAB2_ACTIVE}"><a href="#settingCaptcha" aria-controls="settingCaptcha" aria-offsets="2" role="tab" data-toggle="tab">{LANG.captcha}</a></li>
        <li role="presentation" class="{TAB3_ACTIVE}"><a href="#settingIp" aria-controls="settingIp" aria-offsets="3" role="tab" data-toggle="tab">{LANG.banip}</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane{TAB0_ACTIVE}" id="settingBasic">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                	<div class="table-responsive">
                    	<table class="table table-striped table-bordered table-hover table-first">
                    		<colgroup>
                    			<col style="width: 40%" />
                    			<col style="width: 60%" />
                    		</colgroup>
                    		<tbody>
                    			<tr>
                    				<td><strong>{LANG.is_login_blocker}</strong></td>
                    				<td><input type="checkbox" value="1" name="is_login_blocker" {IS_LOGIN_BLOCKER} /></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.login_number_tracking}</strong></td>
                    				<td><input type="text" value="{LOGIN_NUMBER_TRACKING}" name="login_number_tracking" class="required form-control w100"/></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.login_time_tracking}</strong> ({GLANG.min})</td>
                    				<td><input type="text" value="{LOGIN_TIME_TRACKING}" name="login_time_tracking" class="required form-control w100"/></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.login_time_ban}</strong> ({GLANG.min})</td>
                    				<td><input type="text" value="{LOGIN_TIME_BAN}" name="login_time_ban" class="required form-control w100"/></td>
                    			</tr>
                                <tr>
                                    <td><strong>{LANG.two_step_verification}</strong></td>
                                    <td>
                                        <span class="help-block">{LANG.two_step_verification_note}</span>
                                        <select name="two_step_verification" class="form-control w200">
                                            <!-- BEGIN: two_step_verification --><option value="{TWO_STEP_VERIFICATION.key}"{TWO_STEP_VERIFICATION.selected}>{TWO_STEP_VERIFICATION.title}</option><!-- END: two_step_verification -->
                                        </select>
                                    </td>
                                </tr>
                    			<tr>
                    				<td><strong>{LANG.nv_anti_agent}</strong></td>
                    				<td><input type="checkbox" value="1" name="nv_anti_agent" {ANTI_AGENT} /></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.proxy_blocker}</strong></td>
                    				<td>
                        				<select name="proxy_blocker" class="form-control w200">
                        					<!-- BEGIN: proxy_blocker -->
                        					<option value="{PROXYOP}" {PROXYSELECTED}>{PROXYVALUE} </option>
                        					<!-- END: proxy_blocker -->
                        				</select>
                                    </td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.str_referer_blocker}</strong></td>
                    				<td><input type="checkbox" value="1" name="str_referer_blocker" {REFERER_BLOCKER} /></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.nv_anti_iframe}</strong></td>
                    				<td><input type="checkbox" value="1" name="nv_anti_iframe" {ANTI_IFRAME} /></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.nv_allowed_html_tags}</strong></td>
                    				<td><textarea name="nv_allowed_html_tags" class="form-control" style="height: 100px" class="required">{NV_ALLOWED_HTML_TAGS}</textarea></td>
                    			</tr>
                    		</tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-primary w100" name="submitbasic" value="{LANG.submit}" />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                    	</table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB1_ACTIVE}" id="settingFlood">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                	<div class="table-responsive">
                    	<table class="table table-striped table-bordered table-hover table-first">
                    		<colgroup>
                    			<col style="width: 40%" />
                    			<col style="width: 60%" />
                    		</colgroup>
                    		<tbody>
                    			<tr>
                    				<td><strong>{LANG.is_flood_blocker}</strong></td>
                    				<td><input type="checkbox" value="1" name="is_flood_blocker" {IS_FLOOD_BLOCKER} /></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.max_requests_60}</strong></td>
                    				<td><input type="text" value="{MAX_REQUESTS_60}" name="max_requests_60" class="required form-control w100"/></td>
                    			</tr>
                    			<tr>
                    				<td><strong>{LANG.max_requests_300}</strong></td>
                    				<td><input type="text" value="{MAX_REQUESTS_300}" name="max_requests_300" class="required form-control w100"/></td>
                    			</tr>
                    		</tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-primary w100" name="submitflood" value="{LANG.submit}" />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                    	</table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>

                <!-- BEGIN: noflips -->
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>{LANG.noflood_ip_list}</strong></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{LANG.banip_ip}</th>
                                <th>{LANG.banip_mask}</th>
                                <th>{LANG.banip_timeban}</th>
                                <th>{LANG.banip_timeendban}</th>
                                <th class="text-center">{LANG.banip_funcs}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loop -->
                            <tr>
                                <td>{ROW.dbip}</td>
                                <td>{ROW.dbmask}</td>
                                <td>{ROW.dbbegintime}</td>
                                <td>{ROW.dbendtime}</td>
                                <td class="text-center">
                                    <a class="edit btn btn-default btn-xs" title="{LANG.banip_edit}" href="{ROW.url_edit}"><em class="fa fa-edit fa-fw"></em>{LANG.banip_edit}</a>
                                    <a class="deleteone-ip btn btn-danger btn-xs" title="{LANG.banip_delete}" href="{ROW.url_delete}"><em class="fa fa-trash-o fa-fw"></em>{LANG.banip_delete}</a>
                                </td>
                            </tr>
                            <!-- END: loop -->
                        </tbody>
                    </table>
                </div>
                <!-- END: noflips -->

                <form action="{FORM_ACTION}" method="post">
                    <input type="hidden" name="flid" value="{FLDATA.flid}" />
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>{NOFLOODIP_TITLE}</strong></div>
                        <table class="table table-striped table-bordered table-hover">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td>
                                        {LANG.banip_address} (<span style="color:red">*</span>)
                                        <br/>(xxx.xxx.xxx.xxx)
                                    </td>
                                    <td><input class="w200 form-control" type="text" name="flip" value="{FLDATA.flip}" /></td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_mask}</td>
                                    <td>
                                    <select name="flmask" class="form-control w200">
                                        <option value="0">{MASK_TEXT_ARRAY.0}</option>
                                        <option value="3"{FLDATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
                                        <option value="2"{FLDATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
                                        <option value="1"{FLDATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_begintime}</td>
                                    <td><input type="text" name="flbegintime" class="w150 datepicker form-control pull-left" value="{FLDATA.begintime}"/></td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_endtime}</td>
                                    <td><input type="text" name="flendtime" class="w150 datepicker form-control pull-left text" value="{FLDATA.endtime}" /></td>
                                </tr>
                                <tr>
                                    <td class="top">{LANG.banip_notice}</td>
                                    <td><textarea cols="70" rows="5" class="form-control" name="flnotice" style="width:550px;height:100px">{FLDATA.notice}</textarea></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center"><input type="submit" value="{LANG.banip_confirm}" name="submitfloodip" class="btn btn-primary" /></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB2_ACTIVE}" id="settingCaptcha">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                		<table class="table table-striped table-bordered table-hover table-first">
                			<colgroup>
                				<col style="width: 40%" />
                				<col style="width: 60%" />
                			</colgroup>
                			<tbody>
                				<tr>
                					<td><strong>{LANG.captcha}</strong></td>
                					<td>
                    					<select name="gfx_chk" class="form-control w200">
                    						<!-- BEGIN: opcaptcha -->
                    						<option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
                    						<!-- END: opcaptcha -->
                    					</select>
                                    </td>
                				</tr>
                				<tr>
                					<td><strong>{LANG.captcha_type}</strong></td>
                					<td>
                    					<select name="captcha_type" class="form-control w200" data-toggle="ctcaptcha">
                    						<!-- BEGIN: captcha_type -->
                    						<option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
                    						<!-- END: captcha_type -->
                    					</select>
                                    </td>
                				</tr>
                			    <tr data-captcha="typebasic"{DISPLAY_CAPTCHA_BASIC}>
                                    <td><strong>{LANG.captcha_num}</strong></td>
                                    <td>
                                        <select name="nv_gfx_num" class="form-control w200">
                                            <!-- BEGIN: nv_gfx_num -->
                                            <option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
                                            <!-- END: nv_gfx_num -->
                                        </select>
                                    </td>
                                </tr>
                                <tr data-captcha="typebasic"{DISPLAY_CAPTCHA_BASIC}>
                                    <td><strong>{LANG.captcha_size}</strong></td>
                                    <td><input class="form-control pull-left" style="width:50px;" type="text" value="{NV_GFX_WIDTH}" name="nv_gfx_width" maxlength="3"/> <span class="text-middle pull-left">&nbsp;x&nbsp;</span> <input class="form-control pull-left" style="width:50px;" type="text" value="{NV_GFX_HEIGHT}" name="nv_gfx_height" maxlength="3"/></td>
                                </tr>
                                <tr data-captcha="typerecaptcha"{DISPLAY_CAPTCHA_RECAPTCHA}>
                                    <td><strong>{LANG.recaptcha_sitekey} (<span style="color:red">*</span>)</strong></td>
                                    <td>
                                        <input class="form-control pull-left w300" type="text" value="{RECAPTCHA_SITEKEY}" name="recaptcha_sitekey" maxlength="200"/>
                                        &nbsp; <a href="https://www.google.com/recaptcha/admin" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="text-middle"><i class="fa fa-info-circle"></i></a>
                                    </td>
                                </tr>
                                <tr data-captcha="typerecaptcha"{DISPLAY_CAPTCHA_RECAPTCHA}>
                                    <td><strong>{LANG.recaptcha_secretkey} (<span style="color:red">*</span>)</strong></td>
                                    <td>
                                        <input class="form-control pull-left w300" type="text" value="{RECAPTCHA_SECRETKEY}" name="recaptcha_secretkey" maxlength="200"/>
                                        &nbsp; <a href="https://www.google.com/recaptcha/admin" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="text-middle"><i class="fa fa-info-circle"></i></a>
                                    </td>
                                </tr>
                                <tr data-captcha="typerecaptcha"{DISPLAY_CAPTCHA_RECAPTCHA}>
                                    <td><strong>{LANG.recaptcha_type}</strong></td>
                                    <td>
                                        <select name="recaptcha_type" class="form-control w300">
                                            <!-- BEGIN: recaptcha_type -->
                                            <option value="{RECAPTCHA_TYPE.value}"{RECAPTCHA_TYPE.select}>{RECAPTCHA_TYPE.text}</option>
                                            <!-- END: recaptcha_type -->
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-primary w100" name="submitcaptcha" value="{LANG.submit}" />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB3_ACTIVE}" id="settingIp">
            <div class="setting-tabcontenttop">
                <!-- BEGIN: listip -->
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>{LANG.banip}</strong></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{LANG.banip_ip}</th>
                                <th>{LANG.banip_mask}</th>
                                <th>{LANG.banip_area}</th>
                                <th>{LANG.banip_timeban}</th>
                                <th>{LANG.banip_timeendban}</th>
                                <th class="text-center">{LANG.banip_funcs}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loop -->
                            <tr>
                                <td>{ROW.dbip}</td>
                                <td>{ROW.dbmask}</td>
                                <td>{ROW.dbarea}</td>
                                <td>{ROW.dbbegintime}</td>
                                <td>{ROW.dbendtime}</td>
                                <td class="text-center">
                                    <a class="edit btn btn-default btn-xs" title="{LANG.banip_edit}" href="{ROW.url_edit}"><em class="fa fa-edit fa-fw"></em>{LANG.banip_edit}</a>
                                    <a class="deleteone-ip btn btn-danger btn-xs" title="{LANG.banip_delete}" href="{ROW.url_delete}"><em class="fa fa-trash-o fa-fw"></em>{LANG.banip_delete}</a>
                                </td>
                            </tr>
                            <!-- END: loop -->
                        </tbody>
                    </table>
                </div>
                <!-- END: listip -->

                <form action="{FORM_ACTION}" method="post">
                    <input type="hidden" name ="cid" value="{DATA.cid}" />
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>{BANIP_TITLE}</strong></div>
                        <table class="table table-striped table-bordered table-hover">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center"><input type="submit" value="{LANG.banip_confirm}" name="submit" class="btn btn-primary w100"/></td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>
                                        {LANG.banip_address} (<span style="color:red">*</span>)
                                        <br/>(xxx.xxx.xxx.xxx)
                                    </td>
                                    <td><input class="w200 form-control" type="text" name="ip" value="{DATA.ip}" /></td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_mask}</td>
                                    <td>
                                        <select name="mask" class="form-control w200">
                                            <option value="0">{MASK_TEXT_ARRAY.0}</option>
                                            <option value="3"{DATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
                                            <option value="2"{DATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
                                            <option value="1"{DATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_area} (<span style="color:red">*</span>)</td>
                                    <td>
                                        <select name="area" class="form-control w200">
                                            <option value="0">{BANIP_AREA_ARRAY.0}</option>
                                            <option value="1"{DATA.selected_area_1}>{BANIP_AREA_ARRAY.1}</option>
                                            <option value="2"{DATA.selected_area_2}>{BANIP_AREA_ARRAY.2}</option>
                                            <option value="3"{DATA.selected_area_3}>{BANIP_AREA_ARRAY.3}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_begintime}</td>
                                    <td><input type="text" name="begintime" class="w150 datepicker form-control pull-left" value="{DATA.begintime}"/></td>
                                </tr>
                                <tr>
                                    <td>{LANG.banip_endtime}</td>
                                    <td><input type="text" name="endtime" class="w150 datepicker form-control pull-left text" value="{DATA.endtime}" /></td>
                                </tr>
                                <tr>
                                    <td class="top">{LANG.banip_notice}</td>
                                    <td><textarea cols="70" rows="5" class="form-control" name="notice" style="width:550px;height:100px">{DATA.notice}</textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="gselectedtab" value="{SELECTEDTAB}"/>
<script type="text/javascript">
//<![CDATA[
var LANG = [];
LANG.banip_delete_confirm = '{LANG.banip_delete_confirm}';
LANG.banip_del_success = '{LANG.banip_del_success}';
$(document).ready(function() {
    $('#settingTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
        $('[name="gselectedtab"]').val($(this).attr('aria-offsets'));
    });
});
//]]>
</script>
<!-- END: main -->