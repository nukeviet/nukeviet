<!-- BEGIN: main -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.group_manage}</h2>
<div id="pageContent"></div>
<ul class="nav navbar-nav">
    <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
</ul>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $("div#pageContent").load("{MODULE_URL}={OP}&list&random=" + nv_randomPassword(10));
    });
    //]]>
</script>
<!-- END: main -->

<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <col span="4"/>
        <thead>
            <tr>
                <th> {LANG.title} </th>
                <th class="text-center"> {LANG.add_time} </th>
                <th class="text-center"> {LANG.exp_time} </th>
                <th class="text-center"> {LANG.users} </th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td><a title="{LANG.users}" href="{LOOP.link_userlist}">{LOOP.title}</a></td>
                <td class="text-center">{LOOP.add_time}</td>
                <td class="text-center">{LOOP.exp_time}</td>
                <td class="text-center">{LOOP.number}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: list -->

<!-- BEGIN: listUsers -->
<!-- BEGIN: pending -->
<div id="id_pending">
	<h3 class="m-bottom">{PTITLE}</h3>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="50"/>
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
					<td class="text-center">{LOOP.stt}</td>
					<td>{LOOP.username} ({LOOP.full_name})</td>
					<td class="text-right">
    					<!-- BEGIN: tools -->
    	                <button class="approved btn btn-success btn-sm" title="{LANG.approved}" data-id="{LOOP.userid}"><i class="fa fa-check"></i></button>
    	                <button class="denied btn btn-warning btn-sm" title="{LANG.denied}" data-id="{LOOP.userid}"><i class="fa fa-minus-circle"></i></button>
    					<!-- END: tools -->
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<!-- BEGIN: page -->
	<div class="text-center">{PAGE}</div>
	<!-- END: page -->
