<!-- BEGIN: main -->
<form method="POST" action="{FORM_ACTION}" class="well form-horizontal">
    <input type="hidden" name="cfg" value="1">
    <div class="form-group">
        <label class="col-sm-8 control-label">{LANG.cron_launcher}</label>
        <div class="col-sm-16">
            <div class="radio">
                <label>
                    <input type="radio" name="cronjobs_launcher" value="system" data-toggle="codeShow"<!-- BEGIN: launcher_system --> checked<!-- END: launcher_system -->> {LANG.cron_launcher_system}
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="cronjobs_launcher" value="server" data-toggle="codeShow"<!-- BEGIN: launcher_server --> checked<!-- END: launcher_server -->> {LANG.cron_launcher_server}
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
<!-- BEGIN: crj -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <col span="2" style="width: 50%" />
        <thead>
            <tr class="bg-primary">
                <th colspan="2">
                    <div>
                        <!-- BEGIN: edit -->
                        <a class="btn btn-primary btn-xs pull-right" style="margin-left: 5px" href="{DATA.edit.2}">{DATA.edit.1}</a>
                        <!-- END: edit -->
                        <!-- BEGIN: disable -->
                        <a class="btn btn-primary btn-xs pull-right" style="margin-left: 5px" href="{DATA.disable.2}">{DATA.disable.1}</a>
                        <!-- END: disable -->
                        <!-- BEGIN: delete -->
                        <a class="btn btn-primary btn-xs pull-right" href="javascript:void(0);" onclick="nv_is_del_cron('{DATA.id}', '{DATA.delete.2}');">{DATA.delete.1}</a>
                        <!-- END: delete -->
                    </div> {DATA.caption}
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.key}</td>
                <td>{ROW.value}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: crj -->
<!-- END: main -->