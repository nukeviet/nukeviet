<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
	<li class="active"><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
	<li><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<div id="clinfomessage" class="alert <!-- BEGIN: error -->alert-danger<!-- END: error --><!-- BEGIN: info -->alert-info<!-- END: info -->">
	{pagetitle}{errorinfo}
</div>
<div id="clinfo">
	<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
	<form id="frm" action="" method="post" enctype="multipart/form-data" role="form" class="form-horizontal"<!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
		<div class="form-group">
			<label for="title" class="col-sm-6 control-label">{LANG.addads_title}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<input class="required form-control" type="text" name="title" id="title" value="{DATA.title}"/>
			</div>
		</div>
		<div class="form-group">
			<label for="block" class="col-sm-6 control-label">{LANG.addads_block}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<select name="block" id="banner_plan" class="form-control" data-blocked="{LANG.upload_blocked}.">
					<!-- BEGIN: blockitem -->
					<option value="{blockitem.id}" data-image="{blockitem.typeimage}" data-uploadtype="{blockitem.uploadtype}"{blockitem.selected}>{blockitem.title}</option>
					<!-- END: blockitem -->
				</select>
			</div>
		</div>
		<div class="form-group" id="banner_uploadimage">
			<label for="image" class="col-sm-6 control-label">{LANG.addads_adsdata}<span id="banner_uploadtype"></span><span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<input type="file" name="image" id="image" value="" class="form-control"/>
			</div>
		</div>
		<div class="form-group" id="banner_imagealt">
			<label for="description" class="col-sm-6 control-label">{LANG.addads_description}:</label>
			<div class="col-sm-18">
				<input type="text" name="description" id="description" value="{DATA.description}" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-sm-6 control-label">{LANG.addads_url}<span id="banner_urlrequired" class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<input class="url form-control" type="text" name="url" id="url" value="{DATA.url}"/>
			</div>
		</div>
        <!-- BEGIN: captcha -->
    	<div class="form-group">
    		<label class="col-sm-6 control-label">{N_CAPTCHA} <span class="text-danger"> (*)</span>:</label>
    		<div class="col-sm-18">
    			<input type="text" maxlength="6" value="" id="fcode_iavim" name="captcha" class="form-control pull-left margin-right" style="width: 150px;" />
                <img height="32" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" alt="{N_CAPTCHA}" class="captchaImg" />
                <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#fcode_iavim');" />
    		</div>
    	</div>
        <!-- END: captcha -->
        <!-- BEGIN: recaptcha -->
        <div class="form-group">
            <div class="col-sm-offset-6 col-sm-18">
                <div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="3" data-btnselector="[type=submit]"></div>
            </div>
        </div>
        <!-- END: recaptcha -->
		<div class="form-group">
			<div class="col-sm-offset-6 col-sm-18">
				<input type="submit" name="confirm" value="{LANG.addads_confirm}" class="btn btn-primary"/>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#frm').validate();
	});
	</script>
</div>
<!-- END: main -->
