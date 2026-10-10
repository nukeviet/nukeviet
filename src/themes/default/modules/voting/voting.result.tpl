{if not empty($VOTING.note)}
{if $VOTING.is_error}
<div class="alert alert-danger" role="alert">{$VOTING.note}</div>
{else}
<div class="alert alert-info" role="alert">{$VOTING.note}</div>
{/if}
{/if}
<div class="fw-medium fs-5 mb-3 text-center text-primary">{$VOTING.question}</div>
<div class="vstack gap-2 mb-3">
    {foreach from=$VOTING.row item=row}
    <div class="v-item">
        <p class="mb-1">{$row.title}</p>
        {if $VOTING.total}
            {assign var="width" value=($row.hitstotal / $VOTING.total) * 100}
            {assign var="width" value=$width|round:2}
        {else}
            {assign var="width" value=0}
        {/if}
        {assign var="widthShow" value=$width|dnumber}
        <div class="progress" role="progressbar" aria-label="{$row.title}" aria-valuenow="{$width}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: {$width}%">{$widthShow}%</div>
        </div>
    </div>
    {/foreach}
</div>
<div class="text-center">
    {$LANG->getModule('voting_total')} <strong>{$VOTING.total}</strong> {$LANG->getModule('voting_counter')} - {$LANG->getModule('voting_pubtime')} <strong>{$VOTING.pubtime}</strong>
</div>
