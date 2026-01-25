<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<div class="container block-content-wrap">
    <!-- BEGIN: error -->
    <div id="edit">&nbsp;</div>
    <div class="alert alert-danger panel-block-content"><span id="message">{ERROR}</span></div>
    <!-- END: error -->
    <form class="form-horizontal" method="post" action="{FORM_ACTION}" id="block-content-form" data-page-url="{PAGE_URL}" data-selectthemes="{SELECTTHEMES}" data-load-image="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif">
        <div class="panel panel-primary panel-block-content">
            <div class="panel-heading"><strong>{THEME}&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;{PAGE_TITLE}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.of_module}:</label>
                    <div class="col-sm-18">
                        <div class="row">
                            <div class="col-sm-12">
                                <select name="module_type" class="form-control margin-bottom-sm">
                                    <option value="">{LANG.block_select_type}</option>
                                    <option value="theme" {THEME_SELECTED}>{LANG.block_type_theme}</option>
                                    <!-- BEGIN: module -->
                                    <option value="{MODULE.key}" {MODULE.selected}>{MODULE.title}</option>
                                    <!-- END: module -->
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <select name="file_name" class="form-control">
                                    {BLOCKLIST}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix" id="block_config"></div>
                <div class="form-group margin-top-lg">
                    <label class="control-label col-sm-6">{LANG.block_title}:</label>
                    <div class="col-sm-18">
                        <input class="form-control" name="title" type="text" value="{ROW.title}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_link}:</label>
                    <div class="col-sm-18">
                        <input class="form-control" name="link" type="text" value="{ROW.link}" maxlength="255" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_tpl}:</label>
                    <div class="col-sm-9">
                        <select id="template" name="template" class="form-control">
                            <option value="">{LANG.block_default}</option>
                            <!-- BEGIN: template -->
                            <option value="{TEMPLATE.key}" {TEMPLATE.selected}>{TEMPLATE.title}</option>
                            <!-- END: template -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_pos}:</label>
                    <div class="col-sm-9">
                        <select name="position" class="form-control">
                            <!-- BEGIN: position -->
                            <option value="{POSITION.key}" {POSITION.selected}>{POSITION.title}</option>
                            <!-- END: position -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.dtime_type}:</label>
                    <div class="col-sm-9">
                        <select name="dtime_type" class="form-control">
                            <!-- BEGIN: dtime_type -->
                            <option value="{DTIME_TYPE.key}" {DTIME_TYPE.sel}>{DTIME_TYPE.title}</option>
                            <!-- END: dtime_type -->
                        </select>
                    </div>
                </div>
                <div id="dtime_details">{DTIME_DETAILS}</div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.show_device}:</label>
                    <div class="col-sm-18">
                        <div class="checkbox">
                            <!-- BEGIN: active_device -->
                            <div class="clearfix">
                                <label id="active_{ACTIVE_DEVICE.key}">
                                    <input name="active_device[]" id="active_device_{ACTIVE_DEVICE.key}" type="checkbox" value="{ACTIVE_DEVICE.key}" {ACTIVE_DEVICE.checked} />&nbsp;{ACTIVE_DEVICE.title}
                                </label>
                            </div>
                            <!-- END: active_device -->
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{GLANG.groups_view}:</label>
                    <div class="col-sm-18">
                        <div class="checkbox list-group-users">
                            <!-- BEGIN: groups_list -->
                            <div class="clearfix">
                                <label>
                                    <input name="groups_view[]" type="checkbox" value="{GROUPS_LIST.key}" {GROUPS_LIST.selected} />&nbsp;{GROUPS_LIST.title}
                                </label>
                            </div>
                            <!-- END: groups_list -->
                        </div>
                    </div>
                </div>

                <!-- BEGIN: edit -->
                <div class="alert alert-info panel-block-content"><span id="message" class="clearfix"><i class="fa fa-bell-o fa-3x pull-left" style="margin-top:6px"></i> {LANG.block_group_notice}</span></div>
                <div class="form-group">
                    <div class="col-sm-18 col-sm-offset-6">
                        <div>{BLOCKS_NUM}</div>
                        <div class="checkbox">
                            <div class="clearfix">
                                <label><input type="checkbox" value="1" name="leavegroup" />{LANG.block_leavegroup}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: edit -->

                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.add_block_module}:</label>
                    <div class="col-sm-18">
                        <div class="radio">
                            <!-- BEGIN: add_block_module -->
                            <label id="labelmoduletype{I}" {SHOWSDISPLAY}> <input type="radio" name="all_func" class="moduletype{I}" value="{B_KEY}" {CK} /> {B_VALUE} </label>
                            <!-- END: add_block_module -->
                        </div>
                    </div>
                </div>

                <div id="shows_all_func" {SHOWS_ALL_FUNC}>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-nowrap" style="vertical-align: middle">
                                    {LANG.block_select_module}
                                </th>
                                <th style="vertical-align: middle">
                                    {LANG.block_select_function}
                                    <button type="button" name="checkallmod" class="btn btn-default btn-xs pull-right"><i class="fa fa-check fa-fw"></i>{LANG.block_checkall}</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loopfuncs -->
                            <tr class="funclist" id="idmodule_{M_TITLE}">
                                <td class="text-nowrap">
                                    <label><input {M_CHECKED} type="checkbox" value="{M_TITLE}" class="form-control checkmodule" /> <strong>{M_CUSTOM_TITLE}</strong></label>
                                </td>
                                <td>
                                    <div class="row">
                                        <!-- BEGIN: fuc -->
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="ellipsis">
                                                <label title="{FUNCNAME}"><input type="checkbox" {SELECTED} name="func_id[]" value="{FUNCID}" class="form-control" /> {FUNCNAME}</label>
                                            </div>
                                        </div>
                                        <!-- END: fuc -->
                                    </div>
                                </td>
                            </tr>
                            <!-- END: loopfuncs -->
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.status}:</label>
                    <div class="col-sm-9">
                        <div class="radio">
                            <label><input type="radio" name="act" value="1" {ROW.is_act} /> {LANG.act_1}</label>&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="act" value="0" {ROW.is_deact} /> {LANG.act_0}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default panel-block-content-last">
            <div class="panel-body text-center">
                <input type="hidden" name="bid" value="{ROW.bid}" />
                <input type="hidden" name="checkss" value="{ROW.checkss}" />
                <input type="submit" name="confirm" value="{LANG.block_confirm}" class="btn btn-primary" />
                <input type="button" onclick="window.close()" value="{LANG.back}" class="btn btn-default" />
            </div>
        </div>
    </form>
