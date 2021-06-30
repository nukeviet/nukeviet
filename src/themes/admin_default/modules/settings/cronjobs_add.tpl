<!-- BEGIN: main -->
<div class="alert alert-info <!-- BEGIN: error -->alert-danger<!-- END: error -->">
    {DATA.title}
</div>
<form method="post" action="{DATA.action}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w200">
                <col class="w20">
                <col class="w350">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <td>{DATA.cron_name.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input class="form-control" name="cron_name" id="cron_name" type="text" value="{DATA.cron_name.1}" maxlength="{DATA.cron_name.2}" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>{DATA.run_file.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                    <select name="run_file" class="form-control">
                        <option value="">{DATA.run_file.1}</option>
                        <!-- BEGIN: run_file -->
                        <option value="{RUN_FILE.key}"{RUN_FILE.selected}>{RUN_FILE.key}</option>
                        <!-- END: run_file -->
                    </select></td>
                    <td><span>&lArr;</span> {DATA.run_file.4}</td>
                </tr>
                <tr>
                    <td>{DATA.run_func.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input class="form-control" name="run_func_iavim" id="run_func_iavim" type="text" value="{DATA.run_func.1}" maxlength="{DATA.run_func.2}" /></td>
                    <td><span>&lArr;</span> {DATA.run_func.3}</td>
                </tr>
                <tr>
                    <td>{DATA.params.0}:</td>
                    <td>&nbsp;</td>
                    <td><input class="form-control" name="params_iavim" id="params_iavim" type="text" value="{DATA.params.1}" maxlength="{DATA.params.2}" /></td>
                    <td><span>&lArr;</span> {DATA.params.3}</td>
                </tr>
                <tr>
                    <td>{DATA.start_time.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                    <select name="hour" class="form-control pull-left" style="width: 65px">
                        <!-- BEGIN: hour -->
                        <option value="{HOUR.key}"{HOUR.selected}>{HOUR.key}</option>
                        <!-- END: hour -->
                    </select>
                    <span class="pull-left text-middle">&nbsp;{DATA.hour.0}&nbsp;</span>
                    <select name="min" class="form-control pull-left" style="width: 65px">
                        <!-- BEGIN: min -->
                        <option value="{MIN.key}"{MIN.selected}>{MIN.key}</option>
                        <!-- END: min -->
                    </select>
                    <span class="pull-left text-middle">&nbsp;{DATA.min.0}&nbsp;</span>
                    <span class="pull-left text-middle">&nbsp;{DATA.start_time.1}&nbsp;</span>
                    <input name="start_date" id="start_date" value="{DATA.start_time.2}" class="form-control pull-left" style="width: 90px;" maxlength="10" readonly="readonly" type="text" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>{DATA.interval.0}:</td>
                    <td>&nbsp;</td>
                    <td><input name="interval_iavim" id="interval_iavim" type="text" value="{DATA.interval.1}" class="w100 form-control pull-left" maxlength="{DATA.interval.2}" /><span class="text-middle">&nbsp;{DATA.interval.3}</span></td>
                    <td><span>&lArr;</span> {DATA.interval.4}</td>
                </tr>
                <tr>
                    <td>{LANG.cron_interval_type}:</td>
                    <td>&nbsp;</td>
                    <td class="form-inline">
                        <select class="form-control" name="inter_val_type">
                            <!-- BEGIN: inter_val_type -->
                            <option value="{INTER_VAL_TYPE.key}"{INTER_VAL_TYPE.selected}>{INTER_VAL_TYPE.title}</option>
                            <!-- END: inter_val_type -->
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>{DATA.del.0}:</td>
                    <td>&nbsp;</td>
                    <td><input name="del" type="checkbox" value="1"{DELETE} /></td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <input name="go_add" type="submit" value="{DATA.submit}" class="btn btn-primary">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->
