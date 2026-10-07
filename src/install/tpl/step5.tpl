<form id="check_database" action="{$ACTIONFORM}" method="post" autocomplete="off" novalidate data-toggle="installDb"
    data-lang-installing-db="{$LANG->getModule('installing_db')|escape}"
    data-lang-installing-modules="{$LANG->getModule('installing_modules')|escape}"
    data-lang-installing-module="{$LANG->getModule('installing_module')|escape}"
    data-lang-finalizing="{$LANG->getModule('finalizing')|escape}"
    data-lang-install-done="{$LANG->getModule('install_done')|escape}"
    data-lang-install-error="{$LANG->getModule('install_error')|escape}">
    <input type="text" value="" class="d-none" tabindex="-1" aria-hidden="true">
    <input type="password" value="" class="d-none" tabindex="-1" aria-hidden="true">
    <p class="text-body-secondary">{$LANG->getModule('properties')} <span class="text-danger">*</span> {$LANG->getModule('is_required')}</p>
    <div id="database_config" class="row g-3">
        <div class="col-12">
            <label for="dbtype" class="form-label">{$LANG->getModule('database_type')} <span class="text-danger">*</span></label>
            <div class="d-flex align-items-center gap-2">
                <select class="form-select" id="dbtype" name="dbtype" data-url="{$STEP_URL}5">
                    {foreach from=$DBTYPES key=value item=text}
                    <option value="{$value}"{if $DATABASE.dbtype eq $value} selected{/if}>{$text}</option>
                    {/foreach}
                </select>
                <span class="spinner-border spinner-border-sm text-primary d-none" id="dbtype-check" role="status"></span>
            </div>
            <div class="form-text">{$LANG->getModule('database_default')} <strong>MySQL</strong></div>
        </div>
        <div class="col-sm-9">
            <label for="dbhost" class="form-label">{$LANG->getModule('host_name')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dbhost" name="dbhost" value="{$DATABASE.dbhost}" required>
            <div class="form-text">{$LANG->getModule('host_name_note')} <strong>localhost</strong>.</div>
        </div>
        <div class="col-sm-3">
            <label for="dbport" class="form-label">Port</label>
            <input type="text" class="form-control" id="dbport" name="dbport" value="{$DATABASE.dbport}" inputmode="numeric">
        </div>
        <div class="col-sm-6">
            <label for="dbuname" class="form-label">{$LANG->getModule('db_username')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dbuname" name="dbuname" value="{$DATABASE.dbuname}" required>
            <div class="form-text">{$LANG->getModule('db_username_note')}.</div>
        </div>
        <div class="col-sm-6">
            <label for="dbpass" class="form-label">{$LANG->getModule('db_pass')}</label>
            <input type="password" class="form-control" id="dbpass" name="dbpass" value="{$DATABASE.dbpass}" autocomplete="off">
            <div class="form-text">{$LANG->getModule('db_pass_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="dbname" class="form-label">{$LANG->getModule('db_name')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dbname" name="dbname" value="{$DATABASE.dbname}" required>
            <div class="form-text">{$LANG->getModule('db_name_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="prefix" class="form-label">{$LANG->getModule('prefix')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="prefix" name="prefix" value="{$DATABASE.prefix}" required>
        </div>
        <div class="col-12" id="db_detete_wrap" hidden>
            <div class="alert alert-warning mb-0">
                <p class="mb-2">{$LANG->getModule('db_err_prefix')}</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="db_detete" id="db_detete" value="1">
                    <label class="form-check-label" for="db_detete">{$LANG->getModule('db_detete')}</label>
                </div>
            </div>
        </div>
        {if not empty($DATABASE.error)}
        <div class="col-12">
            <div class="alert alert-danger mb-0">{$DATABASE.error}</div>
        </div>
        {/if}
        <div class="col-12">
            <button class="btn btn-primary" type="submit" id="btn_install_db"><i class="fa-solid fa-database"></i> {$LANG->getModule('refesh')}</button>
        </div>
    </div>
</form>

<div id="nv_install_progress" class="mt-2" hidden>
    <p class="fw-medium mb-2" id="nv_progress_caption">{$LANG->getModule('installing_db')}</p>
    <div class="progress mb-3" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="height: 1.25rem">
        <div id="nv_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
    </div>
    <div id="nv_progress_log" class="install-log"></div>
</div>

<div class="install-nav" id="step5_nav">
    <a class="btn btn-outline-secondary back_step" href="{$STEP_URL}4"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    {if $NEXTSTEP}
    <span class="next_step"><a class="btn btn-primary" href="{$STEP_URL}6">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
    {/if}
</div>
