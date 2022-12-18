<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script>
    var cat = '{LANG.cat}';
    var caton = '{LANG.caton}';
    var nv_lang_data = '{NV_LANG_DATA}';
</script>
<div id="tools">
    <div class="row">
        <div class="col-sm-18 col-md-19 col-lg-20 m-bottom">
            <div class="input-group w300">
                <span class="input-group-addon">{LANG.select_menu_block}</span>
                <select class="form-control change-mid">
                    <!-- BEGIN: mid -->
                    <option value="{MID.key}" {MID.sel}>{MID.title}</option>
                    <!-- END: mid -->
                </select>
            </div>
        </div>
        <div class="col-sm-6 col-md-5 col-lg-4 m-bottom">
            <button type="button" class="btn btn-primary btn-block add-menu" data-modal-title="{LANG.add_item}" data-url="{FORM_ACTION}&action=add">{LANG.add_item}</button>
        </div>
    </div>
</div>
<!-- BEGIN: table -->
<form id="menulist" method="post" action="{FORM_ACTION}" data-mid="{PAGE.mid}" data-parentid="{PAGE.parentid}" data-reload-confirm="{LANG.action_menu_reload_confirm}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center text-nowrap" style="width:1%"><input class="form-control" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" type="checkbox"></th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.number}</th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.id}</th>
                    <th class="text-center text-nowrap">{LANG.title}</th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.groups_view}</th>
                    <th class="text-center text-nowrap" style="width:1%" colspan="2">{LANG.sub_menu}</th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.display}</th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.action}</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td class="text-center text-nowrap" style="width:1%;padding-top:14px"><input class="form-control" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" type="checkbox"></td>
                    <td colspan="8">
                        <button type="button" data-error="{LANG.msgnocheck}" class="btn btn-default multi-delete">{LANG.multi_delete}</button>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: loop1 -->
                <tr class="item" data-id="{ROW.id}" data-num="{ROW.nu}">
                    <td class="text-center" style="width:1%;padding-top:14px"><input class="form-control" type="checkbox" name="idcheck[]" value="{ROW.id}" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"></td>
                    <td class="text-center" style="width:1%">
                        <select class="form-control chang_weight" style="width: auto;">
                            <!-- BEGIN: weight -->
                            <option value="{stt}" {select}>{stt}</option>
                            <!-- END: weight -->
                        </select>
                    </td>
                    <td class="text-right" style="width:1%;padding-top:14px">{ROW.id}</td>
                    <td style="padding-top:14px">
                        <!-- BEGIN: icon -->
                        <img src="{ROW.icon}" height="20px" />
                        <!-- END: icon -->
                        <!-- BEGIN: link --><a href="javascript:void(0)" data-toggle="popover" data-trigger="click" data-contents="{ROW.link}">
                            <!-- END: link -->
                            <strong>{ROW.title} </strong>
                            <!-- BEGIN: link2 -->
                        </a><!-- END: link2 -->
                    </td>
                    <td style="width:1%;padding-top:14px">
                        <ul class="list-unstyled mb-0">
                            <!-- BEGIN: group -->
                            <li class="text-nowrap">- {GROUP}</li>
                            <!-- END: group -->
                            </u>
                    </td>
                    <td style="width:1%;padding-top:14px">{ROW.sub}</td>
                    <td style="width:1%">
                        <a href="{ROW.url_title}" class="btn btn-default"><i class="fa fa-chevron-down"></i></a>
                    </td>
                    <td class="text-center" style="width:1%;padding-top:14px">
                        <input class="form-control change-active" type="checkbox" id="change_active_{ROW.id}" {ROW.active} />
                    </td>
                    <td class="text-right text-nowrap" style="width:1%">
                        <!-- BEGIN: reload -->
                        <button type="button" class="btn btn-default menu_reload" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.action_menu_reload_note}"><em class="fa fa-refresh fa-fw"></em></button>
                        <!-- END: reload -->
                        <button type="button" class="btn btn-default edit-menu" data-modal-title="{LANG.edit_menu}" data-url="{ROW.edit_url}" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.edit}"><em class="fa fa-edit fa-fw"></em></button>
                        <button type="button" class="btn btn-default item_delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.delete}"><em class="fa fa-trash-o fa-fw"></em></button>
                    </td>
                </tr>
                <!-- END: loop1 -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: table -->
