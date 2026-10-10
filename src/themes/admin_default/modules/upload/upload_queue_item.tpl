<div class="queue-files-item" data-toggle="qitem" data-id="">
    <div class="queue-col-name" data-toggle="qitem-name"></div>
    <div class="queue-col-alt">
        <div{if $UPLOAD_ALT_REQUIRE eq 'true'} class="form-control-required"{/if}>
            <input name="queue_item_alt" data-toggle="qitem-alt" class="form-control form-control-sm" type="text" value="" aria-label="{$LANG->getModule('altimage')}">
        </div>
    </div>
    <div class="queue-col-size" data-toggle="qitem-size"></div>
    <div class="queue-col-status" data-toggle="qitem-status">0%</div>
    <div class="queue-col-tool" data-toggle="qitem-actions">
        <a href="#" class="link-danger" data-toggle="qitem-del" aria-label="{$LANG->getModule('upload_delfile')}" title="{$LANG->getModule('upload_delfile')}"><i class="fa-solid fa-ban"></i></a>
        <i data-toggle="qitem-uploading" class="d-none fa-solid fa-spinner fa-spin-pulse text-primary"></i>
        <i data-toggle="qitem-success" class="d-none fa-solid fa-circle-check text-success"></i>
        <i data-toggle="qitem-error" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="error" title="error" class="d-none fa-solid fa-triangle-exclamation text-danger"></i>
    </div>
</div>
