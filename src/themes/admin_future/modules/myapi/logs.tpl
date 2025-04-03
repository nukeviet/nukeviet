<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div id="logs" data-page-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-header">  
            <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">     
                <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
                <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-6 g-2 gx-xxl-3">
                    <div class="col col-xxl-auto">
                        <div class="form-group mb-3">
                            <div class="input-group w-100">
                                <span class="input-group-text" title="{$LANG->getModule('api_role')}"><i class="fa-solid fa-object-group"></i></span>
                                <select class="form-select role-id" name="role_id">
                                    <option value="0">{$LANG->getModule('api_role_select')}</option>
                                    {foreach $ROLES as $ROLE_ID => $ROLE}
                                    <option value="{$ROLE_ID}" {if $ROLE_ID == $GET_DATA.role_id}selected="selected"{/if}>{$ROLE}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col col-xxl-auto">
                        <div class="form-group mb-3">
                            <div class="input-group w-100">
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
                    <div class="col col-xxl-auto">
                        <div class="form-group mb-3">
                            <div class="input-group w-100">
                                <span class="input-group-text" title="{$LANG->getModule('api_role_object')}"><i class="fa-solid fa-user"></i></span>
                                <select class="form-select userid" name="userid" data-placeholder="{$LANG->getModule('api_role_object')}">
                                    {if !empty($GET_DATA.userid)}
                                    <option value="{$GET_DATA.userid}" selected="selected">{$GET_DATA.username}</option>
                                    {/if}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col col-xxl-auto">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text" title="{$LANG->getModule('fromdate')}"><i class="fa-solid fa-calendar"></i></span>
                                <input type="text" class="form-control fromdate" name="fromdate" value="{$GET_DATA.fromdate}" maxlength="10" placeholder="{$LANG->getModule('fromdate')}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col col-xxl-auto">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text" title="{$LANG->getModule('todate')}"><i class="fa-solid fa-calendar"></i></span>
                                <input type="text" class="form-control todate" name="todate" value="{$GET_DATA.todate}" maxlength="10" placeholder="{$LANG->getModule('todate')}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-auto">
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary w-100 w-100">{$LANG->getModule('filter_logs')}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {if !empty($DATA)}
        <div class="card-body">
            <div class="table-responsive m-bottom">
                <table class="table table-bordered table-striped list" data-delete-confirm="{$LANG->getModule('log_del_confirm')}">
                    <thead class="bg-primary">
                        {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                        <th style="width: 1%;"><input type="checkbox" class="form-check-input checkall"></th>
                        {/if}
                        <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('log_time')}</th>
                        <th class="text-center text-nowrap">{$LANG->getModule('api_role')}</th>
                        <th class="text-center text-nowrap">API</th>
                        <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('api_role_object')}</th>
                        <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('log_ip')}</th>
                        {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                        <th style="width: 1%;"></th>
                        {/if}
                    </thead>
                    <tbody>
                        {foreach $DATA as $LOG}
                        <tr class="item" data-id="{$LOG.id}">
                            {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                            <td style="width: 1%;"><input type="checkbox" class="form-check-input checkitem"></td>
                            {/if}
                            <td class="text-center" style="width: 1%;">{$LOG.log_time}</td>
                            <td>{$LOG.role_title} ({$LANG->getModule('api_role_type')}: {$LOG.role_type}, {$LANG->getModule('api_role_object')}: {$LOG.role_object})</td>
                            <td class="text-center text-nowrap" style="width: 1%;">{$LOG.command}</td>
                            <td class="text-center text-nowrap" style="width: 1%;">{$LOG.username}</td>
                            <td class="text-center" style="width: 1%;">{$LOG.log_ip}</td>
                            {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                            <td><button type="button" class="btn btn-secondary log-del"><i class="fa-solid fa-trash-o"></i> {$LANG->getGlobal('delete')}</button></td>
                            {/if}
                        </tr>
                        {/foreach}
                    </tbody>
                    {if !empty($smarty.const.MANUALL_DEL_API_LOG) and $smarty.const.MANUALL_DEL_API_LOG === true}
                    <tfoot>
                        <tr>
                            <td style="width: 1%;"><input type="checkbox" class="form-check-input checkall"></td>
                            <td colspan="6">
                                <button type="button" class="btn btn-secondary log-multidel"><i class="fa-solid fa-trash-o"></i> {$LANG->getModule('del_selected')}</button>
                                <button type="button" class="btn btn-secondary log-delall"><i class="fa-solid fa-trash-o"></i> {$LANG->getModule('del_all')}</button>
                            </td>
                        </tr>
                    </tfoot>
                    {/if}
                </table>
            </div>
        </div>
        {/if}
        {if !empty($GENERATE_PAGE)}
        <div class="card-footer">
            <div class="d-flex flex-wrap justify-content-end align-items-center">
                {$GENERATE_PAGE}
            </div>
        </div>
        {/if}
    </div>
</div>
