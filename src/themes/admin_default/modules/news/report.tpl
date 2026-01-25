<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-bordered table-striped list-report" data-url="{ACTION_URL}" data-del-confirm="{LANG.report_del_confirm}">
        <thead>
            <tr class="bg-primary">
                <th class="text-center" style="width: 1%;"><input type="checkbox" class="form-control checkall"></th>
                <th>{LANG.contents}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.post_time}</th>
                <th style="width: 1%;"></th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr class="item" data-id="{REPORT.id}">
                <td class="text-center" style="width: 1%;"><input type="checkbox" class="form-control checkitem" value="{REPORT.id}"></td>
                <td>
                    <p><a href="{REPORT.url}"><strong>{LANG.article}: {REPORT.title}</strong></a></p>
                    <p>{REPORT.orig_content_short}</p>
                    <small class="text-muted">{LANG.post_ip}: {REPORT.post_ip}, {LANG.post_email}: {REPORT.post_email}</small>
                </td>
                <td class="text-center" style="width: 1%;">{REPORT.post_time_format}</td>
                <td class="text-center" style="width: 1%;">
                    <button type="button" class="btn btn-default btn-sm m-bottom report_del_action" title="{GLANG.delete}"><i class="fa fa-trash fa-fw"></i></button>
                    <button type="button" class="btn btn-default btn-sm m-bottom report_del_mail_action" title="{LANG.report_delete}"><i class="fa fa-envelope-o fa-fw"></i></button>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" style="width: 1%;"><input type="checkbox" class="form-control checkall"></td>
                <td colspan="3"><button type="button" class="btn btn-default report_del_check_action" data-not-checked="{LANG.report_not_checked}"><i class="fa fa-trash fa-fw"></i> {LANG.report_del_checked}</button></td>
            </tr>
        </tfoot>
    </table>
</div>
<!-- END: main -->