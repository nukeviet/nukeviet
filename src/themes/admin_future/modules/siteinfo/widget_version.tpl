<div class="card-body pb-0">
    <h5 class="card-title">{$LANG->getModule('version')}</h5>
    <table class="table">
        <tbody>
            {foreach from=$FIELDS item=field}
            <tr>
                <td class="ps-0">{$field.key}</td>
                <td class="pe-0">{$field.value}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    {if not empty($INFO)}
    <p class="pt-2 mb-2 text-danger">
        {$INFO}
    </p>
    {/if}
</div>
<div class="card-footer text-center">
    <a class="btn btn-primary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=webtools&amp;{$smarty.const.NV_OP_VARIABLE}=checkupdate">{$LANG->getModule('checkversion')}</a>
</div>
