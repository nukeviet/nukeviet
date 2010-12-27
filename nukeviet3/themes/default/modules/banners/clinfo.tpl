<!-- BEGIN: clinfo -->
<!-- BEGIN: management -->
<div style="border:1px #ccc solid;padding:5px">
<h3 style="margin-bottom:15px"><strong>{LANG.tool_management}</strong></h3>
<div class="plan_title" style="float:left"><a href="{clientinfo_link}">{LANG.client_info}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_addads}">{LANG.client_addads}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_stats}">{LANG.client_stats}</a></div>
<div style="clear:both"></div>
</div>
<!-- END: management -->
<div id="clinfo">
    <!-- BEGIN: name_value -->
    <div class="{CLASS}">
        <div class="value">{INFO_VALUE}</div>{INFO_NAME}:
    </div>
    <!-- END: name_value -->
</div>
<div style="height:27px;clear: both;">
    <a class="button2" href="javascript:void(0);" onclick="{EDIT_ONCLICK}"><span><span>{EDIT_NAME}</span>
        </span></a>
</div>
<!-- END: clinfo -->
