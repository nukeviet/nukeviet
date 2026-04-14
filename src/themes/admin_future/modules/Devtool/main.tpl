<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>

{* Cảnh báo ghi đè *}
<div class="alert alert-warning border-warning border-2 d-flex align-items-start gap-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-triangle-exclamation fa-lg mt-1 flex-shrink-0"></i>
    <div>
        <strong>Cảnh báo quan trọng:</strong> Chức năng sẽ ghi đè các file PHP và TPL đã tồn tại trong module đích.
        Hãy đảm bảo bạn đã:
        <ul class="mb-0 mt-1">
            <li>Commit toàn bộ thay đổi hiện tại lên <strong>Git</strong> trước khi tiếp tục</li>
            <li>Hoặc tạo <strong>backup</strong> thủ công thư mục module</li>
        </ul>
    </div>
</div>

{* Bước 1: Chọn Module *}
<div class="card border-secondary border-2 border-bottom-0 border-start-0 border-end-0 shadow-sm mb-3">
    <div class="card-header py-3 bg-body-tertiary d-flex align-items-center gap-2">
        <span class="badge bg-secondary rounded-pill">1</span>
        <h5 class="mb-0 text-secondary"><i class="fa-solid fa-puzzle-piece me-1"></i>Chọn Module đích</h5>
    </div>
    <div class="card-body py-3 bg-body">
        <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="row g-2 align-items-end" id="formModuleFilter">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}" />
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}" />
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="main" />
            <div class="col-md-9">
                <label class="form-label text-muted small mb-1">Code sinh ra sẽ được đặt vào module nào?</label>
                <select name="module_filter" class="form-select select2" id="moduleFilterSelect">
                    <option value="">-- Hiển thị tất cả bảng --</option>
                    {foreach from=$MODULES item=mod}
                    <option value="{$mod}" {if $MODULE_FILTER==$mod}selected{/if}>{$mod}</option>
                    {/foreach}
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-filter me-1"></i>Lọc bảng
                </button>
            </div>
        </form>
    </div>
</div>

{* Bước 2: Chọn Bảng (đã lọc) *}
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 shadow-sm">
    <div class="card-header py-3 bg-body-tertiary d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-pill">2</span>
        <h5 class="mb-0 text-primary"><i class="fa-solid fa-database me-1"></i>Chọn Bảng Dữ Liệu
            {if $MODULE_FILTER}
            <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle ms-2 fw-normal">{$MODULE_FILTER}</span>
            {/if}
        </h5>
    </div>
    <div class="card-body py-4 bg-body">
        {if $MODULE_FILTER}
        <p class="text-muted mb-3">
            Hiển thị <strong>{$TABLES|count}</strong> bảng thuộc module <code>{$MODULE_FILTER}</code>.
        </p>
        {else}
        <p class="text-muted mb-3">Hệ thống sẽ quét cấu trúc bảng để tự động gợi ý Mapping Schema cho AI.</p>
        {/if}

        {if $TABLES}
        <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="row g-2">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}" />
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}" />
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="schemas" />
            {if $MODULE_FILTER}
            <input type="hidden" name="module_filter" value="{$MODULE_FILTER}" />
            {/if}
            <div class="col-md-9">
                <select name="table" class="form-select select2" required>
                    <option value="">-- Chọn bảng cơ sở dữ liệu --</option>
                    {foreach from=$TABLES item=tab}
                    <option value="{$tab}">{$tab}</option>
                    {/foreach}
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-sliders me-1"></i>Thiết lập Schema
                </button>
            </div>
        </form>
        {else}
        <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-circle-info fa-2x mb-2 d-block"></i>
            Không tìm thấy bảng nào khớp với module <strong>{$MODULE_FILTER}</strong>.
            Hãy kiểm tra lại tên module hoặc tiền tố CSDL.
        </div>
        {/if}
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });

        // Tự động submit khi chọn module (không cần bấm nút)
        $('#moduleFilterSelect').on('change', function () {
            $('#formModuleFilter').submit();
        });
    });
</script>
