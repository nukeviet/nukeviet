<!-- BEGIN: suspend -->
<div class="alert bg-primary">
    <h3 class="mb-0">{LANG.suspend_status}: <strong>{SUSPEND_STATUS}</strong></h3>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <!-- BEGIN: suspend_info2 -->
        <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.suspend_info_yes}:</caption>
        <col style="width: 1%" />
        <col span="2" style="width: 25%" />
        <col style="width: 50%" />
        <thead>
            <tr class="active">
                <th colspan="2">{LANG.suspend_start}</th>
                <th>{LANG.suspend_end}</th>
                <th>{LANG.suspend_reason}</th>
            </tr>
        </thead>
        <!-- END: suspend_info2 -->
        <tbody>
            <!-- BEGIN: suspend_info -->
            <td>{SUSPEND_INFO}</td>
            <!-- END: suspend_info -->
            <!-- BEGIN: suspend_info1 -->
            <!-- BEGIN: loop -->
            <tr<!-- BEGIN: inactive --> class="inactive"
                <!-- END: inactive -->>
                <td>
                    <!-- BEGIN: active --><em class="fa fa-play"></em><!-- END: active -->
                    <!-- BEGIN: inactive2 --><em class="fa fa-stop"></em><!-- END: inactive2 -->
                </td>
                <td>{VALUE0}</td>
                <td>{VALUE1}</td>
                <td>{VALUE2}</td>
                </tr>
                <!-- END: loop -->
                <!-- END: suspend_info1 -->
        </tbody>
    </table>
</div>
<!-- BEGIN: change_suspend -->
<div class="row">
    <div class="col-md-12 col-md-offset-6 col-lg-10 col-lg-offset-7">
        <form method="post" action="{ACTION}" class="panel panel-default" id="suspend-form">
            <div class="panel-heading">
                <h3 class="mb-0">
                    <strong>{NEW_SUSPEND_CAPTION}</strong>
                </h3>
            </div>
            <div class="panel-body">
                <!-- BEGIN: new_reason -->
                <div class="form-group">
                    <label for="new_reason">{LANG.suspend_reason}</label>
                    <input name="new_reason" id="new_reason" type="text" class="form-control required" maxlength="255" data-error="{LANG.susp_reason_empty}" />
                </div>
                <!-- END: new_reason -->
                <div class="mb" style="display:flex;justify-content: center">
                    <div class="display:inline-flex">
                        <div class="checkbox mb">
                            <label>
                                <input type="checkbox" name="sendmail" value="1"> {LANG.suspend_sendmail}
                            </label>
                        </div>
                        <!-- BEGIN: clean_history -->
                        <div class="checkbox mb">
                            <label>
                                <input type="checkbox" name="clean_history" value="1"> {LANG.clean_history}
                            </label>
                        </div>
                        <!-- END: clean_history -->
                    </div>
                </div>
                <input name="save" type="hidden" value="1" />
                <input type="hidden" name="checkss" value="{CHECKSS}" />
                <div class="text-center">
                    <input type="submit" value="{GLANG.submit}" class="btn btn-primary" />
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: change_suspend -->
<!-- END: suspend -->