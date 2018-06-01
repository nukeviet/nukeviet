<!-- BEGIN: mainrefresh -->
<div class="text-center">
	{DATA.content}
</div>
<meta http-equiv="refresh" content="5;URL={DATA.urlrefresh}" />
<!-- END: mainrefresh -->

<!-- BEGIN: main -->
<form action="{CONTENT_URL}" name="fsea" method="post" id="fsea" class="form-horizontal">

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.name} <span class="txtrequired">(*)</span></label>
		<div class="col-sm-20">
			<input type="text" class="form-control" name="title" id="idtitle" value="{DATA.title}" />
		</div>
	</div>
    
    <!-- BEGIN: alias -->
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.alias}</label>
		<div class="col-sm-20">
			<input type="text" class="form-control pull-left" name="alias" id="idalias" value="{DATA.alias}" maxlength="255" style="width: 94%;" />
			<em class="fa fa-refresh pull-right" style="cursor: pointer; vertical-align: middle; margin: 9px 0 0 4px" onclick="get_alias('{OP}');" alt="Click">&nbsp;</em>
		</div>
	</div>
    <!-- END: alias -->

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.content_cat} <span class="txtrequired">(*)</span></label>
		<div class="col-sm-20">
			<div style="height: 130px; width: 100%; overflow: auto; text-align: left; border: solid 1px #ddd; padding: 11px;">
				<table>
					<tbody>
						<!-- BEGIN: catid -->
						<tr>
							<td><input class="news_checkbox" name="catids[]" value="{DATACATID.value}" type="checkbox"{DATACATID.checked}>{DATACATID.title}</td>
						</tr>
						<!-- END: catid -->
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.content_topic}</label>
		<div class="col-sm-20">
			<select name="topicid" class="form-control">
				<!-- BEGIN: topic -->
				<option value="{DATATOPIC.value}"{DATATOPIC.selected}>{DATATOPIC.title}</option>
				<!-- END: topic -->
			</select>
		</div>
	</div>
    
    <!-- BEGIN: layout_func -->
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.pick_layout}</label>
		<div class="col-sm-20">
			<select name="layout_func" class="form-control">
				<option value="">{LANG.default_layout}</option>
				<!-- BEGIN: loop -->
				<option value="{LAYOUT_FUNC.key}"{LAYOUT_FUNC.selected}>{LAYOUT_FUNC.key}</option>
				<!-- END: loop -->
			</select>
		</div>
	</div>
    <!-- END: layout_func -->
    
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.content_homeimg}</label>
		<div class="col-sm-20">
			<input class="form-control" name="homeimgfile" id="homeimg" value="{DATA.homeimgfile}" type="text" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.content_homeimgalt}</label>
		<div class="col-sm-20">
			<input maxlength="255" value="{DATA.homeimgalt}" name="homeimgalt" type="text" class="form-control" />
		</div>
	</div>
    
	<div class="form-group">
		<label>{LANG.content_hometext}</label>
		<textarea class="form-control" rows="6" cols="60" name="hometext"> {DATA.hometext}</textarea>
	</div>

	<div class="form-group">
		<label>{LANG.content_bodytext} <span class="txtrequired">(*)</span></label>
		{HTMLBODYTEXT}
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.source}</label>
		<div class="col-sm-20">
			<input maxlength="255" value="{DATA.sourcetext}" name="sourcetext" type="text" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.search_author}</label>
		<div class="col-sm-20">
			<input maxlength="255" value="{DATA.author}" name="author" type="text" class="form-control" />
		</div>
	</div>

    <!-- BEGIN: captcha -->
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.captcha} <span class="txtrequired">(*)</span></label>
		<div class="col-sm-20">
			<input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" class="form-control pull-left" style="width: 150px;" />
            <img height="32" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" alt="{LANG.captcha}" class="captchaImg" />
            <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#fcode_iavim');" />
		</div>
	</div>
    <!-- END: captcha -->
    
    <!-- BEGIN: recaptcha -->
    <div class="form-group">
        <label class="col-sm-4 control-label">{N_CAPTCHA} <span class="txtrequired">(*)</span></label>
        <div class="col-sm-20">
            <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
            <script type="text/javascript">
            nv_recaptcha_elements.push({
                id: "{RECAPTCHA_ELEMENT}",
                btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent())
            })
            </script>
        </div>
    </div>
    <!-- END: recaptcha -->
    
	<ul class="list-inline text-center">
		<input type="hidden" name="contentid" value="{DATA.id}" />
		<input type="hidden" name="checkss" value="{CHECKSS}" />
		<li><input type="submit" class="btn btn-primary" value="{LANG.save_draft}" name="status4"></li>
		<!-- BEGIN: save_temp -->
		<li><input type="submit" class="btn btn-primary" value="{LANG.save_temp}" name="status0"></li>
		<!-- END: save_temp -->
		<!-- BEGIN: postcontent -->
		<li><input type="submit" class="btn btn-primary" value="{LANG.save_content}" name="status1"></li>
		<!-- END: postcontent -->
	</ul>
	<br />
</form>
<!-- END: main -->