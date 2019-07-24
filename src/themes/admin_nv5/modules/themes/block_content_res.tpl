<script type="text/javascript">
alert('{$BLOCKMESS}');

{if not empty($BLOCKREDIRECT)}
window.opener.location.href = '{$BLOCKREDIRECT}';
{else}
window.opener.location.reload();
{/if}
window.opener.focus();
window.close();
</script>
