<form action="{$NV_BASE_ADMINURL}index.php" method="get" class="form-search">
    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
    <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
    <div class="row">
        <div class="col-xs-24 col-sm-8 col-lg-5">
            <div class="form-group">
                <label>{$LANG->getModule('keywords')}:</label>
                <input type="text" class="form-control" name="q" value="{$SEARCH.q}" placeholder="{$LANG->getModule('keywords')}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-lg-3">
            <div class="form-group">
                <label>{$LANG->getModule('from')}:</label>
                <input type="text" class="form-control datepicker-search" name="f" value="{$SEARCH.from}" autocomplete="off" placeholder="{$DATE_FORMAT}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-lg-3">
            <div class="form-group">
                <label>{$LANG->getModule('to')}:</label>
                <input type="text" class="form-control datepicker-search" name="t" value="{$SEARCH.to}" autocomplete="off" placeholder="{$DATE_FORMAT}">
            </div>
        </div>
        <div class="col-xs-24 col-sm-6 col-lg-5">
            <div class="form-group">
                <label>{$LANG->getModule('tpl_incat')}:</label>
                <select class="form-control select2" name="c">
                    <option value="0">{$LANG->getModule('all')}</option>
                    {foreach from=$CATS item=cat}
                    <option value="{$cat.catid}"{if $cat.catid eq $SEARCH.catid} selected="selected"{/if}>{$cat.title}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="col-xs-24 col-sm-24 col-lg-8">
            <div class="form-group">
                <label class="visible-lg-block">&nbsp;</label>
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i> {$LANG->getGlobal('search')}
                </button>
            </div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-nowrap bg-primary" style="width: 40%;">{$LANG->getModule('tpl_title')}</th>
                <th class="text-nowrap bg-primary" style="width: 25%;">{$LANG->getModule('tpl_incat')}</th>
                <th class="text-nowrap bg-primary" style="width: 15%;">{$LANG->getModule('add_edit')}</th>
                <th class="text-nowrap bg-primary text-center" style="width: 20%;">{$LANG->getModule('function')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$DATA item=row}
            <tr>
                <td>
                    <div>
                        {if $row.is_disabled}<i class="text-muted fa fa-times-circle" title="{$LANG->get('tpl_is_disabled')}"></i>{else}<i class="text-success fa fa-check-circle" title="{$LANG->get('tpl_is_active')}"></i>{/if}
                        {if $row.is_disabled}<span class="text-muted">{$row.title}</span> <span class="label label-default">{$LANG->get('tpl_is_disabled_label')}</span>{else}{$row.title}{/if}
                        {if $row.module_name}
                        <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;m={$row.module_name}&amp;l={$row.lang}"><span class="label label-primary">{$MODULES[$row.lang][$row.module_name]}</span></a>
                        <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;l={$row.lang}"><span class="label label-info">{$LANGS[{$row.lang}].name}</span></a>
                        {elseif not $row.is_system}<span class="label label-danger">{$LANG->get('tpl_custom_label')}</span>{/if}
                    </div>
                </td>
                <td>
                    {if isset($CATS[$row.catid])}
                    <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;c={$row.catid}">{$CATS[$row.catid].title}</a>
                    {elseif not empty($row.catid)}
                    #{$row.catid}
                    {/if}
                </td>
                <td class="cell-detail">
                    <span>{$row.time_add|date:1}</span>
                    {if not empty($row.time_update)}<div class="text-muted">{$row.time_update|date:1}</div>{/if}
                </td>
                <td class="text-nowrap text-center">
                    <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=test&amp;emailid={$row.emailid}" class="text-primary" title="{$LANG->get('test')}" data-toggle="tooltip"><i class="fa fa-lg fa-paper-plane"></i></a>
                    <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=contents&amp;copyid={$row.emailid}" class="text-primary ml-1" title="{$LANG->get('copy')}" data-toggle="tooltip"><i class="fa fa-lg fa-copy"></i></a>
                    <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=contents&amp;emailid={$row.emailid}" class="text-primary ml-1" title="{$LANG->get('edit')}" data-toggle="tooltip"><i class="fa fa-lg fa-pencil"></i></a>
                    {if not $row.is_system and not $row.module_name}<a href="#" class="text-danger ml-1" title="{$LANG->get('delete')}" data-click="deltpl" data-emailid="{$row.emailid}" data-checksess="{$smarty.const.NV_CHECK_SESSION}" data-toggle="tooltip"><i class="fa fa-lg fa-trash"></i></a>{/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
        {if not empty($GENERATE_PAGE)}
        <tfoot>
            <tr>
                <td colspan="4">
                    {$GENERATE_PAGE}
                </td>
            </tr>
        </tfoot>
        {/if}
    </table>
</div>

<link rel="stylesheet" type="text/css" href="{$ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="{$ASSETS_STATIC_URL}/js/select2/select2.min.css">

<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$NV_LANG_INTERFACE}.js"></script>
