<!-- BEGIN: main -->
<div class="container-fluid margin-top-lg margin-bottom">
    <div class="row">
        <div class="col-xs-14">
            <div class="margin-bottom">{LANG.group}: {DATA.title}</div>
            <h2>{GLANG.inform_notifications}</h2>
        </div>
        <div class="col-xs-10 text-right">
            <a href="{GROUP_MANAGER_URL}" class="btn btn-primary" title="{LANG.group_manage}"><i class="fa fa-reply"></i> {LANG.group_manage}</a>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row"><div id="inform_notifications" class="col-xs-24" data-ajax-url="{INFORM_MANAGER_URL}"></div></div>
</div>
<script>
$(function() {
    var informObj = $('#inform_notifications');
    $.ajax({
        type: "GET",
        url: informObj.data('ajax-url'),
        success: function(a) {
            informObj.html(a)
        }
    });
})
</script>
<!-- END: main -->
