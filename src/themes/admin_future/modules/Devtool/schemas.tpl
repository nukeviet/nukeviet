<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<form action="{$NV_BASE_ADMINURL}index.php" method="post" class="ajax-submit" data-callback="onSchemaSaveSuccess">
    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}" />
    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}" />
    <input type="hidden" name="{$NV_OP_VARIABLE}" value="schemas-save" />
    <input type="hidden" name="table" value="{$TABLE}" />
    <input type="hidden" name="target_module" value="{$MODULE_FILTER}" />
    <input type="hidden" name="submit" value="1" />
    <input type="hidden" name="checkss" value="{$CHECKSS}" />

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-table-list me-2"></i>MAPPING CỘT: {$TABLE}</h5>
            <span class="badge bg-info-subtle text-info-emphasis">AI Schema Builder</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="bg-body-tertiary text-center border-top-0">
                        <tr>
                            <th style="width: 9%">Tên cột</th>
                            <th style="width: 7%">Loại SQL</th>
                            <th style="width: 18%">Kiểu hiển thị Form</th>
                            <th style="width: 4%">Buộc</th>
                            <th style="width: 4%">Ẩn</th>
                            <th style="width: 4%">List</th>
                            <th style="width: 13%">Tiêu đề hiển thị</th>
                            <th style="width: 41%">Ghi chú AI (Logic riêng)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$COLUMNS item=col}
                        {* Xác định view_type đang chọn (từ config cũ hoặc gợi ý mặc định) *}
                        {assign var="cur_view" value=($col.config.view_type|default:$col.default_view)}
                        {* Xác định choice_type đang chọn *}
                        {assign var="cur_choice_type" value=($col.choice_config.choice_type|default:'static')}

                        <tr>
                            <td class="fw-bold text-body px-3">{$col.field}</td>
                            <td class="text-center"><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">{$col.sql_type}</span></td>
                            <td>
                                <select name="columns[{$col.field}][view_type]" class="form-select select2 view-type-select" data-field="{$col.field}">
                                    {foreach from=$col.view_options key=opt_val item=opt_name}
                                    <option value="{$opt_val}" {if $cur_view==$opt_val}selected{/if}>{$opt_name}</option>
                                    {/foreach}
                                </select>
                                {if $col.has_choice}
                                <div class="choice-panel border rounded mt-2 p-2 bg-body-secondary"
                                     id="choice-panel-{$col.field}"
                                     {if $cur_view != 'select' && $cur_view != 'radio'}style="display:none"{/if}>
                                    <div class="d-flex gap-3 mb-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input choice-type-toggle"
                                                   name="columns[{$col.field}][choice_type]" value="static"
                                                   id="cs-static-{$col.field}" data-field="{$col.field}"
                                                   {if $cur_choice_type != 'sql'}checked{/if}>
                                            <label class="form-check-label small" for="cs-static-{$col.field}">
                                                <i class="fa-solid fa-list-ul me-1"></i>Tĩnh
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input choice-type-toggle"
                                                   name="columns[{$col.field}][choice_type]" value="sql"
                                                   id="cs-sql-{$col.field}" data-field="{$col.field}"
                                                   {if $cur_choice_type == 'sql'}checked{/if}>
                                            <label class="form-check-label small" for="cs-sql-{$col.field}">
                                                <i class="fa-solid fa-database me-1"></i>CSDL
                                            </label>
                                        </div>
                                    </div>
                                    <div id="cs-static-cfg-{$col.field}" {if $cur_choice_type == 'sql'}style="display:none"{/if}>
                                        <textarea name="columns[{$col.field}][choice_values]"
                                                  class="form-control form-control-sm font-monospace" rows="3"
                                                  placeholder="0:Không hoạt động&#10;1:Hoạt động&#10;2:Chờ duyệt">{$col.choice_config.choice_values}</textarea>
                                        <div class="form-text">Mỗi dòng: <code>value:Nhãn</code></div>
                                    </div>
                                    <div id="cs-sql-cfg-{$col.field}" {if $cur_choice_type != 'sql'}style="display:none"{/if}>
                                        <select name="columns[{$col.field}][choice_table]"
                                                class="form-select form-select-sm choice-table-select mb-1"
                                                data-field="{$col.field}"
                                                data-saved-id="{$col.choice_config.choice_id_col}"
                                                data-saved-text="{$col.choice_config.choice_text_col}">
                                            <option value="">-- Chọn bảng nguồn --</option>
                                            {foreach from=$ALL_TABLES item=t}
                                            <option value="{$t}" {if $col.choice_config.choice_table == $t}selected{/if}>{$t}</option>
                                            {/foreach}
                                        </select>
                                        <select name="columns[{$col.field}][choice_id_col]"
                                                class="form-select form-select-sm choice-col-id mb-1"
                                                data-field="{$col.field}">
                                            {if $col.choice_config.choice_id_col}
                                            <option value="{$col.choice_config.choice_id_col}" selected>{$col.choice_config.choice_id_col}</option>
                                            {else}
                                            <option value="">-- Cột ID/value --</option>
                                            {/if}
                                        </select>
                                        <select name="columns[{$col.field}][choice_text_col]"
                                                class="form-select form-select-sm choice-col-text"
                                                data-field="{$col.field}">
                                            {if $col.choice_config.choice_text_col}
                                            <option value="{$col.choice_config.choice_text_col}" selected>{$col.choice_config.choice_text_col}</option>
                                            {else}
                                            <option value="">-- Cột nhãn hiển thị --</option>
                                            {/if}
                                        </select>
                                    </div>
                                </div>
                                {/if}
                                <input type="hidden" name="columns[{$col.field}][sql_type]" value="{$col.sql_type}" />
                            </td>
                            <td class="text-center">
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="checkbox" name="columns[{$col.field}][required]" value="1" {if ($col.config.required|default:false)}checked{/if}>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="checkbox" name="columns[{$col.field}][hidden]" value="1" {if ($col.config.hidden|default:false)}checked{/if}>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="checkbox" name="columns[{$col.field}][list]" value="1" {if ($col.config.list|default:true)}checked{/if}>
                                </div>
                            </td>
                            <td class="px-3">
                                <input type="text" name="columns[{$col.field}][label_vi]" value="{$col.config.label_vi|default:$col.comment|default:$col.field}" class="form-control">
                            </td>
                            <td class="px-3">
                                <input type="text" name="columns[{$col.field}][note]" value="{$col.config.note|default:''}" class="form-control" placeholder="Ví dụ: Chỉ hiện cho SuperAdmin">
                            </td>
                        </tr>

                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0 border-top border-success border-3">
        <div class="card-header bg-body-tertiary py-3">
            <h5 class="mb-0 text-success fw-bold"><i class="fa-solid fa-sliders me-2"></i>Tính năng Trang & Hệ thống</h5>
        </div>
        <div class="card-body bg-body py-4">
            <!-- Bố cục hàng ngang giống settings/system -->
            <div class="row mb-4">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Tên Function (OP)</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" name="page_settings[function_name]" value="{$PAGE_SETTINGS.function_name|default:'main'}" class="form-control">
                    <div class="form-text">Tên file PHP trong thư mục admin (ví dụ: main.php)</div>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Loại Giao diện</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="page_settings[layout_type]" class="form-select select2">
                        <option value="list_and_form" {if $PAGE_SETTINGS.layout_type=='list_and_form' }selected{/if}>Cả Danh sách và Form</option>
                        <option value="list_only" {if $PAGE_SETTINGS.layout_type=='list_only' }selected{/if}>Chỉ Danh sách</option>
                        <option value="form_only" {if $PAGE_SETTINGS.layout_type=='form_only' }selected{/if}>Chỉ Form</option>
                        <option value="mvc_only" {if $PAGE_SETTINGS.layout_type=='mvc_only' }selected{/if}>Chỉ tạo MVC (không sinh giao diện)</option>
                    </select>
                </div>
            </div>

            <hr class="my-4 opacity-50">

            <div class="row mb-4">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Trạng thái (Active)</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="page_settings[features][active_field]" class="form-select select2">
                        <option value="">-- Không sử dụng --</option>
                        {foreach from=$COLUMNS item=col}
                        <option value="{$col.field}" {if $PAGE_SETTINGS.features.active_field==$col.field}selected{/if}>{$col.field}</option>
                        {/foreach}
                    </select>
                    <div class="form-text text-success"><i class="fa-solid fa-circle-info me-1"></i>Chọn cột có kiểu dữ liệu TINYINT nếu có tính năng bật/tắt</div>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Sắp xếp (Weight)</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="page_settings[features][weight_field]" class="form-select select2">
                        <option value="">-- Không sử dụng --</option>
                        {foreach from=$COLUMNS item=col}
                        <option value="{$col.field}" {if $PAGE_SETTINGS.features.weight_field==$col.field}selected{/if}>{$col.field}</option>
                        {/foreach}
                    </select>
                    <div class="form-text">Thường là cột <code>weight</code> hoặc <code>sort</code></div>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Nguồn tạo Alias</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="page_settings[features][alias_source_field]" class="form-select select2">
                        <option value="">-- Không sử dụng --</option>
                        {foreach from=$COLUMNS item=col}
                        <option value="{$col.field}" {if $PAGE_SETTINGS.features.alias_source_field==$col.field}selected{/if}>{$col.field}</option>
                        {/foreach}
                    </select>
                    <div class="form-text">Gợi ý: Cột <code>title</code> hoặc <code>name</code></div>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Khu vực sinh mã</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="page_settings[area]" class="form-select select2">
                        <option value="admin" {if $PAGE_SETTINGS.area|default:'admin'=='admin' }selected{/if}>Quản trị (Admin)</option>
                        <option value="site" {if $PAGE_SETTINGS.area=='site' }selected{/if}>Ngoài Site (Frontend)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-sm-8 offset-sm-3">
                    <div class="d-flex flex-wrap gap-4">
                        <div class="form-check form-switch custom-switch-lg">
                            <input class="form-check-input" type="checkbox" name="page_settings[features][pagination]" value="1" id="pagination_enable" {if ($PAGE_SETTINGS.features.pagination|default:true)}checked{/if}>
                            <label class="form-check-label fw-bold ml-2" for="pagination_enable">Phân trang</label>
                        </div>
                        <div class="form-check form-switch custom-switch-lg">
                            <input class="form-check-input" type="checkbox" name="page_settings[features][search]" value="1" id="search_enable" {if ($PAGE_SETTINGS.features.search|default:true)}checked{/if}>
                            <label class="form-check-label fw-bold ml-2" for="search_enable">Tìm kiếm</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-50">

            <div class="row mb-0">
                <label class="col-sm-3 col-form-label text-sm-end fw-bold">Ghi chú tổng quát cho AI</label>
                <div class="col-sm-8 col-lg-9">
                    <textarea name="page_settings[note]" class="form-control" rows="4" placeholder="Nhập các mô tả nghiệp vụ hoặc logic phức tạp để AI đọc hiểu khi sinh mã nguồn...">{$PAGE_SETTINGS.note|default:''}</textarea>
                    <div class="form-text mt-2"><i class="fa-solid fa-lightbulb text-warning me-1"></i>Mẹo: Hãy mô tả chi tiết luồng xử lý (ví dụ: Khi xóa A phải kiểm tra B, hoặc Cột X tự động tính theo công thức Y).</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thanh tác vụ cố định dưới cùng -->
    <div class="card border-0 shadow-lg text-center py-3 bg-body border-top">
        <div class="d-flex justify-content-center gap-3">
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&{$NV_NAME_VARIABLE}={$MODULE_NAME}&{$NV_OP_VARIABLE}=main{if $MODULE_FILTER}&module_filter={$MODULE_FILTER}{/if}" class="btn btn-outline-secondary px-4 py-2">
                <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
            </a>
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                <i class="fa-solid fa-save me-2"></i>Lưu Cấu Hình Schema
            </button>
            <a id="btn-create-mvc" href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&{$NV_NAME_VARIABLE}={$MODULE_NAME}&{$NV_OP_VARIABLE}=schemas-mvc&table={$TABLE}{if $MODULE_FILTER}&module_filter={$MODULE_FILTER}{/if}"
               class="btn btn-success px-4 py-2 fw-bold" data-is-existing="{if $IS_EXISTING}1{else}0{/if}">
                <i class="fa-solid fa-code me-2"></i>Tạo MVC
            </a>
        </div>
    </div>
