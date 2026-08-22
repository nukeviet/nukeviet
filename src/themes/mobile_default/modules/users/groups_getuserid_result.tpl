<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>{LANG.username}/{LANG.email}</th>
                <th>{LANG.regdate}</th>
                <th class="text-center">{LANG.funcs}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td><strong>{ROW.userid}</strong></td>
                <td>
                    {ROW.username}<br />
                    <small>{ROW.email}</small>
                </td>
                <td>{ROW.regdate}</td>
                <td class="text-center text-nowrap">
                    <a data-toggle="nv_active" data-userid="{ROW.userid}" href="#">
                        <i class="fa fa-edit" data-icon="fa-edit"></i> {LANG.active}
                    </a>
                </td>
            </tr>
            <!-- END: row -->
        </tbody>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: center">
                    <div class="fr generatePage">{GENERATE_PAGE}</div>
                </td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: data -->
<!-- BEGIN: nodata -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <td class="text-center">{LANG.noresult}</td>
            </tr>
        </tbody>
    </table>
</div>
<!-- END: nodata -->
<!-- END: main -->
