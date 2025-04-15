<!-- BEGIN: main -->
<!-- BEGIN: list -->
<div class="card">
<div class="card-body">
    <div class="table-responsive table-card">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="text-center">
                    <th class="text-nowrap">{$LANG->getModule('weight')}</th>
                    <th class="text-nowrap">{$LANG->getModule('mod_name')}</th>
                    <th class="text-nowrap">{$LANG->getModule('activecomm')}</th>
                    <th class="text-nowrap">{$LANG->getModule('allowed_comm')}</th>
                    <th class="text-nowrap">{$LANG->getModule('view_comm')}</th>
                    <th class="text-nowrap">{$LANG->getModule('auto_postcomm')}</th>
                    <th class="text-nowrap">{$LANG->getModule('emailcomm')}</th>
                    <th class="text-nowrap">{$LANG->getModule('funcs')}</th>
                </tr>
            </thead>
            <tbody>
                {assign var="WEIGHT" value=1}
                {foreach $SITE_MOD_COMM as $MOD => $ROW}
                <!-- BEGIN: loop -->
                {if !empty($MODULE_CONFIG.$MOD.allowed_comm)}
                    {assign var="ARRAY_ALLOWED_COMM" value="intval"|array_map:($MODULE_CONFIG.$MOD.allowed_comm|split:',')}
                {else}
                    {assign var="ARRAY_ALLOWED_COMM" value=[]}
                {/if}
                {if (-1)|in_array:$ARRAY_ALLOWED_COMM:true}
                    {append var="ROW" value=$LANG->getModule('allowed_comm_item') index="allowed_comm"}
                {else}
                    {assign var="ALLOWED_COMM" value=[]}
                    {foreach $ARRAY_ALLOWED_COMM as $GID}
                        {append var="ALLOWED_COMM" value=$GROUPS.$GID}
                    {/foreach}
                    {append var="ROW" value=$ALLOWED_COMM|join:"<br>" index="allowed_comm"}
                {/if}

                {if !empty($MODULE_CONFIG.$MOD.view_comm)}
                    {assign var="ARRAY_VIEW_COMM" value=$MODULE_CONFIG.$MOD.view_comm|split:','}
                {else}
                    {assign var="ARRAY_VIEW_COMM" value=[]}
                {/if}
                {assign var="VIEW_COMM" value=[]}
                {foreach $ARRAY_VIEW_COMM as $GID}
                    {append var="VIEW_COMM" value=$GROUPS.$GID}
                {/foreach}
                {append var="ROW" value=$VIEW_COMM|join:"<br>" index="view_comm"}
                <tr>
                    <td class="text-center">{$WEIGHT}</td>
                    <td>{if !empty($ROW.admin_title)}{$ROW.admin_title}{else}{$ROW.custom_title}{/if}</td>
                    <td class="text-center"><em class="fa-solid fa-{if $MODULE_CONFIG.$MOD.activecomm}check{else}xmark{/if} fa-lg">&nbsp;</em></td>
                    <td>{$ROW.allowed_comm}</td>
                    <td>{$ROW.view_comm}</td>
                    <td>{$LANG->getModule('auto_postcomm_'|cat:$MODULE_CONFIG.$MOD.auto_postcomm)}</td>
                    <td class="text-center"><em class="fa-solid fa-{if $MODULE_CONFIG.$MOD.emailcomm}check{else}xmark{/if} fa-lg">&nbsp;</em></td>
                    <td class="text-center"><em class="fa-solid fa-edit fa-lg">&nbsp;</em><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&mod_name={$MOD}">{$LANG->getModule('edit')}</a></td>
                </tr>
                <!-- END: loop -->
                {assign var="WEIGHT" value=$WEIGHT+1}
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
<!-- END: list -->
