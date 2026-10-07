<div class="alert alert-info">{$LANG->getModule('spdata_note')}</div>
<form method="post" action="{$ACTIONFORM}">
    {if not empty($DATA.error)}
    <div class="alert alert-danger">{$DATA.error}</div>
    {/if}
    <div class="list-group mb-3">
        {foreach from=$SAMPLES item=row}
        <label class="list-group-item d-flex gap-3" for="package{$row.key}">
            <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="package" id="package{$row.key}" value="{$row.title}" required>
            <span>
                <strong class="d-block text-dark">{$row.title}</strong>
                <small class="{if $row.compatible}text-success{else}text-warning-emphasis{/if}">{$row.message}</small>
            </span>
        </label>
        {/foreach}
    </div>
    <button type="submit" name="submit" value="1" class="btn btn-primary"><i class="fa-solid fa-download"></i> {$LANG->getModule('spdata_choose')}</button>
</form>
{if $NEXTSTEP}
<div class="install-nav">
    <span class="next_step"><a class="btn btn-primary" href="{$STEP_URL}8">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
</div>
{/if}
