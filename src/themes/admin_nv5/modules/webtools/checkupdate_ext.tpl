{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('checkExtensions')}: {$ERROR}</div>
</div>
{else}
<div class="card card-table">
    <div class="card-header card-header-divider mx-0 px-4 mb-0 pb-2">
        {$LANG->get('checkExtensions')}
        <div class="card-subtitle">
            {$LANG->get('checkDate')}: {$EXTUPDDATE} (<a id="extUpdRefresh" href="#">{$LANG->get('reCheck')}</a>)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 24%" class="text-nowrap">{$LANG->get('extName')}</th>
                        <th style="width: 24%" class="text-nowrap">{$LANG->get('extType')}</th>
                        <th style="width: 51%" class="text-nowrap">{$LANG->get('extInfo')}</th>
                        <th style="width: 1%" class="text-right text-nowrap">{$LANG->get('extNote')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_EXTS item=row}
                    <tr>
                        <td class="align-top">{$row.name}</td>
                        <td class="align-top">{$row.type}</td>
                        <td class="align-top">
                            <div class="ext-info-title cursor-pointer">{$row.info}</div>
                            <div class="ext-info-content d-none mt-2">
                                <ul class="pl-4">
                                    {foreach from=$row.tip item=tip}
                                    <li><strong>{$tip.title}:</strong> {$tip.content}</li>
                                    {/foreach}
                                </ul>
                                {if $row.isinvalid}
                                {* Ứng dụng không có phiên bản *}
                                <div role="alert" class="alert alert-danger alert-dismissible">
                                    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                                    <div class="icon"><i class="far fa-times-circle"></i></div>
                                    <div class="message">{$LANG->get('extNote1_detail')}</div>
                                </div>
                                {/if}
                                {if not empty($row.upmess)}
                                {* Thông tin nâng cấp *}
                                <div role="alert" class="alert alert-{$row.upmode} alert-dismissible">
                                    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                                    <div class="icon"><i class="far fa-times-circle"></i></div>
                                    <div class="message">{$row.upmess}</div>
                                </div>
                                {/if}
                            </div>
                        </td>
                        <td class="align-top text-right text-nowrap">
                            <i class="{if $row.icon eq 'warning'}text-warning fas fa-exclamation-triangle{elseif $row.icon eq 'danger'}text-danger fas fa-times-circle{else}text-success fas fa-check-circle{/if}" data-toggle="tooltip" data-placement="top" title="{$row.note}"></i>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-secondary" href="{$LINKNEWEXT}">{$LANG->get('extNew')}</a>
    </div>
</div>
{/if}
