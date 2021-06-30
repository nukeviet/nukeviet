<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col class="w100">
            <col span="1">
            <col span="2" class="w150">
        </colgroup>
        <thead>
            <tr class="text-center">
                <th class="text-nowrap">{LANG.order}</th>
                <th class="text-nowrap">{LANG.title}</th>
                <th class="text-nowrap">{LANG.add_time}</th>
                <th class="text-nowrap">{LANG.edit_time}</th>
                <th class="text-nowrap">{LANG.status}</th>
                <th class="text-center text-nowrap">{LANG.hitstotal}</th>
                <th class="text-center text-nowrap">{LANG.feature}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td class="text-center">
                    <select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}');" class="form-control input-sm">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td>
                    <a href="{ROW.url_view}" title="{ROW.title}" target="_blank">{ROW.title}</a>
                </td>
                <td>{ROW.add_time}</td>
                <td>{ROW.edit_time}</td>
                <td class="text-center">
                    <select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');" class="form-control input-sm">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
                        <!-- END: status -->
                    </select>
                </td>
                <td class="text-center">{ROW.hitstotal}</td>
                <td class="text-center text-nowrap">
                    <!-- BEGIN: copy_page -->
                    <a href={URL_COPY} title="{LANG.title_copy_page}" class="btn btn-success btn-sm"><i class="fa fa-copy"></i></a>
                    <!-- END: copy_page -->
                    <a href="{ROW.url_edit}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> {GLANG.edit}</a> <a href="javascript:void(0);" onclick="nv_module_del({ROW.id}, '{ROW.checkss}');" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> {GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->
