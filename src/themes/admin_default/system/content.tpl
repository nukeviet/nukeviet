{include file='header.tpl'}
<div class="body min-vh-100">
    {$MODULE_CONTENT}
</div>
<div id="admin-session-timeout" class="nv-offcanvas text-bg-warning p-3">
    {$LANG->getGlobal('timeoutsess_nouser')}, <a data-toggle="cancel" href="#">{$LANG->getGlobal('timeoutsess_click')}</a>. {$LANG->getGlobal('timeoutsess_timeout')}: <span data-toggle="sec"> 60 </span> {$LANG->getGlobal('sec')}
</div>
{include file='footer.tpl'}
