<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div class="push" id="push" data-page-url="{PAGE_URL}" data-delete-confirm="{LANG.delete_confirm}">
    <div class="filter">
        <!-- BEGIN: filter -->
        <div class="select">
            <label>{LANG.filter_by_criteria}</label>
            <select class="form-control" name="filter">
                <option value="">{LANG.filter_all}</option>
                <!-- BEGIN: loop -->
                <option value="{FILTER.key}" {FILTER.sel}>{FILTER.title}</option>
                <!-- END: loop -->
            </select>
        </div>
        <!-- END: filter -->
        <div class="add-push">
            <button type="button" class="btn btn-primary" data-toggle="push_action" data-type="add" data-title="{LANG.push_add}">{LANG.add_push}</button>
        </div>
    </div>
    <!-- BEGIN: items -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center" style="width: 1%;"><i class="fa fa-question-circle-o" title="{LANG.status}"></i></th>
                    <th class="text-center" style="width: 15%;">{LANG.sender}</th>
                    <th class="text-center" style="width: 20%;">{LANG.receiver}</th>
                    <th class="text-center">{LANG.content}</th>
                    <th class="text-center text-nowrap" style="width: 1%;">{LANG.add_time}</th>
                    <th class="text-center text-nowrap" style="width: 1%;">{LANG.exp_time}</th>
                    <th class="text-center text-nowrap" style="width: 1%;">{LANG.views}</th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr class="item status-{ITEM.status}" data-id="{ITEM.id}">
                    <td class="text-center" style="width: 1%;vertical-align:middle">
                        <!-- BEGIN: waiting --><i class="fa fa-hourglass-half" title="{LANG.waiting}"></i><!-- END: waiting -->
                        <!-- BEGIN: active --><i class="fa fa-cog fa-spin" title="{LANG.active}"></i><!-- END: active -->
                        <!-- BEGIN: expired --><i class="fa fa-ban" title="{LANG.expired}"></i><!-- END: expired -->
                    </td>
                    <td style="width: 15%;vertical-align:middle">
                        <!-- BEGIN: from_system -->{LANG.from_system}<!-- END: from_system -->
                        <!-- BEGIN: from_group -->{LANG.from_group}<br/>{LANG.id} #{ITEM.sender_group}: <a href="{ITEM.sender_group_link}">{ITEM.sender_group_name}</a><!-- END: from_group -->
                        <!-- BEGIN: from_admin -->{LANG.from_admin}<br/>{LANG.id} #{ITEM.sender_admin}: <a href="{ITEM.sender_admin_link}">{ITEM.sender_admin_name}</a><!-- END: from_admin -->
                    </td>
                    <td style="width: 20%;vertical-align:middle">
                        {ITEM.receiver_title}<!-- BEGIN: to_group -->: <!-- BEGIN: group --><a href="{GROUP.link}">{GROUP.name}</a><!-- BEGIN: comma -->, <!-- END: comma --><!-- END: group --><!-- END: to_group --><!-- BEGIN: to_user -->: <!-- BEGIN: user --><a href="#" data-toggle="viewUser" data-id="{LANG.id}: {USER.0}" data-username="{LANG.username}: {USER.1}" data-fullname="{LANG.fullname}: {USER.2}">{USER.2}</a><!-- BEGIN: comma -->, <!-- END: comma --><!-- END: user --><!-- END: to_user -->
                    </td>
                    <td style="vertical-align:middle">
                        {ITEM.message.0}<!-- BEGIN: message_1 --><span class="more">... <u data-toggle="more">{LANG.view_more}</u></span><span class="morecontent" style="display: none">{ITEM.message.1}</span><!-- END: message_1 -->
                        <!-- BEGIN: link --><div class="push-link"><a href="{ITEM.link}" target="_blank">{LANG.push_link}</a></div><!-- END: link -->
                    </td>
                    <td class="text-center" style="width: 1%;vertical-align:middle">
                        {ITEM.add_time_format}
                    </td>
                    <td class="text-center" style="width: 1%;vertical-align:middle">
                        {ITEM.exp_time_format}
                    </td>
                    <td class="text-center" style="width: 1%;vertical-align:middle">
                        {ITEM.views}
                    </td>
                    <td class="text-center text-nowrap" style="width: 1%;vertical-align:middle">
                        <button class="btn btn-default btn-sm" data-toggle="push_action" data-type="edit" data-title="{LANG.push_edit}">{GLANG.edit}</button>
                        <button class="btn btn-default btn-sm" data-toggle="push_del">{GLANG.delete}</button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
    <!-- END: items -->
    <!-- BEGIN: is_empty -->
    <div class="is_empty">
        <p><i class="fa fa-bell-slash-o fa-2x"></i></p>
        {LANG.no_notifications}
    </div>
    <!-- END: is_empty -->
</div>
<div class="push-action" id="push-action" style="display:none">
    <div class="row">
        <div class="col-md-16">
            <div class="panel panel-primary">
                <div class="panel-heading"></div>
                <div class="panel-body"></div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->