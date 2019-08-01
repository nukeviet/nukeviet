{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{elseif $WARNING_LEVEL eq 1}
<div role="alert" class="alert alert-warning alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="message" id="prevent-link">{$MESSAGE}</div>
</div>
<script type="text/javascript">
$(function(){
    $('#prevent-link a').click(function(e){
        e.preventDefault();
        $('#getUpd').html(nv_loading).load($(this).attr('href'));
    });
});
</script>
{else}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message" id="prevent-link">{$MESSAGE}</div>
</div>
<script type="text/javascript">
$(function(){
    $('#prevent-link a').click(function(e){
        e.preventDefault();
        $('#getUpd').html(nv_loading).load($(this).attr('href'));
    });
});
</script>
{/if}