</form>


<script>
    var _select2Opts = {
        language: nv_lang_interface,
        dir: $('html').attr('dir'),
        width: '100%'
    };

    // NukeViet URL variables (từ Smarty)
    var _nvAdminUrl = '{$NV_BASE_ADMINURL}';
    var _nvLangVar = '{$NV_LANG_VARIABLE}';
    var _nvLangData = '{$NV_LANG_DATA}';
    var _nvNameVar = '{$NV_NAME_VARIABLE}';
    var _nvOpVar = '{$NV_OP_VARIABLE}';
    var _nvModuleName = '{$MODULE_NAME}';

    // Load danh sách cột của bảng được chọn → đổ vào select ID col và Text col
    function loadTableColumns(field, table, savedId, savedText) {
        var $idSel = $('[name="columns[' + field + '][choice_id_col]"]');
        var $textSel = $('[name="columns[' + field + '][choice_text_col]"]');

        if (!table) {
            $idSel.html('<option value="">-- Cột ID/value --</option>');
            $textSel.html('<option value="">-- Cột nhãn hiển thị --</option>');
            return;
        }

        $idSel.html('<option value="">Đang tải...</option>').prop('disabled', true);
        $textSel.html('<option value="">Đang tải...</option>').prop('disabled', true);

        var params = {};
        params[_nvLangVar] = _nvLangData;
        params[_nvNameVar] = _nvModuleName;
        params[_nvOpVar] = 'schemas-columns';
        params['table'] = table;

        $.getJSON(_nvAdminUrl + 'index.php', params, function (res) {
            if (res.status !== 'OK') {
                $idSel.html('<option value="">-- Lỗi tải cột --</option>').prop('disabled', false);
                $textSel.html('<option value="">-- Lỗi tải cột --</option>').prop('disabled', false);
                return;
            }
            var idOpts = '<option value="">-- Cột ID/value --</option>';
            var textOpts = '<option value="">-- Cột nhãn hiển thị --</option>';
            $.each(res.columns, function (i, col) {
                var esc = $('<span>').text(col).html();
                idOpts += '<option value="' + esc + '"' + (col === savedId ? ' selected' : '') + '>' + esc + '</option>';
                textOpts += '<option value="' + esc + '"' + (col === savedText ? ' selected' : '') + '>' + esc + '</option>';
            });
            $idSel.html(idOpts).prop('disabled', false);
            $textSel.html(textOpts).prop('disabled', false);
        });
    }

    // Khởi tạo select2 cho dropdown chọn bảng nguồn (lazy — gọi khi panel SQL lần đầu hiện)
    function initChoiceTableSelect2(field) {
        var $sel = $('#cs-sql-cfg-' + field + ' .choice-table-select');
        if ($sel.length && !$sel.data('select2')) {
            $sel.select2(_select2Opts);
        }
    }

    // Hiện/ẩn div choice-panel theo view_type
    function toggleChoicePanel(field, viewType) {
        var $panel = $('#choice-panel-' + field);
        if (!$panel.length) return;
        if (viewType === 'select' || viewType === 'radio') {
            $panel.show();
            if ($('#cs-sql-cfg-' + field).is(':visible')) {
                initChoiceTableSelect2(field);
            }
        } else {
            $panel.hide();
        }
    }

    // Chuyển đổi giữa panel tĩnh và panel CSDL
    function toggleChoiceSource(field, sourceType) {
        if (sourceType === 'sql') {
            $('#cs-static-cfg-' + field).hide();
            $('#cs-sql-cfg-' + field).show();
            initChoiceTableSelect2(field);
        } else {
            $('#cs-sql-cfg-' + field).hide();
            $('#cs-static-cfg-' + field).show();
        }
    }

    $(document).ready(function () {
        // Khởi tạo select2 — loại trừ choice-table-select (lazy-init khi panel SQL hiện)
        $('.select2:not(.choice-table-select)').select2(_select2Opts);

        // Khởi tạo trạng thái ban đầu cho choice-panel theo view_type hiện tại
        $('.view-type-select').each(function () {
            var field = $(this).data('field');
            var viewType = $(this).val();
            toggleChoicePanel(field, viewType);
        });

        // Thay đổi view_type → toggle choice-panel
        $(document).on('change', '.view-type-select', function () {
            var field = $(this).data('field');
            var viewType = $(this).val();
            toggleChoicePanel(field, viewType);
        });

        // Chuyển đổi static ↔ sql
        $(document).on('change', '.choice-type-toggle', function () {
            var field = $(this).data('field');
            var sourceType = $(this).val();
            toggleChoiceSource(field, sourceType);
        });

        // Khi chọn bảng nguồn → load danh sách cột
        $(document).on('change', '.choice-table-select', function () {
            var field = $(this).data('field');
            var table = $(this).val();
            loadTableColumns(field, table, '', '');
        });

        // Khởi tạo: load columns cho các field đang ở chế độ sql và đã có bảng chọn
        $('.choice-table-select').each(function () {
            var $sel = $(this);
            var table = $sel.val();
            if (!table) return;
            if (!$('#cs-sql-cfg-' + $sel.data('field')).is(':visible')) return;
            var field = $sel.data('field');
            loadTableColumns(field, table, $sel.data('saved-id') || '', $sel.data('saved-text') || '');
        });

        // Khởi tạo: Ẩn nút Tạo MVC nếu chưa có cấu hình
        var $btnMvc = $('#btn-create-mvc');
        if ($btnMvc.length && $btnMvc.data('is-existing') == 0) {
            $btnMvc.hide();
        }
    });

    // Callback toàn cục cho form ajax-submit (NukeViet standard)
    function onSchemaSaveSuccess(res) {
        if (res && (res.status === 'OK' || (res.mess && res.mess.indexOf('thành công') !== -1))) {
            var $btn = $('#btn-create-mvc');
            if ($btn.length) {
                $btn.fadeIn().css('display', 'inline-block');
            }
        }
    }

</script>

<style>
    .form-check-input {
        cursor: pointer;
    }

    .card-header h5 {
        letter-spacing: 0.5px;
    }

    .card {
        border-radius: 12px;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .custom-switch-lg .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }

    #sqlCodeContent {
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 13px;
        line-height: 1.5;
    }
</style>
