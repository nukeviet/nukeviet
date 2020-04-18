<!-- BEGIN: main -->
<!-- BEGIN: is_forum -->
<div class="alert alert-warning">{LANG.modforum}</div>
<!-- END: is_forum -->
<div style="padding-top:10px;">
    <form class="form-inline" role="form" action="{FORM_ACTION}" method="post" onsubmit="nv_check_form(this);return false;">
        <span><strong>{LANG.search_type}:</strong></span>
        <select class="form-control" name="method" id="f_method">
            <option value="">---</option>
            <!-- BEGIN: method -->
            <option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
            <!-- END: method -->
        </select>
        <input class="form-control" type="text" name="value" id="f_value" value="{SEARCH_VALUE}" />
        <input class="btn btn-primary" name='search' type="submit" value="{LANG.submit}" />
        <p>
            {LANG.search_note}
        </p>
    </form>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption><em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}</caption>
        <thead>
            <tr>
                <!-- BEGIN: head_td -->
                <th><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></th>
                <!-- END: head_td -->
                <th class="text-center w200">{LANG.funcs}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: xusers -->
            <tr>
                <td> {CONTENT_TD.userid} </td>
                <td>
                    <!-- BEGIN: user_link -->
                    <a href="{VIEW_LINK}"><strong>{CONTENT_TD.username}</strong></a>
                    <!-- END: user_link -->
                    <!-- BEGIN: user_text -->
                    <strong>{CONTENT_TD.username}</strong>
                    <!-- END: user_text -->
                </td>
                <td> {CONTENT_TD.full_name} </td>
                <td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
                <td> {CONTENT_TD.lastedit} </td>
                <td class="text-center">
                    <!-- BEGIN: allowed -->
                    <a href="javascript:void(0);" class="btn btn-xs btn-success" onclick="nv_editcensor_row_accept({CONTENT_TD.userid}, '{LANG.editcensor_confirm_approval}');"><i class="fa fa-check"></i> {LANG.approved}</a>
                    <a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="nv_editcensor_row_del({CONTENT_TD.userid}, '{LANG.editcensor_confirm_denied}');"><i class="fa fa-trash"></i> {LANG.denied}</a>
                    <!-- END: allowed -->
                </td>
            </tr>
            <!-- END: xusers -->
        </tbody>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="8"> {GENERATE_PAGE} </td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: main -->
