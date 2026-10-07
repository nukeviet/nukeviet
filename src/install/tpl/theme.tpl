<!DOCTYPE html>
<html lang="{$smarty.const.NV_LANG_DATA}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{$MAIN_TITLE} - {$LANG->getModule('titlesetup')}</title>
    <link rel="shortcut icon" href="{$smarty.const.NV_BASE_SITEURL}favicon.ico">
    <link rel="stylesheet" href="{$smarty.const.NV_BASE_SITEURL}install/css/install.css">
    <script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.min.js"></script>
    <script src="{$smarty.const.NV_BASE_SITEURL}themes/admin_future/js/bootstrap.bundle.min.js"></script>
    <script src="{$smarty.const.NV_BASE_SITEURL}install/js/install.js"></script>
</head>
<body data-base-siteurl="{$smarty.const.NV_BASE_SITEURL}">
    <header class="install-header">
        <div class="install-container">
            <img class="install-logo" src="{$smarty.const.ASSETS_STATIC_URL}/images/logo.svg" alt="NukeViet">
            <h1 class="install-title d-none d-sm-block">{$LANG->getModule('titlesetup')} <span class="badge text-bg-light fw-normal">v{$VERSION}</span></h1>
            <div class="dropdown ms-auto">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-language"></i> {$LANGS[$smarty.const.NV_LANG_DATA]}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    {foreach from=$LANGS key=lang item=langname}
                    <li><a class="dropdown-item{if $lang eq $smarty.const.NV_LANG_DATA} active{/if}" href="{$smarty.const.NV_BASE_SITEURL}install/index.php?{$smarty.const.NV_LANG_VARIABLE}={$lang}&amp;step={$MAIN_STEP}&amp;t={$smarty.const.NV_CURRENTTIME}">{$langname}</a></li>
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
    <div class="modal fade" id="install-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('install_error')}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</body>
</html>
