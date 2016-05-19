<!-- BEGIN: main -->
<span class="pa" data-toggle="tip" data-target="#guestBlock" data-click="y"><em class="fa fa-user fa-lg pointer mbt-lg"></em><span class="hidden">{LANG.contactUs}</span></span>
<!-- START FORFOOTER -->
<div id="guestBlock" class="hidden">
    <div class="guestBlock">
        <h3><a href="#" onclick="switchTab(this);tipAutoClose(true);" class="guest-sign pointer margin-right current" data-switch=".log-area, .reg-area" data-obj=".guestBlock">{GLANG.signin}</a> <!-- BEGIN: allowuserreg2 --><a href="#" onclick="switchTab(this);tipAutoClose(false);" class="guest-reg pointer" data-switch=".reg-area, .log-area" data-obj=".guestBlock">{GLANG.register}</a> <!-- END: allowuserreg2 --></h3>
        <div class="log-area">
        	<form action="{USER_LOGIN}" method="post" onsubmit="return login_validForm(this);" autocomplete="off" novalidate>
	            <div class="nv-info margin-bottom">{GLANG.logininfo}</div>
	            <div class="form-group">
	                <div class="input-group">
	                    <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
	                    <input type="text" class="required form-control" placeholder="{GLANG.username}" value="" name="nv_login" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.username_empty}">
	                </div>
	            </div>
	            <div class="form-group">
	                <div class="input-group">
	                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
	                    <input type="password" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
	                </div>
	            </div>
	            <!-- BEGIN: captcha -->
	            <div class="form-group">
	                <div class="middle text-right clearfix">
	                    <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" /><em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.bsec');"></em><input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="bsec" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" onkeypress="inputSignIn(event);" />
	                </div>
	            </div>
	        	<!-- END: captcha -->
	            <div class="text-center">
	        	   <a href="{USER_LOSTPASS}" class="margin-right">{GLANG.lostpass}?</a>
	               <button class="bsubmit btn btn-primary" type="submit" data-errorMessage="{LANG.errorMessage}" data-loginOk="{LANG.login_ok}">{GLANG.loginsubmit}</button>
	        	</div>
	            <!-- BEGIN: openid -->
	        	<hr />
	        	<div class="text-center">
	        		<!-- BEGIN: server -->
	        		<a title="{OPENID.title}" href="{OPENID.href}" class="margin-right"><img alt="{OPENID.title}" title="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /></a>
	        		<!-- END: server -->
	        	</div>
	        	<!-- END: openid -->
        	</form>
        </div>
        <!-- BEGIN: allowuserreg -->
        <div class="reg-area hidden">
            <form action="{USER_REGISTER}" method="post" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
	            <div class="nv-info margin-bottom" data-default="{LANG.info}">{LANG.info}</div>
	            <div class="inputs">
	                <div class="form-group">
	                    <span><input type="text" class="form-control" name="last_name" value="" maxlength="255" placeholder="{LANG.last_name}"/></span>
	                </div>
	                <div class="form-group">
	                    <span><input type="text" class="form-control" name="first_name" value="" maxlength="255" placeholder="{LANG.first_name}" /></span>
	                </div>
	                
	                <div class="form-group">
	                    <span><input type="text" class="required form-control" name="username" value="" maxlength="{NICK_MAXLENGTH}" placeholder="{LANG.account}" data-pattern="/^(.){{NICK_MINLENGTH},{NICK_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.username_empty}"/></span>
	                </div>
	                <div class="form-group">
	                    <span><input type="email" class="required form-control" placeholder="{LANG.email}" name="email" maxlength="100" onkeypress="validErrorHidden(this);" data-mess="{GLANG.email_empty}"/></span>
	            	</div>
	                
	                <div class="form-group">
	                    <span><input type="password" class="required password form-control" name="password" value="" maxlength="{PASS_MAXLENGTH}" placeholder="{LANG.password}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}" /></span>
	                </div>
	                <div class="form-group">
	                    <span><input type="password" class="required password form-control" name="re_password" value="" maxlength="{PASS_MAXLENGTH}" placeholder="{LANG.re_password}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.re_password_empty}" /></span>
	            	</div>
	            
	                <div class="form-group">
	                    <div class="input-group">
	                        <input type="text" class="required form-control" name="your_question" value="" maxlength="255" placeholder="{LANG.question}"  data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.your_question_empty}" />
	                        <span class="input-group-btn"><a type="button" class="btql btn btn-default" onclick="showQlist(this)" data-show="no"><em class="fa fa-caret-down fa-lg"></em></a></span>
				            <div class="qlist" data-show="no">
				                <ul>
				                    <!-- BEGIN: frquestion -->
				                    <li><a href="#" onclick="addQuestion(this);">{QUESTION}</a></li>
				                    <!-- END: frquestion -->
				                </ul>
				            </div>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <span><input type="text" class="required form-control" name="answer" value="" maxlength="255" placeholder="{LANG.answer_question}" maxlength="255" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.answer_empty}" /></span>
	                </div>
			     	<!-- BEGIN: field -->
			    	<!-- BEGIN: loop -->
			        <!-- BEGIN: textbox -->
			        <div class="form-group">
			            <div>
			                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" onkeypress="validErrorHidden(this);" data-mess=""/>
			            </div>
			        </div>
			        <!-- END: textbox -->
			    
			        <!-- BEGIN: date -->
			        <div class="form-group">
			            <div class="input-group">
			                <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" readonly="readonly" onchange="validErrorHidden(this);" onfocus="datepickerShow(this);" data-mess=""/>
			                <span class="input-group-addon pointer" onclick="button_datepickerShow(this);">
			                    <em class="fa fa-calendar"></em>
			                </span>
			            </div>
			        </div>
			        <!-- END: date -->
			        
			        <!-- BEGIN: textarea -->
			        <div class="form-group">
			            <div>
			                <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="custom_fields[{FIELD.field}]" onkeypress="validErrorHidden(this);" data-mess="">{FIELD.value}</textarea>
			            </div>
			        </div>
			        <!-- END: textarea -->
			        
			        <!-- BEGIN: editor -->
			        {EDITOR}
			        <!-- END: editor -->
			        
			        <!-- BEGIN: select -->
			        <div class="form-group">
			            <div>
			                <select name="custom_fields[{FIELD.field}]" class="form-control {FIELD.required} {FIELD.class}" onchange="validErrorHidden(this);" data-mess="">
			                    <!-- BEGIN: loop -->
			    				<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>
			    					{FIELD_CHOICES.value}
			    				</option>
			                    <!-- END: loop -->
			                </select>
			            </div>
			        </div>
			        <!-- END: select -->
			        
			        <!-- BEGIN: radio -->
			        <div>
			            <div>
			                <div class="form-group clearfix radio-box {FIELD.required}" data-mess="">
			                    <label for="custom_fields[{FIELD.field}]" class="col-sm-8 control-label {FIELD.required}" title="{FIELD.description}">
			                        {FIELD.title}
			                    </label>
			                    <div class="btn-group col-sm-16">
			                        <!-- BEGIN: loop -->
			             			<label for="lb_{FIELD_CHOICES.id}" class="radio-box">
			            				<input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="validErrorHidden(this,5);" {FIELD_CHOICES.checked}>
			                            {FIELD_CHOICES.value}
			                        </label>
			                        <!-- END: loop -->
			                    </div>
			                </div>
			            </div>
			        </div>
			        <!-- END: radio -->
			        
			        <!-- BEGIN: checkbox -->
			        <div>
			            <div>
			                <div class="form-group clearfix check-box {FIELD.required}" data-mess="">
			                    <label for="custom_fields[{FIELD.field}]" class="col-sm-8 control-label {FIELD.required}" title="{FIELD.description}">
			                        {FIELD.title}
			                    </label>
			                    <div class="btn-group col-sm-16">
			                        <!-- BEGIN: loop -->
			                        <label for="lb_{FIELD_CHOICES.id}" class="check-box">
			            				<input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="validErrorHidden(this,5);" {FIELD_CHOICES.checked}>
			            				{FIELD_CHOICES.value}
			                        </label>
			                        <!-- END: loop -->
			                    </div>
			                </div>
			            </div>
			        </div>
			        <!-- END: checkbox -->
			        
			        <!-- BEGIN: multiselect -->
			        <div class="form-group">
			            <div>
			    			<select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class} {FIELD.required} form-control" onchange="validErrorHidden(this);" data-mess="">
			    				<!-- BEGIN: loop -->
			    				<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
			    				<!-- END: loop -->
			    			</select>
			            </div>
			        </div>
			        <!-- END: multiselect -->
			    	<!-- END: loop -->
			    	<!-- END: field -->
	               <!-- BEGIN: reg_captcha -->
	                <div class="form-group">
	                    <div class="middle text-right clearfix">
	                        <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" />
							<em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.brsec');"></em>
							<input type="text" style="width:100px;" class="brsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" data-pattern="/^(.){{GFX_MAXLENGTH},{GFX_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
	                    </div>
	                </div>
	                <!-- END: reg_captcha -->
			        <div>
			            <div>
			                <div class="form-group text-center check-box required" data-mess="">
			                    <input type="checkbox" name="agreecheck" value="1" class="fix-box" onclick="validErrorHidden(this,3);"/>{LANG.accept2} <a onclick="usageTermsShow('{LANG.usage_terms}');" href="javascript:void(0);"><span class="btn btn-default btn-xs">{LANG.usage_terms}</span></a>
			                </div>
			            </div>
			        </div>
	                <div class="text-center">
			            <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
			            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
	                   <button class="brsubmit btn btn-primary" type="submit" data-errorMessage="{LANG.errorMessage}" data-regOK="{LANG.register_ok}">{GLANG.register}</button>
	            	</div>
	            </div>
       		</form>
        </div>
        <!-- END: allowuserreg -->
    </div>
