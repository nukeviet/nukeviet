{if !empty($LANGS)}
<div class="language">
    {$LANG->get('langsite')}:
    <select name="lang" class="nv_change_site_lang">
{foreach $LANGS as $l}
        <option value="{$l.url}"{if $l.sel} selected="selected"{/if}>{$l.name}</option>
{/foreach}
    </select>
</div>
{/if}
