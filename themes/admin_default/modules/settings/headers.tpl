<!-- BEGIN: main -->
<div class="clearfix">
    <ul class="nav nav-tabs setting-tabnav" role="tablist" id="settingTabs">
        <li role="presentation" class="{TAB0_ACTIVE}"><a href="#settingCSP" aria-controls="settingCSP" aria-offsets="0" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=0">{LANG.csp}</a></li>
        <li role="presentation" class="{TAB1_ACTIVE}"><a href="#settingRP" aria-controls="settingRP" aria-offsets="1" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=1">{LANG.rp}</a></li>
        <li role="presentation" class="{TAB2_ACTIVE}"><a href="#settingPP" aria-controls="settingPP" aria-offsets="2" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=2">{LANG.pp}</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane{TAB0_ACTIVE}" id="settingCSP">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td colspan="2">{LANG.csp_desc} <a href="https://content-security-policy.com/" target="_blank">{LANG.csp_details}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.csp_act}</strong></td>
                                    <td>
                                        <label>
                                            <input type="checkbox" value="1" name="nv_csp_act"{CSP_ACT}/>
                                        </label>
                                    </td>
                                </tr>
                                <!-- BEGIN: csp_directive -->
                                <tr>
                                    <td><strong>{DIRECTIVE.name}</strong><br />{DIRECTIVE.desc}</td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="directives[{DIRECTIVE.name}]">{DIRECTIVE.value}</textarea>
                                        <div class="form-text text-muted">{LANG.csp_note}</div>
                                    </td>
                                </tr>
                                <!-- END: csp_directive -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{GLANG.submit}" name="submitcsp" class="btn btn-primary w100"/>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB1_ACTIVE}" id="settingRP">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div>
                        <div class="well-sm">
                            {LANG.rp_desc} <a href="https://www.w3.org/TR/referrer-policy/" target="_blank">{LANG.rp_details}</a>.<br />
                            {LANG.rp_desc2}
                        </div>

                        <strong>{LANG.rp_directives}:</strong>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <!-- BEGIN: rp_directive -->
                            <div class="panel panel-default">
                                <a role="button" class="panel-heading btn-block" data-toggle="collapse" data-parent="#accordion" href="#collapse-{RP_DIRECTIVE.name}" aria-expanded="false" aria-controls="collapse-{RP_DIRECTIVE.name}">
                                    {RP_DIRECTIVE.name}
                                </a>
                                <div id="collapse-{RP_DIRECTIVE.name}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{RP_DIRECTIVE.name}">
                                    <div class="panel-body">
                                        <code><strong>{RP_DIRECTIVE.name}</strong></code>: {RP_DIRECTIVE.desc}
                                    </div>
                                </div>
                            </div>
                            <!-- END: rp_directive -->
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first border-top">
                            <colgroup>
                                <col style="width: 30%" />
                                <col style="width: 70%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td colspan="2">{LANG.rp_note}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.rp_act}</strong>
                                    </td>
                                    <td><input type="checkbox" value="1" name="nv_rp_act"{RP_ACT}/></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.rp_directives}</strong>
                                    </td>
                                    <td>
                                        <input class="form-control pull-left" type="text" value="{RP}" name="nv_rp" maxlength="500"/>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{GLANG.submit}" name="submitrp" class="btn btn-primary w100"/>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB2_ACTIVE}" id="settingPP">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first border-top">
                            <colgroup>
                                <col style="width: 30%" />
                                <col style="width: 70%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        - {LANG.pp_desc}. <a href="https://www.w3.org/TR/permissions-policy/" target="_blank">{LANG.rp_details}</a>.<br />
                                        - {LANG.pp_desc2}.<br />
                                        - {LANG.pp_desc3}.<br />
                                        - {LANG.pp_note}.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.pp_act}</strong>
                                    </td>
                                    <td><input type="checkbox" value="1" name="nv_pp_act"{PP_ACT}></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.fp_act}</strong>
                                    </td>
                                    <td><input type="checkbox" value="1" name="nv_fp_act"{FP_ACT}></td>
                                </tr>
                                <!-- BEGIN: pp_directive -->
                                <tr>
                                    <td><strong>{DIRECTIVE.name}</strong><br />{DIRECTIVE.desc}</td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="directives[{DIRECTIVE.name}]">{DIRECTIVE.value}</textarea>
                                    </td>
                                </tr>
                                <!-- END: pp_directive -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{GLANG.submit}" name="submitpp" class="btn btn-primary w100" />
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="gselectedtab" value="{SELECTEDTAB}"/>
<script type="text/javascript">
$(document).ready(function() {
    $('#settingTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
        $('[name="gselectedtab"]').val($(this).attr('aria-offsets'));
    });
});
</script>
<!-- END: main -->
