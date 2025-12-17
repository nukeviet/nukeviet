<form id="bVoting{$UNIQUEID}-v{$VOTING.vid}" method="post" action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}" data-toggle="ajax-form" data-precheck="nv_precheck_form" novalidate
    data-callback="votingProcessResult"{if $VOTING.active_captcha}{$CAPTCHA_ATTRS}{/if} data-result-title="{$LANG->getModule('voting_result')}"
>
    <div class="fw-medium mb-2">
        {$VOTING.question}
    </div>
    {foreach from=$VOTING.items item=item}
    {if $VOTING.accept gt 1}
    <div class="form-check">
        <input class="form-check-input" data-valid data-min="1" data-max="{$VOTING.accept ?: 1}" data-error-mess="{$VOTING.errsm}" data-error-type="feedback" type="checkbox" name="option[{$item.id}]" value="{$item.id}" id="b{$UNIQUEID}votingI{$item.id}">
        <label class="form-check-label" for="b{$UNIQUEID}votingI{$item.id}">{$item.title}</label>
    </div>
    {else}
    <div class="form-check">
        <input class="form-check-input" data-valid data-error-mess="{$VOTING.errsm}" data-error-type="feedback" type="radio" name="option" value="{$item.id}" id="b{$UNIQUEID}votingI{$item.id}">
        <label class="form-check-label" for="b{$UNIQUEID}votingI{$item.id}">{$item.title}</label>
    </div>
    {/if}
    {/foreach}
    <div class="mt-2">
        <button type="submit" class="btn btn-success">{$LANG->getModule('voting_hits')}</button>
        <button id="bVoting{$UNIQUEID}-v{$VOTING.vid}-viewresult" type="button" class="btn btn-primary" data-toggle="votingResult">{$LANG->getModule('voting_result')}</button>
    </div>
    <input type="hidden" name="vid" value="{$VOTING.vid}">
    <input type="hidden" name="checkss" value="{$VOTING.checkss}">
    <input type="hidden" name="nv_ajax_voting" value="1">
</form>
