{if empty($CONTENT)}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message"><a href="{$URL}">{$LANG->get('nv_lang_wite_ok')}: {$INCLUDE_LANG}</a></div>
</div>
<meta http-equiv="Refresh" content="10;URL={$URL}" />
{else}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$CONTENT}</div>
</div>
{/if}
