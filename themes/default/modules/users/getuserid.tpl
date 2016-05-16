<!-- BEGIN: main -->
<div id="getuidcontent">
	<form class="form-group" id="formgetuid" method="get" action="{FORM_ACTION}">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
					<td>{LANG.user_id}</td>
					<td><input class="form-control fixwidthinput" type="text" name="user_id" value="" maxlength="100" /></td>
					<td>{LANG.username}</td>
					<td><input class="form-control fixwidthinput" type="text" name="username" value="" maxlength="100" /></td>
				</tr>
				<tr>
					<td>{LANG.fullname}</td>
					<td><input class="form-control fixwidthinput" type="text" name="full_name" value="" maxlength="100" /></td>
					<td>{LANG.email}</td>
					<td><input class="form-control fixwidthinput" type="text" name="email" value="" maxlength="100" /></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="text-center">
						<input type="reset" class="btn btn-info" value="{LANG.reset}" /> 
						<input class="btn btn-primary" type="submit" name="submit" value="{LANG.search}" />
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<script type="text/javascript">
	$("#formgetuid").submit(function() {
		var a = $(this).attr("action");
		b = $(this).serialize();
		a = a + "&" + b + "&submit";
		$("#formgetuid input, #formgetuid select").attr("disabled", "disabled");
		$.ajax({
			type : "GET",
			url : a,
			success : function(c) {
				$("#resultdata").html(c);
				$("#formgetuid input, #formgetuid select").removeAttr("disabled");
			}
		});
		return !1;
	});
</script>
<div id="resultdata">&nbsp;</div>
<!--  END: main  -->

<!-- BEGIN: resultdata -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50">
			<col class="w100">
			<col>
			<col class="w150" />
			<col class="w50" />
		</colgroup>
		<thead>
			<tr>
				<th>ID</th>
				<th>{LANG.username}</th>
				<th>{LANG.email}</th>
				<th>{LANG.regdate}</th>
				<td class="text-center"></th>
			</tr>
		</thead>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="5" style="text-align: center">
					<div class="fr generatePage">{GENERATE_PAGE}</div>
				</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><strong>{ROW.userid}</strong></td>
				<td>{ROW.username}</td>
				<td>{ROW.email}</td>
				<td>{ROW.regdate}</td>
				<td class="text-center">
					<a title="" onclick="nv_active('{ROW.userid}');" href="javascript:void(0);">
						<em class="fa fa-edit fa-lg">&nbsp;</em> {LANG.active}
					</a>
				</td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	function nv_active(userid) {
		$.ajax({
			type : "POST",
			url : "{FORM_ACTION}",
			data : "act=1&userid=" + userid,
			success : function(a) {
				if(a=="OK"){
					alert('{LANG.actived_users}');
					$('#sitemodal').modal('hide');
					window.location.href = window.location.href;
				}
				else{
					alert('{LANG.not_active}');
				}
				
			}
		});
	}	
	//]]>
</script>
<!-- END: data -->
<!-- BEGIN: nodata -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center">{LANG.noresult}</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: nodata -->
<!-- END: resultdata -->