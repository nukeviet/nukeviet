<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <div class="text-muted">{$LANG->getModule('group')}: {$DATA.title}</div>
        <h1 class="h4 mb-0">{$LANG->getGlobal('inform_notifications')}</h1>
    </div>
    <a href="{$GROUP_MANAGER_URL}" class="btn btn-secondary" title="{$LANG->getModule('group_manage')}">
        <i class="fa-solid fa-reply"></i> {$LANG->getModule('group_manage')}
    </a>
</div>
<div data-toggle="groupInform" data-ajax-url="{$INFORM_MANAGER_URL}"></div>
