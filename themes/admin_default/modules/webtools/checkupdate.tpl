<!-- BEGIN: main -->
<div id="updIf">
	<div id="sysUpd" class="hide"></div>
	<div id="extUpd" class="hide"></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#sysUpd').html(nv_loading).removeClass('hide').load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=sysUpd&num=" + nv_randomPassword(10), function(){
		$("#extUpd").html(nv_loading).removeClass('hide').load("index.php?{NV_NAME_VARIABLE}=webtools&{NV_OP_VARIABLE}=checkupdate&i=extUpd&num=" + nv_randomPassword(10), function(){
			start_tooltip();
		});
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
			<td>
				{VALUE.onlineVersion}
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
<!-- END: sysUpd -->
<!-- BEGIN: extUpd -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
	<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.checkExtensions} </caption>
	<col class="top w150" />
	<col class="top" />
	<col class="top" />
	<thead>
		<tr>
			<th> {LANG.extName} </th>
			<th> {LANG.extType} </th>
			<th> {LANG.extInfo} </th>
			<th class="text-right"> {LANG.extNote} </th>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td style="vertical-align:top">
				{EXTNAME}
			</td>
			<td style="vertical-align:top">
				{EXTTYPE}
			</td>
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
					<div class="alert alert-danger">
						{LANG.extNote1_detail}
					</div>
					<!-- END: note1 -->
					<!-- BEGIN: note2 -->
					<div class="alert alert-warning">
						{LANG.extNote1_detail}
					</div>
					<!-- END: note2 -->
					<!-- BEGIN: updateNotSuport -->
					<div class="alert alert-warning">
						{UPDNOTE}
					</div>
					<!-- END: updateNotSuport -->
					<!-- BEGIN: updateNotLastest -->
					<div class="alert alert-success">
						{UPDNOTE}
					</div>
					<!-- END: updateNotLastest -->
					<!-- BEGIN: updateLastest -->
					<div class="alert alert-success">
						{UPDNOTE}
					</div>
					<!-- END: updateLastest -->
				</div>
			</td>
			<td style="vertical-align:top;" class="text-right">
				<em class="fa fa-lg {EXTICON}" data-toggle="tooltip" data-placement="top" title="{EXTNOTE}">&nbsp;</em>
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
</div>
<div class="text-right">
	{LANG.checkDate}: {EXTUPDDATE} (<a id="extUpdRefresh" href="#">{LANG.reCheck}</a>)
</div>
<div>
	<a class="btn btn-primary" href="{LINKNEWEXT}">{LANG.extNew}</a>
</div>
<!-- END: extUpd -->
<!-- BEGIN: error -->
<div class="alert alert-danger text-center">{ERROR}</div>
<!-- BEGIN: error -->