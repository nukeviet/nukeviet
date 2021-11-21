<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-6">
        <form method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
            <h3 class="text-center text-primary">Thêm album</h3>
            <div class="form-group">
                <label for="exampleInputEmail1">Tên album.</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="cauhoi" value="{cauhoi}" aria-describedby="emailHelp" placeholder="Nhập tên album">
                <input type="hidden" name="id_album" value=""/>
            </div>

             <div class="form-group">
                <label for="exampleInputEmail1">Mô tả album.</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="cauhoi" value="{cauhoi}" aria-describedby="emailHelp" placeholder="Nhập mô tả">
                <input type="hidden" name="id_album" value=""/>
            </div>
            
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary" name="add" id="btn_handel" value="handel">Thêm</button>
            </div>
        </form>
    </div>
    <div class="col-sm-18">
        <h2 class="text-primary text-center">Danh sách ảnh</h2>
        <form method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
            <div class="management_questions">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%">STT</th> 
                            <th class="text-center" style="width: 20%">Tên album</th>
                             <th class="text-center" style="width: 20%">Mô tả</th>
                            <th class="text-center" style="width: 10%">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- BEGIN: album_list -->  
                            <tr>
                                <td class="text-center">{res.id_album}</td>
                                <td class="text-center">{res.name_album}</td>
                                 <td class="text-center">{res.description}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-update" data-id="" title="Sửa câu hỏi">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button class="btn btn-danger" name="delete" value="" title="Xoá câu hỏi">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <!-- END: album_list -->

                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/manage_type.js"></script>
<!-- END: main -->