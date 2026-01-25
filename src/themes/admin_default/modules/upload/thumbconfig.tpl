<!-- BEGIN: main -->
<div class="alert alert-info">
    <p>{LANG.thumb_note}</p>
    <p>- {LANG.thumb_default_size_note}</p>
    <p>- {LANG.thumb_dir_size_note}</p>
</div>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="thumb-config-form">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr class="text-center">
                    <th>{LANG.thumb_dir}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.thumb_type}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.thumb_width_height}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.thumb_quality}</th>
                    <th style="width:1%">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-center"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.pubdate}" class="btn btn-primary"/></td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: loop -->
                <tr class="text-center item" data-did="d{DATA.did}">
                    <td class="text-left"><strong>{DATA.dirname}</strong></td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <select name="thumb_type[{DATA.did}]" class="form-control" style="width: fit-content;">
                            <!-- BEGIN: thumb_type -->
                            <option value="{TYPE.id}" {TYPE.selected}>{TYPE.name}</option>
                            <!-- END: thumb_type -->
                        </select>
                    </td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <div style="display: flex;justify-content: center">
                            <input class="form-control text-center number" style="width: 60px;" type="text" value="{DATA.thumb_width}" name="thumb_width[{DATA.did}]" maxlength="3"/>
                            <span class="text-middle">&nbsp;x&nbsp;</span>
                            <input class="form-control text-center number" style="width: 60px;" type="text" value="{DATA.thumb_height}" name="thumb_height[{DATA.did}]" maxlength="3"/>
                        </div>
                    </td>
                    <td class="text-nowrap text-center w100">
                        <select name="thumb_quality[{DATA.did}]" class="form-control">
                            <!-- BEGIN: thumb_quality -->
                            <option value="{QUALITY.val}" {QUALITY.sel}>{QUALITY.val}</option>
                            <!-- END: thumb_quality -->
                        </select>
                    </td>
                    <td class="text-nowrap text-left" style="width:1%">
                        <a data-toggle="thumbCfgViewEx" data-did="{DATA.did}" data-errmsg="{LANG.prViewExampleError}" href="#" class="btn btn-default"><span class="text-black"><i class="fa fa-search" aria-hidden="true"></i> {LANG.prViewExample}</span></a>
                        <!-- BEGIN: delbtn --><button type="button" class="btn btn-default" data-toggle="remove_config" title="{GLANG.delete}"><em class="fa fa-times" aria-hidden="true"></em></button><!-- END: delbtn -->
                    </td>
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
                    <td class="text-nowrap text-center" style="width:1%">
                        <div style="display: flex;justify-content: center">
                            <input class="form-control text-center number" style="width: 60px;" type="text" value="100" name="other_thumb_width" maxlength="3"/>
                            <span class="text-middle">&nbsp;x&nbsp;</span>
                            <input class="form-control text-center number" style="width: 60px;" type="text" value="120" name="other_thumb_height" maxlength="3"/>
                    </td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <select name="other_thumb_quality" class="form-control">
                            <!-- BEGIN: other_thumb_quality -->
                            <option value="{QUALITY.val}" {QUALITY.sel}>{QUALITY.val}</option>
                            <!-- END: other_thumb_quality -->
                        </select>
                    </td>
                    <td class="text-left" style="width:1%">
                        <a data-toggle="thumbCfgViewEx" data-did="-1" data-errmsg="{LANG.prViewExampleError}" href="#" class="btn btn-default"><span class="text-black"><i class="fa fa-search" aria-hidden="true"></i> {LANG.prViewExample}</span></a>
                    </td>
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

</script>
<!-- END: main -->