
<script src="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=upload&amp;{$smarty.const.NV_OP_VARIABLE}=js&amp;t={$smarty.const.SYS_CACHE_TIMESTAMP}"></script>
<script>
document.addEventListener('nv.upload.ready', () => {
    nvToast('Sự kiện nv.upload.ready đã được kích hoạt!', 'success');
});
</script>
