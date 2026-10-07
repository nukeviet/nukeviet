<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 45%">{$LANG->getModule('update_step_title_1')}</th>
                <th>{$LANG->getModule('update_value')}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{$LANG->getModule('update_current_version')}</td>
                <td class="fw-medium">{$DATA.current_version}</td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_lastest_version')}</td>
                <td class="fw-medium">{$DATA.newVersion}</td>
            </tr>
        </tbody>
    </table>
</div>
{if $DATA.checkversion}
<div class="alert alert-warning mt-3 mb-0">{$LANG->getModule('update_check_version')}</div>
{/if}
