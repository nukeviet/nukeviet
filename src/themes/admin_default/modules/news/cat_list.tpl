<!-- BEGIN: main -->
<!-- BEGIN: cat_title -->
<ol class="breadcrumb breadcrumb-catnav">
    <!-- BEGIN: loop --><li><a href="{CAT.link}">{CAT.title}</a></li><!-- END: loop -->
    <!-- BEGIN: active --><li class="active">{CAT.title}</li><!-- END: active -->
</ol>
<!-- END: cat_title -->
<!-- BEGIN: data -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center w100">{LANG.weight}</th>
                <th class="text-center">{LANG.name}</th>
                <th class="text-center w100">{LANG.numlinks}</th>
                <th class="text-center w100"><img src="{NV_BASE_SITEURL}themes/default/images/icons/new.gif" title="{LANG.newday}"/></th>
                <th class="text-center">{LANG.viewcat_page}</th>
                <th class="text-center">{LANG.status}</th>
                <th class="text-center w150">{LANG.functional}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <!-- BEGIN: stt -->{STT}<!-- END: stt -->
                    <!-- BEGIN: weight -->
                    <button id="cat_weight_{ROW.catid}" data-toggle="changecat" data-mod="weight" data-min="1" data-num="{MAX_WEIGHT}" data-catid="{ROW.catid}" data-current="{STT}" type="button" class="btn btn-default btn-xs btn-block btn-cattool"><span class="caret"></span><span class="text">{STT}</span></button>
                    <!-- END: weight -->
                </td>
                <td>
                    <a href="{ROW.link}"><strong>{ROW.title}</strong>
                    <!-- BEGIN: numsubcat -->
                    <span class="red">({NUMSUBCAT})</span>
                    <!-- END: numsubcat -->
                    </a>
                </td>
                <td class="text-center">
                    <!-- BEGIN: title_numlinks -->
                    {NUMLINKS}
                    <!-- END: title_numlinks -->
                    <!-- BEGIN: numlinks -->
                    <button id="cat_numlinks_{ROW.catid}" data-toggle="changecat" data-mod="numlinks" data-min="0" data-num="{MAX_NUMLINKS}" data-catid="{ROW.catid}" data-current="{NUMLINKS}" type="button" class="btn btn-default btn-xs btn-block btn-cattool"><span class="caret"></span><span class="text">{NUMLINKS}</span></button>
                    <!-- END: numlinks -->
                </td>
                <td class="text-center">
                    <!-- BEGIN: title_newday -->
                    {NEWDAY}
                    <!-- END: title_newday -->
                    <!-- BEGIN: newday -->
                    <button id="cat_newday_{ROW.catid}" data-toggle="changecat" data-mod="newday" data-min="0" data-num="{MAX_NEWDAY}" data-catid="{ROW.catid}" data-current="{NEWDAY}" type="button" class="btn btn-default btn-xs btn-block btn-cattool"><span class="caret"></span><span class="text">{NEWDAY}</span></button>
                    <!-- END: newday -->
                </td>
                <td class="text-left">
                    <!-- BEGIN: disabled_viewcat -->
                    {VIEWCAT}
                    <!-- END: disabled_viewcat -->
                    <!-- BEGIN: viewcat -->
                    <button id="cat_viewcat_{ROW.catid}" data-toggle="changecat" data-mod="viewcat" data-catid="{ROW.catid}" data-current="{VIEWCAT_VAL}" data-mode="{VIEWCAT_MODE}" type="button" class="btn btn-default btn-xs btn-block btn-cattool"><span class="caret"></span><span class="text">{VIEWCAT}</span></button>
                    <!-- END: viewcat -->
                </td>
                <td class="text-center">
                    <!-- BEGIN: disabled_status -->
                    {STATUS}
                    <!-- END: disabled_status -->
                    <!-- BEGIN: status -->
                    <button id="cat_status_{ROW.catid}" data-toggle="changecat" data-mod="status" data-catid="{ROW.catid}" data-current="{STATUS_VAL}" data-cmess="{LANG.cat_status_0_confirm}" type="button" class="btn btn-default btn-xs btn-block btn-cattool"><span class="caret"></span><span class="text">{STATUS}</span></button>
                    <!-- END: status -->
                </td>
                <td class="text-center">{ROW.adminfuncs}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<ul id="cat_list_full" class="hidden">
    <!-- BEGIN: viewcat_full --><li><a href="#" data-value="{K}">{V}</a></li><!-- END: viewcat_full -->
</ul>
<ul id="cat_list_nosub" class="hidden">
    <!-- BEGIN: viewcat_nosub --><li><a href="#" data-value="{K}">{V}</a></li><!-- END: viewcat_nosub -->
</ul>
<ul id="cat_list_status" class="hidden">
    <!-- BEGIN: status --><li><a href="#" data-value="{K}">{V}</a></li><!-- END: status -->
</ul>
<!-- END: data -->
<!-- END: main -->