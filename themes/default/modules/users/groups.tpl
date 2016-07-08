<!-- BEGIN: main -->
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
        <caption>{LANG.group_manage}</caption>
        <col span="4"/>
        <thead>
            <tr class="text-center">
                <th> {LANG.title} </th>
                <th> {LANG.add_time} </th>
                <th> {LANG.exp_time} </th>
                <th> {LANG.users} </th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr class="text-center">
                <td class="text-left"><a title="{LANG.users}" href="{LOOP.link_userlist}">{LOOP.title}</a></td>
                <td>{LOOP.add_time}</td>
                <td>{LOOP.exp_time}</td>
                <td>{LOOP.number}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: list -->

<!-- BEGIN: listUsers -->
<!-- BEGIN: pending -->
<div id="id_pending">
	<h3 class="myh3">{PTITLE}</h3>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="50"/>
			<col span="2" />
			<col width="250" />
			<thead>
				<tr>
					<th class="text-center"> {LANG.STT} </th>
					<th> {LANG.account} </th>
					<th> {LANG.nametitle} </th>
					<th class="text-center"> {GLANG.actions} </th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"> {LOOP.stt} </td>
					<td>{LOOP.username}</td>
					<td>{LOOP.full_name}</td>
					<td class="text-center">
					<!-- BEGIN: tools -->
	                <i class="fa fa-check"></i> <a class="approved" href="javascript:void(0);" data-id="{LOOP.userid}">{LANG.approved}</a>
	                <i class="fa fa-times"></i> <a class="denied" href="javascript:void(0);" data-id="{LOOP.userid}">{LANG.denied}</a>
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
$("a.approved").click(function() {
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
$("a.denied").click(function() {
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
	<h3 class="myh3">{PTITLE}</h3>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="50"/>
			<col span="2" />
			<col width="250" />
			<thead>
				<tr>
					<th class="text-center"> {LANG.STT} </th>
					<th> {LANG.account} </th>
					<th> {LANG.nametitle} </th>
					<th class="text-center"> {GLANG.actions} </th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"> {LOOP.stt} </td>
					<td>{LOOP.username}</td>
					<td>{LOOP.full_name}</td>
					<td class="text-center">
					<!-- BEGIN: tools -->
	                <i class="fa fa-star-half-o"></i> <a class="demote" href="javascript:void(0);" data-id="{LOOP.userid}">{LANG.demote}</a> -
					<em class="fa fa-trash-o">&nbsp;</em> <a class="deleteleader" href="javascript:void(0);" title="{LOOP.userid}">{LANG.exclude_user2}</a>
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
$("a.deleteleader").click(function() {
	confirm("{LANG.delConfirm} ?") && $.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&exclude=" + $(this).attr("title"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
$("a.demote").click(function() {
	$.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&demote=" + $(this).data("id"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
//]]>
</script>
<!-- END: leaders -->

<!-- BEGIN: members -->
<div id="id_members">
	<h3 class="myh3">{PTITLE}</h3>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="50"/>
			<col span="2" />
			<col width="300"/>
			<thead>
				<tr>
					<th class="text-center"> {LANG.STT} </th>
					<th> {LANG.account} </th>
					<th> {LANG.nametitle} </th>
					<th class="text-center"> {GLANG.actions} </th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"> {LOOP.stt} </td>
					<td>{LOOP.username}</td>
					<td>{LOOP.full_name}</td>
					<td class="text-center">
					<!-- BEGIN: tools -->
	                <!--<i class="fa fa-star">&nbsp;</i> <a class="promote" href="javascript:void(0);" data-id="{LOOP.userid}">{LANG.promote}</a> - -->
	                
		                <!-- BEGIN: deletemember -->
							<i class="fa fa-ban">&nbsp;</i><a class="deletemember" href="javascript:void(0);" data-userid="{LOOP.userid}">{LANG.exclude_user2}</a>&nbsp; 
						<!-- END: deletemember -->
						<!-- BEGIN: edituser -->
							<i class="fa fa-pencil-square-o">&nbsp;</i><a href="{LINK_EDIT}" class="edituser">{GLANG.edit}</a>&nbsp; 
						<!-- END: edituser -->
						<!-- BEGIN: deluser -->
							<i class="fa fa-trash-o">&nbsp;</i><a class="deluser" href="javascript:void(0);" data-userid="{LOOP.userid}">{GLANG.delete}</a>
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
$("a.deluser").click(function() {
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
$("a.deletemember").click(function() {
	confirm("{LANG.delConfirm} ?") && $.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&exclude=" + $(this).attr("data-userid"),
		success : function(a) {
			a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a);
		}
	});
	return !1;
});
$("a.promote").click(function() {
	$.ajax({
		type : "POST",
		url : "{MODULE_URL}={OP}",
		data : "gid={GID}&promote=" + $(this).data("id"),
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
<!-- BEGIN: tools -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<div id="ablist" class="m-bottom well">
	<!-- BEGIN: addUserGroup -->
	<select name="uid" id="uid" class="form-control" style="width: 250px">

	</select>
	<input class="btn btn-primary" name="addUser" type="button" value="{LANG.addMemberToGroup}" />
	<!-- END: addUserGroup -->
	<!-- BEGIN: add_user -->
	<a href="{MODULE_URL}=register/{GID}">
		<button class="btn btn-primary" name="add_user" type="button"> <i class="fa fa-user-plus"></i>{LANG.addusers}</button>
	</a>
	<!-- END: add_user -->
	<!-- BEGIN: user_waiting -->
		<button class="btn btn-primary" name="user_waiting" type="button">{LANG.user_waiting}</button>
	<!-- END: user_waiting -->
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#uid").select2({
            placeholder: "{LANG.search_id}",
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

        var markup = '<div class="clearfix">' +
        '<div class="col-sm-19">' + repo.username + '</div>' +
        '<div clas="col-sm-5"><span class="show text-right">' + repo.fullname + '</span></div>' +
        '</div>';
        markup += '</div></div>';
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.username || repo.text;
    }
</script>
<!-- END: tools -->
<div id="pageContent">&nbsp;</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10));
	});
	$("input[name=addUser]").click(function() {
		var a = $("#ablist select[name=uid]").val(), a = intval(a);
		a == 0 && ( a = "");
		$("#ablist select[name=uid]").val(a);
		if (a == "") {
			return alert("{LANG.choiceUserID}"), $("#ablist select[name=uid]").select(), false;
		}
		$("#pageContent input, #pageContent select").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "gid={GID}&uid=" + a + "&rand=" + nv_randomPassword(10),
			success : function(a) {
				a == "OK" ? ($("#ablist select[name=uid]").select2('val', ''), $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10))) : alert(a);
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