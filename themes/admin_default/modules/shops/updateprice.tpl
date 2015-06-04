<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">
    {ERROR}
</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td> {LANG.cateid} </td>
                    <td>
                    <select class="form-control" name="catid">
                        <!-- BEGIN: select_cateid -->
                        <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                        <!-- END: select_cateid -->
                    </select></td>
                </tr>
                <tr>
                    <td> {LANG.newprice} </td>
                    <td>
                    <input class="form-control" type="text" name="newprice" value="{ROW.newprice}" pattern="^[0-9]*$"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="text-align: center">
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
</form>
<!-- END: main -->