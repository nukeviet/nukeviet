<!-- BEGIN: main -->
<div id="updIf">
	<div id="sysUpd" class="hide"></div>
	<div id="extUpd" class="hide"></div>
</div>
<script type="text/javascript">
var nv_loading = '<div class="text-center"><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>';
//<![CDATA[
$(document).ready(function(){
	$('#sysUpd').html(nv_loading).removeClass('hide').load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=sysUpd&num=" + nv_randomPassword(10), function(){
		$("#extUpd").html(nv_loading).removeClass('hide').load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=extUpd&num=" + nv_randomPassword(10))
	});
});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: sysUpd -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
	<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.checkSystem} </caption>
	<thead>
		<tr>
			<th> {LANG.checkContent} </th>
			<th> {LANG.checkValue} </th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="white-space:nowrap"> {LANG.userVersion} </td>
			<td> {VALUE.userVersion} </td>
		</tr>
		<tr>
			<td style="white-space:nowrap"> {LANG.onlineVersion} </td>
			<td> {VALUE.onlineVersion}
			<!-- BEGIN: inf -->
			<div class="newVesionMess">
				{VERSION_INFO}
			</div>
			<div class="newVesionInfo">
				{VERSION_LINK}
			</div>
			<!-- END: inf -->
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="text-right">
	{LANG.checkDate}: {SYSUPDDATE} (<a id="sysUpdRefresh" href="#">{LANG.reCheck}</a>)
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$("#sysUpdRefresh").click(function() {
		$("#sysUpd").html(nv_loading).load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=sysUpdRef&num=" + nv_randomPassword(10));
		return false
	})
});
//]]>
</script>
<!-- END: sysUpd -->
<!-- BEGIN: extUpd -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
	<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.checkExtensions} </caption>
	<col class="top w150" />
	<col class="top" />
	<col class="top w200" />
	<thead>
		<tr>
			<th> {LANG.extName} </th>
			<th> {LANG.extInfo} </th>
			<th class="text-right"> {LANG.extNote} </th>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td style="vertical-align:top">
			<div class="Note {EXTCL}">
				{EXTNAME}
			</div></td>
			<td style="vertical-align:top">
			<div class="ninfo">
				{EXTINFO}
			</div>
			<div class="wttooltip">
				<ul>
					<!-- BEGIN: li -->
					<li>
						<strong>{EXTTOOLTIP.title}</strong>: {EXTTOOLTIP.content}
					</li>
					<!-- END: li -->
				</ul>
				<!-- BEGIN: note1 -->
				<div class="invalid">
					{LANG.extNote1_detail}
				</div>
				<!-- END: note1 -->
			</div></td>
			<td style="vertical-align:top;text-align:right"> {EXTNOTE} </td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
</div>
<div class="text-right">
	{LANG.checkDate}: {EXTUPDDATE} (<a id="extUpdRefresh" href="#">{LANG.reCheck}</a>)
</div>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#extUpdRefresh").click(function() {
			$("#extUpd").html(nv_loading).load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=extUpdRef&num=" + nv_randomPassword(10));
			return false
		});
		$(".ninfo").click(function() {
			$(".ninfo").each(function() {
				$(this).show()
			});
			$(".wttooltip").each(function() {
				$(this).hide()
			});
			$(this).hide().next(".wttooltip").show();
			return false
		});
		$(".wttooltip").click(function() {
			$(this).hide().prev(".ninfo").show()
		})
	});
	//]]>
</script>
<div>
	<a class="btn btn-primary" href="{LINKNEWEXT}">{LANG.extNote2_link}</a>
</div>
<!-- END: extUpd -->
<!-- BEGIN: error -->
<div class="alert alert-danger text-center">{ERROR}</div>
<!-- BEGIN: error -->