<!-- BEGIN: is_empty -->
<div class="alert alert-info text-center">{LANG.no_submenu}</div>
<!-- END: is_empty -->
<!-- START FORFOOTER -->
<div id="edit"></div>
<!-- END FORFOOTER -->
<script>
    $(function() {
        $('#menulist [data-toggle="popover"]').popover({
            html: true,
            placement: 'top',
            content: function() {
                return '<a href="' + $(this).data('contents') + '" target="_blank">' + $(this).data('contents') + '</a>';
            },
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content" style="word-wrap: break-word;"></div></div>'
        });
        $('body').on('click', function(e) {
            $('#menulist [data-toggle=popover]').each(function() {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    (($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false;
                }
            });
        });
    })
</script>
<!-- END: main -->

<!-- BEGIN: row -->
<form action="{FORM_ACTION}" method="post" data-busy="false" role="dialog" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                <div class="modal-title"><strong class="action-row-title">{FORM_CAPTION}</strong></div>
            </div>
            <div class="modal-body action-row-contents" style="max-height: calc(100vh - 200px);overflow-y: auto;">
                <input type="hidden" name="id" value="{DATA.id}">
                <input type="hidden" name="mid" value="{DATA.mid}">
                <input type="hidden" name="pa" value="{DATA.parentid}">
                <input type="hidden" name="action" value="row">
                <table class="table table-striped table-bordered">
                    <colgroup>
                        <col style="width: 30%;" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td><strong>{LANG.name_block}</strong></td>
                            <td>
                                <select name="item_menu" data-parentid="{DATA.parentid}" class="form-control">
                                    <!-- BEGIN: loop -->
                                    <option value="{BLOCK.key}" {BLOCK.sel}>{BLOCK.val}</option>
                                    <!-- END: loop -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.cats}</strong></td>
                            <td>
                                <select name="parentid" id="parentid" class="form-control" style="width: 100%;">
                                    <!-- BEGIN: cat -->
                                    <option value="{CAT.key}" {CAT.selected}>{CAT.title}</option>
                                    <!-- END: cat -->
                                </select>
                            </td>
                        </tr>
                        <tr class="field">
                            <td><strong>{LANG.chomodule}</strong></td>
                            <td>
                                <select name="module_name" class="form-control" style="width: 100%;">
                                    <option value="">{LANG.no}</option>
                                    <!-- BEGIN: module -->
                                    <option value="{MODULE.key}" {MODULE.selected}>{MODULE.title}</option>
                                    <!-- END: module -->
                                </select>
                            </td>
                        </tr>
                        <!-- BEGIN: link -->
                        <tr class="field">
                            <td><strong>{LANG.cho_module_item}</strong></td>
                            <td>
                                <select name="func" class="form-control" style="width: 100%;">
                                    <option value="">{LANG.no}</option>
                                    <!-- BEGIN: item -->
                                    <option value="{ITEM.alias}" data-title="{ITEM.title}" {ITEM.selected}>{ITEM.name}</option>
                                    <!-- END: item -->
                                </select>
                            </td>
                        </tr>
                        <!-- END: link -->
                        <tr>
                            <td><strong>{LANG.title}</strong><sup class="required">*</sup></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="title" id="title" class="form-control" style="height: 32.5px;" value="{DATA.title}" maxlength="250" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default active get-title" type="button"><i class="fa fa-refresh"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.link}</strong></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="link" class="form-control" style="height: 32.5px;" value="{DATA.link}" id="link" maxlength="250" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default active get-link" type="button"><i class="fa fa-refresh"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.icon}</strong></td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="icon" id="icon" value="{DATA.icon}" maxlength="250" />
                                    <span class="input-group-btn">
                                        <input type="button" value="Browse" class="btn btn-default active selectimg" data-path="{UPLOAD_CURRENT}" data-area="icon" />
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.image}</strong></td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="image" id="image" value="{DATA.image}" maxlength="250" />
                                    <span class="input-group-btn">
                                        <input type="button" value="Browse" class="btn btn-default active selectimg" data-path="{UPLOAD_CURRENT}" data-area="image" />
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.note}</strong></td>
                            <td><input type="text" name="note" class="form-control" value="{DATA.note}" maxlength="250" /></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top"><strong> {LANG.groups_view}</strong></td>
                            <td>
                                <select name="groups_view[]" class="form-control" style="width: 100%;" multiple="multiple">
                                    <!-- BEGIN: groups_view -->
                                    <option value="{GROUPS_VIEW.key}" {GROUPS_VIEW.sel}>{GROUPS_VIEW.title}</option>
                                    <!-- END: groups_view -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.target}</strong></td>
                            <td>
                                <select name="target" class="form-control">
                                    <!-- BEGIN: target -->
                                    <option value="{TARGET.key}" {TARGET.selected}>{TARGET.title}</option>
                                    <!-- END: target -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.add_type_active}</strong></td>
                            <td>
                                <select name="active_type" class="form-control">
                                    <!-- BEGIN: active_type -->
                                    <option value="{ACTIVE_TYPE.key}" {ACTIVE_TYPE.selected}>{ACTIVE_TYPE.title}</option>
                                    <!-- END: active_type -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.add_type_css}</strong></td>
                            <td>
                                <input class="form-control" type="text" name="css" value="{DATA.css}" />
                                <div class="help-block mb-0">{LANG.add_type_css_info}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        $("#parentid, [name=module_name], [name=op], [name^=groups_view]").select2()
    })
</script>
<!-- END: row -->