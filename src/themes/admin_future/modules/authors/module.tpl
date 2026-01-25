<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap fw-100" style="width: 15%;">{$LANG->getModule('number')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('module')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('custom_title')}</th>
                        <th class="text-nowrap text-center" style="width: 15%;">{$LANG->getGlobal('level1')}</th>
                        <th class="text-nowrap text-center" style="width: 15%;">{$LANG->getGlobal('level2')}</th>
                        <th class="text-nowrap text-center" style="width: 15%;">{$LANG->getGlobal('level3')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>
                            <select class="form-select" data-toggle="wAdnMod" data-checkss="{$CHECKSS}" data-id="{$row.mid}" data-current="{$row.weight}">
                                {for $weight=1 to $NUMROWS}
                                <option value="{$weight}"{if $weight eq $row.weight} selected{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>{$row.module}</td>
                        <td>{if $LANG->existsGlobal($row.lang_key)}{$LANG->getGlobal($row.lang_key)}{/if}</td>
                        <td class="text-center">
                            <div class="form-check form-switch d-inline-block">
                                <input data-toggle="cAdnMod" data-checkss="{$CHECKSS}" data-level="1" data-id="{$row.mid}"{if $row.act_1} checked{/if} class="form-check-input" type="checkbox" role="switch" aria-label="{$row.module}"{if in_array($row.module, ['siteinfo', 'authors'])} disabled{/if}>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-inline-block">
                                <input data-toggle="cAdnMod" data-checkss="{$CHECKSS}" data-level="2" data-id="{$row.mid}"{if $row.act_2} checked{/if} class="form-check-input" type="checkbox" role="switch" aria-label="{$row.module}"{if $row.module eq 'siteinfo'} disabled{/if}>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-inline-block">
                                <input data-toggle="cAdnMod" data-checkss="{$CHECKSS}" data-level="3" data-id="{$row.mid}"{if $row.act_3} checked{/if} class="form-check-input" type="checkbox" role="switch" aria-label="{$row.module}"{if in_array($row.module, ['siteinfo', 'database', 'settings', 'site'])} disabled{/if}>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
