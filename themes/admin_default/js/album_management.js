$(".btn-update").on("click", function(){
    let id_album = $(this).data('id');
    let name_album = $(this).parents("tr").find("td.name_album").html();
    let mota = $(this).parents("tr").find("td.description").html();
    $('input[name="id_album"]').val(id_album);
    $('input[name="tenalbum"]').val(name_album);
    $('input[name="mota"]').val(mota);
    $("#btn_handel").html('Sửa');
    $("#btn_handel").attr("name",'sua');
    $("#btn_exit").css("visibility","visible");
});
$("#btn_exit").on('click', function(){
    $("#btn_exit").css("visibility","hidden");
    $("#btn_handel").attr("name",'them');
    $("#btn_handel").html('Thêm');
    $('input[name="tenalbum"]').val('');
    $('input[name="mota"]').val('');
});