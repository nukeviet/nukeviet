<!-- BEGIN: main -->
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card">
    <div class="card-header">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php" method="get">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}" />
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}" />
            <div class="row mb-3">
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <input type="text" value="{$FROM.q}" autofocus="autofocus" maxlength="64" name="q" class="form-control" placeholder="{$LANG->getModule('search_key')}" />
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <select name="stype" class="form-select">
                            <option value="">{$LANG->getModule('search_type')}</option>
                            {foreach $ARRAY_SEARCH as $KEY => $VAL}
                            <!-- BEGIN: search_type -->
                            <option value="$KEY" {if $KEY == $STYPE}selected="selected"{/if}>{$VAL}</option>
                            <!-- END: search_type -->
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <select name="module" class="form-select">
                            <option value="" {if $MODULE == ''}selected="selected"{/if}>{$LANG->getModule('search_module_all')}</option>
                            {foreach $SITE_MOD_COMM as $KEY => $VAL}
                            <!-- BEGIN: module -->
                            <option value="{$KEY}" {if $KEY == $MODULE}selected="selected"{/if} >{$VAL.admin_title ?: $VAL.custom_title}</option>
                            <!-- END: module -->
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <select name="sstatus" class="form-select" style="margin-bottom: 10px">
                            {foreach $ARRAY_STATUS_VIEW as $KEY => $VAL}
                            <!-- BEGIN: search_status -->
                            <option value="{$KEY}" {if $KEY == $SSTATUS}selected="selected"{/if}>{$VAL}</option>
                            <!-- END: search_status -->
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <select name="per_page" class="form-select">
                            <option value="">{$LANG->getModule('search_per_page')}</option>
                            {assign var="I" value=15}
                            {while $I < 100}
                            {assign var="I" value=$I+5}
                            <!-- BEGIN: per_page -->
                            <option value="{$I}" {if $I == $PER_PAGE}selected="selected"{/if}>{$I}</option>
                            <!-- END: per_page -->
                            {/while}
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="from_date" id="from_date" value="{$FROM.from_date}" readonly="readonly" placeholder="{$LANG->getModule('from_date')}">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="button" id="from-btn">
                                    <em class="fa fa-calendar fa-fix">&nbsp;</em>
                                </button> </span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="to_date" id="to_date" value="{$FROM.to_date}" readonly="readonly" placeholder="{$LANG->getModule('to_date')}">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="button" id="to-btn">
                                    <em class="fa fa-calendar fa-fix">&nbsp;</em>
                                </button> </span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="form-group">
                        <input type="submit" value="{$LANG->getModule('search')}" class="btn btn-info" />
                    </div>
                </div>
            </div>
            <span class="form-text">{$LANG->getModule('search_note')}</span>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive table-card">
            <table class="table table-striped table-bordered table-hover">
                <colgroup>
                    <col style="width: 50px;"/>
                    <col class="text-center" />
                    <col class="text-center" />
                    <col style="width: 200px;" />
                    <col style="width: 100px;" />
                    <col style="width: 250px;" />
                </colgroup>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>{$LANG->getModule('mod_name')}</th>
                        <th>{$LANG->getModule('content')}</th>
                        <th>{$LANG->getModule('email')}</th>
                        <th>{$LANG->getModule('status')}</th>
                        <th class="text-right">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $ARRAY_ROW as $ROW}
                    <!-- BEGIN: loop -->
                    <tr>
                        <td class="text-center"><input name="commentid" id="checkboxid" type="checkbox" value="{$ROW.cid}" class="form-check-input"/></td>
                        <td>{$ROW.module}</td>
                        <td><a target="_blank" href="{$ROW.link}">{$ROW.title}</a></td>
                        <td>{$ROW.email}</td>
                        <td class="text-center">
                            <input type="checkbox" name="activecheckbox" id="change_active_{$ROW.cid}" onclick="nv_change_active('{$ROW.cid}')" class="form-check-input" {$ROW.active}>
                        </td>
                        <td class="text-right">
                            {if !empty($ROW.attach_link)}
                            <!-- BEGIN: attach -->
                            <a href="{$ROW.attach_link}" class="btn btn-secondary btn-sm mt-1"><i class="fa fa-paperclip fa-fw" aria-hidden="true"></i><span class="d-none d-lg-inline">{$LANG->getModule('attach_download')}</span></a>
                            <!-- END: attach -->
                            {/if}
                            <a href="{$ROW.linkedit}" class="btn btn-secondary btn-sm mt-1"><i class="fa fa-edit fa-fw" aria-hidden="true"></i><span class="d-none d-lg-inline">{$LANG->getModule('edit')}</span></a>
                            <a class="btn btn-danger btn-sm deleteone mt-1" href="{$ROW.linkdelete}"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i><span class="d-none d-lg-inline">{$LANG->getModule('delete')}</span></a>
                        </td>
                    </tr>
                    <!-- END: loop -->
                    {/foreach}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="row mt-1 mb-1">
                                <div class="col-md-6 mb-md-0 mb-2">
                                    <em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{$LANG->getModule('checkall')}</a> &nbsp;&nbsp;
                                    <em class="fa fa-circle-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{$LANG->getModule('uncheckall')}</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end">
                                        <em class="fa fa-exclamation-circle fa-lg">&nbsp;</em>
                                        <a class="disable" href="javascript:void(0);">{$LANG->getModule('disable')}</a>&nbsp;&nbsp;
                                        <em class="fa fa-external-link fa-lg">&nbsp;</em><a class="enable" href="javascript:void(0);">{$LANG->getModule('enable')}</a>&nbsp;&nbsp;
                                        <em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="delete" href="javascript:void(0);">{$LANG->getModule('delete')}</a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {if !empty($GENERATE_PAGE)}
        <!-- BEGIN: generate_page -->
        <div class="d-flex justify-content-sm-end justify-content-center">
            {$GENERATE_PAGE}
        </div>
        <!-- END: generate_page -->
        {/if}
    </div>
</div>
<script type="text/javascript">
    var LANG = [];
    LANG.nocheck = "{$LANG->getModule('nocheck')}";
    LANG.delete_confirm = "{$LANG->getModule('delete_confirm')}";
</script>
<!-- END: main -->
