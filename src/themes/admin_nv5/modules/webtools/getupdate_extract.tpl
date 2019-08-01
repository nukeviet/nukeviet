{if not empty($NO_EXTRACT)}
<h3 class="mt-1 text-danger">{$LANG->get('get_update_cantunzip')}</h3>
<pre><code>{foreach from=$NO_EXTRACT item=file}{$file}<br />{/foreach}</code></pre>
{elseif not empty($ERROR_CREATE_FOLDER)}
<h3 class="mt-1 text-danger">{$LANG->get('get_update_warning_permission_folder')}</h3>
<pre><code>{foreach from=$ERROR_CREATE_FOLDER item=file}{$file}<br />{/foreach}</code></pre>
{elseif not empty($ERROR_MOVE_FOLDER)}
<h3 class="mt-1 text-danger">{$LANG->get('get_update_error_movefile')}</h3>
<pre><code>{foreach from=$ERROR_MOVE_FOLDER item=file}{$file}<br />{/foreach}</code></pre>
{else}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message" id="prevent-link">
        <p>{$LANG->get('get_update_okunzip')}</p>
        <a href="{$URL_GO}" title="{$LANG->get('get_update_okunzip_link')}">{$LANG->get('get_update_okunzip_link')}</a>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
setTimeout(function() {
    window.location = "{$URL_GO}";
}, 5000);
//]]>
</script>
{/if}
