{if $PACKAGE_UPDATE}
<div class="card text-bg-primary mb-4" id="notice-update-package">
    <div class="card-body text-center">
        <p class="mb-2">{$LANG->getModule('update_package_detected')}</p>
        <div class="row g-3 justify-content-center">
            <div class="col-auto">
                <a href="{$smarty.const.NV_BASE_SITEURL}install/update.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-up-from-bracket text-primary"></i> {$LANG->getModule('update_package_do')}</a>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-secondary" data-toggle="deleteUpdPkg" data-checksess="{$smarty.const.NV_CHECK_SESSION}"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i> {$LANG->getModule('update_package_delete')}</a>
            </div>
        </div>
    </div>
</div>
{/if}
{if $IS_EDIT}
<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
{/if}
<div class="widget-containers" data-busy="0">
    <div class="row">
        {if $IS_EDIT}
        <div class="text-center mb-3">
            <button class="btn btn-primary" data-toggle="widgetParentAdd" data-placement="top"><i class="fa-solid fa-plus" data-icon="fa-plus"></i> {$LANG->getModule('add_widget_top')}</button>
        </div>
        {/if}
        {foreach from=$TCONFIG.grid_widgets key=widget_id item=widget}
        <div class="{foreach from=$widget.sizes key=breakpoint item=colsize}col-{$breakpoint}-{$colsize} {/foreach}{if empty($widget.subs) or $IS_EDIT} mb-4{/if}" data-append-class="{if empty($widget.subs) or $IS_EDIT} mb-4{/if}">
            {if $IS_EDIT}
            <div class="widget-edit{if empty($widget.subs)} widget-edit-drop{/if} position-relative bg-warning-subtle p-1{if not empty($widget.subs)} pt-4 pb-0{/if}" id="widget_{$widget_id}" data-id="{$widget_id}" data-parent-id="-1">
            {/if}
            {if not empty($widget.subs)}
            <div class="row h-100">
                {foreach from=$widget.subs key=widget_subid item=subwidget}
                <div class="{foreach from=$subwidget.sizes key=breakpoint item=colsize}col-{$breakpoint}-{$colsize} {/foreach} mb-4" data-append-class="mb-4">
                    {if $IS_EDIT}
                    <div class="widget-edit widget-edit-drop position-relative bg-info-subtle p-1" id="widget_{$widget_id}_sub{$widget_subid}" data-id="{$widget_subid}" data-parent-id="{$widget_id}">
                    {/if}
                    {if isset($WIDGETS[$subwidget.widget_id])}
                    <div class="card widget">
                        {$WIDGETS[$subwidget.widget_id]}
                    </div>
                    {/if}
                    {if $IS_EDIT}
                    <div class="tools position-absolute top-50 start-50 translate-middle">
                        <div class="d-flex">
                            <div class="dropdown" data-bs-toggle="tooltip" title="{$LANG->getModule('widget_resize')}">
                                <a href="#" data-widget="widget_{$widget_id}_sub{$widget_subid}" data-toggle="widgetSize" class="text-bg-secondary rounded-circle position-relative d-block ic" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,5" data-bs-auto-close="outside"><i class="fa-solid fa-ellipsis ico-vc fa-sm"></i></a>
                                <div class="dropdown-menu px-3 pt-3 pb-1">
                                    <p class="fw-medium mb-2">{$LANG->getModule('sizeby_screen')}</p>
                                    {foreach from=$THEME_GRIDS key=key item=value}
                                    <div class="d-flex align-items-center mb-2">
                                        <select data-toggle="widgetResize" data-breakpoint="{$key}" data-id="{$widget_subid}" data-parent-id="{$widget_id}" class="form-select form-select-sm widget-col-select me-2" aria-label="widget_{$widget_id}_sub{$widget_subid}_size_{$key}_label" id="widget_{$widget_id}_sub{$widget_subid}_size_{$key}">
                                            {for $col=1 to 12}
                                            <option value="{$col}"{if $col eq $subwidget.sizes[$key]} selected="selected"{/if}>{$col}</option>
                                            {/for}
                                        </select>
                                        <label for="widget_{$widget_id}_sub{$widget_subid}_size_{$key}" id="widget_{$widget_id}_sub{$widget_subid}_size_{$key}_label"><span class="fw-medium">{$key}</span> {$value}</label>
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                            <a data-toggle="widgetChoose" data-id="{$widget_subid}" data-parent-id="{$widget_id}" href="#" class="ms-2 text-bg-info rounded-circle position-relative ic" data-bs-toggle="tooltip" title="{$LANG->getModule('choose_widget')}"><i class="fa-solid fa-pen ico-vc fa-sm"></i></a>
                            <a data-toggle="widgetDelete" data-id="{$widget_subid}" data-parent-id="{$widget_id}" href="#" class="ms-2 text-bg-danger rounded-circle position-relative ic" data-bs-toggle="tooltip" title="{$LANG->getModule('delete_widget')}"><i class="fa-solid fa-xmark ico-vc fa-sm" data-icon="fa-xmark"></i></a>
                        </div>
                    </div>
                    </div>
                    {/if}
                </div>
                {/foreach}
            </div>
            {else}
            {if isset($WIDGETS[$widget.widget_id])}
            <div class="card widget">
                {$WIDGETS[$widget.widget_id]}
            </div>
            {/if}
            {/if}
            {if $IS_EDIT}
            <div class="tools top-0 position-absolute start-100 translate-middle">
                <div class="d-flex">
                    <a href="#" data-toggle="widgetAddChild" data-placement="top" data-id="{$widget_id}" class="text-bg-primary rounded-circle position-relative ic" data-bs-toggle="tooltip" title="{$LANG->getModule('addchild_widget_top')}"><i class="fa-solid fa-plus ico-vc"></i></a>
                </div>
            </div>
            <div class="tools top-100 position-absolute tool-bottom">
                <div class="d-flex">
                    <a href="#" data-toggle="widgetAddChild" data-placement="bottom" data-id="{$widget_id}" class="text-bg-primary rounded-circle position-relative ic" data-bs-toggle="tooltip" title="{$LANG->getModule('addchild_widget_bottom')}"><i class="fa-solid fa-plus ico-vc"></i></a>
                </div>
            </div>
            <div class="tools position-absolute {if not empty($widget.subs)}tool-top start-50 translate-middle-x{else}top-50 start-50 translate-middle{/if}">
                <div class="d-flex">
                    <div class="dropdown" data-bs-toggle="tooltip" title="{$LANG->getModule('widget_resize')}">
                        <a href="#" data-widget="widget_{$widget_id}" data-toggle="widgetSize" class="text-bg-secondary rounded-circle position-relative d-block ic" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,5" data-bs-auto-close="outside"><i class="fa-solid fa-ellipsis ico-vc fa-sm"></i></a>
                        <div class="dropdown-menu px-3 pt-3 pb-1">
                            <p class="fw-medium mb-2">{$LANG->getModule('sizeby_screen')}</p>
                            {foreach from=$THEME_GRIDS key=key item=value}
                            <div class="d-flex align-items-center mb-2">
                                <select data-toggle="widgetResize" data-breakpoint="{$key}" data-id="{$widget_id}" data-parent-id="-1" class="form-select form-select-sm widget-col-select me-2" aria-label="widget_{$widget_id}_size_{$key}_label" id="widget_{$widget_id}_size_{$key}">
                                    {for $col=1 to 12}
                                    <option value="{$col}"{if $col eq $widget.sizes[$key]} selected="selected"{/if}>{$col}</option>
                                    {/for}
                                </select>
                                <label for="widget_{$widget_id}_size_{$key}" id="widget_{$widget_id}_size_{$key}_label"><span class="fw-medium">{$key}</span> {$value}</label>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    {if empty($widget.subs)}
                    <a href="#" data-toggle="widgetChoose" data-id="{$widget_id}" data-parent-id="-1" class="ms-2 text-bg-info rounded-circle position-relative ic" data-bs-toggle="tooltip" title="{$LANG->getModule('choose_widget')}"><i class="fa-solid fa-pen ico-vc fa-sm"></i></a>
                    {/if}
                    <a href="#" data-toggle="widgetDelete" data-id="{$widget_id}" data-parent-id="-1" class="ms-2 text-bg-danger rounded-circle position-relative ic" data-bs-toggle="tooltip" title="{$LANG->getModule('delete_widget')}"><i class="fa-solid fa-xmark ico-vc fa-sm" data-icon="fa-xmark"></i></a>
                </div>
            </div>
            </div>
            {/if}
        </div>
        {/foreach}
        {if $IS_EDIT}
        <div class="text-center">
            <button class="btn btn-primary" data-toggle="widgetParentAdd" data-placement="bottom"><i class="fa-solid fa-plus" data-icon="fa-plus"></i> {$LANG->getModule('add_widget_bottom')}</button>
        </div>
        {/if}
    </div>
</div>
{if $IS_EDIT}
<div class="modal fade" id="mdChooseWidget" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mdChooseWidgetLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="mdChooseWidgetLabel">{$LANG->getModule('widget_choose')}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="loader text-center">
                    <i class="fa-solid fa-spinner fa-spin-pulse fa-3x"></i>
                </div>
                <div class="content"></div>
            </div>
        </div>
    </div>
</div>
{/if}
