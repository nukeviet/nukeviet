<div id="getUpd" class="d-none">
    <div class="card">
        <div class="card-body text-center p-4">
            <i class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></i>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var url = "{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&{$NV_NAME_VARIABLE}=extensions&{$NV_OP_VARIABLE}=update&eid={$EID}&fid={$FID}&checksess={$CHECKSESS}&num=" + nv_randomPassword(10);
    $('#getUpd').removeClass('d-none').load(url);
});
</script>
