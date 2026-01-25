{foreach from=$ERRORS key=id item=item}
{assign var="item" value=json_decode($item, true) nocache}
<div class="card text-bg-primary">
    <a class="card-header fw-medium" id="heading-error-{$id}" data-bs-toggle="collapse" href="#body-error-{$id}" role="button" aria-expanded="true" aria-controls="body-error-{$id}">
        {$LANG->getModule('errorlog_time')}: {strtotime($item.time)|nv_datetime_format:1:0}
    </a>
    <div class="collapse show" id="body-error-{$id}">
        <div class="card-body text-body bg-body">
            <div class="table-card">
                <table class="table table-striped mb-0">
                    <colgroup>
                        <col style="width: 1%;">
                    </colgroup>
                    <tbody>
                        {foreach from=$item key=key item=value}
                        {if $key neq 'time'}
                        <tr>
                            <td class="text-nowrap">{$LANG->getModule("errorlog_`$key`")}:</td>
                            <td>
                                {if $key eq 'backtrace'}
                                <ul class="list-unstyled mb-0">
                                    {foreach from=$value item=vl}
                                    <li>{$vl}</li>
                                    {/foreach}
                                </ul>
                                {else}{$value}{/if}
                                {if $key eq 'errno'}
                                {assign var="codes" value=split($value, ' ') nocache}
                                {if isset($codes[1]) and !isset($codes[2]) and is_numeric($codes[0]) and $LANG->existsModule("errorcode_`$codes[0]`")}
                                <div class="text-muted"><small>{$LANG->getModule("errorcode_`$codes[0]`")}</small></div>
                                {/if}
                                {/if}
                            </td>
                        </tr>
                        {/if}
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{/foreach}
