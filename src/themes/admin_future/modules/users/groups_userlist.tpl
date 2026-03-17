<div id="listUsersCtn" data-checkss="{CHECKSS}">
    {if $SHOW_ADD_USER}
    <div id="ablist" class="mb-3 d-flex flex-wrap align-items-center gap-2">
        <label for="uid" class="mb-0">{$LANG->getModule('search_id')}:</label>
        <input title="{$LANG->getModule('search_id')}" class="form-control" type="text" name="uid" id="uid" value="" maxlength="11" style="width:100px" autocomplete="off">
        <input type="hidden" id="filtersql_val" value="{$FILTERSQL}">
        <button class="btn btn-primary" name="addUser" type="button">{$LANG->getModule('addMemberToGroup')}</button>
        <button class="btn btn-success" name="searchUser" type="button">{$LANG->getGlobal('search')}</button>
    </div>
    {/if}
    <div id="pageContent">&nbsp;</div>
</div>
