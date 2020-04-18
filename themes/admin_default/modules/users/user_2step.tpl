<!-- BEGIN: turnoff -->
<div class="alert alert-info">{LANG.user_2step_off}</div>
<!-- END: turnoff -->

<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{LANG.user_2step_turnoff}</strong>
    </div>
    <div class="panel-body">
        <div class="text-center">
            <form method="post" action="{FORM_ACTION}" onsubmit="return confirm(nv_is_change_act_confirm[0]);">
                <!-- BEGIN: turnoff_info -->
                <div class="alert alert-info">{LANG.user_2step_turnoff_info}</div>
                <!-- END: turnoff_info -->
                <input type="submit" name="turnoff2step" value="{LANG.user_2step_turnoff}" class="btn btn-danger"/>
            </form>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{LANG.user_2step_codes}</strong>
    </div>
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr>
                <th class="w200 twostepp">{LANG.user_2step_codes}</th>
                <th>{LANG.status}</th>
                <th class="w200">{LANG.user_2step_codes_timecreat}</th>
                <th class="w200">{LANG.user_2step_codes_timeuse}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: code -->
            <tr>
                <td class="twostepp">{CODE.code}</td>
                <td>{CODE.status}</td>
                <td>{CODE.time_creat}</td>
                <td>{CODE.time_used}</td>
            </tr>
            <!-- END: code -->
        </tbody>
    </table>
    <div class="panel-footer">
        <form method="post" action="{FORM_ACTION}" onsubmit="return confirm(nv_is_change_act_confirm[0]);">
            <p>
                <label><input type="checkbox" value="1" name="sendmail"/> {LANG.user_2step_sendmail}</label>
            </p>
            <input type="submit" name="resetbackupcodes" value="{LANG.user_2step_reset}" class="btn btn-danger"/>
        </form>
    </div>
</div>
<!-- END: main -->