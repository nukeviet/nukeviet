<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">{LANG.oa_info}</div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <!-- BEGIN: loop -->
                        <tr>
                            <td class="text-nowrap">{OA.key}</td>
                            <td>
                                <!-- BEGIN: normal -->
                                {OA.val}
                                <!-- END: normal -->
                                <!-- BEGIN: cover -->
                                <p><img src="{OA.val}" width="320" alt="" class="img-thumbnail" style="min-height: 180px;" /></p>
                                <code style="word-break: break-all;">{OA.val}</code>
                                <!-- END: cover -->
                                <!-- BEGIN: avatar -->
                                <p><img src="{OA.val}" width="150" alt="" class="img-thumbnail" style="min-height: 150px;" /></p>
                                <code style="word-break: break-all;">{OA.val}</code>
                                <!-- END: avatar -->
                                <!-- BEGIN: qrcode -->
                                <p><img src="{OA.val}" alt="" width="170px" height="170px"/></p>
                                <code style="word-break: break-all;">{OA.val}</code>
                                <!-- END: qrcode -->
                            </td>
                        </tr>
                        <!-- END: loop -->
                        <tr>
                            <td class="text-nowrap">{LANG.zalo_homepage}</td>
                            <td>
                                <a href="{ZALO_HOMEPAGE}" target="_blank">{ZALO_HOMEPAGE}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="panel-group" id="oa_actions" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab">
                    <h4 class="panel-title"><a href="#" role="button" data-toggle="oa_info_update">{LANG.oa_info_update}</a></h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="getquota_title">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#oa_actions" href="#getquota" aria-expanded="false" aria-controls="getquota">
                            {LANG.proactive_messages_quota}
                        </a>
                    </h4>
                </div>
                <div id="getquota" class="panel-collapse collapse" role="tabpanel" aria-labelledby="getquota_title" data-url="{FORM_ACTION}">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>{LANG.type}</td>
                                <td class="text-right" id="proactive_messages_type">--</td>
                            </tr>
                            <tr>
                                <td>{LANG.total}</td>
                                <td class="text-right" id="proactive_messages_total">--</td>
                            </tr>
                            <tr>
                                <td>{LANG.remain}</td>
                                <td class="text-right" id="proactive_messages_remain">--</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="panel-footer"><em>{LANG.proactive_messages_note}</em></div>
                </div>
            </div>

        </div>
    </div>
</div>

<form id="oa-info-update" method="POST" action="{FORM_ACTION}">
    <input type="hidden" name="oa_info_update" value="1" />
</form>
<!-- END: main -->

<!-- BEGIN: oa_clear -->
<div class="alert alert-warning">{LANG.oa_clear_info}</div>
<button type="button" class="btn btn-primary" data-toggle="oa_clear">{LANG.oa_clear}</button>
<form id="oa-clear" method="POST" action="{FORM_ACTION}">
    <input type="hidden" name="oa_clear" value="1" />
</form>
<!-- END: oa_clear -->