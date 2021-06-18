<!-- BEGIN: main -->
<div class="alert alert-info">{LANG.thumb_note}</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="frm">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="text-center">
                    <th>{LANG.thumb_dir}</th>
                    <th>{LANG.thumb_type}</th>
                    <th>{LANG.thumb_width_height}</th>
                    <th>{LANG.thumb_quality}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-center"><input type="submit" value="{LANG.pubdate}" name="submit" class="btn btn-primary"/></td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: loop -->
                <tr class="text-center">
                    <td class="text-left"><strong>{DATA.dirname}</strong></td>
                    <td>
                        <select name="thumb_type[{DATA.did}]" class="form-control">
                            <!-- BEGIN: thumb_type -->
                            <option value="{TYPE.id}" {TYPE.selected}>{TYPE.name}</option>
                            <!-- END: thumb_type -->
                        </select>
                    </td>
                    <td><input class="form-control w50 pull-left" type="text" value="{DATA.thumb_width}" name="thumb_width[{DATA.did}]" maxlength="3"/><span class="pull-left text-middle">&nbsp;x&nbsp;</span><input class="form-control pull-left w50" type="text" value="{DATA.thumb_height}" name="thumb_height[{DATA.did}]" maxlength="3"/></td>
                    <td><input class="form-control w50" type="text" value="{DATA.thumb_quality}" name="thumb_quality[{DATA.did}]" maxlength="2"/></td>
                    <td><a data-toggle="thumbCfgViewEx" data-did="{DATA.did}" data-errmsg="{LANG.prViewExampleError}" href="#" class="btn btn-default"><span class="text-black"><i class="fa fa-search" aria-hidden="true"></i> {LANG.prViewExample}</span></a></td>
                </tr>
                <!-- END: loop -->
                <tr class="text-center">
                    <td class="text-left">
                        <select name="other_dir" class="form-control">
                            <option value=""> ---- </option>
                            <!-- BEGIN: other_dir -->
                            <option value="{OTHER_DIR.did}">{OTHER_DIR.dirname}</option>
                            <!-- END: other_dir -->
                        </select>
                    </td>
                    <td>
                        <select name="other_type" class="form-control">
                            <!-- BEGIN: other_type -->
                            <option value="{TYPE.id}">{TYPE.name}</option>
                            <!-- END: other_type -->
                        </select>
                    </td>
                    <td><input class="form-control w50 pull-left" type="text" value="100" name="other_thumb_width" maxlength="3"/><span class="pull-left text-middle">&nbsp;x&nbsp;</span><input class="form-control w50 pull-left" type="text" value="120" name="other_thumb_height" maxlength="3"/></td>
                    <td><input class="form-control w50" type="text" value="90" name="other_thumb_quality" maxlength="2"/></td>
                    <td><a data-toggle="thumbCfgViewEx" data-did="-1" data-errmsg="{LANG.prViewExampleError}" href="#" class="btn btn-default"><span class="text-black"><i class="fa fa-search" aria-hidden="true"></i> {LANG.prViewExample}</span></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<div id="thumbprewiew"></div>
<div id="thumbprewiewtmp" class="hidden">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-imgorg">
                    <h2 class="text-right"><strong>{LANG.original_image}</strong></h2>
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" class="img-responsive imgorg pull-right"/>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <h2><strong>{LANG.thumb_image}</strong></h2>
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" class="img-responsive imgthumb"/>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#frm').validate();
});
</script>
<!-- END: main -->