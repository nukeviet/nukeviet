<!-- BEGIN: main -->
<div class="centered">
    <div class="sm-container-box">
        <!-- BEGIN: unlink_account -->
        <h1 class="margin-bottom-sm">{LANG.datadeletion_title}</h1>
        <p class="margin-bottom-lg">{LANG.datadeletion_text} {DATA.request_source}.</p>
        <div class="box-security box-shadow-lg">
            <div class="text-center">
                <div class="margin-bottom-lg">
                    <i class="fa fa-check-circle-o text-primary fa-3x" aria-hidden="true"></i>
                </div>
                <h2>{LANG.datadeletion_success}</h2>
                <p>{LANG.datadeletion_success1}.</p>
                <div class="well text-center">
                    {LANG.datadeletion_id}: <br>
                    <div class="h1 text-primary">{DATA.confirmation_code}</div>
                </div>
                <hr>
                <div class="text-muted small">{LANG.datadeletion_data_info1} {DATA.request_source} {LANG.datadeletion_data_info2}</div>
            </div>
        </div>
        <!-- END: unlink_account -->
        <!-- BEGIN: delete_account -->
        <h1 class="margin-bottom-sm">{LANG.datadeletion_pedding_title}</h1>
        <p class="margin-bottom-lg">{LANG.datadeletion_pedding_sub} {DATA.request_source}.</p>
        <div class="box-security box-shadow-lg">
            <div class="text-center">
                <div class="margin-bottom-lg">
                    <i class="fa fa-clock-o text-warning fa-3x" aria-hidden="true"></i>
                </div>
                <h2>{LANG.request_accepted}</h2>
                <p>{LANG.datadeletion_pedding_body}.</p>
                <div class="well text-center">
                    {LANG.datadeletion_id}: <br>
                    <div class="h1 text-primary margin-bottom-lg">{DATA.confirmation_code}</div>
                    <small>{LANG.datadeletion_pedding_time}:</small><br>
                    <strong class="text-danger">{DATA.deletion_time}</strong>
                </div>
                <hr>
                <div class="text-muted small">{LANG.datadeletion_data_info1} {DATA.request_source} {LANG.datadeletion_pedding_info}.</div>
            </div>
        </div>
        <!-- END: delete_account -->
    </div>
</div>
<!-- END: main -->
