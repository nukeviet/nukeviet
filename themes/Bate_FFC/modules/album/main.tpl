<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/crossword.css" />
     <div class="container">
        <div class="crossword">
            <div class="word">
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter pivot"></span>
                <span class="letter"></span>
            </div>
            <div class="word">
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter pivot"></span>
            </div>
            <div class="word">
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter"></span>
                <span class="letter pivot"></span>
                <span class="letter"></span>
            </div>
        </div>
     </div>
     <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLabel">Câu hỏi</h3>
          <h4>Thời gian còn lại: <span id="time">30</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group" id="cauhoi">
              There will be a question-and-answer session (= a period when people can ask questions) at the end of the talk.
          </div>
          <div class="form-group">
              <input class="form-control" id="input_answer" type="text"/>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="answer">Save changes</button>
        </div>
      </div>
    </div>
</div>
     <script src="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/js/crossword.js"></script>
<!-- END:main -->