<div class="card list-report" data-del-confirm="{$LANG->getModule('report_del_confirm')}">
    <div class="card-body">
        <div class="table-responsive-lg table-card pt-1 bg-table-head rounded-top-2" id="list-news-items">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" name="checkAll[]" data-toggle="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width: 78%;">{$LANG->getModule('contents')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('post_time')}</th>
                        <th class="text-nowrap" style="width: 1%;">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROWS item=row}
                    <tr class="item" data-id="{$row.id}">
                        <td>
                            <input type="checkbox" data-toggle="checkSingle" name="checkSingle[]" value="{$row.id}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td>
                            <div class="fw-medium"><a href="{$row.url}">{$LANG->getModule('article')}: {$row.title}</a></div>
                            <div>{$row.orig_content_short}</div>
                            <small class="text-muted">{$LANG->getModule('post_ip')}: {$row.post_ip}, {$LANG->getModule('post_email')}: {$row.post_email}</small>
                        </td>
                        <td>{$row.post_time_format}</td>
                        <td class="text-nowrap">
                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="report_del_action" data-send-mail="no" data-id="{$row.id}" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash-can text-danger fa-fw" data-icon="fa-trash-can"></i></button>
                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="report_del_action" data-send-mail="yes" data-id="{$row.id}" title="{$LANG->getModule('report_delete')}" aria-label="{$LANG->getModule('report_delete')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('report_delete')}"><i class="fa-solid fa-trash-can-arrow-up text-danger fa-fw" data-icon="fa-trash-can-arrow-up"></i></button>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <button type="button" class="btn btn-secondary text-nowrap text-truncate me-1 my-1 mw-100" data-toggle="report_del_check_action" data-not-checked="{$LANG->getModule('report_not_checked')}"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i> {$LANG->getModule('report_del_checked')}</button>
            </div>
            <div class="pagination-wrap">
                {$GENERATE_PAGE}
            </div>
        </div>
    </div>
</div>

