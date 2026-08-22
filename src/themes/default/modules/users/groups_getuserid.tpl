<!-- BEGIN: main -->
<div id="getuidcontent">
    <form class="form-group" id="formgetuid" method="get" action="{FORM_ACTION}">
        <table class="table table-bordered table-hover">
            <tbody>
                <tr>
                    <td>{LANG.user_id}</td>
                    <td><input class="form-control fixwidthinput" type="text" name="user_id" value="" maxlength="100" /></td>
                    <td>{LANG.username}</td>
                    <td><input class="form-control fixwidthinput" type="text" name="username" value="" maxlength="100" /></td>
                </tr>
                <tr>
                    <td>{LANG.fullname}</td>
                    <td><input class="form-control fixwidthinput" type="text" name="full_name" value="" maxlength="100" /></td>
                    <td>{LANG.email}</td>
                    <td><input class="form-control fixwidthinput" type="text" name="email" value="" maxlength="100" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <input type="reset" class="btn btn-info" value="{LANG.reset}" />
                        <input type="hidden" name="fsubmit" value="1" />
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search fa-fw text-center" data-icon="fa-search"></i> {LANG.search}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
<div id="resultdata">&nbsp;</div>
<!--  END: main  -->
