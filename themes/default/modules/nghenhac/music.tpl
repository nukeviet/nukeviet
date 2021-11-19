<!-- BEGIN: main -->
     <h2 class="text-primary" style="margin-top: 15px;">Danh sách thể loại nhạc</h2>
<div class="row">
    <form method="post" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
        <div class="container">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">STT</th>
                        <th class="text-center" scope="col">Tên thể loại</th>
                        <th class="text-center" scope="col">Ngày thêm</th>
                        <th class="text-center" scope="col">Ngày sửa</th>
                        <th class="text-center" scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: loop_categories -->    
                        <tr>
                            <td class="text-center">{categories.id}</td>
                            <td class="text-center name_cat">{categories.cat_name}</td>
                            <td class="text-center">{categories.add_time}</td>
                            <td class="text-center">{categories.update_time}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-update" data-id="{categories.id}">Sửa</button>
                                <button class="btn btn-danger" name="delete" value="{categories.id}">Xóa</button>

                            </td>
                        </tr>
                    <!-- END: loop_categories -->

                </tbody>
                </table>
        </div>
    </form>

</div>
<!-- END:main -->