<div class="card mb-3 text-bg-primary">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('checkSystem')}
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped mb-0">
                <thead class="text-muted">
                    <tr>
                        <th style="width: 30%;" class="text-nowrap">{$LANG->getModule('checkContent')}</th>
                        <th style="width: 70%;" class="text-nowrap">{$LANG->getModule('checkValue')}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$LANG->getModule('userVersion')}</td>
                        <td>{$GCONFIG.version}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->getModule('onlineVersion')}</td>
                        <td>
                            {$LANG->getModule('newVersion_detail', $VERSION.version, $VERSION.name, $VERSION.date)}
                            {if $VERSION.need_update}
                            <div class="mt-2">
                                {$VERSION.info}
                            </div>
                            <div class="mt-2 text-danger fw-medium">
                                {if $VERSION.version eq $VERSION.updateable}
                                {$LANG->getModule('newVersion_info1', $VERSION.link_update)}
                                {elseif not empty($VERSION.updateable)}
                                {$LANG->getModule('newVersion_info2', $VERSION.updateable, $VERSION.link_update)}
                                {else}
                                {$LANG->getModule('newVersion_info3', $VERSION.link)}
                                {/if}
                            </div>
                            {/if}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end">
                            {$LANG->getModule('checkDate')} {$SYSUPDDATE} (<a id="sysUpdRefresh" href="#">{$LANG->getModule('reCheck')}</a>)
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
