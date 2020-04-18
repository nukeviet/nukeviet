<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input type="text" value="{FROM.q}" autofocus="autofocus" maxlength="64" name="q" class="form-control" placeholder="{LANG.search_key}" />
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <select name="stype" class="form-control">
                        <option value="">{LANG.search_type}</option>
                        <!-- BEGIN: search_type -->
                        <option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
                        <!-- END: search_type -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <select name="module" class="form-control">
                        <!-- BEGIN: module -->
                        <option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
                        <!-- END: module -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <select name="sstatus" class="form-control" style="margin-bottom: 10px">
                        <!-- BEGIN: search_status -->
                        <option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
                        <!-- END: search_status -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <select name="per_page" class="form-control">
                        <option value="">{LANG.search_per_page}</option>
                        <!-- BEGIN: per_page -->
                        <option value="{OPTION.page}" {OPTION.selected} >{OPTION.page}</option>
                        <!-- END: per_page -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="from_date" id="from_date" value="{FROM.from_date}" readonly="readonly" placeholder="{LANG.from_date}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="from-btn">
                                <em class="fa fa-calendar fa-fix">&nbsp;</em>
                            </button> </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="to_date" id="to_date" value="{FROM.to_date}" readonly="readonly" placeholder="{LANG.to_date}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="to-btn">
                                <em class="fa fa-calendar fa-fix">&nbsp;</em>
                            </button> </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input type="submit" value="{LANG.search}" class="btn btn-info" />
                </div>
            </div>
        </div>
        <span class="help-block">{LANG.search_note}</span>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col class="w50" />
            <col class="text-center" />
            <col class="text-center" />
            <col class="w200" />
            <col class="w100" />
            <col class="w250" />
        </colgroup>
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>{LANG.mod_name}</th>
                <th>{LANG.content}</th>
                <th>{LANG.email}</th>
                <th>{LANG.status}</th>
                <th class="text-right">{LANG.funcs}</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6">
                    <em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.checkall}</a> &nbsp;&nbsp;
                    <em class="fa fa-circle-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>
                    <div class="pull-right">
                        <em class="fa fa-exclamation-circle fa-lg">&nbsp;</em>
                        <a class="disable" href="javascript:void(0);">{LANG.disable}</a>&nbsp;&nbsp;
                        <em class="fa fa-external-link fa-lg">&nbsp;</em><a class="enable" href="javascript:void(0);">{LANG.enable}</a>&nbsp;&nbsp;
                        <em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="delete" href="javascript:void(0);">{LANG.delete}</a>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
            <!-- BEGIN: generate_page -->
            <tr>
                <td colspan="6" class="text-center">
                    <div class="text-center">
                        {GENERATE_PAGE}
                    </div>
                </td>
            </tr>
            <!-- END: generate_page -->
        </tfoot>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center"><input name="commentid" id="checkboxid" type="checkbox" value="{ROW.cid}"/></td>
                <td>{ROW.module}</td>
                <td><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
                <td>{ROW.email}</td>
                <td class="text-center">
                    <input type="checkbox" name="activecheckbox" id="change_active_{ROW.cid}" onclick="nv_change_active('{ROW.cid}')" {ROW.active}>
                </td>
                <td class="text-right">
                    <!-- BEGIN: attach -->
                    <a href="{ATTACH_LINK}" class="btn btn-default btn-xs"><i class="fa fa-paperclip fa-fw" aria-hidden="true"></i>{LANG.attach_download}</a>
                    <!-- END: attach -->
                    <a href="{ROW.linkedit}" class="btn btn-default btn-xs"><i class="fa fa-edit fa-fw" aria-hidden="true"></i>{LANG.edit}</a>
                    <a class="btn btn-danger btn-xs deleteone" href="{ROW.linkdelete}"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
var LANG = [];
LANG.nocheck = '{LANG.nocheck}';
LANG.delete_confirm = '{LANG.delete_confirm}';
</script>
<!-- END: main -->
