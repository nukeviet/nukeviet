<div class="card mb-3 text-bg-primary">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('checkExtensions')}
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped mb-0">
                <thead class="text-muted">
                    <tr>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->getModule('extName')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->getModule('extType')}</th>
                        <th style="width: 45%;" class="text-nowrap">{$LANG->getModule('extInfo')}</th>
                        <th style="width: 5%;" class="text-nowrap text-center">{$LANG->getModule('extNote')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$EXTS item=ext}
                    <tr>
                        <td class="text-break">{$ext.name}</td>
                        <td>{$ext.type_text}</td>
                        <td>
                            <a href="#" data-toggle="viewUpExtInfo" data-title="{$ext.name}">{$LANG->getModule('userVersion')}: {$ext.version ?: 'n/a'}; {$LANG->getModule('onlineVersion')}: {if not empty($ext.new_version)}{$ext.new_version}{elseif not empty($ext.version) and $ext.origin}{$ext.version}{else}n/a{/if}</a>
                            <div class="d-none" data-toggle="viewUpExtInfoBody">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('userVersion')}</div>
                                            <div class="col-6 fw-medium text-break">{$ext.version ?: 'n/a'}{if not empty($ext.date_show)} ({$ext.date_show}){/if}</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('onlineVersion')}</div>
                                            <div class="col-6 fw-medium text-break">{if not empty($ext.new_version)}{$ext.new_version}{elseif not empty($ext.version) and $ext.origin}{$ext.version}{else}n/a{/if}{if not empty($ext.new_date_show)} ({$ext.new_date_show}){/if}</div>
                                        </div>
                                    </li>
                                    {if not empty($ext.author)}
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('extAuthor')}</div>
                                            <div class="col-6 fw-medium text-break">{$ext.author}</div>
                                        </div>
                                    </li>
                                    {/if}
                                    {if not empty($ext.license)}
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('extLicense')}</div>
                                            <div class="col-6 fw-medium text-break">{$ext.license}</div>
                                        </div>
                                    </li>
                                    {/if}
                                    {if not empty($ext.mode)}
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('extMode')}</div>
                                            <div class="col-6 fw-medium text-break">{if $ext.mode eq 'sys'}{$LANG->getModule('extModeSys')}{else}{$LANG->getModule('extModeOther')}{/if}</div>
                                        </div>
                                    </li>
                                    {/if}
                                    {if not empty($ext.link)}
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('extLink')}</div>
                                            <div class="col-6 fw-medium text-break"><a href="{$ext.link}" target="_blank">{$ext.link}</a></div>
                                        </div>
                                    </li>
                                    {/if}
                                    {if not empty($ext.support)}
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-5">{$LANG->getModule('extSupport')}</div>
                                            <div class="col-6 fw-medium text-break"><a href="{$ext.support}" target="_blank">{$ext.support}</a></div>
                                        </div>
                                    </li>
                                    {/if}
                                </ul>
                                {if empty($ext.version) or $ext.note_level gt 0}
                                <div class="alert alert-danger mb-0 mt-3" role="alert">
                                    {$LANG->getModule('extNote1_detail')}
                                </div>
                                {/if}
                                {if $ext.up_need}
                                {if empty($ext.up_new_version)}
                                <div class="alert alert-warning mb-0 mt-3" role="alert">
                                    {$LANG->getModule('extUpdNote1', $ext.link)}
                                </div>
                                {elseif $ext.up_new_version.new neq $ext.new_version}
                                <div class="alert alert-info mb-0 mt-3" role="alert">
                                    {$LANG->getModule('extUpdNote2', $ext.up_new_version.new, $ext.up_link)}
                                </div>
                                {else}
                                <div class="alert alert-success mb-0 mt-3" role="alert">
                                    {$LANG->getModule('extUpdNote3', $ext.up_link)}
                                </div>
                                {/if}
                                {/if}
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="fa-solid{if $ext.status_level eq 3} text-success fa-circle-check{elseif $ext.status_level eq 2} text-danger fa-circle-xmark{else} text-warning fa-triangle-exclamation{/if}" data-bs-toggle="tooltip" data-bs-title="{$ext.status_note}" aria-label="{$ext.status_note}" data-bs-trigger="hover" title="{$ext.status_note}"></i>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end">
                            {$LANG->getModule('checkDate')} {$EXTUPDDATE} (<a id="extUpdRefresh" href="#">{$LANG->getModule('reCheck')}</a>)
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="card-footer bg-body-tertiary text-body">
        <a class="btn btn-success" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=extensions&amp;{$smarty.const.NV_OP_VARIABLE}=newest">{$LANG->getModule('extNew')}</a>
    </div>
</div>
