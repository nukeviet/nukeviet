<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message"><a href="{$URL}">{$LANG->get('nv_lang_readok')}</a></div>
</div>
<pre><code>
{foreach from=$ARRAY_FILENAME item=filename}{if not empty($filename)}<p>{$filename}</p>{/if}{/foreach}
</code></pre>
<meta http-equiv="Refresh" content="10;URL={$URL}">
