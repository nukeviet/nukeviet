<!-- BEGIN: view -->
<table class="table table-bordered table-striped">
    <tbody>
        <!-- BEGIN: image -->
        {if !empty($DEPARTMENT.image)}
        <tr>
            <td class="text-nowrap">{$LANG->getModule('image')}:</td>
            <td><img src="{$DEPARTMENT.image}" class="img-thumbnail" alt=""></td>
        </tr>
        {/if}
        <!-- END: image -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('part_row_title')}:</td>
            <td>{$DEPARTMENT.full_name}</td>
        </tr>
        <tr>
            <td class="text-nowrap">{$LANG->getModule('note_row_title')}:</td>
            <td>{$DEPARTMENT.note}</td>
        </tr>
        <!-- BEGIN: phone -->
        {if !empty($DEPARTMENT.phone)}
        <tr>
            <td class="text-nowrap">{$LANG->getGlobal('phonenumber')}:</td>
            <td>{$DEPARTMENT.phone}</td>
        </tr>
        {/if}
        <!-- END: phone -->
        <!-- BEGIN: fax -->
        {if !empty($DEPARTMENT.fax)}
        <tr>
            <td class="text-nowrap">Fax:</td>
            <td>{$DEPARTMENT.fax}</td>
        </tr>
        {/if}
        <!-- END: fax -->
        <!-- BEGIN: email -->
        {if !empty($DEPARTMENT.email)}
        <tr>
            <td class="text-nowrap">{$LANG->getGlobal('email')}:</td>
            <td>{$DEPARTMENT.email}</td>
        </tr>
        {/if}
        <!-- END: email -->
        <!-- BEGIN: address -->
        {if !empty($DEPARTMENT.address)}
        <tr>
            <td class="text-nowrap">{$LANG->getModule('address')}:</td>
            <td>{$DEPARTMENT.address}</td>
        </tr>
        {/if}
        <!-- END: address -->
        <!-- BEGIN: other -->
        {foreach $DEPARTMENT.others as $OTHER_TITLE => $OTHER_VALUE}
        <tr>
            <td class="text-nowrap">{$OTHER_TITLE|ucfirst}:</td>
            <td>{$OTHER_VALUE|replace:',':'<br/>'}</td>
        </tr>
        {/foreach}
        <!-- END: other -->
        <!-- BEGIN: cats -->
        {if !empty($DEPARTMENT.cats)}
        <tr>
            <td class="text-nowrap">{$LANG->getModule('cats')}:</td>
            <td>{$DEPARTMENT.cats}</td>
        </tr>
        {/if}
        <!-- END: cats -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('your_authority')}:</td>
            <td>{$DEPARTMENT.your_authority}</td>
        </tr>
    </tbody>
</table>
<!-- END: view -->
