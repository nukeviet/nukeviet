<!-- BEGIN: main -->
<div class="contain-export-button">
    <form method="post" action="{ACTION}" id="export_news">
        <!-- BEGIN: has_label -->
        <button class="btn btn-primary btn_news_export"><i class="fa fa-download"></i> {DATA.export_label}</button>
        <!-- END: has_label -->
        <!-- BEGIN: empty_label -->
        <button class="btn btn-primary btn_news_export"><i class="fa fa-download"></i> Tải về</button>
        <!-- END: empty_label -->
        <input type="hidden" name="newsexport" value="1"/>
    </form>
</div>
<script>
    /*
    $("#export_news").on("submit", function(){
        $(".btn_news_export").prop('disabled', true);
    });
    function export_news(event, form){
        event.preventDefault();
        var data = $(form).serialize();
        $.ajax({
            type: form.method,
            url: form.action,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Request Error!!!');
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }
    */
</script>
<!-- END: main -->