<!-- BEGIN: config -->
<form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&mod_name={$MOD_NAME}" method="post">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <input type="hidden" name="save" value="1">
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-body">
            <div class="row mb-3">
                {* <label class="col-sm-3 col-xxl-4 col-form-label text-sm-end" for="activecomm">{$LANG->getModule('activecomm')}</div> *}
                <div class="col-sm-8 col-lg-6 col-xxl-7 offset-sm-3 offset-xxl-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" name="activecomm" id="activecomm" role="switch" {if $DATA.activecomm}checked{/if}>
                    <label class="form-check-label" for="activecomm">{$LANG->getModule('activecomm')}</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('allowed_comm')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    {assign var="ARRAY_ALLOWED_COMM" value="intval"|array_map:($DATA.allowed_comm|split:',')}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="-1" name="allowed_comm[]" id="_activecomm"{if (-1)|in_array:$ARRAY_ALLOWED_COMM:true} checked{/if}>
                        <label class="form-check-label" for="_activecomm">{$LANG->getModule('allowed_comm_item')}</label>
                    </div>
                    {foreach $GROUPS as $GID => $GROUP}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{$GID}" name="allowed_comm[]" id="activecomm_{$GID}"{if $GID|intval|in_array:$ARRAY_ALLOWED_COMM:true} checked{/if}>
                        <label class="form-check-label" for="activecomm_{$GID}">{$GROUP}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('view_comm')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    {assign var="ARRAY_VIEW_COMM" value="intval"|array_map:($DATA.view_comm|split:',')}
                    {foreach $GROUPS as $GID => $GROUP}
                    <!-- BEGIN: view_comm -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{$GID}" name="view_comm[]" id="view_comm_{$GID}"{if $GID|intval|in_array:$ARRAY_VIEW_COMM:true} checked{/if}>
                        <label class="form-check-label" for="view_comm_{$GID}">{$GROUP}</label>
                    </div>
                    <!-- END: view_comm -->
                    {/foreach}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('setcomm')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    {assign var="ARRAY_SET_COMM" value="intval"|array_map:($DATA.setcomm|split:',')}
                    <!-- BEGIN: setcomm -->
                    {foreach $GROUPS as $GID => $GROUP}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{$GID}" name="setcomm[]" id="setcomm_{$GID}"{if $GID|intval|in_array:$ARRAY_SET_COMM:true} checked{/if}>
                        <label class="form-check-label" for="setcomm_{$GID}">{$GROUP}</label>
                    </div>
                    {/foreach}
                    <!-- END: setcomm -->
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-xxl-4 col-form-label text-sm-end" for="auto_postcomm">{$LANG->getModule('auto_postcomm')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    <select name="auto_postcomm" class="form-select" id="auto_postcomm">
                        {for $I = 0 to 2}
                        <!-- BEGIN: auto_postcomm -->
                        <option value="{$I}" {if $I == $DATA.auto_postcomm}selected{/if}>{$LANG->getModule('auto_postcomm_'|cat:$I)}</option>
                        <!-- END: auto_postcomm -->
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-7 offset-sm-3 offset-xxl-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" name="emailcomm" id="emailcomm" role="switch" {if $DATA.emailcomm}checked{/if}>
                    <label class="form-check-label" for="emailcomm">{$LANG->getModule('emailcomm')}</label>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-xxl-4 col-form-label text-sm-end" for="sortcomm">{$LANG->getModule('sortcomm')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    <select name="sortcomm" class="form-select" id="sortcomm">
                        {for $I = 0 to 2}
                        <!-- BEGIN: sortcomm -->
                        <option value="{$I}" {if $I == $DATA.sortcomm}selected{/if}>{$LANG->getModule('sortcomm_'|cat:$I)}</option>
                        <!-- END: sortcomm -->
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('adminscomm')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                {if !empty($ADMINSCOM)}
                    {assign var="ARRAY_ADMINS_COMM" value="intval"|array_map:($DATA.adminscomm|split:',')}
                    {foreach $ADMINSCOM as $OPTION}
                    <!-- BEGIN: adminscomm -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{$OPTION.userid}" name="adminscomm[]" id="adminscomm_{$OPTION.userid}"{if $OPTION.userid|in_array:$ARRAY_ADMINS_COMM} checked{/if}>
                        <label class="form-check-label" for="adminscomm_{$OPTION.userid}">{$OPTION.username}</label>
                    </div>
                    <!-- END: adminscomm -->
                    {/foreach}
                    {/if}
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-xxl-4 col-form-label text-sm-end" for="perpagecomm">{$LANG->getModule('perpagecomm')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    <input type="text" name="perpagecomm" value="{$DATA.perpagecomm}" id="perpagecomm" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-xxl-4 col-form-label text-sm-end" for="timeoutcomm">{$LANG->getModule('timeoutcomm')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-7">
                    <input type="text" name="timeoutcomm" value="{$DATA.timeoutcomm}" id="timeoutcomm" class="form-control">
                    <div class="form-text">{$LANG->getModule('timeoutcomm_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-7 offset-sm-3 offset-xxl-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" name="allowattachcomm" id="allowattachcomm" role="switch" {if !empty($DATA.allowattachcomm)}checked{/if}>
                    <label class="form-check-label" for="allowattachcomm">{$LANG->getModule('allowattachcomm')}</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-7 offset-sm-3 offset-xxl-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" name="alloweditorcomm" id="alloweditorcomm" role="switch" {if !empty($DATA.alloweditorcomm)}checked{/if}>
                    <label class="form-check-label" for="alloweditorcomm">{$LANG->getModule('alloweditorcomm')}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-7 offset-sm-3 offset-xxl-4">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="alert alert-info">{$LANG->getModule('adminscomm_note')}</div>
