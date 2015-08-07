<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<div id="rankForm">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{TITLE}</caption>
			<col class="w150" />
			<col class="w300" />
			<tr>
				<td>{LANG.keyword}</td>
				<td><input name="keyword" type="text" id="keyword" maxlength="60" class="form-control" /></td>
				<td>
					<select name="lr" id="lr" class="form-control w200">
						<option value="">{LANG.languageSelect}</option>
						<option value="af">Afrikaans</option>
						<option value="sq">Albanian</option>
						<option value="ar">Arabic</option>
						<option value="be">Belarusian</option>
						<option value="bg">Bulgarian</option>
						<option value="ca">Catalan</option>
						<option value="zh-CN">Chinese</option>
						<option value="hr">Croatian</option>
						<option value="cs">Czech</option>
						<option value="da">Danish</option>
						<option value="nl">Dutch</option>
						<option value="en">English</option>
						<option value="et">Estonian</option>
						<option value="tl">Filipino</option>
						<option value="fi">Finnish</option>
						<option value="fr">French</option>
						<option value="gl">Galician</option>
						<option value="de">German</option>
						<option value="el">Greek</option>
						<option value="ht">Haitian Creole</option>
						<option value="iw">Hebrew</option>
						<option value="hi">Hindi</option>
						<option value="hu">Hungarian</option>
						<option value="is">Icelandic</option>
						<option value="id">Indonesian</option>
						<option value="ga">Irish</option>
						<option value="it">Italian</option>
						<option value="ja">Japanese</option>
						<option value="ko">Korean</option>
						<option value="lv">Latvian</option>
						<option value="lt">Lithuanian</option>
						<option value="mk">Macedonian</option>
						<option value="ms">Malay</option>
						<option value="mt">Maltese</option>
						<option value="no">Norwegian</option>
						<option value="fa">Persian</option>
						<option value="pl">Polish</option>
						<option value="pt">Portuguese</option>
						<option value="ro">Romanian</option>
						<option value="ru">Russian</option>
						<option value="sr">Serbian</option>
						<option value="sk">Slovak</option>
						<option value="sl">Slovenian</option>
						<option value="es">Spanish</option>
						<option value="sw">Swahili</option>
						<option value="sv">Swedish</option>
						<option value="th">Thai</option>
						<option value="tr">Turkish</option>
						<option value="uk">Ukrainian</option>
						<option value="vi">Vietnamese</option>
						<option value="cy">Welsh</option>
						<option value="yi">Yiddish</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>{LANG.accuracy}</td>
				<td>
					<select name="accuracy" id="accuracy" class="form-control">
						<option value="keyword">{LANG.byKeyword}</option>
						<option value="phrase">{LANG.byPhrase}</option>
					</select>
				</td>
				<td>
					<div id="fsubmit">
						<a id="keywordRankCheck" class="btn btn-primary" href="#">{LANG.check}</a>
					</div>
					<div id="load_img">&nbsp;</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<br />
<br />
<div id="keywordRankResult">&nbsp;</div>
<script type="text/javascript">
//<![CDATA[
var LANG = [];
LANG.keywordInfo = '{LANG.keywordInfo}';
$(document).ready(function() {
	$("#lr").select2();
});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: process -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<!-- BEGIN: result -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col span="2" style="width: 50%" />
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {LOOP.key} </td>
				<td> {LOOP.value} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: result -->
<!-- BEGIN: MainResult -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col span="2" style="width: 50%" />
		<thead>
			<tr>
				<td colspan="2"> {LANG.mainResult} </td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: tr -->
			<tr>
				<td> {TR.key} </td>
				<td> {TR.value} </td>
			</tr>
			<!-- END: tr -->
		</tbody>
	</table>
</div>
<!-- END: MainResult -->
<!-- BEGIN: TopPages -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col class="w50">
		<col>
		<thead>
			<tr>
				<td colspan="2"> {CAPTION} </td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: top -->
			<tr>
				<td class="text-right"> {ID} </td>
				<td><a href="{URL}" target="_blank"{A_CLASS}><span>{URL}</span></a></td>
			</tr>
			<!-- END: top -->
		</tbody>
	</table>
</div>
<!-- END: TopPages -->
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#keyword").removeAttr('disabled');
		$("#lr").removeAttr('disabled');
		$("#accuracy").removeAttr('disabled');
		$("#load_img").text("");
		$("#fsubmit").show();
	});
	//]]>
</script>
<!-- END: process -->