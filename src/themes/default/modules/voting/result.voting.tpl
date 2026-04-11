<div class="margin">
    <!-- BEGIN: note -->
    <div class="alert alert-info">{VOTINGNOTE}</div>
    <!-- END: note -->
    <h3 class="text-primary text-center margin-bottom-lg">{VOTINGQUESTION}</h3>
    <!-- BEGIN: result -->
    <div class="row">
        <div class="col-xs-24 col-md-12">{VOTING.title}</div>
        <div class="col-xs-24 col-md-12">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="{WIDTH}" aria-valuemin="0" aria-valuemax="100" style="width: {WIDTH}%;"><span class="text-danger">{WIDTH}%</span></div>
            </div>
        </div>
    </div>
    <!-- END: result -->
    <p class="text-center">
        <strong>{LANG.voting_total}</strong>: {TOTAL} {LANG.voting_counter} - <strong>{LANG.voting_pubtime}: </strong>{PUBLTIME}
    </p>
</div>
