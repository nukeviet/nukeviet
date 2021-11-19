<!-- BEGIN: main -->
    <div class="container">
        <div class="col-16 text-danger" style="font-size: 20px;">Điểm số của bạn: <span id="diem" style="font-weight:bold;"></span> </div>
        <div>
    </div>
    <div class="container">
        <h2>Top 50 người chơi điểm cao nhất</h2>
        <table class="table">
  <thead>
    <tr>
      <th scope="col">STT</th>
      <th scope="col">Tên người chơi</th>
      <th scope="col">Điểm</th>
      <th scope="col">Thời gian</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: loop_results -->
    <tr>
      <th scope="row">{result.stt}</th>
      <td>{result.username}</td>
      <td>{result.diem}</td>
      <td>{result.timeupdate}</td>
    </tr>
    <!-- END:loop_results -->
  </tbody>
</table>    
    <div class="text-center">
        <button class="btn btn-primary">Chơi lại</button>
    <div>
    </div>
    <script>
        $("#diem").html(localStorage.diem);
    </script>
<!-- END:main -->