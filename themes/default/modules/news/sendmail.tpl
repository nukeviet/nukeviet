<!-- BEGIN: main -->
{SENDMAIL.script}
<!-- BEGIN: close -->
<script type="text/javascript">
	var howLong = 10000;
	setTimeout("self.close()", howLong);
</script>
<!-- END: close -->
<style type="text/css">
	body {
		padding: 20px;
		font-size: 11pt;
	}
</style>

<div id="sendmail">
	<!-- BEGIN: content -->
	<div class="panel panel-default">
		<div class="panel-body">
			<h1 class="text-center">{LANG.sendmail}</h1>
			<form id="sendmailForm" action="{SENDMAIL.action}" method="post" class="form-horizontal" role="form">
				
				<div class="form-group">
					<label for="sname" class="col-sm-2 control-label">{LANG.sendmail_name}<em>*</em></label>
					<div class="col-sm-10">
						<input id="sname" type="text" name="name" value="{SENDMAIL.v_name}" class="form-control" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="syourmail_iavim" class="col-sm-2 control-label">{LANG.sendmail_youremail}</label>
					<div class="col-sm-10">
						<input id="syourmail_iavim" type="text" name="youremail" value="{SENDMAIL.v_mail}" class="form-control" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="semail" class="col-sm-2 control-label">{LANG.sendmail_email}<em>*</em></label>
					<div class="col-sm-10">
						<input id="semail" type="text" name="email" value="{SENDMAIL.to_mail}" class="form-control" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="scontent" class="col-sm-2 control-label">{LANG.sendmail_content}</label>
					<div class="col-sm-10">
						<textarea id="scontent"  name="content" rows="5" cols="20" class="form-control">{SENDMAIL.content}</textarea>
					</div>
				</div>
				
				<!-- BEGIN: captcha -->
				<div class="form-group">
					<label for="semail" class="col-sm-2 control-label">{LANG.captcha}<em>*</em></label>
					<div class="col-sm-10">
						<input name="nv_seccode" type="text" id="seccode" class="form-control" maxlength="{GFX_NUM}" style="width: 100px; float: left !important; margin: 2px 5px 0 !important;"/><img class="pull-left" style="margin-top: 5px;" id="vimg" alt="{N_CAPTCHA}" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh pull-left resfresh1" style="margin: 9px;" onclick="nv_change_captcha('vimg','seccode');"/>
					</div>
				</div>
				<!-- END: captcha -->
							
				<input type="hidden" name="checkss" value="{SENDMAIL.checkss}" /><input type="hidden" name="catid" value="{SENDMAIL.catid}" /><input type="hidden" name="id" value="{SENDMAIL.id}" /><input type="submit" value="{LANG.sendmail_submit}" class="btn btn-default" />
			</form>
			<!-- END: content -->
			
			<!-- BEGIN: result -->
			<div class="alert alert-warning" style="margin-top: 10px;">
				<div>
					{RESULT.err_name}
				</div>
				<div>
					{RESULT.err_email}
				</div>
				<div>
					{RESULT.err_yourmail}
				</div>
				<div class="text-center">
					{RESULT.send_success}
				</div>
			</div>
			<!-- END: result -->
		</div>
	</div>
</div>
<!-- END: main -->