</div>
<script src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="{NV_BASE_SITEURL}themes/admin_default/js/block_content.js"></script>
</div>
<!-- END: main -->

<!-- BEGIN: dtime_details -->
<div class="form-group">
    <label class="control-label col-sm-6">{LANG.dtime_details}:</label>
    <div class="col-sm-18 dtime">
        <!-- BEGIN: dtime_type_specific -->
        <!-- BEGIN: loop -->
        <div class="dtime_details">
            <input type="text" name="start_date[]" class="form-control date w100" value="{DTIME_TYPE_ONCE.start_date}" maxlength="4">
            <select name="start_h[]" class="form-control w50">
                <!-- BEGIN: start_h -->
                <option value="{START_H.val}" {START_H.sel}>{START_H.name}</option>
                <!-- END: start_h -->
            </select>
            <select name="start_i[]" class="form-control w50">
                <!-- BEGIN: start_i -->
                <option value="{START_I.val}" {START_I.sel}>{START_I.name}</option>
                <!-- END: start_i -->
            </select>
            <span>-</span>
            <input type="text" name="end_date[]" class="form-control date w100" value="{DTIME_TYPE_ONCE.end_date}" maxlength="4">
            <select name="end_h[]" class="form-control w50">
                <!-- BEGIN: end_h -->
                <option value="{END_H.val}" {END_H.sel}>{END_H.name}</option>
                <!-- END: end_h -->
            </select>
            <select name="end_i[]" class="form-control w50">
                <!-- BEGIN: end_i -->
                <option value="{END_I.val}" {END_I.sel}>{END_I.name}</option>
                <!-- END: end_i -->
            </select>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default active del_dtime">-</button>
                <button type="button" class="btn btn-default active add_dtime">+</button>
            </div>
        </div>
        <!-- END: loop -->
        <div class="help-block">{LANG.dtime_type_specific_note}</div>
        <script>
            $(function(){$('.date').datepicker({dateFormat:"dd/mm/yy",changeMonth:true,changeYear:true,showOtherMonths:true})})
        </script>
        <!-- END: dtime_type_specific -->
        <!-- BEGIN: dtime_type_daily -->
        <!-- BEGIN: loop -->
        <div class="dtime_details">
            <select name="start_h[]" class="form-control w50">
                <!-- BEGIN: start_h -->
                <option value="{START_H.val}" {START_H.sel}>{START_H.name}</option>
                <!-- END: start_h -->
            </select>
            <select name="start_i[]" class="form-control w50">
                <!-- BEGIN: start_i -->
                <option value="{START_I.val}" {START_I.sel}>{START_I.name}</option>
                <!-- END: start_i -->
            </select>
            <span>-</span>
            <select name="end_h[]" class="form-control w50">
                <!-- BEGIN: end_h -->
                <option value="{END_H.val}" {END_H.sel}>{END_H.name}</option>
                <!-- END: end_h -->
            </select>
            <select name="end_i[]" class="form-control w50">
                <!-- BEGIN: end_i -->
                <option value="{END_I.val}" {END_I.sel}>{END_I.name}</option>
                <!-- END: end_i -->
            </select>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default active del_dtime">-</button>
                <button type="button" class="btn btn-default active add_dtime">+</button>
            </div>
        </div>
        <!-- END: loop -->
        <div class="help-block">{LANG.dtime_type_daily_note}</div>
        <!-- END: dtime_type_daily -->
        <!-- BEGIN: dtime_type_weekly -->
        <!-- BEGIN: loop -->
        <div class="dtime_details">
            <select name="day_of_week[]" class="form-control w50">
                <!-- BEGIN: day_of_week -->
                <option value="{DAY_OF_WEEK.val}" {DAY_OF_WEEK.sel}>{DAY_OF_WEEK.name}</option>
                <!-- END: day_of_week -->
            </select>
            <span>: </span>
            <select name="start_h[]" class="form-control w50">
                <!-- BEGIN: start_h -->
                <option value="{START_H.val}" {START_H.sel}>{START_H.name}</option>
                <!-- END: start_h -->
            </select>
            <select name="start_i[]" class="form-control w50">
                <!-- BEGIN: start_i -->
                <option value="{START_I.val}" {START_I.sel}>{START_I.name}</option>
                <!-- END: start_i -->
            </select>
            <span>-</span>
            <select name="end_h[]" class="form-control w50">
                <!-- BEGIN: end_h -->
                <option value="{END_H.val}" {END_H.sel}>{END_H.name}</option>
                <!-- END: end_h -->
            </select>
            <select name="end_i[]" class="form-control w50">
                <!-- BEGIN: end_i -->
                <option value="{END_I.val}" {END_I.sel}>{END_I.name}</option>
                <!-- END: end_i -->
            </select>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default active del_dtime">-</button>
                <button type="button" class="btn btn-default active add_dtime">+</button>
            </div>
        </div>
        <!-- END: loop -->
        <div class="help-block">{LANG.dtime_type_weekly_note}</div>
        <!-- END: dtime_type_weekly -->
        <!-- BEGIN: dtime_type_monthly -->
        <!-- BEGIN: loop -->
        <div class="dtime_details">
            <select name="day[]" class="form-control w50">
                <!-- BEGIN: day -->
                <option value="{DAY.val}" {DAY.sel}>{DAY.val}</option>
                <!-- END: day -->
            </select>
            <span>: </span>
            <select name="start_h[]" class="form-control w50">
                <!-- BEGIN: start_h -->
                <option value="{START_H.val}" {START_H.sel}>{START_H.name}</option>
                <!-- END: start_h -->
            </select>
            <select name="start_i[]" class="form-control w50">
                <!-- BEGIN: start_i -->
                <option value="{START_I.val}" {START_I.sel}>{START_I.name}</option>
                <!-- END: start_i -->
            </select>
            <span>-</span>
            <select name="end_h[]" class="form-control w50">
                <!-- BEGIN: end_h -->
                <option value="{END_H.val}" {END_H.sel}>{END_H.name}</option>
                <!-- END: end_h -->
            </select>
            <select name="end_i[]" class="form-control w50">
                <!-- BEGIN: end_i -->
                <option value="{END_I.val}" {END_I.sel}>{END_I.name}</option>
                <!-- END: end_i -->
            </select>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default active del_dtime">-</button>
                <button type="button" class="btn btn-default active add_dtime">+</button>
            </div>
        </div>
        <!-- END: loop -->
        <div class="help-block">{LANG.dtime_type_monthly_note}</div>
        <!-- END: dtime_type_monthly -->
        <!-- BEGIN: dtime_type_yearly -->
        <!-- BEGIN: loop -->
        <div class="dtime_details">
            <select name="month[]" class="form-control w50">
                <!-- BEGIN: month -->
                <option value="{MONTH.val}" {MONTH.sel}>{MONTH.val}</option>
                <!-- END: month -->
            </select>
            <select name="day[]" class="form-control w50">
                <!-- BEGIN: day -->
                <option value="{DAY.val}" {DAY.sel}>{DAY.val}</option>
                <!-- END: day -->
            </select>
            <span>: </span>
            <select name="start_h[]" class="form-control w50">
                <!-- BEGIN: start_h -->
                <option value="{START_H.val}" {START_H.sel}>{START_H.name}</option>
                <!-- END: start_h -->
            </select>
            <select name="start_i[]" class="form-control w50">
                <!-- BEGIN: start_i -->
                <option value="{START_I.val}" {START_I.sel}>{START_I.name}</option>
                <!-- END: start_i -->
            </select>
            <span>-</span>
            <select name="end_h[]" class="form-control w50">
                <!-- BEGIN: end_h -->
                <option value="{END_H.val}" {END_H.sel}>{END_H.name}</option>
                <!-- END: end_h -->
            </select>
            <select name="end_i[]" class="form-control w50">
                <!-- BEGIN: end_i -->
                <option value="{END_I.val}" {END_I.sel}>{END_I.name}</option>
                <!-- END: end_i -->
            </select>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default active del_dtime">-</button>
                <button type="button" class="btn btn-default active add_dtime">+</button>
            </div>
        </div>
        <!-- END: loop -->
        <div class="help-block">{LANG.dtime_type_yearly_note}</div>
        <!-- END: dtime_type_yearly -->
    </div>
</div>
<!-- END: dtime_details -->