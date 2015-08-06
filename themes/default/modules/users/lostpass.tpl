<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>

<div class="page panel panel-default">
    <div class="panel-body">
        <h3 class="margin-bottom-lg">{LANG.lostpass_page_title}</h3>
        <!-- BEGIN: step1 -->
        <div class="info margin-bottom-lg">
        	{LANG.lostpass_info}:
        	<ul class="nv-list-item">
        		<li>
        			<em class="fa fa-angle-right">&nbsp;</em> {LANG.lostpass_info1}
        		</li>
        		<li>
        			<em class="fa fa-angle-right">&nbsp;</em> {LANG.lostpass_info2}
        		</li>
        	</ul>
        </div>
        <form id="lostpassForm" action="{FORM1_ACTION}" method="post" role="form" class="form-horizontal m-bottom">
        	<p class="h3">
                <strong>{DATA.info}</strong>
        	</p>
            <div class="clearfix margin-bottom-lg"></div>
        	<div class="form-group">
        		<label for="userField_iavim" class="col-sm-8 control-label">{LANG.field}</label>
        		<div class="col-sm-16">
        			<input type="text" class="required form-control" name="userField" id="userField_iavim" value="{DATA.userField}" maxlength="100"/>
        		</div>
        	</div>
        	<div class="form-group">
        		<label for="seccode_iavim" class="col-sm-8 control-label">{LANG.captcha}</label>
        		<div class="col-sm-8">
        			<input type="text" class="required form-control" name="nv_seccode" id="seccode_iavim" maxlength="{GFX_MAXLENGTH}" />
        		</div>
        		<div class="col-sm-8">
        			<label class="control-label">
        				<img class="captchaImg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
        				&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="change_captcha('#seccode_iavim');">&nbsp;</em>
        			</label>
        		</div>
        	</div>
        	<div class="form-group">
        		<div class="col-sm-offset-8 col-sm-16">
        			<input type="hidden" name="checkss" value="{DATA.checkss}" />
        			<input type="submit" value="{LANG.lostpass_submit}" class="btn btn-primary" />
        		</div>
        	</div>
        </form>
        <!-- END: step1 -->
        <!-- BEGIN: step2 -->
        <form id="lostpassForm" action="{FORM2_ACTION}" method="post" role="form" class="form-horizontal m-bottom">
            <p class="h3">
                <strong>{DATA.info}</strong>
        	</p>
        	<div class="alert alert-info">
        		{LANG.lostpass_question}: <strong>{QUESTION}</strong>
        	</div>
            <div class="clearfix margin-bottom-lg"></div>
        	<div class="form-group">
        		<label for="answer" class="col-sm-8 control-label">{LANG.answer_question}</label>
        		<div class="col-sm-16">
        			<input type="text" class="required form-control" id="answer" name="answer" value="{DATA.answer}" maxlength="255" />
        		</div>
        	</div>
        	<div class="form-group">
        		<div class="col-sm-offset-8 col-sm-16">
        			<input type="hidden" name="userField" value="{DATA.userField}" />
        			<input type="hidden" name="nv_seccode" value="{DATA.nv_seccode}" />
        			<input type="hidden" name="checkss" value="{DATA.checkss}" />
        			<input type="hidden" name="send" value="1" />
        			<input type="submit" value="{LANG.lostpass_submit}" class="btn btn-primary" />
        		</div>
        	</div>
        </form>
        <!-- END: step2 -->
        <!-- BEGIN: new_pass -->
        <form id="lostpassForm" action="{FORM_ACTION}" method="post" role="form" class="form-horizontal m-bottom">   	
        	<div class="form-group">
        		<label for="answer" class="col-sm-8 control-label">{LANG.pass_new}</label>
        		<div class="col-sm-16">
        			<input type="password" class="required form-control" id="pass_new" name="pass_new" maxlength="255" />
        		</div>
        	</div>
        	<div class="form-group">
        		<label for="answer" class="col-sm-8 control-label">{LANG.pass_new_re}</label>
        		<div class="col-sm-16">
        			<input type="password" class="required form-control" id="pass_new_re" name="pass_new_re" maxlength="255" />
        		</div>
        	</div>
        	<div class="form-group">
        		<div class="col-sm-offset-8 col-sm-16">
        			<input type="hidden" name="u" value="{USERID}" />
        			<input type="hidden" name="k" value="{K}" />
        			<input type="submit" value="{LANG.lostpass_submit}" class="btn btn-primary" />
        		</div>
        	</div>
        </form>
        <!-- END: new_pass -->
    </div>
</div>
<script type="text/javascript">
$(function(){
    $.validator.setDefaults({
    	highlight: function(a) {
    		$(a).closest(".form-group").addClass("has-error")
    	},
    	unhighlight: function(a) {
    		$(a).closest(".form-group").removeClass("has-error")
    	},
    	errorElement: "span",
    	errorClass: "help-block",
    	errorPlacement: function(a, b) {
    		b.parent(".input-group").length ? a.insertAfter(b.parent()) : b.parent(".radio-inline").length ? a.insertAfter(b.parent().parent()) : a.insertAfter(b)
    	}
    });
	$('#lostpassForm').validate();
});
</script>
<!-- END: main -->