<!-- BEGIN: main -->
<div class="row m-bottom">
    <div class="col-xs-24">
        <!-- BEGIN: upload_allowed -->
        <div class="pull-left">
            <div class="dropdown">
                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{LANG.install_package}</button>
                <div class="dropdown-menu" role="menu">
                    <div class="nv-panel-body w350">
                        <!-- BEGIN: upload -->
                        <form onsubmit="return checkform();" class="form-inline" method="post" enctype="multipart/form-data" action="{SUBMIT_URL}">
                            <input type="file" class="form-control w200 input-sm" name="extfile"/>
                            <input type="submit" name="submit" class="btn btn-primary btn-sm" value="{LANG.install_submit}"/>
                        </form>
                        <!-- END: upload -->
                        <!-- BEGIN: nozlib -->
                        <span class="text-danger"><strong>{GLANG.error_zlib_support}</strong></span>
                        <!-- END: nozlib -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END: upload_allowed -->
        <div class="pull-right">
            <em class="fa {THEME_CONFIG.sys_icon}">&nbsp;</em> {LANG.extType_sys}&nbsp; - &nbsp;
            <em class="fa {THEME_CONFIG.admin_icon}">&nbsp;</em> {LANG.extType_admin}
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>{LANG.ext_type}</th>
                <th>{LANG.extname}</th>
                <th>{LANG.file_version}</th>
                <th>{LANG.author}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.type}</td>
                <td>
                    {ROW.basename}
                    <!-- BEGIN: icons -->
                    <div class="pull-right text-right">
                        <!-- BEGIN: loop -->
                        <em class="fa {ICON}">&nbsp;</em>
                        <!-- END: loop -->
                    </div>
                    <!-- END: icons -->
                </td>
                <td>{ROW.version}</td>
                <td>{ROW.author}
                    <div class="pull-right text-right">
                        <em class="fa fa-cloud-download fa-lg package-ext icon-pointer" data-toggle="tooltip" data-placement="top" title="{LANG.package}" data-href="{ROW.url_package}">&nbsp;</em>
                        <!-- BEGIN: delete -->
                        <em class="fa fa-trash-o fa-lg delete-ext icon-pointer" data-toggle="tooltip" data-placement="top" title="{GLANG.delete}" data-href="{ROW.url_delete}">&nbsp;</em>
                        <!-- END: delete -->
                    </div>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<script type="text/javascript">
LANG.install_error_nofile = '{LANG.install_error_nofile}';
LANG.install_error_filetype = '{LANG.install_error_filetype}';
LANG.delele_ext_confirm = '{LANG.delele_ext_confirm}';
</script>
<!-- END: main -->