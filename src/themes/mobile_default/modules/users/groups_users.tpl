<!-- BEGIN: main -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.group_manage}</h2>
<!-- BEGIN: tools -->
<div id="ablist" class="container-fluid margin-bottom" data-checkss="{DATA.checkss}" data-url="{MODULE_URL}={OP}" data-gid="{GID}" data-area="page">
    <div class="row">
        <div class="col-sm-14 col-md-14 margin-bottom-lg">
            <!-- BEGIN: addUserGroup -->
            <select name="uid" id="uid" class="form-control" style="width:150px" data-placeholder="{LANG.addMemberToGroup}" data-checkss="{DATA.checkss}" data-gid="{GID}" data-min-search="{MIN_SEARCH}"></select>
            <button class="btn btn-primary" name="addUser" type="button" title="{LANG.addMemberToGroup}" data-msg-nochoice="{LANG.choiceUserID}"><i class="fa fa-plus" data-icon="fa-plus"></i></button>
            <!-- END: addUserGroup -->
        </div>
        <div class="col-sm-10 col-md-10 text-right margin-bottom-lg">
            <a href="{EDIT_GROUP_URL}" class="btn btn-primary" title="{GLANG.edit}"><i class="fa fa-pencil-square-o"></i></a>
            <!-- BEGIN: inform_notifications -->
            <a href="{INFORM_NOTIFICATIONS_URL}" class="btn btn-primary" title="{GLANG.inform_notifications}"><i class="fa fa-bell-o"></i></a>
            <!-- END: inform_notifications -->
            <!-- BEGIN: add_user -->
            <a href="{MODULE_URL}=register/{GID}" class="btn btn-primary" title="{LANG.addusers}"><i class="fa fa-user-plus"></i></a>
            <!-- END: add_user -->
            <!-- BEGIN: user_waiting -->
            <button class="btn btn-primary" name="user_waiting" type="button" title="{LANG.user_waiting}" data-title="{LANG.user_waiting}" aria-label="{LANG.user_waiting}"><i class="fa fa-search-plus" data-icon="fa-search-plus"></i></button>
            <!-- END: user_waiting -->
        </div>
    </div>
</div>
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<!-- END: tools -->
<table class="table table-bordered">
    <tr>
        <td rowspan="4" style="width:80px;border-top:0"><img title="{DATA.title}" alt="{DATA.title}" src="{ASSETS_STATIC_URL}/images/pix.svg" width="80" height="80" style="background-image:url({DATA.group_avatar});background-repeat:no-repeat;background-size:cover;" /></td>
        <td class="text-nowrap" style="width:80px;border-top:0"><strong>{LANG.group_title}</strong></td>
        <td style="border-top:0">{DATA.title}
            <!-- BEGIN: group_desc --> ({DATA.description})<!-- END: group_desc -->
        </td>
    </tr>
    <tr class="active">
        <td class="text-nowrap"><strong>{LANG.group_type}</strong></td>
        <td>{DATA.group_type_mess}
            <!-- BEGIN: group_type_note --> ({DATA.group_type_note})<!-- END: group_type_note -->
        </td>
    </tr>
    <tr>
        <td class="text-nowrap"><strong>{LANG.group_exp_time}</strong></td>
        <td>{DATA.exp}</td>
    </tr>
    <tr class="active">
        <td class="text-nowrap"><strong>{LANG.group_userr}</strong></td>
        <td>{DATA.numbers}</td>
    </tr>
</table>
<!-- BEGIN: group_content -->
<div style="margin-bottom:20px">
    {DATA.content}
