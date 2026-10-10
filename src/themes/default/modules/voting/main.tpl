<div class="vstack gap-3">
    {foreach from=$ITEMS item=VOTING}
    <div class="card">
        <div class="card-body">
            {include file='voting.form.tpl'}
        </div>
    </div>
    {/foreach}
</div>
