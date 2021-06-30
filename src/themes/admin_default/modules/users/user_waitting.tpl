<!-- BEGIN: main -->
<div class="alert alert-warning">{LANG.warning}</div>
<div id="users">
    <div class="row">
        <div class="col-md-16">
            <div class="form-group">
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
        </div>
        <div class="col-md-8">
            <!-- BEGIN: resend_email -->
            <div class="form-group text-right">
                <a href="{RESEND_URL}" class="btn btn-info"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> {LANG.userwait_resend_email}</a>
            </div>
            <!-- END: resend_email -->
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}</caption>
            <thead>
                <tr>
                    <!-- BEGIN: head_td -->
                    <th><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></th>
                    <!-- END: head_td -->
                    <th>{LANG.funcs}</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="8"> {GENERATE_PAGE} </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: xusers -->
                <tr>
                    <td> {CONTENT_TD.userid} </td>
                    <td> {CONTENT_TD.username} </td>
                    <td> {CONTENT_TD.full_name} </td>
                    <td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
                    <td> {CONTENT_TD.regdate} </td>
                    <td>
                        <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ACTIVATE_URL}&amp;checkss={CONTENT_TD.checkss}">{LANG.awaiting_active}</a> &nbsp;
                        <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_waiting_row_del({CONTENT_TD.userid}, '{CONTENT_TD.checkss}');">{LANG.delete}</a>
                    </td>
                </tr>
                <!-- END: xusers -->
            </tbody>
        </table>
    </div>
</div>
<!-- END: main -->
