{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{else}
{if $WARNING}
<div role="alert" class="alert alert-warning alert-dismissible mb-0">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="message" id="prevent-link">{$LANG->get('get_update_warning', $LINK_UNZIP)}</div>
</div>
<script type="text/javascript">
$(function() {
    $('#prevent-link a').click(function(e){
        e.preventDefault();
        $('#getUpd').html('<div class="card"><div class="card-body text-center p-4"><i class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></i></div></div>').load($(this).attr('href'));
    });
});
</script>
{else}
<div role="alert" class="alert alert-success alert-dismissible mb-0">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message" id="prevent-link">{$LANG->get('get_update_ok', $LINK_UNZIP)}</div>
</div>
<script type="text/javascript">
$(function() {
    $('#prevent-link a').click(function(e){
        e.preventDefault();
        $('#getUpd').html('<div class="card"><div class="card-body text-center p-4"><i class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></i></div></div>').load($(this).attr('href'));
    });
});
</script>
{/if}
{/if}
