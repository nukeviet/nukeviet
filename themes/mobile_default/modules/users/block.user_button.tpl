<!-- BEGIN: main -->
<span class="pa" data-toggle="tip" data-target="#guestBlock" data-click="y"><em class="fa fa-user fa-lg pointer mbt-lg"></em><span class="hidden">{LANG.contactUs}</span></span>
<!-- START FORFOOTER -->
<div id="guestBlock" class="hidden">
    <div class="guestBlock">
        <h3><a href="#" onclick="switchTab(this);tipAutoClose(true);" class="guest-sign pointer margin-right current" data-switch=".log-area, .reg-area" data-obj=".guestBlock">{GLANG.signin}</a> <!-- BEGIN: allowuserreg2 --><a href="#" onclick="switchTab(this);tipAutoClose(false);" class="guest-reg pointer" data-switch=".reg-area, .log-area" data-obj=".guestBlock">{GLANG.register}</a> <!-- END: allowuserreg2 --></h3>
        <div class="log-area">
            <div class="nv-info margin-bottom">{GLANG.logininfo}</div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                    <input type="text" class="required form-control" placeholder="{GLANG.username}" value="" name="blogin" maxlength="100" onkeypress="inputSignIn(event);">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                    <input type="password" class="required form-control" placeholder="{GLANG.password}" value="" name="bpass" maxlength="100" onkeypress="inputSignIn(event);">
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
               <button class="bsubmit btn btn-primary" type="button" onclick="buttonSignIn('.log-area');" data-errorMessage="{LANG.errorMessage}" data-loginOk="{LANG.login_ok}">{GLANG.loginsubmit}</button>
        	</div>
            <!-- BEGIN: openid -->
        	<hr />
        	<div class="text-center">
        		<!-- BEGIN: server -->
        		<a title="{OPENID.title}" href="{OPENID.href}" class="margin-right"><img alt="{OPENID.title}" title="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /></a>
        		<!-- END: server -->
        	</div>
        	<!-- END: openid -->
        </div>
        <!-- BEGIN: allowuserreg -->
        <div class="reg-area hidden">
            <div class="nv-info margin-bottom">{LANG.info}</div>
            <div class="inputs">
                <div class="form-group">
                    <span><input type="text" class="form-control" name="brlname" value="" maxlength="255" placeholder="{LANG.last_name}" onkeypress="inputReg(event);" /></span>
                </div>
                <div class="form-group">
                    <span><input type="text" class="form-control" name="brfname" value="" maxlength="255" placeholder="{LANG.first_name}" onkeypress="inputReg(event);" /></span>
                </div>
                
                <div class="form-group">
                    <span><input type="text" class="required form-control" name="brlogin" value="" maxlength="{NICK_MAXLENGTH}" placeholder="{LANG.account}" onkeypress="inputReg(event);" /></span>
                </div>
                <div class="form-group">
                    <span><input type="email" class="required form-control" name="bremail" value="" maxlength="255" placeholder="{LANG.email}" onkeypress="inputReg(event);" /></span>
            	</div>
                
                <div class="form-group">
                    <span><input type="password" class="required password form-control" name="brpass" value="" maxlength="{PASS_MAXLENGTH}" placeholder="{LANG.password}" onkeypress="inputReg(event);" /></span>
                </div>
                <div class="form-group">
                    <span><input type="password" class="required password form-control" name="brpass2" value="" maxlength="{PASS_MAXLENGTH}" placeholder="{LANG.re_password}" onkeypress="inputReg(event);" /></span>
            	</div>
            
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="required form-control" name="bryq" value="" maxlength="255" placeholder="{LANG.question}" onkeypress="inputReg(event);" />
                        <span class="input-group-btn"><a type="button" class="btql btn btn-default" onclick="showQlist()" data-show="no"><em class="fa fa-caret-down fa-lg"></em></a></span>
                        <div class="qlist"></div>
                    </div>
                </div>
                <div class="form-group">
                    <span><input type="text" class="required form-control" name="brya" value="" maxlength="255" placeholder="{LANG.answer_question}" onkeypress="inputReg(event);" /></span>
                </div>
                <!-- BEGIN: captcha_reg -->
                <div class="form-group">
                    <div class="middle text-right clearfix">
                        <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" /><em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.brsec');"></em><input type="text" style="width:100px;" class="brsec required form-control display-inline-block" name="brsec" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" onkeypress="inputReg(event);" />
                    </div>
                </div>
                <!-- END: captcha_reg -->
                <div class="form-group">
                    <div class="checkbox">
            		  <input type="checkbox" class="required fix-box" value="1" name="bragr">{LANG.accept2} <a href="#" onclick="usageTermsShow('{LANG.usage_terms}');"><span class="btn btn-default btn-xs">{LANG.usage_terms}</span></a>
                    </div>
                </div>
                <div class="text-center">
                   <input type="hidden" name="checkss" value="{CHECKSESS}" />
                   <button class="brsubmit btn btn-primary" type="button" onclick="buttonReg('.reg-area');" data-errorMessage="{LANG.errorMessage}" data-regOK="{LANG.register_ok}">{GLANG.register}</button>
            	</div>
            </div>
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