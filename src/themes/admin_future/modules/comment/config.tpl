<div class="card" id="cmt-config">
    <div class="card-body pt-4">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-1">
                <thead>
                    <tr>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('mod_name')}</th>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('allowed_comm')}</th>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('view_comm')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('auto_postcomm_s')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('config')}</th>
                        <th class="text-nowrap text-center" style="width: 4%;">{$LANG->getModule('funcs')}</th>
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
                        <td>{$ROW.allowed_comm}</td>
                        <td>{$ROW.view_comm}</td>
                        <td>{$LANG->getModule('auto_postcomm_'|cat:$MODULE_CONFIG.$MOD.auto_postcomm)}</td>
                        <td class="text-nowrap">
                            {if $MODULE_CONFIG.$MOD.activecomm}
                                {assign var="TITLE_ACTIVECOMM" value=$LANG->getModule('activecomm')}
                            {else}
                                {assign var="TITLE_ACTIVECOMM" value=$LANG->getModule('activecomm_no')}
                            {/if}
                            {if $MODULE_CONFIG.$MOD.emailcomm}
                                {assign var="TITLE_EMAILCOMM" value=$LANG->getModule('emailcomm')}
                            {else}
                                {assign var="TITLE_EMAILCOMM" value=$LANG->getModule('emailcomm_no')}
                            {/if}
                            <i class="fa-solid fa-comment{if !$MODULE_CONFIG.$MOD.activecomm}-slash{/if} fa-lg" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-offset="0,10" title="{$TITLE_ACTIVECOMM}" aria-label="{$TITLE_ACTIVECOMM}"></i>
                            &nbsp;
                            <i class="fa-solid fa-envelope{if $MODULE_CONFIG.$MOD.emailcomm}-circle-check{/if} fa-lg" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-offset="0,10" title="{$TITLE_EMAILCOMM}" aria-label="{$TITLE_EMAILCOMM}"></i>
                        </td>
                        <td class="text-center text-nowrap"><button class="btn btn-secondary" data-mod="{$MOD}"><i class="fa-solid fa-pencil"></i>&nbsp;{$LANG->getModule('edit')}</button></td>
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
            <div class="modal-body" id="config_comm_body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                <button type="button" class="btn btn-primary" id="config_comm_submit">{$LANG->getGlobal('submit')}</button>
            </div>
        </div>
    </div>
</div>
