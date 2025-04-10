<!-- BEGIN: main -->
<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="text-center">
                <th>{$LANG->getModule('weight')}</th>
                <th>{$LANG->getModule('mod_name')}</th>
                <th>{$LANG->getModule('activecomm')}</th>
                <th>{$LANG->getModule('allowed_comm')}</th>
                <th>{$LANG->getModule('view_comm')}</th>
                <th>{$LANG->getModule('auto_postcomm')}</th>
                <th>{$LANG->getModule('emailcomm')}</th>
                <th>{$LANG->getModule('funcs')}</th>
            </tr>
        </thead>
        <tbody>
            {assign var="WEIGHT" value=1}
            {foreach $SITE_MOD_COMM as $MOD => $ROW}
            <!-- BEGIN: loop -->
            {if !empty($MODULE_CONFIG.$MOD.allowed_comm)}
                {assign var="ARRAY_ALLOWED_COMM" value=$MODULE_CONFIG.$MOD.allowed_comm|split:','}
                {foreach $ARRAY_ALLOWED_COMM as $K => $V}
                    {append var="ARRAY_ALLOWED_COMM" value=$V|intval index=$K}
                {/foreach}
            {else}
                {assign var="ARRAY_ALLOWED_COMM" value=[]}
            {/if}
            {if -1|in_array:$ARRAY_ALLOWED_COMM:true}
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
<!-- END: list -->

<!-- BEGIN: config -->
{* <form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&mod_name={$MOD_NAME}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col style="width: 300px;" />
                <col style="width: auto;" />
            </colgroup>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><input type="hidden" name="save" value="1"><input type="submit" value="{$LANG->getModule('save')}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td><strong>{$LANG->getModule('activecomm')}</strong></td>
                    <td><input type="checkbox" value="1" name="activecomm" {$ACTIVECOMM} /></td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('allowed_comm')}</strong></td>
                    <td>
                        <!-- BEGIN: allowed_comm -->
                        <div class="row">
                            <label><input name="allowed_comm[]" type="checkbox" value="{$OPTION.value}" {$OPTION.checked} />{$OPTION.title}</label>
                        </div>
                        <!-- END: allowed_comm -->
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('view_comm')}</strong></td>
                    <td>
                        <!-- BEGIN: view_comm -->
                        <div class="row">
                            <label><input name="view_comm[]" type="checkbox" value="{$OPTION.value}" {$OPTION.checked} />{$OPTION.title}</label>
                        </div>
                        <!-- END: view_comm -->
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('setcomm')}</strong></td>
                    <td>
                        <!-- BEGIN: setcomm -->
                        <div class="row">
                            <label><input name="setcomm[]" type="checkbox" value="{$OPTION.value}" {$OPTION.checked} />{$OPTION.title}</label>
                        </div>
                        <!-- END: setcomm -->
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('auto_postcomm')}</strong></td>
                    <td>
                        <select name="auto_postcomm" class="form-control w300">
                            <!-- BEGIN: auto_postcomm -->
                            <option value="{$OPTION.key}" {$OPTION.selected}>{$OPTION.title}</option>
                            <!-- END: auto_postcomm -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('emailcomm')}</strong></td>
                    <td><input type="checkbox" value="1" name="emailcomm" {$EMAILCOMM} /></td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('sortcomm')}</strong></td>
                    <td>
                        <select name="sortcomm" class="form-control w300">
                            <!-- BEGIN: sortcomm -->
                            <option value="{$OPTION.key}" {$OPTION.selected}>{$OPTION.title}</option>
                            <!-- END: sortcomm -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('adminscomm')}</strong></td>
                    <td>
                        <!-- BEGIN: adminscomm -->
                        <label style="display:inline-block;width:200px"> <input name="adminscomm[]" type="checkbox" value="{$OPTION.key}" {$OPTION.checked}>{$OPTION.title} </label>
                        <!-- END: adminscomm -->
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('perpagecomm')}</strong></td>
                    <td>
                        <input type="text" name="perpagecomm" value="{$DATA.perpagecomm}" class="w300 form-control" />
                        <span class="help-block m-bottom-none">{$LANG->getModule('perpagecomm_note')}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('timeoutcomm')}</strong></td>
                    <td>
                        <input type="text" name="timeoutcomm" value="{$DATA.timeoutcomm}" class="w300 form-control" />
                        <span class="help-block m-bottom-none">{$LANG->getModule('timeoutcomm_note')}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('allowattachcomm')}</strong></td>
                    <td><input type="checkbox" value="1" name="allowattachcomm" {$ALLOWATTACHCOMM} /></td>
                </tr>
                <tr>
                    <td><strong>{$LANG->getModule('alloweditorcomm')}</strong></td>
                    <td><input type="checkbox" value="1" name="alloweditorcomm" {$ALLOWEDITORCOMM} /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<div class="alert alert-info">{$LANG->getModule('adminscomm_note')}</div> *}
<!-- END: config -->
<!-- END: main -->
