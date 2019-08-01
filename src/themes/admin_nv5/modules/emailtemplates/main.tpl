<div class="row">
    {for $col=1 to 2}
    <div class="col-12 col-md-6">
        {foreach from=$CATS[$col] item=cat}
        <div class="card card-table">
            <div class="card-header mb-0">{$cat.title}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">{$LANG->get('tpl_title')}</th>
                                <th style="width: 1%;" class="text-nowrap text-right">{$LANG->get('actions')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$DATA[$cat.catid] item=row}
                            <tr>
                                <td style="width: 1%;" class="pr-0">
                                    {if $row.is_disabled}<i class="text-muted fas fa-times-circle" title="{$LANG->get('tpl_is_disabled')}"></i>{else}<i class="text-success fas fa-check-circle" title="{$LANG->get('tpl_is_active')}"></i>{/if}
                                </td>
                                <td class="mw100">{if $row.is_disabled}<span class="text-muted">{$row.title}</span> <span class="badge badge-secondary">{$LANG->get('tpl_is_disabled_label')}</span>{else}{$row.title}{/if}{if not $row.is_system} <span class="badge badge-danger">{$LANG->get('tpl_custom_label')}</span>{/if}</td>
                                <td class="text-nowrap text-right">
                                    <a href="{$EDIT_BASEURL}{$row.emailid}" class="text-muted" title="{$LANG->get('edit')}"><i class="fas fa-edit"></i></a>
                                    {if not $row.is_system}<a href="#" class="text-danger ml-2" title="{$LANG->get('delete')}" data-toggle="deltpl" data-emailid="{$row.emailid}"><i class="fas fa-trash-alt"></i></a>{/if}
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    {/for}
</div>
