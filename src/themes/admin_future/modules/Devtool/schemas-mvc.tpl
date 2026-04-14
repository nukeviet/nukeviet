<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">
        <i class="fa-solid fa-code me-2 text-success"></i>Tạo MVC: <code>{$TABLE}</code>
    </h4>
    <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&{$NV_NAME_VARIABLE}={$MODULE_NAME}&{$NV_OP_VARIABLE}=schemas&table={$TABLE}{if $MODULE_FILTER}&module_filter={$MODULE_FILTER}{/if}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i>Quay lại Schema
    </a>
</div>

{* Thông tin tổng quát *}
{assign var="ps" value=$ENTITY.page_settings}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="text-muted small">Module đích</div>
                <div class="fw-bold text-primary">{$ps.module}</div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="text-muted small">Function (OP)</div>
                <div class="fw-bold">{$ps.function_name}</div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="text-muted small">Loại layout</div>
                <div class="fw-bold">{$ps.layout_type}</div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="text-muted small">Số file sẽ tạo</div>
                <div class="fw-bold text-success">{$FILES|count} file</div>
            </div>
        </div>
    </div>
</div>

{* Danh sách file sẽ được tạo *}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-body-tertiary">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-folder-open me-2"></i>Danh sách file sẽ được tạo</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="bg-body-secondary">
                <tr>
                    <th>#</th>
                    <th>Đường dẫn file</th>
                    <th class="text-center" style="width:120px">Trạng thái</th>
                    <th class="text-center" style="width:100px">Preview</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$FILES item=file name=floop}
                <tr>
                    <td class="text-muted">{$smarty.foreach.floop.iteration}</td>
                    <td><code class="small">{$file.path}</code></td>
                    <td class="text-center">
                        {if $file.exists}
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-triangle-exclamation me-1"></i>Đã tồn tại (sẽ ghi đè)</span>
                        {else}
                            <span class="badge bg-success"><i class="fa-solid fa-plus me-1"></i>Tạo mới</span>
                        {/if}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-info btn-sm"
                                onclick="showPreview({$smarty.foreach.floop.iteration - 1})">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

{* Vùng preview code *}
<div class="card border-0 shadow-sm mb-4" id="preview-card" style="display:none">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span class="fw-bold"><i class="fa-solid fa-file-code me-2"></i><span id="preview-title"></span></span>
        <button type="button" class="btn btn-sm btn-outline-light" onclick="copyPreview()">
            <i class="fa-solid fa-copy me-1"></i>Copy
        </button>
    </div>
    <div class="card-body p-0">
        <pre id="preview-content" class="bg-dark text-light m-0 p-3" style="max-height: 450px; overflow-y:auto; font-size: 12px; line-height: 1.5;"></pre>
    </div>
</div>

{* Form sinh file *}
<form id="form-generate" action="{$NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}" />
    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}" />
    <input type="hidden" name="{$NV_OP_VARIABLE}" value="schemas-mvc" />
    <input type="hidden" name="table" value="{$TABLE}" />
    <input type="hidden" name="module_filter" value="{$MODULE_FILTER}" />
    <input type="hidden" name="submit_generate" value="1" />
    <input type="hidden" name="checkss" value="{$CHECKSS}" />
</form>

<div class="card border-0 shadow-lg text-center py-3 bg-body border-top">
    <div class="d-flex justify-content-center gap-3">
        <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&{$NV_NAME_VARIABLE}={$MODULE_NAME}&{$NV_OP_VARIABLE}=schemas&table={$TABLE}{if $MODULE_FILTER}&module_filter={$MODULE_FILTER}{/if}" class="btn btn-outline-secondary px-4 py-2">
            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
        </a>
        <button type="button" class="btn btn-success px-5 py-2 fw-bold" id="btn-generate">
            <i class="fa-solid fa-code me-2"></i>Sinh file MVC
        </button>
    </div>
</div>

{* Dữ liệu file content dạng JSON để JS preview *}
<script id="files-data" type="application/json">
{$FILES_JSON nofilter}
</script>

<div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="result-modal-header">
                <h5 class="modal-title fw-bold" id="result-modal-title"></h5>
            </div>
            <div class="modal-body" id="result-modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
{literal}
    var filesElement = document.getElementById('files-data');
    var filesData = [];
    if (filesElement && filesElement.textContent.trim() !== '') {
        try {
            filesData = JSON.parse(filesElement.textContent);
        } catch (e) {
            console.error('JSON Parse Error:', e);
            console.log('JSON Content:', filesElement.textContent);
        }
    }

    function showPreview(idx) {
        var file = filesData[idx];
        if (!file) return;
        document.getElementById('preview-title').textContent = file.path;
        document.getElementById('preview-content').textContent = file.content;
        document.getElementById('preview-card').style.display = '';
        document.getElementById('preview-card').scrollIntoView({behavior: 'smooth'});
    }

    function copyPreview() {
        var text = document.getElementById('preview-content').textContent;
        navigator.clipboard.writeText(text).then(function () {
            alert('Đã copy nội dung file!');
        });
    }

    document.getElementById('btn-generate').addEventListener('click', function () {
        if (!confirm('Bạn có chắc muốn ghi ' + filesData.length + ' file MVC? Các file đã tồn tại sẽ bị ghi đè.')) {
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang tạo...';

        var form = document.getElementById('form-generate');
        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {Accept: 'application/json'},
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-code me-2"></i>Sinh file MVC';

            var isOk = res.status === 'OK';
            var header = document.getElementById('result-modal-header');
            header.className = 'modal-header ' + (isOk ? 'bg-success text-white' : 'bg-warning');
            document.getElementById('result-modal-title').textContent = isOk ? 'Tạo MVC thành công!' : 'Có lỗi khi tạo';

            var body = '<p>' + (res.mess || '') + '</p>';
            if (res.written && res.written.length) {
                body += '<p class="fw-bold text-success">File đã ghi:</p><ul>';
                res.written.forEach(function (p) { body += '<li><code>' + p + '</code></li>'; });
                body += '</ul>';
            }
            if (res.errors && res.errors.length) {
                body += '<p class="fw-bold text-danger">File lỗi:</p><ul>';
                res.errors.forEach(function (p) { body += '<li><code>' + p + '</code></li>'; });
                body += '</ul>';
            }
            document.getElementById('result-modal-body').innerHTML = body;

            var modal = new bootstrap.Modal(document.getElementById('resultModal'));
            modal.show();
        })
        .catch(function (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-code me-2"></i>Sinh file MVC';
            alert('Lỗi kết nối: ' + err.message);
        });
    });
{/literal}
</script>
