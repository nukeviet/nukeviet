{if isset($DATA['website'])}
<div class="card card-table">
    <div class="card-header">
        <div class="mb-1">{$DATA.website.caption}</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$DATA['website']['field'] item=row}
                    <tr>
                        <td style="width: 50%;">{$row.key}</td>
                        <td style="width: 50%;">{$row.value}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}

{if isset($DATA['server'])}
<div class="card card-table">
    <div class="card-header">
        <div class="mb-1">{$DATA.server.caption}</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$DATA['server']['field'] item=row}
                    <tr>
                        <td style="width: 50%;">{$row.key}</td>
                        <td style="width: 50%;">{$row.value}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}

{if isset($DATA['chmod'])}
<div class="card card-table">
    <div class="card-header clearfix">
        <div class="float-left mb-1">{$DATA.chmod.caption}</div>
        <div class="tools">
            <i class="fas fa-wrench" id="checkchmod" data-url="{$URL_CHMOD}" title="{$LANG->get('checkchmod')}"></i>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$DATA['chmod']['field'] item=row}
                    <tr>
                        <td style="width: 50%;">{$row.key}</td>
                        <td style="width: 50%;">{$row.value}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
