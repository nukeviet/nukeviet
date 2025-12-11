{if empty($CREDENTIAL.userid)}
<input type="hidden" name="add" value="1">
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-sm-end" for="getUser">{$LANG->getModule('api_role_object_'|cat:$ROLE_OBJECT)}</label>
    <div class="col-sm-9">
        <select class="form-select w-100" name="userid" id="getUser" data-get-user-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;role_id={$ROLE_ID}&amp;action=getUser" data-placeholder="{$LANG->getModule('api_role_credential_search')}">
        </select>
    </div>
</div>
{else}
<input type="hidden" name="edit" value="1">
<input type="hidden" name="userid" value="{$CREDENTIAL.userid}">
{/if}
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-sm-end" for="credential_add_adddate">{$LANG->getModule('api_role_credential_addtime')}</label>
    <div class="col-sm-9">
        <div class="input-group" style="width:fit-content">
            <input type="text" class="form-control w-50 adddate" id="credential_add_adddate" name="adddate" value="{$CREDENTIAL.adddate}" maxlength="10" placeholder="{$LANG->getModule('api_role_credential_addtime')}">
            <select name="addhour" class="form-select" style="width: fit-content">
                {for $I = 0 to 23}
                <option value="{$I}" {if $I == $CREDENTIAL.addhour}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
            <select name="addmin" class="form-select" style="width: fit-content">
                {for $I = 0 to 59}
                <option value="{$I}" {if $I == $CREDENTIAL.addmin}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
        </div>
        <div class="form-text">{$LANG->getModule('addtime_note')}</div>
    </div>
</div>
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-sm-end" for="credential_add_enddate">{$LANG->getModule('endtime')}</label>
    <div class="col-sm-9">
        <div class="input-group" style="width:fit-content">
            <input type="text" class="form-control w-50 enddate" id="credential_add_enddate" name="enddate" value="{$CREDENTIAL.enddate}" maxlength="10" placeholder="{$LANG->getModule('endtime')}">
            <select name="endhour" class="form-select" style="width: fit-content">
                {for $I = 0 to 23}
                <option value="{$I}" {if $I == $CREDENTIAL.endhour}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
            <select name="endmin" class="form-select" style="width: fit-content">
                {for $I = 0 to 59}
                <option value="{$I}" {if $I == $CREDENTIAL.endmin}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
        </div>
        <div class="form-text">{$LANG->getModule('endtime_note')}</div>
    </div>
</div>
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-sm-end" for="credential_add_quota">{$LANG->getModule('quota')}</label>
    <div class="col-sm-9">
        <input type="number" class="form-control quota" id="credential_add_quota" name="quota" value="{$CREDENTIAL.quota}" maxlength="20" placeholder="{$LANG->getModule('quota')}" style="width: 150px;">
        <div class="form-text">{$LANG->getModule('quota_note')}</div>
    </div>
</div>
<div class="row">
    <div class="text-center">
        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
    </div>
</div>
