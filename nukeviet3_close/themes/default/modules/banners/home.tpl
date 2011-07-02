<!-- BEGIN: main -->
<!-- BEGIN: management -->
<div style="border:1px #ccc solid;padding:5px">
<h3 style="margin-bottom:15px"><strong>{LANG.tool_management}</strong></h3>
<div class="plan_title" style="float:left"><a href="{clientinfo_link}">{LANG.client_info}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_addads}">{LANG.client_addads}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_stats}">{LANG.client_stats}</a></div>
<div style="clear:both"></div>
</div>
<!-- END: management -->
    <!-- BEGIN: if_banner_plan -->
    <!-- BEGIN: info -->
    <div class="module_info">{MAIN_PAGE_INFO}:</div>
    <!-- END: info -->
    <div class="banner_plan">
        <!-- BEGIN: banner_plan -->
        <div class="plan_title">&bull; {PLAN_TITLE}</div>
        <div class="plan_content">
            <strong>{PLAN_LANG_TITLE}</strong>: {PLAN_LANG_NAME}, 
            <strong>{PLAN_SIZE_TITLE}</strong>: {PLAN_SIZE_NAME}, 
            <strong>{PLAN_FORM_TITLE}</strong>: {PLAN_FORM_NAME}
        </div>
        <div style="padding-left:5px"><strong>{PLAN_DESCRIPTION_TITLE}</strong>: {PLAN_DESCRIPTION_NAME}</div>
        <!-- END: banner_plan -->
    </div>
    <!-- END: if_banner_plan -->
    <div id="{CONTAINERID}"></div>
    <script type="text/javascript">
    {AJ}
    </script>
<!-- END: main -->