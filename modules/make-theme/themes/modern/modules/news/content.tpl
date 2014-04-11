<!-- BEGIN: mainrefresh -->
<div class="title" style="text-align: center;">
	<br />
	<br />
	{DATA.content}
	<br />
	<br />
</div>
<meta http-equiv="refresh" content="5;URL={DATA.urlrefresh}" />
<!-- END: mainrefresh -->
<!-- BEGIN: main -->
<style type="text/css">
	.txtrequired {
		color: #ff0000;
	}

	.news {
		margin: 10px 0;
		clear: both;
	}

	.news label {
		width: 150px;
		text-align: left;
		float: left;
		display: inline;
		border-bottom: 1px dotted #ccc;
	}

	.news input {
		width: 450px;
	}

	.news select {
		width: 450px;
	}

	.news input, .news select {
		border: 1px solid #ccc;
	}

	.textareaform {
		width: 600px;
		border: 1px solid #ccc;
	}

	.news_checkbox {
		width: 18px !important;
	}
</style>
<form action="{CONTENT_URL}" name="fsea" method="post" id="fsea">
	<div class="news">
		<label><strong>{LANG.name}</strong> <span class="txtrequired">(*)</span></label>
		<input maxlength="255" name="title" id="idtitle" value="{DATA.title}" type="text">
	</div>
	<div class="news">
		<label><strong>{LANG.alias}: </strong></label>
		<input style="width: 400px;" name="alias" id="idalias" value="{DATA.alias}" maxlength="255" type="text">
		&nbsp;&nbsp; <img src="{NV_BASE_SITEURL}images/refresh.png" widht="16" style="cursor: pointer; vertical-align: middle;" onclick="get_alias();" alt="" height="16">
	</div>
	<div class="news">
		<label><strong>{LANG.content_cat}</strong> <span class="txtrequired">(*)</span></label>
		<div style="height: 130px; width: 450px; overflow: auto; text-align: left;">
			<table>
				<tbody>
					<!-- BEGIN: catid -->
					<tr>
						<td><input class="news_checkbox" name="catids[]" value="{DATACATID.value}" type="checkbox"{DATACATID.checked}> {DATACATID.title}</td>
					</tr>
					<!-- END: catid -->
				</tbody>
			</table>
		</div>
	</div>
	<div class="news">
		<label><strong>{LANG.content_topic}</strong></label>
		<select name="topicid">
			<!-- BEGIN: topic -->
			<option value="{DATATOPIC.value}"{DATATOPIC.selected}>{DATATOPIC.title}</option>
			<!-- END: topic -->
		</select>
	</div>
	<div class="news">
		<label><strong>{LANG.content_homeimg}</strong></label>
		<input name="homeimgfile" id="homeimg" value="{DATA.homeimgfile}" type="text">
	</div>
	<div class="news">
		<label><strong>{LANG.content_homeimgalt}</strong></label>
		<input maxlength="255" value="{DATA.homeimgalt}" name="homeimgalt" type="text">
	</div>
	<div class="news">
		<label><strong>{LANG.imgposition}</strong></label>
		<select name="imgposition">
			<!-- BEGIN: imgposition -->
			<option value="{DATAIMGOP.value}"{DATAIMGOP.selected}>{DATAIMGOP.title}</option>
			<!-- END: imgposition -->
		</select>
	</div>
	<br />
	<strong>{LANG.content_hometext}</strong></label>
	<br /><textarea class="textareaform" rows="6" cols="60" name="hometext"> {DATA.hometext}</textarea>
	<br />
	<br />
	<strong>{LANG.content_bodytext}</strong><span class="txtrequired">(*)</span>
	<br />
	<div style="width: 610px;">
		{HTMLBODYTEXT}
	</div>
	<div class="news">
		<label><strong>{LANG.source}</strong></label>
		<input maxlength="255" value="{DATA.sourcetext}" name="sourcetext" type="text">
	</div>
	<div class="news">
		<label><strong>{LANG.search_author}</strong></label>
		<input maxlength="255" value="{DATA.author}" name="author" type="text">
	</div>
	<div class="news">
		<label><strong>{LANG.content_keywords}:</strong></label>
		<input maxlength="255" value="{DATA.keywords}" name="keywords" type="text">
	</div>
	<div class="news">
		<label><strong>{LANG.captcha}:</strong> <span class="txtrequired">(*)</span></label>
		<input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" style="width: 100px;" />
		<img height="22" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" alt="{LANG.captcha}" id="vimg" /><img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','fcode_iavim');" />
	</div>
	<br />
	<div style="width: 500px; text-align: center;">
		<input type="hidden" name="contentid" value="{DATA.id}" />
		<input type="hidden" name="checkss" value="{CHECKSS}" />
		<input type="submit" value="{LANG.save_draft}" name="status4">
		<!-- BEGIN: save_temp -->
		<input type="submit" value="{LANG.save_temp}" name="status0">
		<!-- END: save_temp -->
		<!-- BEGIN: postcontent -->
		<input type="submit" value="{LANG.save_content}" name="status1">
		<!-- END: postcontent -->
	</div>
	<br />
</form>
<script type="text/javascript">
	function get_alias() {
		var title = strip_tags(document.getElementById('idtitle').value);
		if (title != '') {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(), 'get_alias=' + encodeURIComponent(title), function(res) {
				if (res != "") {
					document.getElementById('idalias').value = res;
				} else {
					document.getElementById('idalias').value = '';
				}
			});
		}
		return false;
	}
</script>
<!-- END: main -->