</div>
<!-- END: group_content -->
<!-- BEGIN: no_users -->
<div class="alert alert-warning">{LANG.error_users_not_found}</div>
<!-- END: no_users -->
<!-- BEGIN: users -->
<!-- BEGIN: pending -->
<div id="id_pending" class="m-bottom" data-checkss="{DATA.checkss}" data-url="{MODULE_URL}={OP}" data-gid="{GID}" data-area="page">
    <h3 class="m-bottom">{PTITLE} <strong class="text-danger">({DATA.data_number_view.pending})</strong></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col width="50" />
            <thead>
                <tr>
                    <th class="text-center">{LANG.STT}</th>
                    <th>{LANG.account} ({LANG.nametitle})</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">{STT}</td>
                    <td>{LOOP.username} ({LOOP.full_name})</td>
                    <td class="text-right">
                        <!-- BEGIN: tools -->
                        <button class="btn btn-success btn-sm" title="{LANG.approved}" aria-label="{LANG.approved}" data-id="{LOOP.userid}" data-toggle="approved"><i class="fa fa-check" data-icon="fa-check"></i></button>
                        <button class="btn btn-warning btn-sm" title="{LANG.denied}" data-id="{LOOP.userid}" data-toggle="denied"><i class="fa fa-minus-circle"></i></button>
                        <!-- END: tools -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <!-- BEGIN: viewmore -->
    <a class="btn btn-sm btn-primary" href="{DATA.link_types.pending}">{GLANG.view_more}</a>
    <!-- END: viewmore -->
    <!-- BEGIN: page -->
    <div class="text-center">{PAGE}</div>
    <!-- END: page -->
</div>
<!-- END: pending -->

<!-- BEGIN: leaders -->
<div id="id_leaders" class="m-bottom">
    <h3 class="m-bottom">{PTITLE} <strong class="text-danger">({DATA.data_number_view.leaders})</strong></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col width="50" />
            <thead>
                <tr>
                    <th class="text-center">{LANG.STT}</th>
                    <th>{LANG.account} ({LANG.nametitle})</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center"> {STT} </td>
                    <td>
                        <!-- BEGIN: textuser -->
                        {LOOP.username} ({LOOP.full_name})
                        <!-- END: textuser -->
                        <!-- BEGIN: linkuser -->
                        <a href="{LOOP.link_view}">{LOOP.username} ({LOOP.full_name})</a>
                        <!-- END: linkuser -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <!-- BEGIN: viewmore -->
    <a class="btn btn-sm btn-primary" href="{DATA.link_types.leaders}">{GLANG.view_more}</a>
    <!-- END: viewmore -->
    <!-- BEGIN: page -->
    <div class="text-center">{PAGE}</div>
    <!-- END: page -->
</div>
<!-- END: leaders -->

<!-- BEGIN: members -->
<div id="id_members" class="m-bottom" data-checkss="{DATA.checkss}" data-url="{MODULE_URL}={OP}" data-gid="{GID}" data-area="page">
    <h3 class="m-bottom">{PTITLE} <strong class="text-danger">({DATA.data_number_view.members})</strong></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col width="50" />
            <thead>
                <tr>
                    <th class="text-center">{LANG.STT}</th>
                    <th>{LANG.account} ({LANG.nametitle})</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">{STT}</td>
                    <td>
                        <!-- BEGIN: textuser -->
                        {LOOP.username} ({LOOP.full_name})
                        <!-- END: textuser -->
                        <!-- BEGIN: linkuser -->
                        <a href="{LOOP.link_view}">{LOOP.username} ({LOOP.full_name})</a>
                        <!-- END: linkuser -->
                    </td>
                    <td class="text-right">
                        <!-- BEGIN: tools -->
                        <!-- BEGIN: edituser -->
                        <a href="{LOOP.link_edit}" class="edituser btn btn-primary btn-sm" title="{GLANG.edit}"><i class="fa fa-pencil-square-o"></i></a>
                        <!-- END: edituser -->
                        <!-- BEGIN: deletemember -->
                        <button class="deletemember btn btn-warning btn-sm" title="{LANG.exclude_user2}" data-id="{LOOP.userid}" data-toggle="deletemember"><i class="fa fa-minus-circle" data-icon="fa-minus-circle"></i></button>
                        <!-- END: deletemember -->
                        <!-- BEGIN: deluser -->
                        <button class="btn btn-danger btn-sm" title="{GLANG.delete}" data-id="{LOOP.userid}" data-toggle="deluser"><i class="fa fa-trash-o" data-icon="fa-trash-o"></i></button>
                        <!-- END: deluser -->
                        <!-- END: tools -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <!-- BEGIN: viewmore -->
    <a class="btn btn-sm btn-primary" href="{DATA.link_types.members}">{GLANG.view_more}</a>
    <!-- END: viewmore -->
    <!-- BEGIN: page -->
    <div class="text-center">{PAGE}</div>
    <!-- END: page -->
</div>
<!-- END: members -->
<!-- END: users -->
<!-- END: main -->
