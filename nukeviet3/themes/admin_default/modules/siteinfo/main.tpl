<!-- BEGIN: main -->
<!-- BEGIN: main1 -->
<table class="tab1">
    <caption>
        {CAPTION}
    </caption>
    <col valign="top" width="20%" />
    <col valign="top" />
    <col valign="top" width="10%" />
    <thead>
        <tr>
            <td>
                {LANG.moduleName}
            </td>
            <td>
                {LANG.moduleContent}
            </td>
            <td style="text-align:right">
                {LANG.moduleValue}
            </td>
        </tr>
    </thead>
	<!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>
                {MODULE}
            </td>
            <td>
                {KEY}
            </td>
            <td style="text-align:right">
                {VALUE}
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<!-- END: main1 -->
<!-- BEGIN: main2 -->
<table class="tab1">
    <caption>
        {CAPTION} <span style="font-weight:400">(<a href="{ULINK}">{CHECKVERSION}</a>)</span>
    </caption>
    <col valign="top" />
    <col valign="top" width="20%" />
    <thead>
        <tr>
            <td>
                {LANG.moduleContent}
            </td>
            <td style="text-align:right">
                {LANG.moduleValue}
            </td>
        </tr>
    </thead>
	<!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>
                {KEY}
            </td>
            <td style="text-align:right">
                {VALUE}
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<!-- END: main2 -->
<!-- BEGIN: main3 -->
<div id="NukeVietGoogleCode"></div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
$("#NukeVietGoogleCode").load('index.php?nv=siteinfo&gcode=1&num=' + nv_randomPassword(10));
});
//]]>
</script>
<!-- END: main2 -->
<!-- END: main -->
<!-- BEGIN: NukevietChange -->
<table class="tab1">
    <caption>
        {CAPTION}
    </caption>
    <thead>
        <tr>
            <td>
                {LANG.nukevietChange_id}
            </td>
            <td>
                {LANG.nukevietChange_content}
            </td>
            <td>
                {LANG.nukevietChange_author}
            </td>
            <td style="text-align:right">
                {LANG.nukevietChange_updated}
            </td>
        </tr>
    </thead>
	<!-- BEGIN: loop -->
    <tbody{CLASS}>
        <tr>
            <td class="idinfo">
                <a href="{ENTRY.link}" target="_blank" title="">r{ENTRY.id}</a>
            </td>
            <td style="vertical-align:top">
                <div class="ninfo">{ENTRY.title}</div>
                <div class="tooltip">
                	<div class="tooltiptitle">{ENTRY.title}</div>
                	{ENTRY.tooltip}
                </div>
            </td>
            <td class="author">
                {ENTRY.author}
            </td>
            <td class="updated">
                {ENTRY.updated}
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<div style="text-align: right;">{UPDATED}. <a id="gcodeRefresh" href="#">{REFRESH}</a> - <a href="{VISIT}" target="_blank">{LANG.nukevietChange_go}</a></div>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#gcodeRefresh").click(function(){$("#NukeVietGoogleCode").text("").load("index.php?nv=siteinfo&gcode=2&num="+nv_randomPassword(10));return false});$(".ninfo").click(function(){$(".ninfo").each(function(){$(this).show()});$(".tooltip").each(function(){$(this).hide()});$(this).hide().next(".tooltip").show();return false});$(".tooltip").click(function(){$(this).hide().prev(".ninfo").show()})});
//]]>
</script>
<!-- END: NukevietChange -->