{if $IS_ERROR}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('nv_error_write_file')}</div>
</div>
{else}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message"><a href="{$URL}">{$LANG->get('nv_lang_wite_ok')}</a></div>
</div>
<meta http-equiv="Refresh" content="10;URL={$URL}" />
{if not empty($ARRAY_FILENAME)}
<pre><code>
{foreach from=$ARRAY_FILENAME item=filename}{if not empty($filename)}<p>{$filename}</p>{/if}{/foreach}
</code></pre>
{/if}
{/if}
