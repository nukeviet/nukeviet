<!-- BEGIN: main -->
<h2>{LANG.lostpass_page_title}</h2>
<!-- BEGIN: step1 -->
<div class="well">
	<p>
		<em class="fa fa-info fa-lg">&nbsp;</em> {LANG.lostpass_info}:
	</p>
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
	<div class="m-bottom">
		<em class="fa fa-quote-left">&nbsp;</em> 
		{DATA.info} 
		<em class="fa fa-quote-right">&nbsp;</em>
	</div>
	<div class="form-group">
		<label for="userField_iavim" class="col-sm-4 control-label">{LANG.field}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="text" class="required form-control" name="userField" id="userField_iavim" value="{DATA.userField}" maxlength="100"/>
		</div>
	</div>	
	<div class="form-group">
		<label for="seccode_iavim" class="col-sm-4 control-label">{LANG.captcha}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-4">
			<input type="text" class="required form-control" name="nv_seccode" id="seccode_iavim" maxlength="{GFX_MAXLENGTH}" />
		</div>
		<div class="col-sm-4">
			<label class="control-label">
				<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
				&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimg','seccode_iavim');">&nbsp;</em>
			</label>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input type="submit" value="{LANG.lostpass_submit}" class="btn btn-primary" />
		</div>
	</div>
</form>
<!-- END: step1 -->
<!-- BEGIN: step2 -->
<form id="lostpassForm" action="{FORM2_ACTION}" method="post" role="form" class="form-horizontal m-bottom">
	<div class="m-bottom">
		<em class="fa fa-quote-left">&nbsp;</em> 
		{DATA.info} 
		<em class="fa fa-quote-right">&nbsp;</em>
	</div>
	<div class="alert alert-info">
		{LANG.lostpass_question}: <strong>{QUESTION}</strong>
	</div>
	<div class="form-group">
		<label for="answer" class="col-sm-4 control-label">{LANG.answer_question}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="text" class="required form-control" id="answer" name="answer" value="{DATA.answer}" maxlength="255" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<input type="hidden" name="userField" value="{DATA.userField}" />
			<input type="hidden" name="nv_seccode" value="{DATA.nv_seccode}" />
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input type="hidden" name="send" value="1" />
			<input type="submit" value="{LANG.lostpass_submit}" class="btn btn-primary" />
		</div>
	</div>
</form>
<!-- END: step2 -->
<!-- END: main -->