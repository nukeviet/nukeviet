<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/highlightjs/default.min.css">
<script src="{ASSETS_STATIC_URL}/js/highlightjs/highlight.min.js"></script>
<script src="{ASSETS_STATIC_URL}/js/highlightjs/lang/accesslog.min.js"></script>
<div class="row m-bottom">
    <div class="col-sm-16 col-md-14 col-lg-12">
        <div class="d-flex">
            <select class="form-control" id="errorfile" data-url="{PAGE_URL}">
                <!-- BEGIN: error_file -->
                <option value="{ERRORFILE.val}" {ERRORFILE.sel}>{ERRORFILE.name}</option>
                <!-- END: error_file -->
            </select>
            <select class="form-control" id="display-mode" data-url="{PAGE_URL}" style="width: fit-content;margin-left:5px">
                <!-- BEGIN: display_mode -->
                <option value="{MODE.val}" {MODE.sel}>{MODE.name}</option>
                <!-- END: display_mode -->
            </select>
        </div>
    </div>
</div>

<div class="panel panel-primary" id="error-content" style="<!-- BEGIN: plaintext_mode_hide -->display:none;<!-- END: plaintext_mode_hide -->">
    <div class="panel-heading"><i class="fa fa-bug" aria-hidden="true"></i> <strong class="error_file_name">{ERROR_FILE_NAME}</strong></div>
    <pre class="mb-0" style="border-top-left-radius:0;border-top-right-radius:0"><code class="language-accesslog" style="padding: 0;background-color: transparent;white-space: pre">{ERROR_FILE_CONTENT}</code></pre>
</div>
<div class="panel-group" id="errorlist" role="tablist" aria-multiselectable="true" style="<!-- BEGIN: tabular_mode_hide -->display:none;<!-- END: tabular_mode_hide -->">
    {ERRORLIST}
</div>
<!-- END: main -->

<!-- BEGIN: errorlist -->
<!-- BEGIN: error -->
<div class="panel panel-primary">
    <a class="panel-heading" role="tab" id="heading-{ERROR.id}" style="display: block;text-decoration:none" data-toggle="collapse" href="#collapse-{ERROR.id}" aria-expanded="true" aria-controls="collapse-{ERROR.id}">
        {LANG.errorlog_time}: {ERROR.time}
    </a>
    <div id="collapse-{ERROR.id}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-{ERROR.id}">
        <table class="table table-striped table-bordered">
            <colgroup>
                <col style="width:1%" />
            </colgroup>
            <tbody>
                <!-- BEGIN: option -->
                <tr>
                    <td class="text-nowrap">{OPTION.title}:</td>
                    <td>
                        {OPTION.value}
                        <!-- BEGIN: note -->
                        <div class="small text-muted">{NOTE}</div><!-- END: note -->
                    </td>
                </tr>
                <!-- END: option -->
            </tbody>
        </table>
    </div>
</div>
<!-- END: error -->
<!-- END: errorlist -->

<!-- BEGIN: filelist_empty -->
<div class="alert alert-info">{LANG.error_filelist_empty}</div>
<!-- END: filelist_empty -->