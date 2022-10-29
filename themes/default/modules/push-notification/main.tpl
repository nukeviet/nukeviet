<!-- BEGIN: main -->
<div id="push" class="panel panel-default push" data-page-url="{PAGE_URL}">
    <div class="panel-heading filter-select">
        <label>{LANG.filter_by_criteria}</label>
        <select class="form-control" name="filter">
            <option value="">{LANG.filter_all}</option>
            <!-- BEGIN: filter -->
            <option value="{FILTER.key}">{FILTER.title}</option>
            <!-- END: filter -->
        </select>
    </div>
    <div class="load_content" id="generate_page"></div>
</div>
<!-- END: main -->

<!-- BEGIN: user_get_list -->
<!-- BEGIN: main_cont -->
<ul class="list-unstyled items" data-url="{PAGE_URL}">
    <!-- BEGIN: loop -->
    <li class="item hidden-{LOOP.is_hidden} shown-{LOOP.shown_time} viewed-{LOOP.is_viewed} favorite-{LOOP.is_favorite}" data-id="{LOOP.id}">
        <span class="avatar">
            <!-- BEGIN: sender_system --><em class="fa fa-windows"></em><!-- END: sender_system -->
            <!-- BEGIN: sender_group --><em class="fa fa-users"></em><!-- END: sender_group -->
            <!-- BEGIN: sender_admin --><em class="fa fa-star"></em><!-- END: sender_admin -->
        </span>
        <div class="title">{LOOP.title}</div>
        <div class="message">
            {LOOP.message.0}
            <!-- BEGIN: message_1 --><span class="more">... <u data-toggle="more">{LANG.view_more}</u></span><span class="morecontent" style="display: none">{LOOP.message.1}</span><!-- END: message_1 -->
            <!-- BEGIN: is_link -->
            <div class="details"><a href="{LOOP.link}"><i class="fa fa-caret-right"></i> {LANG.details}</a></div><!-- END: is_link -->
        </div>
        <div class="foot">
            <span>{LOOP.add_time}</span>
            <div>
                <!-- BEGIN: set_viewed --><button type="button" class="btn btn-viewed" data-toggle="pushNotifySetStatus" data-status="viewed" title="{LANG.mark_as_viewed}"><i class="fa fa-bell-o"></i></button><!-- END: set_viewed -->
                <!-- BEGIN: set_unviewed --><button type="button" class="btn btn-viewed" data-toggle="pushNotifySetStatus" data-status="unviewed" title="{LANG.mark_as_unviewed}"><i class="fa fa-bell-o"></i></button><!-- END: set_unviewed -->
                <!-- BEGIN: set_favorite --><button type="button" class="btn btn-favorite" data-toggle="pushNotifySetStatus" data-status="favorite" title="{LANG.mark_as_favorite}"><i class="fa fa-heart-o"></i></button><!-- END: set_favorite -->
                <!-- BEGIN: set_unfavorite --><button type="button" class="btn btn-favorite" data-toggle="pushNotifySetStatus" data-status="unfavorite" title="{LANG.mark_as_unfavorite}"><i class="fa fa-heart-o"></i></button><!-- END: set_unfavorite -->
                <!-- BEGIN: set_hidden --><button type="button" class="btn" data-toggle="pushNotifySetStatus" data-status="hidden" title="{LANG.hidden}"><i class="fa fa-trash-o"></i></button><!-- END: set_hidden -->
                <!-- BEGIN: set_unhidden --><button type="button" class="btn" data-toggle="pushNotifySetStatus" data-status="unhidden" title="{LANG.show}"><i class="fa fa-eye"></i></button><!-- END: set_unhidden -->
            </div>
        </div>
    </li><!-- END: loop -->
</ul>
<!-- END: main_cont -->
<!-- BEGIN: main_empty -->
<div class="notify-empty">
    <p><i class="fa fa-bell-slash-o fa-2x"></i></p>{LANG.no_notifications}
</div>
<!-- END: main_empty -->
<!-- BEGIN: generate_page -->
<div class="panel-footer text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: user_get_list -->

