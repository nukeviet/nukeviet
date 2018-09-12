{if !empty($DATA['PHP Core'])}
<div class="card card-table">
    <div class="card-header">{$LANG->get('configuration_php')}</div>
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
                    {foreach from=$DATA['PHP Core'] key=key item=row}
                    <tr>
                        <td>{$key}</td>
                        {if is_array($row)}
                        <td>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        {else}
                        <td>{$row}</td>
                        <td>{$row}</td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