</div>
<!-- END FORFOOTER -->
<script src="{NV_BASE_SITEURL}themes/default/js/users.js"></script>
<!-- END: main -->
<!-- BEGIN: signed -->
<span data-toggle="tip" data-target="#userBlock" data-click="y"><strong class="pointer bg-gainsboro" style="background-image:url({AVATA})"></strong><span class="hidden">{LANG.full_name}</span></span>
<!-- START FORFOOTER -->
<div id="userBlock" class="hidden">
    <div class="nv-info" style="display:none"></div>
    <div class="userBlock clearfix">
    	<h3 class="text-center"><span class="lev-{LEVEL} text-normal">{WELCOME}:</span> {USER.full_name}</h3>
    	<div class="row">
    		<div class="col-xs-8 text-center">
    			<a title="{LANG.edituser}" href="#"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail bg-gainsboro" /></a>
    		</div>
    		<div class="col-xs-16">
    		    <ul class="nv-list-item sm">
    		    	<li class="active"><a href="{URL_MODULE}">{LANG.user_info}</a></li>
    		    	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
    		    	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}editinfo/openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
    		    	<!-- BEGIN: regroups --><li><a href="{URL_HREF}editinfo/group">{LANG.in_group}</a></li><!-- END: regroups -->
    		    </ul>
    		</div>
    	</div>
        <!-- BEGIN: admintoolbar -->
        <div class="margin-top boder-top padding-top">
            <p class="margin-bottom-sm"><strong>{GLANG.for_admin}</strong></p>
            <ul class="nv-list-item sm">
                <li><em class="fa fa-cog fa-horizon margin-right-sm"></em><a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}" title="{GLANG.admin_page}"><span>{GLANG.admin_page}</span></a></li>
                <!-- BEGIN: is_modadmin -->
        		<li><em class="fa fa-key fa-horizon margin-right-sm"></em><a href="{URL_ADMINMODULE}" title="{GLANG.admin_module_sector} {MODULENAME}"><span>{GLANG.admin_module_sector} {MODULENAME}</span></a></li>
        		<!-- END: is_modadmin -->
                <!-- BEGIN: is_spadadmin -->
        		<li><em class="fa fa-arrows fa-horizon margin-right-sm"></em><a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span>{LANG_DBLOCK}</span></a></li>
        		<!-- END: is_spadadmin -->
                <li><em class="fa fa-user fa-horizon margin-right-sm"></em><a href="{URL_AUTHOR}" title="{GLANG.admin_view}"><span>{GLANG.admin_view}</span></a></li>
            </ul>
        </div>
        <!-- END: admintoolbar -->
    </div>
    <div class="tip-footer">
        <div class="row">
            <div class="col-xs-16 small">
                <em class="button btn-sm icon-enter" title="{LANG.current_login}"></em>{USER.current_login_txt}
            </div>
            <div class="col-xs-8 text-right">
                <button type="button" class="btn btn-default btn-sm active" onclick="{URL_LOGOUT}(this);"><em class="icon-exit"></em>&nbsp;{LANG.logout_title}&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<script src="{NV_BASE_SITEURL}themes/default/js/users.js"></script>
<!-- END: signed -->