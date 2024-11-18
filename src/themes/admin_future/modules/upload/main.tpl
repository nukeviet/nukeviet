<script src="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=upload&amp;{$smarty.const.NV_OP_VARIABLE}=js&amp;l={$smarty.const.NV_LANG_INTERFACE}&amp;t={$smarty.const.SYS_CACHE_TIMESTAMP}"></script>
<script>
document.addEventListener('nv.upload.ready', () => {
    new nukeviet.Picker('#inline-picker', {
        show: 'inline',
        //path: 'uploads/news',
        //currentpath: 'uploads/news/authors',
    });
    new nukeviet.Picker('#btn-picker', {
        //path: 'uploads/news',
        currentpath: 'uploads/news/2024_06',
        type: 'image'
    });
});
</script>
<div class="fms-ctn-page card">
    <div id="inline-picker" class="h-100 d-flex align-items-center justify-content-center">
        <i class="fa-solid fa-spinner fa-spin-pulse fa-3x"></i>
    </div>
</div>
<button class="btn btn-primary mt-3" type="button" id="btn-picker">btn-picker</button>
