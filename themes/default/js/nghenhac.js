$(document).ready(function(){
    console.log("abc00");
    // let arr_question = 
    // [
    //     {"qestion":"Bỏ ngoài nướng trong, ăn ngoài bỏ trong là gì", "answer":"abcxnz", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "xyudmgho", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "usngke", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "ebce1", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "usnvre", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "abixyz", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "uyrwte", "second":30, "status":0},
    //     {"qestion":"Bà đó bả chết bả bay lên trời", "answer": "utngre", "second":30, "status":0},
    //     {"qestion":"Trò chơi này được tạo ra bằng hệ quản trị nội dung nào? ", "answer": "nukeviet", "second":30, "status":0}
    // ]
    let arr_question = [];
    function get_all_question()
    {
        $.ajax({
            url:window.location.href,
            method:"post",
            dataType:"json",
            data:{"action":"getallquestion"}
        }).done(function(res){
            console.log(res);
            res.forEach(function(value,index){
                let obj = {"id": value['id'],"qestion": value['question'],  "second":30, "status":0};
                arr_question.push(obj);
            });
            $(".word").each(function(key, value){
                $(this).data("question", arr_question[key]['id']);
            })
            $(".pivot").each(function(key, value){
                $(this).data("question", arr_question[8]['id']);
            })
        })
    }
    function find_question_by_id(id_cauhoi)
    {
        let question = arr_question.find(e => e['id'] == id_cauhoi);
        return question;
    }
    
    get_all_question();
    
    let thoigian = 1;
    let cauhoanthanh = 0;
    let diem = 240;
    let total_point = setInterval(() => {
        diem -= thoigian;
    }, 1000);
    let status = 0;
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    let curent_id ;
    let curent_row;
    let curent_time;
    
    let changeTime;
    let timeout;
    $(".pivot").on('click', function(){
        if(status == 0){
            if(cauhoanthanh<4){
                Toast.fire({
                    icon: 'warning',
                    title: 'Hãy hoàn thành 4 câu hàng ngang'
                });
            } else {
                $("#cauhoi").html("Trò chơi này được tạo ra bằng hệ quản trị nội dung nào? ");
                $("#exampleModal").modal("show");
                let id_cauhoi = $(this).data("question");
                curent_time = find_question_by_id(id_cauhoi)['second'];
                $("#time").html(curent_time);
                $("#time").html(curent_time--);
                curent_id = id_cauhoi;
                let cauhoi = find_question_by_id(id_cauhoi)['qestion'];
                $("#cauhoi").html(cauhoi);
                $("#exampleModal").modal("show");
                let time_rest = find_question_by_id(id_cauhoi)['second'];
                changeTime = setInterval(() => {
                    handelChangeSecond();
                }, 1000);
                timeout = setTimeout(() => { 
                    clearInterval(changeTime);
                    end_game();
                }, time_rest * 1000);
            }
        } else {
            Toast.fire({
                icon: 'warning',
                title: 'Trò chơi đã kết thúc'
            });
        }
        
    });
    $('.letter:not(.pivot)').on('click', function (){
        if(status == 0)
        {
            let row_parent = $(this).parents('div.word');
            let id_cauhoi = row_parent.data('question');
            if(find_question_by_id(id_cauhoi)["status"] == 0)
            {
                curent_row = row_parent;
                curent_time = find_question_by_id(id_cauhoi)['second'];
                $("#time").html(curent_time);
                $("#time").html(curent_time--);
                curent_id = id_cauhoi;
                let cauhoi = find_question_by_id(id_cauhoi)['qestion'];
                $("#cauhoi").html(cauhoi);
                $("#exampleModal").modal("show");
                let time_rest = find_question_by_id(id_cauhoi)['second'];
                changeTime = setInterval(() => {
                    handelChangeSecond();
                }, 1000);
                timeout = setTimeout(() => { 
                    clearInterval(changeTime);
                    $.ajax({
                        url:window.location.href,
                        method:"post",
                        dataType:"json",
                        data:{"action":"get_result", "id":curent_id}
                    }).done(function(res){
                        answer_question(curent_row,res['answer']);
                    });
                }, time_rest * 1000);
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Câu hỏi này đã hoàn thành'
                });
            }
        } else {
            Toast.fire({
                icon: 'warning',
                title: 'Trò chơi đã kết thúc'
            });
        }
        
        
    });
    $("#answer").on("click", function(){
        $.ajax({
            url:window.location.href,
            method:"post",
            dataType:"json",
            data:{"action":"get_result", "id":curent_id}
        }).done(function(res){
            console.log(res);
            if($("#input_answer").val() == res['answer'])
            {
                Toast.fire({
                    icon: 'success',
                    title: 'Chính xác'
                });
                if(curent_id == 9)
                {
                    end_game();
                } else {
                    cauhoanthanh +=1;
                    answer_question(curent_row, res['answer']);
                }
                
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Không chính xác'
                });
            }
            $('#exampleModal').on('hidden.bs.modal', function () {
                clearInterval(changeTime);
                clearTimeout(timeout);
            });
        });
        
    })
    
    function end_game()
    {
        
            send_result_to_server();
            $.ajax({
                url:window.location.href,
                method:"post",
                dataType:"json",
                data:{"action":"get_all_answer"}
            }).done(function(res){
                arr_question = [];
                res.forEach(function(value,index){
                    let obj = {"id": value['id'],"answer":value['answer'],"qestion": value['question'],  "second":30, "status":0};
                    arr_question.push(obj);
                });
                status = 1;
                let index_arr = 0;
                arr_question.forEach(function(el,index){
                    if(el['id'] == curent_id)
                    {
                        index_arr = index;
                    }
                });
                arr_question[index_arr]["status"] = 1;
                $("#exampleModal").modal("hide");
                clearInterval(total_point);
                $(".word").each(function(index){
                    $(this).find("span").each(function(index2){
                        $(this).html(arr_question[index]["answer"][index2]);
                    })
                });
                localStorage.diem = diem;
                Swal.fire({
                    icon: 'success',
                    title: 'Trò chơi kết thúc',
                    text: 'Điểm số của bạn là'+ diem,
                    footer: ''
                })
                .then(function() {
                    window.location = "index.php?language=vi&nv=nghenhac&op=result_page";
                });
            })
    }
    function answer_question(curent_row, answer)
    {
        console.log(curent_row);
        curent_row.find("span").each(function(index){
            $(this).html(answer[index]);
        });
        let index_arr = 0;
        arr_question.forEach(function(el,index){
            if(el['id'] == curent_id)
            {
                index_arr = index;
            }
        })
        arr_question[index_arr]["status"] = 1;
        $("#exampleModal").modal("hide");
    }
    function handelChangeSecond()
    {
        let curent = curent_time--;
        $("#time").html(curent);
        let index_arr = 0;
        arr_question.forEach(function(el,index){
            if(el['id'] == curent_id)
            {
                index_arr = index;
            }
        })
        arr_question[index_arr]['second'] = curent;
    }
    function send_result_to_server()
    {
        $.ajax({
            url:window.location.href,
            method:"post",
            data:{"diem":diem}
        }).done(function(res){
            console.log(res);
        })
        
    }
    
});