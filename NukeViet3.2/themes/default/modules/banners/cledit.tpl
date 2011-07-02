<!-- BEGIN: cledit -->
<div class="cledit">
    <!-- BEGIN: lt -->
    <div class="lt">
        <label>{LT_NAME}:</label>
        <input name="{LT_ID}" id="{LT_ID}" value="{LT_VALUE}" type="text" maxlength="{LT_MAXLENGTH}" />
    </div>
    <!-- END: lt -->
</div>
<div class="cledit">
    <!-- BEGIN: npass -->
    <div class="lt">
        <label>{NPASS_NAME}:</label>
        <input name="{NPASS_ID}" id="{NPASS_ID}" value="" type="password" maxlength="{NPASS_MAXLENGTH}" />
    </div>
    <!-- END: npass -->
</div>
<div style="height:27px;clear: both;">
    <input type="button" value="{EDIT_NAME}" name="{EDIT_ID}" id="{EDIT_ID}" onclick="{EDIT_ONCLICK}"/>
    <input type="button" value="{CANCEL_NAME}" name="cancel" onclick="{CANCEL_ONCLICK}"/>
</div>
<!-- END: cledit -->
