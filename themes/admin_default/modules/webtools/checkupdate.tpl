<!-- BEGIN: main -->
<div id="updIf">
    <div id="sysUpd"></div>
    <div id="modUpd"></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#sysUpd").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=sysUpd&num="+nv_randomPassword(10));$("#modUpd").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=modUpd&num="+nv_randomPassword(10))});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: sysUpd -->
<table class="tab1">
    <caption>
        {LANG.checkSystem}
    </caption>
    <thead>
        <tr>
            <td>
                {LANG.checkContent}
            </td>
            <td>
                {LANG.checkValue}
            </td>
        </tr>
    </thead>
    <tbody class="second">
        <tr>
            <td style="white-space:nowrap">
                {LANG.userVersion}
            </td>
            <td>
                {VALUE.userVersion}
            </td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td style="white-space:nowrap">
                {LANG.onlineVersion}
            </td>
            <td>
                {VALUE.onlineVersion}
                <!-- BEGIN: inf -->
                <div class="newVesionMess">
                {VERSION_INFO}
                </div>
                <div class="newVesionInfo">
                {VERSION_LINK}
                </div>
                <!-- END: inf -->
            </td>
        </tr>
    </tbody>
</table>
<div style="text-align:right;">{LANG.checkDate}: {SYSUPDDATE} (<a id="sysUpdRefresh" href="#">{LANG.reCheck}</a>)</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#sysUpdRefresh").click(function(){$("#sysUpd").text("").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=sysUpdRef&num="+nv_randomPassword(10));return false})});
//]]>
</script>
<!-- END: sysUpd -->
<!-- BEGIN: modUpd -->
<table class="tab1">
    <caption>
        {LANG.checkModules}
    </caption>
    <col valign="top" width="120" />
    <col valign="top" />
    <col valign="top" width="200" />
    <thead>
        <tr>
            <td>
                {LANG.moduleName}
            </td>
            <td>
                {LANG.moduleInfo}
            </td>
            <td style="text-align:right">
                {LANG.moduleNote}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td style="vertical-align:top">
                <div class="Note {MODCL}">{MODNAME}</div>
            </td>
            <td style="vertical-align:top">
                <div class="ninfo">{MODINFO}</div>
                <div class="tooltip">
                    <ul>
                        <!-- BEGIN: li -->
                        <li><strong>{MODTOOLTIP.title}</strong>: {MODTOOLTIP.content}</li>
                        <!-- END: li -->
                    </ul>
                    <!-- BEGIN: note1 -->
                    <div class="invalid">{LANG.moduleNote1_detail}</div>
                    <!-- END: note1 -->
                </div>
            </td>
            <td style="vertical-align:top;text-align:right">
                {MODNOTE}
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<div style="text-align:right;">{LANG.checkDate}: {MODUPDDATE} (<a id="modUpdRefresh" href="#">{LANG.reCheck}</a>)</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#modUpdRefresh").click(function(){$("#modUpd").text("").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=modUpdRef&num="+nv_randomPassword(10));return false});$(".ninfo").click(function(){$(".ninfo").each(function(){$(this).show()});$(".tooltip").each(function(){$(this).hide()});$(this).hide().next(".tooltip").show();return false});$(".tooltip").click(function(){$(this).hide().prev(".ninfo").show()})});
//]]>
</script>
<!-- BEGIN: newMods -->
<div style="text-align:left;">
<a id="newModList" class="button1" href="#"><span><span>{LANG.moduleNote2_link}</span></span></a>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#newModList").click(function(){$("#updIf").text("").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=modNewUpd&num="+nv_randomPassword(10));return false})});
//]]>
</script>
<!-- END: newMods -->
<br /><br />
<!-- END: modUpd -->
<!-- BEGIN: modsNew -->
<table class="tab1">
    <caption>
        {LANG.moduleNote2_link}
    </caption>
    <col valign="top" width="120" />
    <col valign="top" />
    <col valign="top" width="200" />
    <thead>
        <tr>
            <td>
                {LANG.moduleName}
            </td>
            <td>
                {LANG.moduleInfo}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td style="vertical-align:top">
                <div class="Note Note2">{MODNAME}</div>
            </td>
            <td style="vertical-align:top">
                <div class="ninfo">{MODINFO}</div>
                <div class="tooltip">
                    <h3>{MODINFO}</h3>
                    <ul>
                        <!-- BEGIN: li -->
                        <li><strong>{MODTOOLTIP.title}</strong>: {MODTOOLTIP.content}</li>
                        <!-- END: li -->
                    </ul>
                </div>
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<div style="text-align:right;">{LANG.checkDate}: {MODUPDDATE} (<a id="modsNewRefresh" href="#">{LANG.reCheck}</a>)</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#modsNewRefresh").click(function(){$("#updIf").text("").load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=modNewUpdRef&num="+nv_randomPassword(10));return false});$(".ninfo").click(function(){$(".ninfo").each(function(){$(this).show()});$(".tooltip").each(function(){$(this).hide()});$(this).hide().next(".tooltip").show();return false});$(".tooltip").click(function(){$(this).hide().prev(".ninfo").show()})});
//]]>
</script>
<!-- END: modsNew -->