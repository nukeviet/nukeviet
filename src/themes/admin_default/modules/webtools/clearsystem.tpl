<!-- BEGIN: main -->
<div class="row">
    <form class="col-lg-10" id="clearsystem-form" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <td><strong>{LANG.checkContent}</strong></td>
                        <td style="width: 1%;"><input class="form-control" type="checkbox" value="yes" name="check_all[]" onclick="nv_checkAll(this.form, 'deltype[]', 'check_all[]',this.checked);" /></td>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center"><input type="hidden" name="checkss" value="{CHECKSS}" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" /></td>
                    </tr>
                </tfoot>
                <tbody>
                    <!-- BEGIN: clear -->
                    <tr>
                        <td><strong>{CLEAR.title}</strong></td>
                        <td><input class="form-control" type="checkbox" value="{CLEAR.value}" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
                    </tr>
                    <!-- END: clear -->
                </tbody>
            </table>
        </div>
    </form>
    <div class="col-lg-14">
        <div id="pload" class="alert alert-info text-center" style="display:none;">
            <i class="fa fa-spinner fa-spin fa-lg fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
        <div id="presult" class="panel panel-success" style="display:none;">
            <div class="panel-heading">
                <strong>{LANG.deletedetail}:</strong>
            </div>
            <ul class="list-group contents"></ul>
        </div>
        <div id="pnoresult" class="alert alert-danger text-center" style="display:none;">
            {LANG.no_files_to_delete}
        </div>
    </div>
</div>
<!-- END: main -->