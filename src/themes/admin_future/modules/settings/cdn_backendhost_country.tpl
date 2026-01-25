<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;by_country=1" novalidate>
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <ul class="list-group list-group-flush">
        {function setOptions}
            {if not empty($data)}
            {foreach from=$data item=cdn}
            <option value="{$cdn.val}"{if not empty($cdn.countries) and in_array($code, $cdn.countries)}{assign var="isActive" value=1 scope="parent"} selected{/if}>{$cdn.val eq 'except' ? $LANG->getModule('dont_use') : $cdn.val}</option>
            {/foreach}
            {/if}
        {/function}
        {foreach from=$COUNTRIES key=code item=country}
        {assign var="isActive" value=0 scope="parent"}
        {assign var="options" value={setOptions data=$CDN_URLS}}
        <li class="list-group-item{$isActive ? ' list-group-item-success' : ''}">
            <div class="row g-2 align-items-center">
                <div class="col-6">
                    {$LANG->existsGlobal("country_`$code`") ? $LANG->getGlobal("country_`$code`") : $country[1]}
                </div>
                <div class="col-6">
                    <select class="form-select" name="ccdn[{$code}]">
                        <option value="">{$LANG->getModule('by_default')}</option>
                        {$options}
                    </select>
                </div>
            </div>
        </li>
        {/foreach}
    </ul>
</form>
