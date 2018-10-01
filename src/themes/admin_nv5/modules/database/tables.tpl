<form name="myform" method="post" action="{$FORM_ACTION}" onsubmit="nv_chsubmit(this, 'tables[]');return false;">
    <div class="card card-table card-footer-nav">
        <div class="card-header">
            {$CAPTION}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="db-tables">
                    <thead>
                        <tr>
                            <th style="width:1%;" class="text-nowrap noselect">
                                <label class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'tables[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </th>
                            <th class="text-nowrap">{$LANG->get('table_name')}</th>
                            <th class="text-nowrap">{$LANG->get('table_size')}</th>
                            <th class="text-nowrap">{$LANG->get('table_max_size')}</th>
                            <th class="text-nowrap">{$LANG->get('table_datafree')}</th>
                            <th class="text-nowrap">{$LANG->get('table_numrow')}</th>
                            <th class="text-nowrap">{$LANG->get('table_charset')}</th>
                            <th class="text-nowrap">{$LANG->get('table_type')}</th>
                            <th class="text-nowrap">{$LANG->get('table_auto_increment')}</th>
                            <th class="text-nowrap">{$LANG->get('table_create_time')}</th>
                            <th class="text-nowrap">{$LANG->get('table_update_time')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$TABLES key=key item=tinfo}
                        <tr>
                            <td>
                                <label class="custom-control custom-control-sm custom-checkbox noselect">
                                    <input class="custom-control-input" type="checkbox" name="tables[]" value="{$key}" onclick="nv_UncheckAll(this.form, 'tables[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </td>
                            {foreach from=$tinfo item=value}
                            <td>{$value}</td>
                            {/foreach}
                        </tr>
                        {/foreach}
                        <tr>
                            <td>
                                <label class="custom-control custom-control-sm custom-checkbox noselect">
                                    <input class="custom-control-input" type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'tables[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </td>
                            <td colspan="10"><strong>{$SUMMARY}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="page-tools form-inline">
                <select class="form-control form-control-sm mr-2 my-1" id="op_name" name="{$NV_OP_VARIABLE}" onchange="nv_checkForm();">
                    <option value="download">{$LANG->get('download')}</option>
                    <option value="savefile">{$LANG->get('savefile')}</option>
                    <option value="optimize">{$LANG->get('optimize')}</option>
                </select>
                <select class="form-control form-control-sm mr-2 my-1" id="type_name" name="type">
                    <option value="all">{$LANG->get('download_all')}</option>
                    <option value="str">{$LANG->get('download_str')}</option>
                </select>
                <select class="form-control form-control-sm mr-2 my-1" id="ext_name" name="ext">
                    <option value="sql">{$LANG->get('ext_sql')}</option>
                    <option value="gz">{$LANG->get('ext_gz')}</option>
                </select>
                <input name="checkss" type="hidden" value="{$NV_CHECK_SESSION}" />
                <input name="Submit1" id="subm_form" type="submit" value="{$LANG->get('submit')}" class="btn btn-primary btn-input-sm" />
            </div>
        </div>
    </div>
</form>
<script>
    $('#db-tables').tshift();
</script>
