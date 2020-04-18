<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<!-- BEGIN: info -->
<div class="alert alert-info">{LANG.sampledata_note}</div>
<!-- END: info -->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>{LANG.sampledata_creat}</strong>
            </div>
            <div class="panel-body" id="sampledataarea" data-errsys="{LANG.sampledata_error_sys}" data-init="{LANG.sampledata_dat_init}">
                <form class="form-horizontal" method="post" action="">
                    <input type="hidden" name="delifexists" value="0"/>
                    <div class="form-group">
                        <label class="control-label col-sm-5">{LANG.sampledata_name}:</label>
                        <div class="col-sm-19">
                            <input type="text" class="form-control" name="sample_name" value="{DATA.sample_name}" placeholder="{LANG.sampledata_name_rule}" maxlength="50"/>
                        </div>
                    </div>
                    <div class="form-group form-group-end">
                        <div class="col-sm-19 col-sm-push-5">
                            <button type="submit" class="btn btn-primary" name="submit"><span class="lo"><i class="fa fa-spin fa-spinner"></i>&nbsp;</span>{LANG.sampledata_start}</button>
                        </div>
                    </div>
                </form>
                <div id="spdresult">
                    <div id="spdresulttop"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>{LANG.sampledata_list}</strong>
            </div>
            <!-- BEGIN: empty -->
            <div class="panel-body">
                <div class="alert alert-info alert-inline">{LANG.sampledata_empty}</div>
            </div>
            <!-- END: empty -->
            <!-- BEGIN: data -->
            <div class="list-group">
                <!-- BEGIN: loop -->
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-sm-16"><strong>{ROW.title}</strong></div>
                        <div class="col-sm-8">
                            {ROW.creattime}
                            <a href="javascript:void(0);" class="pull-right text-danger" onclick="nv_delete_sampledata('{ROW.title}');"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </div>
                </div>
                <!-- END: loop -->
            </div>
            <!-- END: data -->
        </div>
    </div>
</div>
<!-- END: main -->