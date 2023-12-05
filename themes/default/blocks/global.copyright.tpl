<div class="copyright">
    <span>&copy;&nbsp;{$LANG->get('copyright_by')} <a href="{$DATA.copyright_url}">{$DATA.copyright_by}</a>.&nbsp; </span>
    <span>{$LANG->get('powered_by')} <a href="http://nukeviet.vn/" target="_blank" rel="dofollow">NukeViet CMS</a>.&nbsp; </span>
{if !empty($DATA.design_by)}
    <span>{$LANG->get('design_by')} {if !empty($DATA.design_url)}<a href="{$DATA.design_url}" target="_blank" rel="dofollow">{/if}{$DATA.design_by}{if !empty($DATA.design_url)}</a>{/if}.&nbsp; </span>
{/if}
{if !empty($DATA.siteterms_url)}
    <span>&nbsp;|&nbsp;&nbsp;<a href="$DATA.siteterms_url">{$LANG->get('siteterms')}</a></span>
{/if}
{if $smarty.const.NV_IS_SPADMIN}
    <span>&nbsp;|&nbsp;&nbsp;{$LANG->get('for_admin')}: [MEMORY_TIME_USAGE]</span>
{/if}
</div>
