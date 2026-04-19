<div class="card mb-3 shadow-sm border-0 bg-body-tertiary">
    <div class="card-header bg-body py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-code me-2"></i>Bước 2: Xem trước mã nguồn cho module [{$TARGET_MODULE}] / op: <code class="text-warning">{$OP_NAME}</code></h5>
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=config-step1&amp;target_module={$TARGET_MODULE}&amp;op_name={$OP_NAME}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay về Bước 1
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4 h-100">
            <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
                <span><i class="fa-brands fa-php me-2 text-info"></i>admin/<strong>{$OP_NAME}</strong>.php</span>
                <button class="btn btn-link btn-sm p-0 text-white btn-copy" data-target="php-code"><i class="fa-solid fa-copy"></i></button>
            </div>
            <div class="card-body p-0">
                <pre id="php-code" class="bg-dark text-light p-3 m-0 scrollbar-thin" style="max-height: 600px; overflow: auto; font-size: 13px;"><code>{$PHP_CODE|escape}</code></pre>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4 h-100">
            <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-code me-2 text-warning"></i><strong>{$OP_NAME}</strong>.tpl</span>
                <button class="btn btn-link btn-sm p-0 text-white btn-copy" data-target="tpl-code"><i class="fa-solid fa-copy"></i></button>
            </div>
            <div class="card-body p-0">
                <pre id="tpl-code" class="bg-dark text-light p-3 m-0 scrollbar-thin" style="max-height: 600px; overflow: auto; font-size: 13px;"><code>{$TPL_CODE|escape}</code></pre>
            </div>
        </div>
    </div>
</div>

<div class="text-center sticky-bottom bg-body py-4 border-top shadow-lg" style="bottom: 0px; z-index: 1000; margin-left: -1rem; margin-right: -1rem;">
    <form action="" method="post" id="form-apply" class="ajax-submit">
        <input type="hidden" name="apply" value="1">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <button type="submit" class="btn btn-lg btn-success px-5 shadow pulse-button">
            <i class="fa-solid fa-rocket me-2"></i> ÁP DỤNG VÀO MODULE [{$TARGET_MODULE}]
        </button>
    </form>
    <div class="mt-2 text-muted x-small">
        <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> Hành động này sẽ tạo/ghi đè các file tương ứng trong module mục tiêu.
    </div>
</div>

<style>
.scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #444; border-radius: 10px; }
.scrollbar-thin::-webkit-scrollbar-track { background: #1a1a1a; }
.scroll-auto { height: 100%; }
.pulse-button:hover {
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
    70% { box-shadow: 0 0 0 15px rgba(25, 135, 84, 0); }
    100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}
</style>

<script>
$(function() {
    $('.btn-copy').on('click', function() {
        var targetId = $(this).data('target');
        var text = $('#' + targetId).text();
        var $btn = $(this);
        
        navigator.clipboard.writeText(text).then(function() {
            $btn.html('<i class="fa-solid fa-check text-success"></i>');
            setTimeout(function() {
                $btn.html('<i class="fa-solid fa-copy"></i>');
            }, 2000);
        });
    });
});
</script>
