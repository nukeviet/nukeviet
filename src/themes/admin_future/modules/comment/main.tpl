<!-- BEGIN: main -->
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card" id="cmt-main">
    <div class="card-header">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php" method="get">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="row mb-3 g-2">
                <div class="col-6 col-md-3">
                    <input type="text" value="{$FROM.q}" maxlength="64" name="q" class="form-control" placeholder="{$LANG->getModule('search_key')}" aria-label="{$LANG->getModule('search_key')}">
                </div>
                <div class="col-6 col-md-3">
                    <select name="stype" class="form-select" aria-label="{$LANG->getModule('search_type')}"
                        <option value="">{$LANG->getModule('search_type')}</option>
                        {foreach $ARRAY_SEARCH as $KEY => $VAL}
                        <option value="{$KEY}" {if $KEY == $STYPE}selected="selected"{/if}>{$VAL}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="module" class="form-select" aria-label="{$LANG->getModule('search_module')}">
                        <option value="" {if $MODULE == ''}selected="selected"{/if}>{$LANG->getModule('search_module_all')}</option>
                        {foreach $SITE_MOD_COMM as $KEY => $VAL}
                        <option value="{$KEY}" {if $KEY == $MODULE}selected="selected"{/if} >{$VAL.admin_title ?: $VAL.custom_title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="sstatus" class="form-select" aria-label="{$LANG->getModule('search_status')}">
                        {foreach $ARRAY_STATUS_VIEW as $KEY => $VAL}
                        <option value="{$KEY}" {if $KEY == $SSTATUS}selected="selected"{/if}>{$VAL}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="per_page" class="form-select" aria-label="{$LANG->getModule('search_per_page')}">
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
                        <input type="text" class="form-control" name="from_date" id="from_date" value="{$FROM.from_date}" readonly="readonly" placeholder="{$LANG->getModule('from_date')}" aria-label="{$LANG->getModule('from_date')}" aria-describedby="from-btn">
                        <button class="btn btn-secondary" type="button" id="from-btn" aria-label="{$LANG->getModule('from_date')}">
                            <i class="fa-solid fa-calendar"></i>
                        </button>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="to_date" id="to_date" value="{$FROM.to_date}" readonly="readonly" placeholder="{$LANG->getModule('to_date')}" aria-label="{$LANG->getModule('to_date')}" aria-describedby="to-btn">
                        <button class="btn btn-secondary" type="button" id="to-btn" aria-label="{$LANG->getModule('to_date')}">
                            <i class="fa-solid fa-calendar"></i>
                        </button>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <button class="btn btn-info">{$LANG->getModule('search')}</button>
                </div>
            </div>
            <span class="form-text">{$LANG->getModule('search_note')}</span>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-cmt-items">
            <table class="table table-striped align-middle table-sticky mb-0">
                <colgroup>
                    <col style="width: 1%;">
                    <col style="width: 10%;">
                    <col style="width: 40%;">
                    <col style="width: 25%;">
                    <col style="width: 5%;">
                    <col style="width: 20%;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-nowrap"><input data-toggle="checkAll" name="checkAll[]" type="checkbox" class="form-check-input"></th>
                        <th class="text-nowrap">{$LANG->getModule('mod_name')}</th>
                        <th class="text-nowrap">{$LANG->getModule('content')}</th>
                        <th class="text-nowrap">{$LANG->getModule('email')}</th>
                        <th class="text-nowrap">{$LANG->getModule('status')}</th>
                        <th class="text-nowrap">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $ARRAY_ROW as $ROW}
                    {append var="ROW" value=$ROW.content|strip_tags|nv_clean60:255 index="title"}
                    <tr>
                        <td><input data-toggle="checkSingle" name="checkSingle[]" type="checkbox" value="{$ROW.cid}" class="form-check-input m-0 align-middle"></td>
                        <td>{$ROW.module}</td>
                        <td><a target="_blank" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$ROW.module}&amp;{$smarty.const.NV_OP_VARIABLE}=view&amp;area={$ROW.area}&amp;id={$ROW.id}">{$ROW.title}</a></td>
                        <td>{if $ROW.userid > 0}<a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=users&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;userid={$ROW.userid}">{$ROW.post_email}</a>{/if}</td>
                        <td class="text-center">
                            <input type="checkbox" name="activecheckbox" id="change_active_{$ROW.cid}" onclick="nv_change_active('{$ROW.cid}')" class="form-check-input m-0 align-middle"{if $ROW.status} checked="checked"{/if}>
                        </td>
                        <td class="text-right">
                            {if !empty($ROW.attach)}
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;downloadfile={($smarty.const.NV_BASE_SITEURL|cat:$smarty.const.NV_UPLOADS_DIR:'/':$MODULE_UPLOAD:'/':$ROW.attach)|urlencode}" class="btn btn-secondary btn-sm mt-1" title="{$LANG->getModule('attach_download')}"><i class="fa-solid fa-paperclip fa-fw" aria-hidden="true"></i><span class="d-none d-xl-inline">{$LANG->getModule('attach_download')}</span></a>
                            {/if}
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;cid={$ROW.cid}" class="btn btn-secondary btn-sm mt-1" title="{$LANG->getModule('edit')}"><i class="fa-solid fa-pencil fa-fw" aria-hidden="true"></i><span class="d-none d-xl-inline">{$LANG->getModule('edit')}</span></a>
                            <a class="btn btn-danger btn-sm deleteone mt-1" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=del&amp;list={$ROW.cid}" title="{$LANG->getModule('delete')}"><i class="fa-solid fa-trash fa-fw" aria-hidden="true"></i><span class="d-none d-xl-inline">{$LANG->getModule('delete')}</span></a>
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
