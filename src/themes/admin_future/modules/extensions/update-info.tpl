{assign var="iconByStatus" value=[
    'ready' => '<i class="fa-solid fa-circle-check text-success"></i>',
    'notlogin' => '<i class="fa-solid fa-face-frown text-warning"></i>',
    'unpaid' => '<i class="fa-solid fa-face-frown text-warning"></i>',
    'invalid' => '<i class="fa-solid fa-face-frown text-danger"></i>'
]}
<div class="card mb-4">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('extUpdCheck')}
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('extname')}</div>
                <div class="col-auto flex-shrink-1">{$DATA.title}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('extUpdCheckStatus')}</div>
                <div class="col-auto flex-shrink-1">{$iconByStatus[$DATA.fileInfo] ?? $iconByStatus.invalid} {$DATA.message}</div>
            </div>
        </li>
    </ul>
    <div class="card-body border-top">
        {if $DATA.fileInfo eq 'ready'}
        <div id="upd-getfile" class="text-center" data-link="{$DATA.link}">
            <i class="fa-solid fa-spinner fa-spin-pulse"></i> {$LANG->getModule('autoinstall_package_processing')}...
        </div>
        {else}
        <div class="alert mb-0 alert-{if isset($iconByStatus[$DATA.fileInfo])}warning{else}danger{/if}" role="alert">
            {$DATA.message_detail}
        </div>
        {/if}
    </div>
</div>