</div>
<script type="text/javascript">
//<![CDATA[
$(".approved").click(function() {
	confirm(nv_is_add_user_confirm[0]) && $.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&approved=" + $(this).data("id"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
$(".denied").click(function() {
	confirm(nv_is_exclude_user_confirm[0]) && $.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&denied=" + $(this).data("id"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
//]]>
</script>
<!-- END: pending -->

<!-- BEGIN: leaders -->
<div id="id_leaders">
	<h3 class="m-bottom">{PTITLE}</h3>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="50"/>
			<thead>
				<tr>
					<th class="text-center">{LANG.STT}</th>
					<th>{LANG.account} ({LANG.nametitle})</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"> {LOOP.stt} </td>
					<td>{LOOP.username} ({LOOP.full_name})</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<!-- BEGIN: page -->
	<div class="text-center">{PAGE}</div>
	<!-- END: page -->
</div>
<!-- END: leaders -->

<!-- BEGIN: members -->
<div id="id_members">
	<h3 class="m-bottom">{PTITLE}</h3>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="50"/>
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
					<td class="text-center">{LOOP.stt}</td>
					<td>{LOOP.username} ({LOOP.full_name})</td>
					<td class="text-right">
					<!-- BEGIN: tools -->
	                    <!-- BEGIN: edituser -->
							<a href="{LINK_EDIT}" class="edituser btn btn-primary btn-sm" title="{GLANG.edit}"><i class="fa fa-pencil-square-o"></i></a>
						<!-- END: edituser -->
						<!-- BEGIN: deletemember -->
							<button class="deletemember btn btn-warning btn-sm" title="{LANG.exclude_user2}" data-userid="{LOOP.userid}"><i class="fa fa-minus-circle"></i></button>
						<!-- END: deletemember -->
						<!-- BEGIN: deluser -->
							<button class="deluser btn btn-danger btn-sm" title="{GLANG.delete}" data-userid="{LOOP.userid}"><i class="fa fa-trash-o"></i></button>
						<!-- END: deluser -->
					<!-- END: tools -->
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<!-- BEGIN: page -->
	<div class="text-center">{PAGE}</div>
	<!-- END: page -->
</div>
<script type="text/javascript">
//<![CDATA[
$(".deluser").click(function() {
	confirm("{LANG.delConfirm} ?") && $.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&del=" + $(this).attr("data-userid"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
$(".deletemember").click(function() {
	confirm("{LANG.excludeUserConfirm} ?") && $.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&exclude=" + $(this).attr("data-userid"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
//]]>
</script>
<!-- END: members -->
<!-- END: listUsers -->

<!-- BEGIN: userlist -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.group_manage}</h2>
<!-- BEGIN: tools -->
<link rel="stylesheet" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<div id="ablist" class="container-fluid margin-bottom">
    <div class="row">
        <div class="col-sm-14  margin-bottom-lg">
            <!-- BEGIN: addUserGroup -->
        	<select name="uid" id="uid" class="form-control" style="width:150px"></select>
        	<button class="btn btn-primary" name="addUser" type="button" title="{LANG.addMemberToGroup}"><i class="fa fa-plus"></i></button>
        	<!-- END: addUserGroup -->
        </div>
        <div class="col-sm-10 text-right  margin-bottom-lg">
            <a href="{EDIT_GROUP_URL}" class="btn btn-primary" title="{GLANG.edit}"><i class="fa fa-pencil-square-o"></i></a>
            <!-- BEGIN: add_user -->
        	<a href="{MODULE_URL}=register/{GID}" class="btn btn-primary" title="{LANG.addusers}"><i class="fa fa-user-plus"></i></a>
        	<!-- END: add_user -->
        	<!-- BEGIN: user_waiting -->
       		<button class="btn btn-primary" name="user_waiting" type="button" title="{LANG.user_waiting}"><i class="fa fa-search-plus"></i></button>
        	<!-- END: user_waiting -->
        </div>
    </div>
</div>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#uid").select2({
            placeholder: "{LANG.addMemberToGroup}",
            ajax: {
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=groups&get_user_json=1&gid={GID}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
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
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 3,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection, // omitted for brevity, see the source of this page
           	language: {
		    inputTooShort: function(args) {
		      // args.minimum is the minimum required length
		      // args.input is the user-typed text
		      return "{MIN_SEARCH}";
		    }
		   }
        });
    });

    function formatRepo (repo) {
        if (repo.loading) return repo.text;
        return '<div>' + repo.username + '<br/>(' + repo.fullname + ')</div>';
    }

    function formatRepoSelection (repo) {
        return repo.username || repo.text;
    }
</script>
<!-- END: tools -->
<table class="table table-bordered">
    <tr>
        <td rowspan="4" style="width:80px;border-top:0"><img title="{DATA.title}" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" width="80" height="80" style="background-image:url({DATA.group_avatar});background-repeat:no-repeat;background-size:cover;" /></td>
        <td class="text-nowrap" style="width:80px;border-top:0"><strong>{LANG.group_title}</strong></td>
        <td style="border-top:0">{DATA.title}<!-- BEGIN: group_desc --> ({DATA.description})<!-- END: group_desc --></td>
    </tr>
    <tr class="active">
        <td class="text-nowrap"><strong>{LANG.group_type}</strong></td>
        <td>{DATA.group_type_mess}<!-- BEGIN: group_type_note --> ({DATA.group_type_note})<!-- END: group_type_note --></td>
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
<div id="pageContent">&nbsp;</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10));
	});
	$("[name=addUser]").click(function() {
		var a = $("#ablist select[name=uid]").val(), a = intval(a);
		a == 0 && ( a = "");
		$("#ablist select[name=uid]").val(a);
		if (a == "") {
			return alert("{LANG.choiceUserID}"), $("#ablist select[name=uid]").select2('open'), false;
		}
		$("#pageContent input, #pageContent button, #pageContent select").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "gid={GID}&uid=" + a + "&rand=" + nv_randomPassword(10),
			success : function(a) {
				a == "OK" ? ($("#ablist select[name=uid]").val("").trigger("change"), $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10))) : alert(a);
			}
		});
		return !1;
	});
	$("button[name=user_waiting]").click(function() {
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "gid={GID}&getuserid=1&rand=" + nv_randomPassword(10),
			success : function(a) {
				modalShow('{LANG.user_waiting}', a);
			}
		});
	});
	//]]>
</script>
<!-- END: userlist -->

<!-- BEGIN: editgroup -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.group_edit}</h2>
<form action="{EDIT_GROUP_URL}" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
    <div class="nv-info" data-default="" style="display:none"></div>
    <div class="form-detail well-lg">
        <div class="form-group">
            <label for="group_title" class="control-label col-sm-7 col-md-6 text-normal">{LANG.group_title}</label>
            <div class="col-sm-17 col-md-18">
                <input type="text" class="form-control required" placeholder="{LANG.group_title}" value="{DATA.title}" name="group_title" id="group_title" maxlength="240" onkeypress="validErrorHidden(this);" data-mess="">
            </div>
        </div>
        
        <div class="form-group">
            <label for="group_desc" class="control-label col-sm-7 col-md-6 text-normal">{LANG.group_desc}</label>
            <div class="col-sm-17 col-md-18">
                <input type="text" class="form-control" placeholder="{LANG.group_desc}" value="{DATA.description}" name="group_desc" id="group_desc" maxlength="240">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-24 text-normal">{LANG.group_content}</label>
            <div class="col-sm-24">{HTMLBODYTEXT}</div>
        </div>

        <div class="text-center">
    		<input type="hidden" name="save" value="1" />
    		<input type="submit" class="btn btn-primary" value="{GLANG.save}"/>
    	</div>
    </div>
</form>
<!-- END: editgroup -->