<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<div id="menu-block">
    <div class="m-bottom">
        <button type="button" class="btn btn-primary add-menu-block" data-url="{FORM_ACTION}">{LANG.add_menu}</button>
    </div>
    <div class="table-responsive">
        <!-- BEGIN: table -->
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w200">
                <col />
                <col style="width:1%">
            </colgroup>
            <thead class="bg-primary">
                <tr class="text-center">
                    <th>{LANG.name_block}</th>
                    <th>{LANG.menu}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop1 -->
                <tr>
                    <td><a href="{ROW.link_view}" title="{ROW.title}"><strong>{ROW.title}</strong></a></td>
                    <td> {ROW.menu_item} </td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <button type="button" class="btn btn-default edit-menu-block" data-url="{ROW.edit_url}" title="{LANG.edit}"><em class="fa fa-edit fa-fw"></em></button>
                        <button type="button" class="btn btn-default delete-menu-block" data-id="{ROW.id}" title="{LANG.delete}"><em class="fa fa-trash-o fa-fw"></em></button>
                    </td>
                </tr>
                <!-- END: loop1 -->
            </tbody>
        </table>
        <!-- END: table -->
    </div>
</div>
<div id="menu-block-modal"></div>
<!-- END: main -->

<!-- BEGIN: block -->
<form method="post" action="{FORM_ACTION}" role="dialog" class="modal fade">
    <input name="save" type="hidden" value="1" />
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                <div class="modal-title"><strong class="action-row-title">{FORM_CAPTION}</strong></div>
            </div>
            <div class="modal-body action-row-contents" style="max-height: calc(100vh - 200px);overflow-y: auto;">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td class="text-nowrap" style="width: 30%;"><strong>{LANG.name_block}: </strong></td>
                            <td><input class="form-control" name="title" type="text" value="{DATAFORM.title}" maxlength="255" /></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap" style="width: 30%;"><strong>{LANG.action_menu}: </strong></td>
                            <td>
                                <select name="action_menu" id="action_menu" class="form-control" style="width: 100%;">
                                    <option value="">&nbsp;</option>
                                    <option value="sys_mod">{LANG.action_menu_sys_1}</option>
                                    <option value="sys_mod_sub">{LANG.action_menu_sys_2}</option>
                                    <optgroup label="{LANG.action_menu_sys_3}">
                                        <!-- BEGIN: action_menu -->
                                        <option value="{OPTIONVALUE}">{OPTIONTITLE}</option>
                                        <!-- END: action_menu -->
                                    </optgroup>
                                </select>
                            </td>
                        <tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <input name="submit1" type="submit" value="{LANG.save}" class="btn btn-primary w100" />
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $("#action_menu").select2();
    })
</script>
<!-- END: block -->