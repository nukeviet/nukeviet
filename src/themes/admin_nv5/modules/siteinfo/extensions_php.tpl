{if !empty($DATA)}
{foreach from=$DATA key=module item=mod_vals}
<div class="card card-table">
    <div class="card-header">{$module}</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 33.3333333%">{$LANG->get('directive')}</th>
                        <th style="width: 33.3333333%">{$LANG->get('local_value')}</th>
                        <th style="width: 33.3333333%">{$LANG->get('master_value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$mod_vals key=key item=row}
                    <tr>
                        <td>{$key}</td>
                        {if not is_array($row)}
                        <td colspan="2">{$row}</td>
                        {elseif isset($row[1])}
                        <td>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        {else}
                        <td colspan="2"></td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/foreach}
{/if}
