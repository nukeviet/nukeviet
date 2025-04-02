<!-- BEGIN: main -->
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card">
    <div class="card-header">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php" method="get">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="row mb-3">
                <div class="col-6 col-md-3">
                    <input type="text" value="{$FROM.q}" autofocus="autofocus" maxlength="64" name="q" class="form-control" placeholder="{$LANG->getModule('search_key')}" />
                </div>
                <div class="col-6 col-md-3">
                    <select name="stype" class="form-select">
                        <option value="">{$LANG->getModule('search_type')}</option>
                        {foreach $ARRAY_SEARCH as $KEY => $VAL}
                        <option value="$KEY" {if $KEY == $STYPE}selected="selected"{/if}>{$VAL}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="module" class="form-select">
                        <option value="" {if $MODULE == ''}selected="selected"{/if}>{$LANG->getModule('search_module_all')}</option>
                        {foreach $SITE_MOD_COMM as $KEY => $VAL}
                        <option value="{$KEY}" {if $KEY == $MODULE}selected="selected"{/if} >{$VAL.admin_title ?: $VAL.custom_title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="sstatus" class="form-select" style="margin-bottom: 10px">
                        {foreach $ARRAY_STATUS_VIEW as $KEY => $VAL}
                        <option value="{$KEY}" {if $KEY == $SSTATUS}selected="selected"{/if}>{$VAL}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="per_page" class="form-select">
                        <option value="">{$LANG->getModule('search_per_page')}</option>
                        {assign var="I" value=15}
                        {while $I < 100}
                        {assign var="I" value=$I+5}
                        <option value="{$I}" {if $I == $PER_PAGE}selected="selected"{/if}>{$I}</option>
                        {/while}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="from_date" id="from_date" value="{$FROM.from_date}" readonly="readonly" placeholder="{$LANG->getModule('from_date')}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="button" id="from-btn">
                                <i class="fa-solid fa-calendar">&nbsp;</i>
                            </button> </span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="to_date" id="to_date" value="{$FROM.to_date}" readonly="readonly" placeholder="{$LANG->getModule('to_date')}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="button" id="to-btn">
                                <i class="fa-solid fa-calendar">&nbsp;</i>
                            </button> </span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <input type="submit" value="{$LANG->getModule('search')}" class="btn btn-info" />
                </div>
            </div>
            <span class="form-text">{$LANG->getModule('search_note')}</span>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-cmt-items">
            <table class="table table-striped align-middle table-sticky mb-0">
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
                        <th class="text-nowrap"><input data-toggle="checkAll" name="checkAll[]" type="checkbox" class="form-check-input"/></th>
                        <th class="text-nowrap">{$LANG->getModule('mod_name')}</th>
                        <th class="text-nowrap">{$LANG->getModule('content')}</th>
                        <th class="text-nowrap">{$LANG->getModule('email')}</th>
                        <th class="text-nowrap">{$LANG->getModule('status')}</th>
                        <th class="text-nowrap">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $ARRAY_ROW as $ROW}
                    <tr>
                        <td><input data-toggle="checkSingle" name="checkSingle[]" type="checkbox" value="{$ROW.cid}" class="form-check-input m-0 align-middle"/></td>
                        <td>{$ROW.module}</td>
                        <td><a target="_blank" href="{$ROW.link}">{$ROW.title}</a></td>
                        <td>{$ROW.email}</td>
                        <td class="text-center">
                            <input type="checkbox" name="activecheckbox" id="change_active_{$ROW.cid}" onclick="nv_change_active('{$ROW.cid}')" class="form-check-input m-0 align-middle" {$ROW.active}>
                        </td>
                        <td class="text-right">
                            {if !empty($ROW.attach_link)}
                            <a href="{$ROW.attach_link}" class="btn btn-secondary btn-sm mt-1" title="{$LANG->getModule('attach_download')}"><i class="fa-solid fa-paperclip fa-fw" aria-hidden="true"></i><span class="d-none d-xl-inline">{$LANG->getModule('attach_download')}</span></a>
                            {/if}
                            <a href="{$ROW.linkedit}" class="btn btn-secondary btn-sm mt-1" title="{$LANG->getModule('edit')}"><i class="fa-solid fa-edit fa-fw" aria-hidden="true"></i><span class="d-none d-xl-inline">{$LANG->getModule('edit')}</span></a>
                            <a class="btn btn-danger btn-sm deleteone mt-1" href="{$ROW.linkdelete}" title="{$LANG->getModule('delete')}"><i class="fa-solid fa-trash fa-fw" aria-hidden="true"></i><span class="d-none d-xl-inline">{$LANG->getModule('delete')}</span></a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <div class="input-group me-1 my-1">
                    <select id="element_action" class="form-select fw-150" aria-label="{$LANG->getGlobal('select_actions')}" aria-describedby="element_action_btn">
                        <option value="disable">{$LANG->getModule('disable')}</option>
                        <option value="enable">{$LANG->getModule('enable')}</option>
                        <option value="delete">{$LANG->getModule('delete')}</option>
                    </select>
                    <button class="btn btn-primary" type="button" id="element_action_btn" data-ctn="#list-cmt-items">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
            {if !empty($GENERATE_PAGE)}
            <div class="pagination-wrap">
                {$GENERATE_PAGE}
            </div>
            {/if}
        </div>
    </div>
</div>
