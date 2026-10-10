{*
Xóa tệp này khỏi giao diện của bạn nếu không có nhu cầu phát triển giao diện thanh công cụ của quản trị
Khi đó hệ thống sẽ lấy từ themes/default/system/admin_toolbar.tpl
*}
<div id="admintoolbar">
    <ul>
        <li><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}"><span class="icon"><i class="fa-solid fa-gears fa-fw"></i></span><span class="text">{$LANG->getGlobal('admin_page')}</span></a></li>
        {if $smarty.const.NV_IS_MODADMIN and not empty($MODULE_INFO.admin_file)}
        <li><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}"><span class="icon"><i class="fa-solid fa-wrench fa-fw"></i></span><span class="text">{$LANG->getGlobal('admin_module_sector')} {$MODULE_INFO.custom_title}</span></a></li>
        {/if}
        {if $ENABLE_DRAG}
        <li><a href="{$URL_DBLOCK}"><span class="icon"><i class="fa-solid fa-object-group fa-fw"></i></span><span class="text">{$LANG->getGlobal($smarty.const.NV_IS_DRAG_BLOCK ? 'no_drag_block' : 'drag_block')}</span></a></li>
        {/if}
        <li><a href="{$URL_AUTHOR}"><span class="icon"><i class="fa-solid fa-user-gear fa-fw"></i></span><span class="text">{$LANG->getGlobal('admin_view')}</span></a></li>
        <li><a href="#" data-toggle="nv_admin_logout"><span class="icon"><i class="fa-solid fa-power-off fa-fw"></i></span><span class="text">{$LANG->getGlobal('logout')}</span></a></li>
    </ul>
</div>
