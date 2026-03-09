<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div id="logs" data-page-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" data-checkss="{$CHECKSS}">
    <div class="card">
        <div class="card-header">
            <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">
                <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
                <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4 col-xxl-2">
                        <div class="form-group">
                            <div class="input-group w-100 flex-nowrap">
                                <span class="input-group-text" title="{$LANG->getModule('api_role')}"><i class="fa-solid fa-object-group"></i></span>
                                <select class="form-select role-id" name="role_id" id="element_role_id">
                                    <option value="0">{$LANG->getModule('api_role_select')}</option>
                                    {foreach $ROLES as $ROLE_ID => $ROLE}
                                    <option value="{$ROLE_ID}" {if $ROLE_ID == $GET_DATA.role_id}selected="selected"{/if}>{$ROLE}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xxl-2">
                        <div class="form-group">
                            <div class="input-group w-100 flex-nowrap">
                                <span class="input-group-text" title="API"><i class="fa-solid fa-terminal"></i></span>
                                <select class="form-select command" name="command">
                                    <option value="">{$LANG->getModule('api_select')}</option>
                                    {foreach $APIS as $COMMAND}
                                    <option value="{$COMMAND}" {if $COMMAND == $GET_DATA.command}selected="selected"{/if}>{$COMMAND}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xxl-2">
                        <div class="form-group">
                            <div class="input-group w-100 flex-nowrap">
                                <span class="input-group-text" title="{$LANG->getModule('api_role_object')}"><i class="fa-solid fa-user"></i></span>
                                <select class="form-select userid" name="userid" data-placeholder="{$LANG->getModule('api_role_object')}">
                                    {if !empty($GET_DATA.userid)}
                                    <option value="{$GET_DATA.userid}" selected="selected">{$GET_DATA.username}</option>
                                    {/if}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xxl-2">
                        <div class="form-group">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" title="{$LANG->getModule('fromdate')}"><i class="fa-solid fa-calendar"></i></span>
                                <input type="text" class="form-control fromdate" name="fromdate" value="{$GET_DATA.fromdate}" maxlength="10" placeholder="{$LANG->getModule('fromdate')}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xxl-2">
                        <div class="form-group">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" title="{$LANG->getModule('todate')}"><i class="fa-solid fa-calendar"></i></span>
                                <input type="text" class="form-control todate" name="todate" value="{$GET_DATA.todate}" maxlength="10" placeholder="{$LANG->getModule('todate')}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100 w-100">{$LANG->getModule('filter_logs')}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {if !empty($DATA)}
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle list mb-1" data-delete-confirm="{$LANG->getModule('log_del_confirm')}">
                    <thead>
                        {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                        <th style="width: 1%;"><input type="checkbox" name="checkAll" class="form-check-input checkall" data-toggle="checkAll" aria-label="{$LANG->getGlobal('toggle_checkall')}"></th>
                        {/if}
                        <th class="text-center text-nowrap" style="width: 10%;">{$LANG->getModule('log_time')}</th>
                        <th class="text-nowrap" style="width: 50%;">{$LANG->getModule('api_role')}</th>
                        <th class="text-center text-nowrap" style="width: 15%;">API</th>
                        <th class="text-center text-nowrap" style="width: 15%;">{$LANG->getModule('api_role_object')}</th>
                        <th class="text-center text-nowrap" style="width: 5%;">{$LANG->getModule('log_ip')}</th>
                        {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                        <th style="width: 4%;"></th>
                        {/if}
                    </thead>
                    <tbody>
                        {foreach $DATA as $LOG}
                        <tr class="item" data-id="{$LOG.id}">
                            {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                            <td><input type="checkbox" name="checkSingle" class="form-check-input checkitem" data-toggle="checkSingle" aria-label="{$LANG->getGlobal('toggle_checksingle')}"></td>
                            {/if}
                            <td class="text-center">{$LOG.log_time}</td>
                            <td class="text-nowrap">{$LOG.role_title} ({$LANG->getModule('api_role_type')}: {$LOG.role_type}, {$LANG->getModule('api_role_object')}: {$LOG.role_object})</td>
                            <td class="text-center text-nowrap">{$LOG.command}</td>
                            <td class="text-center text-nowrap">{$LOG.username}</td>
                            <td class="text-center">{$LOG.log_ip}</td>
                            {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                            <td><button type="button" class="btn btn-secondary log-del text-nowrap"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getGlobal('delete')}</button></td>
                            {/if}
                        </tr>
                        {/foreach}
                        {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                        <tr>
                            <td><input type="checkbox" name="checkAll" class="form-check-input checkall" data-toggle="checkAll" aria-label="{$LANG->getGlobal('toggle_checkall')}"></td>
                            <td colspan="6">
                                <button type="button" class="btn btn-secondary log-multidel"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getModule('del_selected')}</button>
                                <button type="button" class="btn btn-secondary log-delall"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getModule('del_all')}</button>
                            </td>
                        </tr>
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>
        {/if}
        {if !empty($GENERATE_PAGE)}
        <div class="card-footer">
            <div class="d-flex flex-wrap justify-content-end align-items-center">
                <div class="pagination-wrap">
                    {$GENERATE_PAGE}
                </div>
            </div>
        </div>
        {/if}
    </div>
</div>
