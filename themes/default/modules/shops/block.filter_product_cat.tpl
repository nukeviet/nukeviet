<!-- BEGIN: main -->
<div class="panel panel-default panel-filter-product filter_product">
    <div class="ajax-load-qa"></div>
    <div class="panel-body">
        <!-- BEGIN: group -->
        <div class="row">
            <div class="col-md-24">
                <h3 class="margin-bottom-sm">{MAIN_GROUP.title}:</h3>
            </div>
            <!-- BEGIN: sub_group -->
            <div class="col-md-24 sub-groups">
                <div class="clearfix">
                    <!-- BEGIN: loop -->
                    <!-- BEGIN: checkbox -->
                    <label><input type="checkbox" title="{SUB_GROUP.title}" data-alias="{MAIN_GROUP.alias}--{SUB_GROUP.alias}" name="groupid[]" value="{SUB_GROUP.groupid}" {SUB_GROUP.checked}>{SUB_GROUP.title}</label>
                    <!-- END: checkbox -->
                    <!-- BEGIN: label -->
                    <label class="label_group<!-- BEGIN: active --> active<!-- END: active -->"><input type="checkbox" title="{SUB_GROUP.title}" data-alias="{MAIN_GROUP.alias}--{SUB_GROUP.alias}" name="groupid[]" value="{SUB_GROUP.groupid}" {SUB_GROUP.checked}>{SUB_GROUP.title}</label>
                    <!-- END: label -->
                    <!-- BEGIN: image -->
                    <label class="label_group<!-- BEGIN: active --> active<!-- END: active -->" style="background-image: url('{SUB_GROUP.image}')"><input type="checkbox" title="{SUB_GROUP.title}" data-alias="{MAIN_GROUP.alias}--{SUB_GROUP.alias}" name="groupid[]" value="{SUB_GROUP.groupid}" {SUB_GROUP.checked}></label>
                    <!-- END: image -->
                    <!-- END: loop -->
                </div>
                <hr class="sub-break">
            </div>
            <!-- END: sub_group -->
        </div>
        <!-- END: group -->
    </div>
</div>

<script type="text/javascript" data-show="after">
$(document).ready(function() {
    var rewrite_enable = {REWRITE_ENABLE};
    $('input[name="groupid[]"]').click(function() {
        $('.ajax-load-qa').show();
        $('.filter_product .label_group').removeClass('active');
        var listid = '', i = 0;
        var url_group = [];
        $('input[name="groupid[]"]:checked').each(function() {
            url_group.push($(this).attr('data-alias'));
            if (i > 0) {
                listid += ',' + $(this).val();
            } else {
                listid += $(this).val();
            }
            $(this).parent().addClass('active');
            i++;
        });
        window.history.pushState("", "", '{LINK_URL}' + ((url_group.length || rewrite_enable) ? '/' : '') + url_group.join('/') + ((url_group.length && rewrite_enable)  ? '/' : ''));
        $.ajax({
            type: "POST",
            url: "{AJAX_URL}",
            data: 'ajax=1&catid={CATID}&listgroupid=' + listid,
            success: function(data) {
                $('#category').html(data);
                $(".ajax-load-qa").hide();
                $('html, body').animate({
                    scrollTop: $("#category").offset().top
                }, 200);
            },
            error: function() {
                $('.ajax-load-qa').hide();
            },
            timeout : 3000
        });
    });
});
</script>
<!-- END: main -->
