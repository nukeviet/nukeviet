<!-- BEGIN: main -->
<div class="flex-table-laws">
    <div class="table-rows table-head">
        <div class="c-code">{LANG.code}</div>
        <!-- BEGIN: publtime_title -->
        <div class="c-time">{LANG.publtime}</div>
        <!-- END: publtime_title -->
        <!-- BEGIN: comm_time -->
        <div class="c-comtime">{LANG.comm_time_title}</div>
        <!-- END: comm_time -->
        <div class="c-intro">{LANG.trichyeu}</div>
    </div>
    <!-- BEGIN: loop -->
    <div class="table-rows">
        <div class="c-code"><span class="label-name">{LANG.code}:</span><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></div>
        <!-- BEGIN: publtime -->
        <div class="c-time"><span class="label-name">{LANG.publtime}:</span>{ROW.publtime}</div>
        <!-- END: publtime -->
        <!-- BEGIN: comm_time -->
        <div class="c-comtime"><span class="label-name">{LANG.comm_time_title}:</span>{ROW.comm_time}</div>
        <!-- END: comm_time -->
        <div class="c-intro">
            <a href="{ROW.url}">{LAW_TITLE}</a>
            <!-- BEGIN: introtext -->
            <div class="l-intro margin-top-sm">
                {ROW.introtext}
            </div>
            <!-- END: introtext -->
        </div>
    </div>
    <!-- END: loop -->
</div>
<!-- END: main -->
