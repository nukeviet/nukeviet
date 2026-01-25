<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/default.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/highlight.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlightjs/lang/accesslog.min.js"></script>
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex gap-2">
            {assign var="optgroupLabel" value=[
                "error" => $LANG->getModule('errorlog_log'),
                "notice" => $LANG->getModule('noticelog_log'),
                "e256" => $LANG->getModule('errorlog_256'),
                "sendmail" => $LANG->getModule('errorlog_sendmail')
            ]}
            <div>
                <select class="form-select" id="errorfile" data-url="{$PAGE_URL}">
                    {foreach from=$FILELIST key=type item=list}
                    {if not empty($list)}
                    {assign var="list" value=array_keys($list) nocache}
                    <optgroup label="{$optgroupLabel[$type] ?? 'N/A'}">
                        {foreach from=$list item=key}
                        <option value="{$key}"{if $key eq $ERROR_FILE_NAME} selected{/if}>{$key}</option>
                        {/foreach}
                    </optgroup>
                    {/if}
                    {/foreach}
                </select>
            </div>
            <div>
                <select class="form-select" id="display-mode" data-url="{$PAGE_URL}">
                    {foreach from=$MODES key=key item=value}
                    <option value="{$key}"{if $key eq $MODE} selected{/if}>{$value}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
</div>
<div class="card text-bg-primary{if $MODE eq 'tabular'} d-none{/if}" id="error-content">
    <div class="card-header fw-medium">
        <i class="fa-solid fa-bug"></i> <span class="error_file_name">{$ERROR_FILE_NAME}</span>
    </div>
    <div class="card-body text-body bg-body">
        <pre class="mb-0"><code class="language-json errorlog-plain-view">{$ERROR_FILE_CONTENT}</code></pre>
    </div>
</div>
<div id="errorlist" role="tablist" aria-multiselectable="true" class="vstack gap-2{if $MODE neq 'tabular'} d-none{/if}">
    {$ERRORLIST}
</div>
