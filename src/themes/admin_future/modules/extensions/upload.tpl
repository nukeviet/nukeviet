<div class="card mb-4">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('autoinstall_uploadedfile')}
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('autoinstall_uploadedfilesize')}</div>
                <div class="col-auto flex-shrink-1">{$INFO.filesize}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('autoinstall_uploaded_filenum')}</div>
                <div class="col-auto flex-shrink-1">{$INFO.filenum}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('extname')}</div>
                <div class="col-auto flex-shrink-1">{$INFO.extname}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('ext_type')}</div>
                <div class="col-auto flex-shrink-1">{if $LANG->existsModule("extType_`$INFO.exttype`")}{$LANG->getModule("extType_`$INFO.exttype`")}{else}{$LANG->getModule('extType_other')}{/if}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('file_version')}</div>
                <div class="col-auto flex-shrink-1">{$INFO.extversion}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('autoinstall_uploaded_num_exists')}</div>
                <div class="col-auto flex-shrink-1">{$INFO.existsnum}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row flex-nowrap">
                <div class="fw-150 fw-medium">{$LANG->getModule('autoinstall_uploaded_num_invalid')}</div>
                <div class="col-auto flex-shrink-1">{$INFO.invaildnum}</div>
            </div>
        </li>
    </ul>
    <div class="card-body border-top" id="upload-ext-status">
        {if $INFO.checkresult eq 'success'}
        <div class="alert alert-success mb-0" role="alert">{$LANG->getModule('autoinstall_error_check_success')}</div>
        {elseif $INFO.checkresult eq 'warning'}
        <div class="alert alert-warning mb-0" role="alert">{$LANG->getModule('autoinstall_error_check_warning')}</div>
        {elseif ($GCONFIG.extension_upload_mode ?? 0) eq 2}
        <div class="alert alert-danger mb-0" role="alert">{$LANG->getModule('autoinstall_error_check_failpass')}</div>
        {else}
        <div class="alert alert-danger mb-0" role="alert">{$LANG->getModule('autoinstall_error_check_fail')}</div>
        {/if}
    </div>
</div>
{if not empty($INFO.filelist)}
<div id="filelist-loader" class="d-none">
    <div class="card">
        <div class="card-body text-center">
            <i class="fa-solid fa-spinner fa-spin-pulse"></i> {$LANG->getModule('autoinstall_package_processing')}
        </div>
    </div>
</div>
<div id="filelist" data-link="{$EXTRACTLINK}">
    <div class="card">
        <div class="card-header">
            <div class="fs-5 fw-medium">{$LANG->getModule('autoinstall_uploaded_filelist')}</div>
            <div class="mt-1">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><i class="fa-solid fa-square-xmark text-danger"></i> {$LANG->getModule('autoinstall_note_invaild')}</li>
                    <li class="list-inline-item"><i class="fa-solid fa-triangle-exclamation text-warning"></i> {$LANG->getModule('autoinstall_note_exists')}</li>
                </ul>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            {foreach from=$INFO.filelist item=file}
            <li class="list-group-item">
                <div class="d-flex gap-2 justify-content-between">
                    <div class="text-break">{$file.title}</div>
                    {if not empty($file.class)}
                    <div class="text-nowrap d-flex gap-2">
                        {foreach from=$file.class item=cls}
                        {if $cls eq 'invaild'}
                        <i class="fa-solid fa-square-xmark text-danger" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('autoinstall_note_invaild')}"></i>
                        {else}
                        <i class="fa-solid fa-triangle-exclamation text-warning" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('autoinstall_note_exists')}"></i>
                        {/if}
                        {/foreach}
                    </div>
                    {/if}
                </div>
            </li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}
