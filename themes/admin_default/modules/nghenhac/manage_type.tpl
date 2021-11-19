<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-12">
        <form method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
            <div class="form-group">
                <label for="exampleInputEmail1">Câu hỏi.</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="cauhoi" value="{cauhoi}" aria-describedby="emailHelp" placeholder="Nhập câu hỏi">
                <input type="hidden" name="id_cat" value=""/>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Câu trả lời</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="cautraloi" value="{cautraloi}" aria-describedby="emailHelp" placeholder="Nhập câu trả lời">
                <input type="hidden" name="id_cat" value=""/>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="them" id="btn_handel" value="handel">Thêm</button>
                <button type="button" class="btn btn-secondary" id="btn_exit" style="visibility:hidden;">Thoát</button>
            </div>
        </form>
    </div>
</div>
    <h2 class="text-primary" style="margin-top: 15px;">Danh sách câu hỏi</h2>
<div class="row">
    <form method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
        <div class="container">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">STT</th>
                        <th class="text-center" scope="col">Câu hỏi</th>
                        <th class="text-center" scope="col">Câu trả lời</th>
                        <th class="text-center" scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: loop_categories -->    
                        <tr>
                            <td class="text-center">{rows.id}</td>
                            <td class="text-center name_cat">{rows.question}</td>
                            <td class="text-center">{rows.answer}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-update" data-id="{rows.id}">Sửa</button>
                                <button class="btn btn-danger" name="delete" value="{rows.id}">Xóa</button>

                            </td>
                        </tr>
                    <!-- END: loop_categories -->

                </tbody>
                </table>
        </div>
    </form>

</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/manage_type.js"></script>
<!-- END: main -->