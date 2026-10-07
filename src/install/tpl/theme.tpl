<!DOCTYPE html>
<html lang="{$LANG_CODE}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{$MAIN_TITLE} - {$SITE_TITLE}</title>
    <link rel="shortcut icon" href="{$smarty.const.NV_BASE_SITEURL}favicon.ico">
    <link rel="stylesheet" href="{$smarty.const.NV_BASE_SITEURL}install/css/install.css">
    <script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.min.js"></script>
    <script src="{$smarty.const.NV_BASE_SITEURL}themes/admin_future/js/bootstrap.bundle.min.js"></script>
    <script src="{$smarty.const.NV_BASE_SITEURL}install/js/install.js"></script>
    {foreach from=$SCRIPTS item=script}
    <script src="{$script}"></script>
    {/foreach}
</head>
<body data-base-siteurl="{$smarty.const.NV_BASE_SITEURL}">
    <header class="install-header">
        <div class="install-container">
            <img class="install-logo" src="{$smarty.const.ASSETS_STATIC_URL}/images/logo.svg" alt="NukeViet">
            <h1 class="install-title d-none d-sm-block">{$SITE_TITLE} <span class="badge text-bg-light fw-normal">{$VERSION}</span></h1>
            <div class="dropdown ms-auto">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-language"></i> {$LANGS[$LANG_CODE].name}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    {foreach from=$LANGS key=lang item=row}
                    <li><a class="dropdown-item{if $lang eq $LANG_CODE} active{/if}" href="{$row.url}">{$row.name}</a></li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </header>
    <main class="install-main">
        <div class="install-container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="d-lg-none">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-medium">{$MAIN_TITLE}</span>
                            <span>{$CURRENT_NUM}/{$STEPS|count}</span>
                        </div>
                        <div class="progress" role="progressbar" aria-valuenow="{$PROGRESS}" aria-valuemin="0" aria-valuemax="100" style="height: .375rem">
                            <div class="progress-bar" style="width: {$PROGRESS}%"></div>
                        </div>
                    </div>
                    <ol class="install-stepper d-none d-lg-block">
                        {foreach from=$STEPS item=row}
                        <li class="{$row.status}"{if $row.status eq 'current'} aria-current="step"{/if}>
                            <span class="step-num">{if $row.status eq 'passed'}<i class="fa-solid fa-check"></i>{else}{$row.num}{/if}</span>
                            <span>{$row.name}</span>
                        </li>
                        {/foreach}
                    </ol>
                </div>
                <div class="col-lg-9">
                    <div class="card install-card">
                        <div class="card-header">
                            <h2 class="h4 mb-0 text-dark">{$MAIN_TITLE}</h2>
                        </div>
                        <div class="card-body">
                            {$MAIN_CONTENT}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="install-footer">
        <div class="install-container d-md-flex justify-content-between text-center gap-3">
            <div>&copy; 2010 - {$YEAR} {$LANG->getModule('developed')} <a href="https://vinades.vn/" target="_blank" rel="noopener">VINADES.,JSC</a></div>
            <div>{$LANG->getModule('publish')} <a href="https://www.gnu.org/licenses/gpl-2.0.html" target="_blank" rel="noopener">GNU/GPL v2.0</a></div>
        </div>
    </footer>
    <div class="modal fade" id="install-modal" tabindex="-1" aria-hidden="true" data-title-error="{$MODAL_TITLE|escape}" data-title-confirm="{$LANG->getGlobal('confirm')|escape}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')|escape}"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer d-none">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('cancel')}</button>
                    <button type="button" class="btn btn-primary" data-toggle="modalConfirm">{$LANG->getGlobal('ok')}</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