<!-- BEGIN: notifications_manager -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/push-manager.css" rel="stylesheet" />
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" />
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/push-manager.js"></script>
<div id="notifications_manager" class="notifications_manager" data-url="{MANAGER_PAGE_URL}" data-csrf="{CHECKSS}">
    <div class="manager-heading">
        <div>
            <select class="form-control change-status">
                <!-- BEGIN: filter -->
                <option value="{FILTER.key}" {FILTER.sel}>{FILTER.name}</option>
                <!-- END: filter -->
            </select>
        </div>
        <button type="button" class="btn btn-primary margin-bottom" data-toggle="push_action" data-type="add" data-title="{LANG.push_add}">{LANG.push_add}</button>
    </div>
    <div id="generate_page">{PAGE_CONTENT}</div>
    <div id="notification-action" class="notification-action" style="display:none">
        <div class="row">
            <div class="col-md-18">
                <div class="panel panel-primary">
                    <div class="panel-heading"></div>
                    <div class="panel-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: notifications_manager -->

<!-- BEGIN: notifications_list -->
<div id="notification" class="table-responsive notification" data-delete-confirm="{LANG.delete_confirm}">
    <table class="table table-bordered">
        <thead class="bg-primary">
            <tr>
                <th class="text-center" style="width: 1%;"><i class="fa fa-question-circle-o" title="{LANG.status}"></i></th>
                <th class="text-center" style="width: 25%;">{LANG.receiver}</th>
                <th class="text-center">{LANG.content}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.add_time}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.exp_time}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.views}</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr class="notification-item status-{ITEM.status}" data-id="{ITEM.id}">
                <td class="text-center" style="width: 1%;">
                    <!-- BEGIN: waiting --><i class="fa fa-hourglass-half" title="{LANG.waiting}"></i><!-- END: waiting -->
                    <!-- BEGIN: active --><i class="fa fa-cog fa-spin" title="{LANG.active}"></i><!-- END: active -->
                    <!-- BEGIN: expired --><i class="fa fa-ban" title="{LANG.expired}"></i><!-- END: expired -->
                </td>
                <td style="width: 25%;">
                    <!-- BEGIN: to_all -->{LANG.to_group_all}
                    <!-- END: to_all -->
                    <!-- BEGIN: to_member --><button type="button" class="btn btn-xs btn-default member-info" tabindex="0" data-toggle="viewUser" data-id="{LANG.id}: {MEMBER.0}" data-username="{LANG.username}: {MEMBER.1}" data-fullname="{LANG.fullname}: {MEMBER.2}">{MEMBER.2}</button><!-- END: to_member -->
                </td>
                <td>
                    {ITEM.message.0}
                    <!-- BEGIN: message_1 --><span class="more">... <u data-toggle="more">{LANG.view_more}</u></span><span class="morecontent" style="display: none">{ITEM.message.1}</span><!-- END: message_1 -->
                    <!-- BEGIN: link -->
                    <div class="push-link"><a href="{ITEM.link}" target="_blank">{LANG.push_link}</a></div>
                    <!-- END: link -->
                </td>
                <td class="text-center" style="width: 1%;">
                    {ITEM.add_time_format}
                </td>
                <td class="text-center" style="width: 1%;">
                    {ITEM.exp_time_format}
                </td>
                <td class="text-center" style="width: 1%;">
                    {ITEM.views}
                </td>
                <td class="text-center" style="width: 1%;">
                    <button class="btn btn-default btn-sm margin-bottom-sm" data-toggle="push_action" data-type="edit" data-title="{LANG.push_edit}" title="{LANG.push_edit}"><i class="fa fa-pencil-square-o fa-fw"></i></button>
                    <button class="btn btn-default btn-sm" data-toggle="push_del" title="{GLANG.delete}"><i class="fa fa-trash-o fa-fw"></i></button>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
</div>
<script>
    $(function() {
        $('#notification [data-toggle=viewUser]').each(function(e) {
            var content = $(this).data('id') + '<br/>' + $(this).data('username') + '<br/>' + $(this).data('fullname');
            $(this).popover({
                'trigger': 'focus',
                'placement': 'top',
                'html': true,
                'content': content
            })
        })
    })
</script>
<!-- END: notifications_list -->

