<script src="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=upload&amp;{$smarty.const.NV_OP_VARIABLE}=js&amp;langinterface={$smarty.const.NV_LANG_INTERFACE}&amp;t={$smarty.const.SYS_CACHE_TIMESTAMP}"></script>
<script>
document.addEventListener('nv.picker.ready', () => {
    new nukeviet.Picker('#inline-picker', {
        show: 'inline',
        path: '{$REQUEST.path}',
        currentpath: '{$REQUEST.currentpath}',
        type: '{$REQUEST.type}',
        popup: {$REQUEST.popup},
        imgfile: '{$REQUEST.currentfile}',
        CKEditorFuncNum: {$REQUEST.CKEditorFuncNum},
        editorId: '{$REQUEST.editor_id}',
        area: '{$REQUEST.area}',
        alt: '{$REQUEST.alt}'
    });
});
</script>
<div class="fms-ctn-{$REQUEST.popup ? 'fullscreen' : 'page'} card">
    <div id="inline-picker" class="h-100 d-flex align-items-center justify-content-center">
        <i class="fa-solid fa-spinner fa-spin-pulse fa-3x"></i>
    </div>
</div>
