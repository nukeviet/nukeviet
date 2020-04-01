<!-- BEGIN: main -->
<div class="pull-right">
    <a class="btn btn-default btn-xs" href="{CONTENTS.edit.0}">{CONTENTS.edit.1}</a>
    <!-- BEGIN: act -->
    <a class="btn btn-default btn-xs margin-left" href="javascript:void(0);" onclick="{CONTENTS.act.0}">{CONTENTS.act.1}</a>
    <!-- END: act -->
</div>
<div class="clearfix"></div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>
            <em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}
        </caption>
        <tbody>
            <!-- BEGIN: loop1 -->
            <tr>
                <td>{ROW1.0}:</td>
                <td>{ROW1.1}</td>
            </tr>
            <!-- END: loop1 -->
        </tbody>
    </table>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>
            <em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.stat.0}
        </caption>
        <tbody>
            <tr>
                <td>
                    <span class="text-middle pull-left display-inline-block">{CONTENTS.stat.1}:</span>
                    <select name="{CONTENTS.stat.2}" id="{CONTENTS.stat.2}" class="form-control w200 pull-left margin-left">
                        <!-- BEGIN: stat1 -->
                        <option value="{K}">{V}</option>
                        <!-- END: stat1 -->
                    </select>
                    <select name="{CONTENTS.stat.4}" id="{CONTENTS.stat.4}" class="form-control w200 pull-left margin-left">
                        <!-- BEGIN: stat2 -->
                        <option value="{K}">{V}</option>
                        <!-- END: stat2 -->
                    </select>
                    <input type="button" class="btn btn-primary margin-left" value="{CONTENTS.stat.6}" id="{CONTENTS.stat.7}" onclick="{CONTENTS.stat.8}" />
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div id="{CONTENTS.containerid}"></div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{LANG.file_name}</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('a[class=delfile]').click(function(event) {
            event.preventDefault();
            if (confirm('{LANG.file_del_confirm}')) {
                var href = $(this).attr('href');
                $.ajax({
                    type : 'POST',
                    url : href,
                    data : '',
                    success : function(data) {
                        alert(data);
                        window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banner_list';
                    }
                });
            }
        });

        $("#open_modal_image").on("click", function() {
            $('#imagemodal .modal-dialog').css({'width': $(this).data('width')+23});
            $('#imagemodal .modal-body').html('<img src="' + $(this).data('src') + '" />');
            $('#imagemodal').modal('show');
        });

        $("#open_modal_flash").on("click", function() {
            $('#imagemodal .modal-dialog').css({'width': $(this).data('width')+23});
            $('#imagemodal .modal-body').html('<object type="application/x-shockwave-flash" data="' + $(this).data('src') + '" width="' + $(this).data('width') + '"><param name="movie" value="' + $(this).data('src') + '"><param name="wmode" value="transparent"></object>');
            $('#imagemodal').modal('show');
        });
    });
</script>
<!-- END: main -->