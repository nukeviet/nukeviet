<!-- BEGIN: main -->
<form method="POST" action="{FORM_ACTION}" class="well form-horizontal">
    <input type="hidden" name="cfg" value="1">
    <div class="form-group">
        <label class="col-sm-8 control-label">{LANG.cron_launcher}</label>
        <div class="col-sm-16">
            <div class="radio">
                <label>
                    <input type="radio" name="cronjobs_launcher" value="system" data-toggle="codeShow" <!-- BEGIN: launcher_system --> checked
                    <!-- END: launcher_system -->> {LANG.cron_launcher_system}
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="cronjobs_launcher" value="server" data-toggle="codeShow" <!-- BEGIN: launcher_server --> checked
                    <!-- END: launcher_server -->> {LANG.cron_launcher_server}
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-8 control-label">{LANG.cron_launcher_interval}</label>
        <div class="col-sm-16">
            <select name="cronjobs_interval" class="form-control" style="width: auto;">
                <!-- BEGIN: cronjobs_interval -->
                <option value="{CRON_INTERVAL.val}" {CRON_INTERVAL.sel}>{CRON_INTERVAL.name}</option>
                <!-- END: cronjobs_interval -->
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-8 col-sm-16">
            <button type="submit" class="btn btn-primary">{LANG.submit}</button>
        </div>
    </div>
</form>
<!-- BEGIN: cron_code -->
<div id="cron-code">
    <p>{LANG.cron_launcher_server_note}</p>
    <pre class="cron_code">{CRON_CODE}</pre>
</div>
<!-- END: cron_code -->
<!-- BEGIN: next_cron -->
<ul class="list-group">
    <li class="list-group-item">{LAST_CRON}</li>
    <li class="list-group-item">{NEXT_CRON}</li>
</ul>
<!-- END: next_cron -->
<div class="panel-group cronlist" id="cronlist" role="tablist" aria-multiselectable="true">
    <div class="m-bottom">
        <span class="hidden-xs hidden-sm pull-right">{LANG.last_time}</span>
        <strong>{LANG.cron_list}:</strong>
    </div>
    <!-- BEGIN: crj -->
    <div class="panel panel-default">
        <div role="tab" id="cron-heading-{DATA.id}">
            <a class="panel-heading cron-heading collapsed<!-- BEGIN: inactivate --> text-muted<!-- END: inactivate -->" role="button" data-toggle="collapse" data-parent="#cronlist" href="#cron-collapse-{DATA.id}" aria-expanded="false" aria-controls="cron-collapse-{DATA.id}">
                <span class="hidden-xs hidden-sm pull-right">
                    <!-- BEGIN: never -->{LANG.last_time0}<!-- END: never -->
                    <!-- BEGIN: last_time -->
                    <span title="{LANG.last_time}">{DATA.last_time_title}</span>
                    <!-- BEGIN: result1 --><i class="fa fa-check" title="{DATA.last_result_title}"></i><!-- END: result1 -->
                    <!-- BEGIN: result0 --><i class="fa fa-exclamation-triangle" title="{DATA.last_result_title}"></i><!-- END: result0 -->
                    <!-- END: last_time -->
                </span>
                <span class="title">{DATA.caption}</span>
            </a>
        </div>
        <div id="cron-collapse-{DATA.id}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cron-heading-{DATA.id}">
            <table class="table">
                <col span="2" style="width: 50%" />
                <tbody>
                    <!-- BEGIN: loop -->
                    <tr>
                        <td>{ROW.key}:</td>
                        <td>{ROW.value}</td>
                    </tr>
                    <!-- END: loop -->
                </tbody>
                <!-- BEGIN: action -->
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <!-- BEGIN: edit -->
                            <a class="btn btn-primary" style="margin-left: 5px" href="{DATA.edit.2}">{DATA.edit.1}</a>
                            <!-- END: edit -->
                            <!-- BEGIN: disable -->
                            <a class="btn btn-primary" style="margin-left: 5px" href="{DATA.disable.2}">{DATA.disable.1}</a>
                            <!-- END: disable -->
                            <!-- BEGIN: delete -->
                            <a class="btn btn-primary" href="javascript:void(0);" onclick="nv_is_del_cron('{DATA.id}', '{DATA.delete.2}');">{DATA.delete.1}</a>
                            <!-- END: delete -->
                        </td>
                    </tr>
                </tfoot>
                <!-- END: action -->
            </table>
        </div>
    </div>
    <!-- END: crj -->
</div>
<!-- END: main -->