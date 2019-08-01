<div id="updIf">
    <div id="sysUpd" class="hide"></div>
    <div id="extUpd" class="hide"></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('#sysUpd').html(nv_loading).removeClass('hide').load("{$BASE_URL}&i=sysUpd&num=" + nv_randomPassword(10), function() {
        $("#extUpd").html(nv_loading).removeClass('hide').load("{$BASE_URL}&i=extUpd&num=" + nv_randomPassword(10), function() {
            start_tooltip();
        });
    });
});
//]]>
</script>
