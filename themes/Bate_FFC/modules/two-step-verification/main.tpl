<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading"><strong>{LANG.title_2step}</strong></div>
    <div class="list-group">
        <div class="list-group-item">
            <p>
                {LANG.status_2step}:
                <!-- BEGIN: on --><strong class="text-success">{LANG.active_2step}</strong><!-- END: on -->
                <!-- BEGIN: off --><strong class="text-danger">{LANG.deactive_2step}</strong><!-- END: off -->
            </p>
            <!-- BEGIN: turnoff -->
            <div class="clearfix">
                <input class="btn btn-danger" type="button" value="{LANG.turnoff2step}" data-toggle="turnoff2step" data-tokend="{NV_CHECK_SESSION}"/>
            </div>
            <!-- END: turnoff -->
            <!-- BEGIN: turnon -->
            <div class="clearfix">
                <a href="{LINK_TURNON}" class="btn btn-info">{LANG.turnon2step}</a>
            </div>
            <!-- END: turnon -->
        </div>
        <!-- BEGIN: backupcode -->
        <div class="list-group-item">
            <p>{NUM_CODE}: <strong><a href="#modal-backupcode" data-toggle="viewcode">{LANG.backupcode_2step_view}</a></strong></p>
            <div class="clearfix">
                <input class="btn btn-info" type="button" value="{LANG.creat_other_code}" data-toggle="changecode2step" data-tokend="{NV_CHECK_SESSION}"/>
            </div>
        </div>
        <!-- BEGIN: autoshowcode -->
        <script type="text/javascript">
        $(function() {
            $('[data-toggle="viewcode"]').click();
        });
        </script>
        <!-- END: autoshowcode -->
        <!-- END: backupcode -->
    </div>
</div>
<!-- BEGIN: backupcodeModal -->
<div id="modal-backupcode" title="{LANG.backupcode_2step_view}" class="hidden">
    <div class="clearfix">
        <div class="alert alert-warning">{LANG.backupcode_2step_note}</div>
        <div class="row">
            <!-- BEGIN: code -->
            <div class="col-xs-12 text-center">
                <div class="recovery-code">
                    <!-- BEGIN: unuse --><i class="fa fa-square-o" aria-hidden="true"></i><!-- END: unuse -->
                    <!-- BEGIN: used --><i class="fa fa-check-square-o" aria-hidden="true"></i><!-- END: used -->
                    &nbsp; <span>{CODE.code}</span>
                </div>
            </div>
            <!-- END: code -->
        </div>
    </div>
</div>
<!-- END: backupcodeModal -->
<!-- BEGIN: main -->