<!-- BEGIN: notification_action -->
<form method="post" action="{MANAGER_PAGE_URL}" class="form-horizontal" id="notification_action_form">
    <input type="hidden" name="action" value="{DATA.id}">
    <input type="hidden" name="_csrf" value="{CHECKSS}">
    <div class="form-group">
        <label class="col-md-8 control-label">{LANG.receiver}</label>
        <div class="col-md-16">
            <select name="receiver_ids[]" class="receiver_ids" multiple="multiple" style="width: 100%;" data-placeholder="{LANG.to_group_all}">
                <!-- BEGIN: receiver_ids -->
                <option value="{MEMBER.id}" selected="selected">{MEMBER.fullname}</option>
                <!-- END: receiver_ids -->
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{LANG.content}</label>
        <div class="col-md-16">
            <textarea class="form-control message" name="message" maxlength="2000">{DATA.message}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{LANG.push_link}</label>
        <div class="col-md-16">
            <input type="text" name="link" value="{DATA.link}" class="form-control" maxlength="500">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{LANG.add_time}</label>
        <div class="col-md-16 add_time">
            <input type="text" name="add_time" value="{DATA.add_time}" class="form-control" maxlength="10">
            <select name="add_hour" class="form-control">
                <!-- BEGIN: add_hour -->
                <option value="{ADD_HOUR.val}" {ADD_HOUR.sel}>{ADD_HOUR.name}</option>
                <!-- END: add_hour -->
            </select>
            <select name="add_min" class="form-control">
                <!-- BEGIN: add_min -->
                <option value="{ADD_MIN.val}" {ADD_MIN.sel}>{ADD_MIN.name}</option>
                <!-- END: add_min -->
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{LANG.exp_time}</label>
        <div class="col-md-16 exp_time">
            <select class="form-control margin-bottom sample_exp_time">
                <option value="0"></option>
                <option value="1">{LANG.after_1_day}</option>
                <option value="2">{LANG.after_2_days}</option>
                <option value="7">{LANG.after_7_days}</option>
                <option value="10">{LANG.after_10_days}</option>
                <option value="15">{LANG.after_15_days}</option>
                <option value="30">{LANG.after_30_days}</option>
            </select><br />
            <input type="text" name="exp_time" value="{DATA.exp_time}" class="form-control" maxlength="10">
            <select name="exp_hour" class="form-control">
                <option value="-1"></option>
                <!-- BEGIN: exp_hour -->
                <option value="{EXP_HOUR.val}" {EXP_HOUR.sel}>{EXP_HOUR.name}</option>
                <!-- END: exp_hour -->
            </select>
            <select name="exp_min" class="form-control">
                <option value="-1"></option>
                <!-- BEGIN: exp_min -->
                <option value="{EXP_MIN.val}" {EXP_MIN.sel}>{EXP_MIN.name}</option>
                <!-- END: exp_min -->
            </select>
            <div>{LANG.empty_is_unlimited}</div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
        <button type="button" class="btn btn-default" data-toggle="notification_action_cancel">{GLANG.cancel}</button>
    </div>
</form>
<script>
    $(function() {
        var actionFormObj = $('#notification_action_form'),
            ajax_url = actionFormObj.attr('action');
        $('.receiver_ids', actionFormObj).select2({
            ajax: {
                type: 'POST',
                cache: !1,
                url: ajax_url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        get_user_json: 1,
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 3,
            templateResult: function(repo) {
                if (repo.loading) return repo.text;
                return '<div>' + repo.fullname + '<br/>(ID #' + repo.id + ', ' + repo.username + ', ' + repo.email + ')</div>';
            },
            templateSelection: function(repo) {
                return repo.fullname || repo.text;
            }
        });

        $('[name=add_time], [name=exp_time]', actionFormObj).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true
        });

        $('.sample_exp_time', actionFormObj).on('change', function() {
            var val = parseInt($(this).val()),
                origTime = $('[name=add_time]', actionFormObj).val();
            if (val && origTime != '') {
                origTime = origTime.split('/');
                var date = new Date(origTime[2], origTime[1], origTime[0], 0, 0, 0);
                date.setDate(date.getDate() + val);
                exp_time = date.getDate().toString().padStart(2, '0') + '/' + date.getMonth().toString().padStart(2, '0') + '/' + date.getFullYear();
                $('[name=exp_time]', actionFormObj).val(exp_time);
                $('[name=exp_hour]', actionFormObj).val($('[name=add_hour]', actionFormObj).val());
                $('[name=exp_min]', actionFormObj).val($('[name=add_min]', actionFormObj).val());
            }
            $(this).val('0')
        })
    })
</script>
<!-- END: notification_action -->