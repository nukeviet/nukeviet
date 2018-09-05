{if empty($DATA)}
<div role="alert" class="alert alert-warning alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="message">{$LANG->get('notification_empty')}</div>
</div>
{else}
<div class="card card-table card-footer-nav">
    <div class="card-body">
        <div class="row card-body-search-form search-form-right">
            <div class="col-12">
                <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="form-inline">
                    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
                    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                    <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
                    <label>{$LANG->get('search')}: </label>
                    <div class="input-group">
                        <select class="form-control form-control-sm" name="v">
                            {for $row=0 to 2}
                            <option value="{$row}"{if $row eq $DATA_SEARCH['v']} selected="selected"{/if}>{$LANG->get("notification_s`$row`")}</option>
                            {/for}
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <form>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:5%;">
                                <label class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </th>
                            <th class="text-nowrap" style="width:50%;">{$LANG->get('moduleContent')}</th>
                            <th class="text-nowrap" style="width:25%;">{$LANG->get('log_time')}</th>
                            <th class="text-right text-nowrap" style="width:20%;">{$LANG->get('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$DATA item=row}
                        <tr{if not $row['view']} class="primary"{/if}>
                            <td>
                                <label class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="idcheck[]" value="{$row.id}" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </td>
                            <td class="cell-detail has-round-image">
                                <img src="{$row.photo}" alt="{$row.send_from}">
                                <span class="cell-detail-description">{$row.send_from}</span>
                                <span>{$row.title}</span>
                            </td>
                            <td class="cell-detail">
                                <span>{$row.add_time_d}</span>
                                <span class="cell-detail-description">{$row.add_time_h}</span>
                            </td>
                            <td class="text-right text-nowrap">
                                {if not $row['view']}<a title="{$LANG->get('notification_mark_read')}" href="#" class="btn btn-hspace btn-secondary" data-toggle="view-notification" data-id="{$row.id}"><i class="icon icon-left fas fa-circle"></i></a>{/if}
                                <a title="{$LANG->get('delete')}" href="#" class="btn btn-danger" data-toggle="del-notification" data-id="{$row.id}"><i class="icon icon-left fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <div class="page-tools">
            <div class="btn-group">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" data-boundary="window">{$LANG->get('do_with_selected')} <span class="icon-dropdown fas fa-chevron-down"></span></button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="#" data-toggle="del-notifications"><i class="icon fas fa-circle"></i>{$LANG->get('notification_mark_read')}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="del-notifications"><i class="icon far fa-trash-alt"></i>{$LANG->get('delete')}</a>
                </div>
            </div>
        </div>
        {if not empty($GENERATE_PAGE)}
        <nav class="page-nav">
            {$GENERATE_PAGE}
        </nav>
        {/if}
    </div>
</div>
{/if}
