<!-- BEGIN: main -->
<div class="flex-table-laws-inline">
    <div class="flex-table-laws">
        <div class="table-rows table-head">
            <div class="c-code">{LANG.code}</div>
            <!-- BEGIN: publtime_title -->
            <div class="c-time">{LANG.publtime}</div>
            <!-- END: publtime_title -->
            <div class="c-intro">{LANG.trichyeu}</div>
            <!-- BEGIN: down_in_home -->
            <div class="c-file">{LANG.files}</div>
            <!-- END: down_in_home -->
            <!-- BEGIN: send_comm_title -->
            <div class="c-comment a-center">{LANG.comm_time}</div>
            <!-- END: send_comm_title -->
        </div>
    </div>
    <!-- BEGIN: loop -->
    <div class="flex-table-heading">
        <strong><a href="{DATA.url_subject}">{DATA.title}</a> <span class="text-danger">({DATA.numcount})</span></strong>
    </div>
    <!-- BEGIN: rows -->
    {HTML}
    <!-- END: rows -->
    <!-- END: loop -->
</div>
<!-- END: main -->
