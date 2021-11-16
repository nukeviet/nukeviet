<!-- BEGIN: main -->
<!-- BEGIN: chatbot_note -->
<div class="alert alert-danger">{LANG.chatbot_note}</div>
<!-- END: chatbot_note -->
<!-- BEGIN: if_not_popup -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#zalo_events" aria-controls="zalo_events" role="tab" data-toggle="tab" data-location="{PAGE_LINK}&tab=zalo_events">{LANG.action_for_zalo_event}</a></li>
    <li role="presentation"><a href="#command_keywords" aria-controls="command_keywords" role="tab" data-toggle="tab" data-location="{PAGE_LINK}&tab=command_keywords">{LANG.action_for_command_keyword}</a></li>
</ul>
<script>
    $(function() {
        $('[aria-controls="{TAB_ACTIVE}"]').tab('show')
    })
</script>
<div class="tab-content" style="padding-top: 15px">
    <div role="tabpanel" class="tab-pane" id="zalo_events">
        <div class="table-responsive">
            <form method="POST" action="{PAGE_LINK}" data-toggle="event_action_submit" data-action-error-mess="{LANG.action_empty}" data-parameter-error-mess="{LANG.parameter_empty}">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-primary">
                            <th style="width: 45%;">{LANG.event}</th>
                            <th>{LANG.action}</th>
                            <th class="text-nowrap" style="width:80px">{LANG.parameter}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <input type="hidden" name="zalo_events" value="1">
                                <button type="submit" class="btn btn-primary">{GLANG.save}</button>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <!-- BEGIN: event -->
                        <tr class="event" data-key="{EVENT.key}">
                            <td style="width: 45%;min-width: 150px">{EVENT.name}</td>
                            <td>
                                <div class="form-group m-bottom-none">
                                    <select name="action[{EVENT.key}]" class="form-control" style="min-width: 100px;" data-toggle="event_action_change" data-default="{EVENT.action}"<!-- BEGIN: readonly --> readonly="readonly"<!-- END: readonly -->>
                                        <option value=""></option>
                                        <!-- BEGIN: user_orient_actions -->
                                        <option value="{ACTION.key}" data-url="{ACTION.url}" data-view="{ACTION.view_url}" {ACTION.sel}>{ACTION.name}</option>
                                        <!-- END: user_orient_actions -->
                                    </select>
                                </div>
                            </td>
                            <td style="width:140px">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="parameter[{EVENT.key}]" id="parameter_{EVENT.key}" data-default="{EVENT.parameter}" value="{EVENT.parameter}" readonly="readonly" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" title="{LANG.parameter_edit}" data-toggle="parameter_change"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-default" data-toggle="parameter_view"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <!-- END: event -->
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="command_keywords">
<!-- END: if_not_popup -->
        <div<!-- BEGIN: if_popup --> style="padding:10px 15px;background-color:#fff"<!-- END: if_popup -->>
            <div class="well well-sm">{LANG.command_keyword_note}</div>
            <div class="table-responsive">
                <form method="POST" action="{PAGE_LINK}" data-toggle="command_keyword_submit" data-keyword-error-mess="{LANG.keyword_empty}" data-action-error-mess="{LANG.action_empty}" data-parameter-error-mess="{LANG.parameter_empty}">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-primary">
                                <!-- BEGIN: if_popup2 -->
                                <th style="width: 1%;"></th>
                                <!-- END: if_popup2 -->
                                <th style="width: 45%;">{LANG.if_command_keyword}</th>
                                <th>{LANG.action}</th>
                                <th style="width: 1%;"></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <input type="hidden" name="command_keywords" value="1">
                                    <button type="submit" class="btn btn-primary">{GLANG.save}</button>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <!-- BEGIN: keyword -->
                            <tr class="event" data-idfield="{IDFIELD}">
                                <!-- BEGIN: if_popup -->
                                <td style="width: 1%;">
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="keyword_select" data-value="{KEYWORD.key}"<!-- BEGIN: disabled --> disabled<!-- END: disabled -->>{LANG.keyword_select}</button>
                                </td>
                                <!-- END: if_popup -->
                                <td style="width: 45%;min-width: 200px">
                                    <div class="form-group">
                                        <input type="text" name="title[]" value="{KEYWORD.title}" placeholder="{LANG.description}" data-default="{KEYWORD.title}" class="form-control" maxlength="250" data-toggle="text_get_alias"/>
                                    </div>
                                    <div class="input-group readonly-item">
                                        <input type="text" name="keyword[]" value="{KEYWORD.key}" placeholder="{LANG.command_keyword}" data-default="{KEYWORD.key}" class="form-control" maxlength="50" data-toggle="alias_get" readonly/>
                                        <span class="input-group-addon">
                                            <input type="checkbox" data-toggle="readonly_remove" style="margin:0">
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="action[]" class="form-control" style="min-width: 150px;" data-toggle="event_action_change" data-default="{KEYWORD.action}">
                                            <option value=""></option>
                                            <!-- BEGIN: user_orient_actions -->
                                            <option value="{ACTION.key}" data-url="{ACTION.url}" data-view="{ACTION.view_url}" {ACTION.sel}>{ACTION.name}</option>
                                            <!-- END: user_orient_actions -->
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon" style="background-color:#fff">{LANG.parameter}</span>
                                        <input type="text" class="form-control" style="min-width: 50px;" name="parameter[]" id="par_{KEYWORD.i}" data-default="{KEYWORD.parameter}" value="{KEYWORD.parameter}" readonly="readonly" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" title="{LANG.parameter_edit}" data-toggle="parameter_change"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn btn-default" data-toggle="parameter_view"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <!-- BEGIN: remove -->
                                    <button type="button" class="close" data-toggle="event_remove" title="{GLANG.delete}" data-confirm-mess="{LANG.delete_confirm}"><em class="fa fa-times"></em></button>
                                    <!-- END: remove -->
                                </td>
                            </tr>
                            <!-- END: keyword -->
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
<!-- BEGIN: if_not_popup2 -->
    </div>
</div>
<!-- END: if_not_popup2 -->

<!-- Modal -->
<div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="previewLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="previewLabel">{LANG.viewfile}</h4>
            </div>
            <div class="modal-body content"></div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="action-title"></div>
            </div>
            <div class="modal-body action-content"></div>
        </div>
    </div>
</div>
<!-- END: main -->