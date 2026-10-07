{if $NOT_EXIST_MOD}
<div class="alert alert-warning">
    <p>{$LANG->getModule('updatemod_notexist')}</p>
    {include file='updatedelete.tpl'}
</div>
{else}
<div class="table-responsive mb-4">
    <table class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 45%">{$LANG->getModule('update_info_backage')}</th>
                <th>{$LANG->getModule('update_value')}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{$LANG->getModule('update_release_date')}</td>
                <td class="fw-medium">{$RELEASE_DATE}</td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_author')}</td>
                <td class="fw-medium">{$CONFIG.author}</td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_to_version')}</td>
                <td class="fw-medium">{$CONFIG.to_version}</td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_to_version_support')}</td>
                <td class="fw-medium">{$ALLOW_OLD_VERSION}</td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_website_support')}</td>
                <td><a href="{$CONFIG.support_website}" target="_blank" rel="noopener">{$CONFIG.support_website}</a></td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_auto_type')}</td>
                <td class="fw-medium">{$UPDATE_AUTO_TYPE}</td>
            </tr>
        </tbody>
    </table>
</div>
{if not empty($DATA.sysnotsupport)}
<div class="table-responsive mb-4">
    <table class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 45%">{$LANG->getModule('check_server')}</th>
                <th>{$LANG->getModule('update_file_info')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$DATA.sysnotsupport item=ext}
            <tr>
                <td>{$ext[0]}</td>
                <td><span class="badge text-bg-danger">{$ext[1]}</span></td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{/if}
<div class="table-responsive mb-4">
    <table class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 45%">{$LANG->getModule('update_version_current')}</th>
                <th>{$LANG->getModule('update_value')}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{$LANG->getModule('update_version_current_name')}</td>
                <td class="fw-medium">{$DATA.current_version}</td>
            </tr>
            <tr>
                <td>{$LANG->getModule('update_ability')}</td>
                <td><span class="badge {if $DATA.isupdate_allow}text-bg-success{else}text-bg-danger{/if}">{$DATA.ability}</span></td>
            </tr>
        </tbody>
    </table>
</div>
{if $DATA.isupdate_allow}
<div class="alert alert-info mb-0">{$LANG->getModule('update_info_start')}</div>
<div class="install-nav">
    <span class="next_step"><a class="btn btn-primary" href="{$UPDATE_URL}?step=2">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
</div>
{else}
<div class="alert alert-danger mb-0">
    <p>{$DATA.ability}.</p>
    {include file='updatedelete.tpl'}
</div>
{/if}
{/if}
