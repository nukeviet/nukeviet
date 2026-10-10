<a id="developer-error" href="#md-developer-error" class="bg-danger-subtle rounded-circle d-block text-center position-fixed fa-beat" data-bs-toggle="modal">
    <i class="fa-solid fa-triangle-exclamation text-danger fa-lg"></i>
</a>
<div class="modal" tabindex="-1" id="md-developer-error" aria-labelledby="md-developer-error-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="md-developer-error-title">{$LANG->getGlobal('error_info_caption')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body pb-2">
                {foreach from=$ERRORS item=error}
                <div class="card mb-2 border-1 border-{$CONFIGS[$error.errno][2]}">
                    <div class="card-body{if $CONFIGS[$error.errno][2] eq 'danger'} text-danger{/if}">
                        <div class="card-title h5"><i class="{$CONFIGS[$error.errno][1]} text-{$CONFIGS[$error.errno][2]}"></i> {$CONFIGS[$error.errno][0]}</div>
                        {$error.info}
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
