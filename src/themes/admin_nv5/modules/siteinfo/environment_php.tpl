{if not empty($DATA['Environment'])}
<div class="card card-table">
    <div class="card-header">{$LANG->get('environment_php')}</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 50%">{$LANG->get('variable')}</th>
                        <th style="width: 50%">{$LANG->get('value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA['Environment'] key=key item=value}
                    <tr>
                        <td>{$key}</td>
                        <td>{$value}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
