{if not empty($DATA['PHP Variables'])}
<div class="card card-table">
    <div class="card-header">{$LANG->get('variables_php')}</div>
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
                    {foreach from=$DATA['PHP Variables'] key=key item=value}
                    {if $key|substr:1:7 neq '_COOKIE' and not in_array($key, $NO_SHOW)}
                    <tr>
                        <td>{$key}</td>
                        <td>{$value}</td>
                    </tr>
                    {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
