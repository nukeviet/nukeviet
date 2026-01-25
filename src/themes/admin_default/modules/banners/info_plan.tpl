<!-- BEGIN: main -->
<div id="plan_info">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <caption><em class="fa fa-file-text-o"></em> {ROW.caption}</caption>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <a class="btn btn-primary btn-sm" href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=edit_plan&amp;id={ROW.id}">{GLANG.edit}</a>
                        <a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="nv_pl_chang_act2({ROW.id});">{LANG.change_act}</a>
                        <a class="btn btn-primary btn-sm" href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=add_banner&amp;pid={ROW.id}">{LANG.add_banner}</a>
                        <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="nv_pl_del2({ROW.id});">{GLANG.delete}</a>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>{LANG.title}:</td>
                    <td>{ROW.title}</td>
                </tr>
                <tr>
                    <td>{LANG.blang}:</td>
                    <td>{ROW.blang_format}</td>
                </tr>
                <tr>
                    <td>{LANG.form}:</td>
                    <td>{ROW.form_format}</td>
                </tr>
                <tr>
                    <td>{LANG.size}:</td>
                    <td>{ROW.width} x {ROW.height}px</td>
                </tr>
                <tr>
                    <td>{LANG.is_act}:</td>
                    <td>{ROW.is_act}</td>
                </tr>
                <tr>
                    <td>{LANG.require_image}:</td>
                    <td>{ROW.require_image}</td>
                </tr>
                <tr>
                    <td>{LANG.uploadtype}:</td>
                    <td>{ROW.uploadtype}</td>
                </tr>
                <tr>
                    <td>{LANG.plan_uploadgroup}:</td>
                    <td>{ROW.uploadgroup}</td>
                </tr>
                <tr>
                    <td>{LANG.plan_exp_time}:</td>
                    <td>{ROW.plan_exp_time}</td>
                </tr>
                <!-- BEGIN: description -->
                <tr>
                    <td colspan="2">{ROW.description}</td>
                </tr>
                <!-- END: description -->
            </tbody>
        </table>
    </div>
</div>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <a class="panel-heading d-block" role="tab" id="list_act-title" data-toggle="collapse" data-parent="#accordion" href="#list_act" aria-expanded="false" aria-controls="list_act">
            {LANG.banners_list1}
        </a>
        <div id="list_act" class="panel-collapse collapse" role="tabpanel" aria-labelledby="list_act-title" data-pid="{ROW.id}" data-act="1" data-location="{LOCATION}&amp;accordion=list_act">
            <div id="banners_list_act" class="panel-body">
                <p class="text-center">
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
                </p>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <a class="panel-heading d-block" role="tab" id="list_queue-title" data-toggle="collapse" data-parent="#accordion" href="#list_queue" aria-expanded="false" aria-controls="list_queue">
            {LANG.banners_list4}
        </a>
        <div id="list_queue" class="panel-collapse collapse" role="tabpanel" aria-labelledby="list_queue-title" data-pid="{ROW.id}" data-act="4" data-location="{LOCATION}&amp;accordion=list_queue">
            <div id="banners_list_queue" class="panel-body">
                <p class="text-center">
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
                </p>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <a class="panel-heading d-block" role="tab" id="list_timeract-title" data-toggle="collapse" data-parent="#accordion" href="#list_timeract" aria-expanded="false" aria-controls="list_timeract">
            {LANG.banners_list0}
        </a>
        <div id="list_timeract" class="panel-collapse collapse" role="tabpanel" aria-labelledby="list_timeract-title" data-pid="{ROW.id}" data-act="0" data-location="{LOCATION}&amp;accordion=list_timeract">
            <div id="banners_list_timeract" class="panel-body">
                <p class="text-center">
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
                </p>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <a class="panel-heading d-block" role="tab" id="list_exp-title" data-toggle="collapse" data-parent="#accordion" href="#list_exp" aria-expanded="false" aria-controls="list_exp">
            {LANG.banners_list2}
        </a>
        <div id="list_exp" class="panel-collapse collapse" role="tabpanel" aria-labelledby="list_exp-title" data-pid="{ROW.id}" data-act="2" data-location="{LOCATION}&amp;accordion=list_exp">
            <div id="banners_list_exp" class="panel-body">
                <p class="text-center">
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
                </p>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <a class="panel-heading d-block" role="tab" id="list_deact-title" data-toggle="collapse" data-parent="#accordion" href="#list_deact" aria-expanded="false" aria-controls="list_deact">
            {LANG.banners_list3}
        </a>
        <div id="list_deact" class="panel-collapse collapse" role="tabpanel" aria-labelledby="list_deact-title" data-pid="{ROW.id}" data-act="3" data-location="{LOCATION}&amp;accordion=list_deact">
            <div id="banners_list_deact" class="panel-body">
                <p class="text-center">
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#accordion .collapse').on('show.bs.collapse', function() {
            locationReplace($(this).data("location"));
            $(this).parents('.panel').toggleClass('panel-default panel-primary');
            if (!$(this).data('loaded')) {
                $(this).data('loaded', true);
                nv_show_banners_list('banners_' + $(this).attr('id'), 0, $(this).data('pid'), $(this).data('act'))
            }
        }).on('hide.bs.collapse', function() {
            $(this).parents('.panel').toggleClass('panel-default panel-primary')
        });
        <!-- BEGIN: accordion -->
        $('#{ACCORDION}').collapse('show')
        <!-- END: accordion -->
    })
</script>
<!-- END: main -->