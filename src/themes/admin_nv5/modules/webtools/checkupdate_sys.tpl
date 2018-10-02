{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('checkSystem')}: {$ERROR}</div>
</div>
{else}
<div class="card card-table">
    <div class="card-header card-header-divider mx-0 px-4 mb-0 pb-2">
        {$LANG->get('checkSystem')}
        <div class="card-subtitle">
            {$LANG->get('checkDate')}: {$DATA.timestamp} (<a id="sysUpdRefresh" href="#">{$LANG->get('reCheck')}</a>)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 40%">{$LANG->get('checkContent')}</th>
                        <th style="width: 60%">{$LANG->get('checkValue')}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-top">{$LANG->get('userVersion')}</td>
                        <td class="align-top">{$DATA.userVersion}</td>
                    </tr>
                    <tr>
                        <td class="align-top">{$LANG->get('onlineVersion')}</td>
                        <td class="align-top">
                            {$DATA.onlineVersion}
                            {if $DATA.isNewVersion}
                            <div class="mt-1">
                                <h4>{$DATA.new_version_info}</h4>
                                <div>{$DATA.new_version_link}</div>
                            </div>
                            {/if}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
