<!-- BEGIN: main -->
<div class="flex-table-laws">
    <div class="table-rows table-head">
        <div class="c-stt a-center">{LANG.stt}</div>
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
    <!-- BEGIN: loop -->
    <div class="table-rows">
        <div class="c-stt a-center">{ROW.stt}</div>
        <div class="c-code"><span class="label-name">{LANG.code}:</span><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></div>
        <!-- BEGIN: publtime -->
        <div class="c-time"><span class="label-name">{LANG.publtime}:</span>{ROW.publtime}</div>
        <!-- END: publtime -->
        <div class="c-intro">
            <a href="{ROW.url}">{ROW.introtext}<!-- BEGIN: shownumbers --> ({ROW.number_comm})<!-- END: shownumbers --></a>
            <!-- BEGIN: comment_time -->
            <div class="margin-top-sm clearfix text-warning">{COMMENT_TIME}</div>
            <!-- END: comment_time -->
            <!-- BEGIN: admin_link -->
            <div class="margin-top-sm clearfix">
                <a class="btn btn-primary btn-xs" href="{ROW.edit_link}"><em class="fa fa-edit margin-right"></em>{LANG.edit}</a>
                <a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="{ROW.delete_link}"><em class="fa fa-trash-o margin-right"></em>{LANG.delete}</a>
            </div>
            <!-- END: admin_link -->
        </div>
        <!-- BEGIN: down_in_home -->
        <div class="c-file">
            <span class="label-name">{LANG.files}:</span>
            <!-- BEGIN: files -->
            <ul class="laws-download list-unstyled">
                <!-- BEGIN: loopfile -->
                <li><a href="{FILE.url}" title="{FILE.title}"><i class="fa fa-download"></i> {FILE.titledown}</a></li>
                <!-- END: loopfile -->
            </ul>
            <!-- END: files -->
        </div>
        <!-- END: down_in_home -->
        <!-- BEGIN: send_comm -->
        <div class="c-comment a-center"><span class="label-name">{LANG.comm_time}:</span><a href="{ROW.url}#comment" title="{LANG.comm_time}"><span class="send_comm"></span></a></div>
        <!-- END: send_comm -->
        <!-- BEGIN: comm_close -->
        <div class="c-comment a-center"><span class="label-name">{LANG.comm_time}:</span><a href="{ROW.url}#comment" title="{LANG.uncomm_time}"><span class="comm_close"></span></a></div>
        <!-- END: comm_close -->
    </div>
    <!-- END: loop -->
</div>
<!-- BEGIN: generate_page -->
<div class="generate_page">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
