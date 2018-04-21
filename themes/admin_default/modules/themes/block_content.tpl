<!-- BEGIN: head -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript">
//<![CDATA[
var htmlload = '<div class="text-center"><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif"/></div>';
//]]>
</script>
<!-- END: head -->

<!-- BEGIN: main -->
<div class="container block-content-wrap">
    <!-- BEGIN: block_group_notice -->
    <div class="alert alert-danger panel-block-content"><span id="message">{LANG.block_group_notice}</span></div>
    <!-- END: block_group_notice -->
    <!-- BEGIN: error -->
    <div id="edit">&nbsp;</div>
    <div class="alert alert-danger panel-block-content"><span id="message">{ERROR}</span></div>
    <!-- END: error -->
    <form class="form-horizontal" method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;selectthemes={SELECTTHEMES}&amp;blockredirect={BLOCKREDIRECT}">
        <div class="panel panel-default panel-block-content">
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_type}:</label>
                    <div class="col-sm-18">
                        <div class="row">
                            <div class="col-sm-12">
                                <select name="module_type" class="form-control margin-bottom-sm">
                                    <option value="">{LANG.block_select_type}</option>
                                    <option value="theme"{THEME_SELECTED}>{LANG.block_type_theme}</option>
                                    <!-- BEGIN: module -->
                                    <option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
                                    <!-- END: module -->
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <select name="file_name" class="form-control">
                                    <option value="">{LANG.block_select}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix" id="block_config"></div>
                <div class="form-group margin-top-lg">
                    <label class="control-label col-sm-6">{LANG.block_title}:</label>
                    <div class="col-sm-18">
                        <input class="form-control" name="title" type="text" value="{ROW.title}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_link}:</label>
                    <div class="col-sm-18">
                        <input class="form-control" name="link" type="text" value="{ROW.link}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_tpl}:</label>
                    <div class="col-sm-9">
                        <select id="template" name="template" class="form-control">
                            <option value="">{LANG.block_default}</option>
                            <!-- BEGIN: template -->
                            <option value="{TEMPLATE.key}"{TEMPLATE.selected}>{TEMPLATE.title}</option>
                            <!-- END: template -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_pos}:</label>
                    <div class="col-sm-9">
                        <select name="position" class="form-control">
                            <!-- BEGIN: position -->
                            <option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
                            <!-- END: position -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_exp_time}:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input name="exp_time" id="exp_time" value="{ROW.exp_time}" maxlength="10" type="text" class="form-control" placeholder="dd/mm/yyyy"/>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default" id="exp_time_btn">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.show_device}:</label>
                    <div class="col-sm-18">
                        <div class="checkbox">
                            <!-- BEGIN: active_device -->
                            <div class="clearfix">
                                <label id="active_{ACTIVE_DEVICE.key}">
                                    <input name="active_device[]" id="active_device_{ACTIVE_DEVICE.key}" type="checkbox" value="{ACTIVE_DEVICE.key}"{ACTIVE_DEVICE.checked}/>&nbsp;{ACTIVE_DEVICE.title}
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
                                    <input name="groups_view[]" type="checkbox" value="{GROUPS_LIST.key}"{GROUPS_LIST.selected}/>&nbsp;{GROUPS_LIST.title}
                                </label>
                            </div>
                            <!-- END: groups_list -->
                        </div>
                    </div>
                </div>
                <!-- BEGIN: edit -->
                <div class="form-group">
                    <label class="control-label col-sm-6">{LANG.block_groupbl} <span class="text-danger"><strong>{ROW.bid}</strong></span>:</label>
                    <div class="col-sm-18">
                        <div class="checkbox">
                            <div class="clearfix">
                                <label><input type="checkbox" value="1" name="leavegroup"/>{LANG.block_leavegroup} ({BLOCKS_NUM} {LANG.block_count})</label>
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
                            <label id="labelmoduletype{I}"{SHOWSDISPLAY}> <input type="radio" name="all_func" class="moduletype{I}" value="{B_KEY}"{CK}/> {B_VALUE} </label>
                            <!-- END: add_block_module -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-block-content" {SHOWS_ALL_FUNC} id="shows_all_func">
            <div class="panel-heading">
                {LANG.block_function} <button type="button" name="checkallmod" class="btn btn-default btn-xs"><i class="fa fa-check fa-fw"></i>{LANG.block_check}</button>
            </div>
            <div class="list-group">
                <!-- BEGIN: loopfuncs -->
                <div class="list-group-item funclist" id="idmodule_{M_TITLE}">
                    <dl class="dl-horizontal">
                        <dt>
                            <div class="ellipsis text-left">
                                <label><input {M_CHECKED} type="checkbox" value="{M_TITLE}" class="checkmodule"/> <strong>{M_CUSTOM_TITLE}</strong></label>
                            </div>
                        </dt>
                        <dd>
                            <div class="row">
                                <!-- BEGIN: fuc -->
                                <div class="col-xs-12 col-sm-6">
                                    <div class="ellipsis">
                                        <label title="{FUNCNAME}"><input type="checkbox"{SELECTED} name="func_id[]" value="{FUNCID}" /> {FUNCNAME}</label>
                                    </div>
                                </div>
                                <!-- END: fuc -->
                            </div>
                        </dd>
                    </dl>


                </div>
                <!-- END: loopfuncs -->
            </div>
        </div>
        <div class="panel panel-default panel-block-content-last">
            <div class="panel-body text-center">
                <input type="hidden" name="bid" value="{ROW.bid}" />
                <input type="submit" name="confirm" value="{LANG.block_confirm}" class="btn btn-primary" />
                <input type="button" onclick="window.close()" value="{LANG.back}" class="btn btn-default" />
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var bid = parseInt('{ROW.bid}');
    var bid_module = '{ROW.module}';
    var selectthemes = '{SELECTTHEMES}';
    var lang_block_no_func = '{LANG.block_no_func}';
    var lang_block_error_nogroup = '{LANG.block_error_nogroup}';
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/block_content.js"></script>
</div>
<!-- END: main -->
<!-- BEGIN: blockredirect -->
<script type="text/javascript">
    alert('{BLOCKMESS}');
    <!-- BEGIN: redirect -->
    window.opener.location.href = '{BLOCKREDIRECT}';
    <!-- END: redirect -->
    <!-- BEGIN: refresh -->
    window.opener.location.href = window.opener.location.href
    <!-- END: refresh -->
    window.opener.focus();
    window.close();
</script>
<!-- END: blockredirect -->