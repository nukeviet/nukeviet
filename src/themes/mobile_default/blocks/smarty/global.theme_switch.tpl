<div class="form-group">
    <label for="nvchoosetheme{$CONFIG.bid}">{$LANG->get('selecttheme')}:</label>
    <select class="form-control" data-toggle="nvchoosetheme" data-tokend="{$TOKEND}" id="nvchoosetheme{$CONFIG.bid}">
{foreach $THEMES as $theme}
        <option value="{$theme.key}"{if $theme.sel} selected="selected"{/if}>{$theme.title}</option>
{/foreach}
        <option value="">{$LANG->get('default_theme')}</option>
    </select>
</div>
