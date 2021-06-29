<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <col style="width:80px;white-space:nowrap" />
        <thead>
            <tr>
                <th>{LANG.funcs_subweight}</th>
                <th>{LANG.funcs_in_submenu}</th>
                <th>{LANG.funcs_title}</th>
                <!-- BEGIN: change_alias_head -->
                <th>{LANG.funcs_alias}</th>
                <!-- END: change_alias_head -->
                <th>{LANG.custom_title}</th>
                <th>{LANG.site_title}</th>
                <th>{LANG.funcs_layout}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>
                    <select name="change_weight_{ROW.func_id}" id="change_weight_{ROW.func_id}" onchange="nv_chang_func_weight({ROW.func_id});" class="form-control">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.key}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td><input name="chang_func_in_submenu_{ROW.func_id}" id="chang_func_in_submenu_{ROW.func_id}" type="checkbox" value="1" onclick="nv_chang_func_in_submenu({ROW.func_id})" {ROW.in_submenu_checked} {ROW.disabled} /></td>
                <td>{ROW.func_name}</td>
                <!-- BEGIN: change_alias -->
                <td><a href="#" onclick="nv_funChange({ROW.func_id}, 'change_alias', event);">{ROW.alias}</a></td>
                <!-- END: change_alias -->
                <!-- BEGIN: show_alias -->
                <td>{ROW.alias}</td>
                <!-- END: show_alias -->
                <td><a href="#" onclick="nv_funChange({ROW.func_id}, 'change_custom_name', event);">{ROW.func_custom_name}</a></td>
                <td><a href="#" onclick="nv_funChange({ROW.func_id}, 'change_site_title', event);">{ROW.func_site_title}</a></td>
                <td>{ROW.layout}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="funChange" tabindex="-1" role="dialog" aria-labelledby="funChange-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="funChange-label"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="funChange-name" class="control-label" id="funChange-title"></label>
                    <input type="text" class="form-control" name="newvalue" value="" maxlength="" id="funChange-name">
					<input type="hidden" name="type" value="" id="funChange-type">
					<input type="hidden" name="id" value="" id="funChange-id">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="nv_funChangeSubmit(event);">{GLANG.submit}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->