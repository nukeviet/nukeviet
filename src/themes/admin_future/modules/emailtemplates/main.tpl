<div class="card">
    <div class="card-body">
        <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="form-search">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-lg-3">
                    <label for="element_q">{$LANG->getModule('keywords')}:</label>
                    <input type="text" class="form-control" id="element_q" name="q" value="{$SEARCH.q}" placeholder="{$LANG->getModule('keywords')}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="element_f">{$LANG->getModule('from')}:</label>
                    <input type="text" class="form-control datepicker-search" name="f" id="element_f" value="{$SEARCH.from}" autocomplete="off" placeholder="{$DATE_FORMAT}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="element_t">{$LANG->getModule('to')}:</label>
                    <input type="text" class="form-control datepicker-search" name="t" id="element_t" value="{$SEARCH.to}" autocomplete="off" placeholder="{$DATE_FORMAT}">
                </div>
                <div class="col-6 col-lg-3">
                    <label for="element_c">{$LANG->getModule('tpl_incat')}:</label>
                    <select class="form-select select2" name="c" id="element_c">
                        <option value="0">{$LANG->getModule('all')}</option>
                        {foreach from=$CATS item=cat}
                        <option value="{$cat.catid}"{if $cat.catid eq $SEARCH.catid} selected="selected"{/if}>{$cat.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> {$LANG->getGlobal('search')}
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 40%;">{$LANG->getModule('tpl_title')}</th>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('tpl_incat')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('add_edit')}</th>
                        <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA item=row}
                    <tr>
                        <td>
                            <div>
                                {if $row.is_disabled}<i class="text-muted fa-solid fa-circle-xmark" title="{$LANG->get('tpl_is_disabled')}" aria-label="{$LANG->get('tpl_is_disabled')}"></i>{else}<i class="text-success fa-solid fa-circle-check" title="{$LANG->get('tpl_is_active')}" aria-label="{$LANG->get('tpl_is_active')}"></i>{/if}
                                {if $row.is_disabled}<span class="text-muted">{$row.title}</span> <span class="badge text-bg-secondary">{$LANG->get('tpl_is_disabled_label')}</span>{else}{$row.title}{/if}
                                {if $row.module_name}
                                <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;m={$row.module_name}&amp;l={$row.lang}"><span class="badge text-bg-primary">{$MODULES[$row.lang][$row.module_name]}</span></a>
                                <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;l={$row.lang}"><span class="badge text-bg-info">{$LANGS[{$row.lang}].name}</span></a>
                                {elseif not $row.is_system}<span class="badge text-bg-danger">{$LANG->get('tpl_custom_label')}</span>{/if}
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
                            <div class="hstack gap-1 d-inline-flex">
                                <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=test&amp;emailid={$row.emailid}" class="btn btn-sm btn-secondary text-center link-secondary" title="{$LANG->get('test')}" aria-label="{$LANG->get('test')}" data-bs-toggle="tooltip" data-bs-trigger="hover"><i class="fa-solid fa-fw fa-paper-plane"></i></a>
                                <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=contents&amp;copyid={$row.emailid}" class="btn btn-sm btn-secondary text-center link-secondary" title="{$LANG->get('copy')}" aria-label="{$LANG->get('copy')}" data-bs-toggle="tooltip" data-bs-trigger="hover"><i class="fa-solid fa-fw fa-copy"></i></a>
                                <a href="{$NV_BASE_ADMINURL}?index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=contents&amp;emailid={$row.emailid}" class="btn btn-sm btn-secondary text-center link-secondary" title="{$LANG->get('edit')}" aria-label="{$LANG->get('edit')}" data-bs-toggle="tooltip" data-bs-trigger="hover"><i class="fa-solid fa-fw fa-pencil"></i></a>
                                {if not $row.is_system and not $row.module_name}<a href="#" class="btn btn-sm btn-secondary text-center link-danger" data-click="deltpl" data-emailid="{$row.emailid}" data-checksess="{$smarty.const.NV_CHECK_SESSION}" title="{$LANG->get('delete')}" aria-label="{$LANG->get('delete')}" data-bs-toggle="tooltip" data-bs-trigger="hover"><i class="fa-solid fa-fw fa-trash" data-icon="fa-trash"></i></a>{/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if not empty($GENERATE_PAGE)}
    <div class="card-footer border-top">
        <div class="pagination-wrap">
            {$GENERATE_PAGE}
        </div>
    </div>
    {/if}
</div>

<link rel="stylesheet" type="text/css" href="{$ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css">

<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$NV_LANG_INTERFACE}.js"></script>
