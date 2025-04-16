<div class="card" id="cmt-config">
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
                    {if !empty($ROW.admin_title)}
                        {append var="ROW" value=$ROW.admin_title index="custom_title"}
                    {/if}
                    <tr>
                        <td class="text-center">{$WEIGHT}</td>
                        <td>{$ROW.custom_title}</td>
                        <td class="text-center"><i class="fa-solid fa-{if $MODULE_CONFIG.$MOD.activecomm}check{else}xmark{/if} fa-lg"></i></td>
                        <td>{$ROW.allowed_comm}</td>
                        <td>{$ROW.view_comm}</td>
                        <td>{$LANG->getModule('auto_postcomm_'|cat:$MODULE_CONFIG.$MOD.auto_postcomm)}</td>
                        <td class="text-center"><i class="fa-solid fa-{if $MODULE_CONFIG.$MOD.emailcomm}check{else}xmark{/if} fa-lg"></i></td>
                        <td class="text-center text-nowrap"><button class="btn btn-secondary" data-mod="{$MOD}"><i class="fa-solid fa-edit fa-lg"></i>&nbsp;{$LANG->getModule('edit')}</button></td>
                    </tr>
                    {assign var="WEIGHT" value=$WEIGHT+1}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="config_comm_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="config_comm_label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title h5" id="config_comm_label"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="config_comm_body"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                <button type="button" class="btn btn-primary" id="config_comm_submit">{$LANG->getGlobal('submit')}</button>
            </div>
        </div>
    </div>
</div>
