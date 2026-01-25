<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.no_row_contact}</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<form name="myform" id="myform" method="post" action="{FORM_ACTION}" data-error="{LANG.please_choose}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-nowrap text-center" style="width:1%;"><input class="form-control" type="checkbox" data-toggle="checkAll" /></th>
                    <th class="text-nowrap text-center" colspan="3">{LANG.name_user_send_title}</th>
                    <th class="text-nowrap text-center">{LANG.to_department}</th>
                    <th class="text-nowrap text-center">{LANG.cat}</th>
                    <th class="text-nowrap text-center">{LANG.title_send_title}</th>
                    <th class="text-nowrap text-center" style="width:1%;">{LANG.send_time}</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td class="text-center" style="width:1%;"><input class="form-control" type="checkbox" data-toggle="checkAll" /></td>
                    <td colspan="7">
                        <button type="button" class="btn btn-default feedback_del_sel"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {LANG.bt_del_row_title}</button> &nbsp;
                        <!-- BEGIN: for_spadmin --><button type="button" class="btn btn-default feedback_del_all"><em class="fa fa-trash-o">&nbsp;</em> {LANG.delall}</button> &nbsp;<!-- END: for_spadmin -->
                        <button type="button" class="btn btn-default feedback_mark" data-mark="unread"><em class="fa fa-bookmark">&nbsp;</em> {LANG.mark_as_unread}</button> &nbsp;
                        <button type="button" class="btn btn-default feedback_mark" data-mark="read"><em class="fa fa-bookmark-o">&nbsp;</em> {LANG.mark_as_read}</button>&nbsp;
                        <button type="button" class="btn btn-default feedback_mark" data-mark="unprocess"><em class="fa fa-circle-o">&nbsp;</em> {LANG.mark_as_unprocess}</button> &nbsp;
                        <button type="button" class="btn btn-default feedback_mark" data-mark="processed"><em class="fa fa-check-circle-o">&nbsp;</em> {LANG.mark_as_processed}</button> &nbsp;
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: row -->
                <tr class="item" title="{ROW.status}" data-url="{ROW.onclick}"<!-- BEGIN: is_processed --> style="color:#aaa"<!-- END: is_processed -->>
                    <td class="text-center" style="width:1%;">
                        <input class="form-control" name="sends[]" type="checkbox" value="{ROW.id}" data-toggle="checkSingle"{ROW.disabled} />
                    </td>
                    <td class="pointer text-nowrap text-center view_feedback" style="width:1%{ROW.style}">
                        <!-- BEGIN: process --><span class="fa fa-spinner fa-spin"></span><!-- END: process -->
                        <!-- BEGIN: processed --><span class="fa fa-check"></span><!-- END: processed -->
                    </td>
                    <td class="pointer text-nowrap text-center view_feedback" style="width:1%{ROW.style}"><img alt="" src="{ROW.image.0}" width="{ROW.image.1}" height="{ROW.image.2}" /></td>
                    <td class="pointer text-nowrap view_feedback" style="{ROW.style}">{ROW.sender_name}</td>
                    <td class="pointer text-nowrap view_feedback" style="{ROW.style}">{ROW.path}</td>
                    <td class="pointer text-nowrap view_feedback" style="{ROW.style}">{ROW.cat}</td>
                    <td class="pointer text-nowrap view_feedback" style="{ROW.style}">{ROW.title}</td>
                    <td class="pointer text-nowrap text-center view_feedback" style="width:1%;{ROW.style}">{ROW.time}</td>
                </tr>
                <!-- END: row -->
            </tbody>
        </table>
    </div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->
<!-- END: data -->
<!-- END: main -->