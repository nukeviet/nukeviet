<!-- BEGIN: main -->
<!-- BEGIN: row -->
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">STT</th>
            <th class="text-center">{LANG.image_name}</th>
            <th class="text-center">{LANG.upload_image}</th>
            <th class="text-center">{LANG.album}</th>
            <th class="text-center">{LANG.user_post}</th>
            <th class="text-center">{LANG.action_table}</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td class="text-center">{ROW.stt}</td>
            <td class="text-center">{ROW.ten_anh}</td>
            <td class="text-center">
                <img src="{ROW.url_anh}" alt="{ROW.ten_anh}" class="img-thumbnail w100 content-image">
            </td>
            <td class="text-center">{ROW.ten_album}</td>
            <td class="text-center">{ROW.username}</td>
            <td class="text-center text-nowrap">
                <a href="{EDIT_URL}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                <a href="{DELETE_URL}" class="btn btn-danger btn-sm delete_song"><i class="fa fa-trash-o"></i> {GLANG.delete}</a>
            </td>
        </tr>
        <!-- END: loop -->
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('.delete_song').click(function () {
            if(confirm("Ban co muon xoa hay khong")) {
                return true;
            }else{
                return false;
            }
        });
    });
</script>
<!-- END: row -->
<h3><b>Them anh</b></h3>
<form class="form-inline" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{DATA.id}" />
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            
            <tr>
                <td>
                    {LANG.image_name} <span class="red">(*)</span>
                </td>
                <td>
                    <input class="form-control" value="{DATA.title}" name="title" id="title" style="width:300px" maxlength="100" />
                </td>
            </tr>
             <tr>
                    <td>
                        {LANG.upload_image}
                    </td>
                    <td>
                        <input type="file" class="form-control" name="image" id="image" style="width:300px" maxlength="100" />
                       
                    </td>
            </tr>
            <tr>
                <td>
                    {LANG.album}
                </td>
                <td>
                    <select class="form-control" name="albumid">
                        <!-- BEGIN: album -->
                        <option value="{ALBUM.key}"{ALBUM.selected}>{ALBUM.val}</option>
                        <!-- END: album -->
                    </select>
                </td>
                 
            </tr>
            <tr>
                    <td>
                        {LANG.status_show}
                    </td>
                    <td>
                        <input type="checkbox" class="form-check-input" checked name="status">
                       
                    </td>
            </tr>
                <td colspan="2" class="text-center">
                    <input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>

<!-- END: main -->