<!-- BEGIN: main -->
<div id="rankForm">
    <h2>{TITLE}</h2>
    <dl class="clearfix">
        <dt class="fl"><label>{LANG.keyword}:</label></dt>
        <dd class="fl"><input name="keyword" type="text" id="keyword" maxlength="60" /></dd>
        <dd class="fl">
            <select name="lr" id="lr">
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
        </dd>
    </dl>
    <dl class="clearfix">
        <dt class="fl"><label>{LANG.accuracy}:</label></dt>
        <dd class="fl">
            <select name="accuracy" id="accuracy">
                <option value="keyword">{LANG.byKeyword}</option>
                <option value="phrase">{LANG.byPhrase}</option>
            </select>
        </dd>
        <dd class="fl">
            <div id="fsubmit">
                <a id="keywordRankCheck" class="button1" href="#"><span><span>{LANG.check}</span></span></a>
            </div>
            <div id="load_img"></div>
        </dd>
    </dl>
</div>
<br /><br />
<div id="keywordRankResult"></div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
  $("#keywordRankCheck").click(function() {
    var keyword = $("#keyword").attr('value');
    keyword = formatStringAsUriComponent(keyword);
    $("#keyword").attr('value',keyword);
    if(keyword.length < 3 || keyword.length > 60)
    {
        alert("{LANG.keywordInfo}");
        return false;
    }
    keyword = rawurlencode(keyword);
    var lr = $("#lr").val();
    var accuracy = $("#accuracy").val();
    $("#keyword").attr('disabled', 'disabled');
    $("#lr").attr('disabled', 'disabled');
    $("#accuracy").attr('disabled', 'disabled');
    $("#fsubmit").hide();
    $("#load_img").html('<p style="text-align:center;"><img alt="" src="{NV_BASE_SITEURL}images/load.gif" width="16" height="16" /></p>');
    $("#keywordRankResult").text("").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=keywordRank&i=process&k=" + keyword + "&l=" + lr + "&a=" + accuracy + "&num=" + nv_randomPassword(10));
    return false
  })
});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: process -->
<!-- BEGIN: error -->
<div class="error">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: result -->
<table class="tab1">
    <col width="50%" />
    <col width="50%" />
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>
                {LOOP.key}
            </td>
            <td>
                {LOOP.value}
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<!-- END: result -->
<!-- BEGIN: MainResult -->
<table class="tab1">
    <thead>
        <tr>
            <td colspan="2">
                {LANG.mainResult}
            </td>
        </tr>
    </thead>
    <col width="50%" />
    <col width="50%" />
    <!-- BEGIN: tr -->
    <tbody {CLASS}>
        <tr>
            <td>
                {TR.key}
            </td>
            <td>
                {TR.value}
            </td>
        </tr>
    </tbody>
    <!-- END: tr -->
</table>
<!-- END: MainResult -->
<!-- BEGIN: TopPages -->
<table class="tab1">
    <thead>
        <tr>
            <td colspan="2">
                {CAPTION}
            </td>
        </tr>
    </thead>
    <col width="30" />
    <col />
    <!-- BEGIN: top -->
    <tbody {CLASS}>
        <tr>
            <td style="text-align:right">
                {ID}
            </td>
            <td>
                <a href="{URL}" target="_blank"{A_CLASS}><span>{URL}</span></a>
            </td>
        </tr>
    </tbody>
    <!-- END: top -->
</table>
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