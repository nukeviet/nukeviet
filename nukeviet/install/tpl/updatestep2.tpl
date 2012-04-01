<!-- BEGIN: main -->

<!-- BEGIN: step1 -->
<div class="infoalert" id="infodetectedupg">
	{LANG.update_info_dump}<br />
	<strong><a class="update_dump" href="{URL_DUMP_BACKUP}&amp;type=sql" title="{LANG.update_dump} sql">{LANG.update_dump} sql</a></strong> {LANG.update_or} 
	<strong><a class="update_dump" href="{URL_DUMP_BACKUP}&amp;type=gz" title="{LANG.update_dump} gz">{LANG.update_dump} gz</a></strong>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.update_dump').click(function(){
			$('#infodetectedupg').append('<div id="dpackagew"><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Waiting..."/></div>');
			$.get( $(this).attr('href') , function(e){
				$('#dpackagew').remove();
				$('#infodetectedupg').append('<br />' + e);
			});
			return !1;
		});
	});
	</script>
</div>
<div class="infoalert">{LANG.update_refuse_dump}</div>
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=1">{LANG.previous}</a></span>
	</li>
	<li><span class="next_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=2">{LANG.next_step}</a></span>
	</li>
</ul>
<!-- END: step1 -->

<!-- BEGIN: step2 -->
<!-- BEGIN: taskempty -->
<div class="infoalert">
	{LANG.update_task_empty}
</div>
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=1">{LANG.previous}</a></span>
	</li>
	<li><span class="next_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=3">{LANG.next_step}</a></span>
	</li>
</ul>
<!-- END: taskempty -->
<!-- BEGIN: manual -->
<div class="infoalert">
	{LANG.update_manual}.
</div>
{DATA.guide}
<br />
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=1">{LANG.previous}</a></span>
	</li>
	<li><span class="next_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=3">{LANG.next_step}</a></span>
	</li>
</ul>
<!-- END: manual -->
<!-- BEGIN: semiautomatic -->
<div class="infoalert">
	{LANG.update_semiautomatic}.
</div>
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=1">{LANG.previous}</a></span>
	</li>
	<li><span class="next_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=3">{LANG.next_step}</a></span>
	</li>
</ul>
<!-- END: semiautomatic -->
<!-- BEGIN: automatic -->
<div class="infoalert">
	{LANG.update_automatic}.
</div>
<table cellspacing="0" summary="summary" style="width: 100%;">
	<tr>
		<th scope="col" class="nobg boder center" style="width:50%">{LANG.update_data_work}</th>
		<th scope="col" class="nobg boder center">{LANG.update_file_work}</th>
	</tr>
	<tr>
		<td class="bleft nopadding">
			<!-- BEGIN: data -->
			<div class="all-work">
				<!-- BEGIN: loop -->
				<div class="workitem">{ROW.title}</div>
				<!-- END: loop -->
			</div>
			<!-- END: data -->
			<!-- BEGIN: nodata -->
			<div class="center">{LANG.update_empty_work}</div>
			<!-- END: nodata -->
		</td>
		<td class="nopadding">
			<!-- BEGIN: file -->
			<div class="all-work">
				<!-- BEGIN: loop -->
				<div class="workitem">{ROW}</div>
				<!-- END: loop -->
			</div>
			<!-- END: file -->
			<!-- BEGIN: nofile -->
			<div class="center">{LANG.update_empty_work}</div>
			<!-- END: nofile -->
		</td>
	</tr>
</table>
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=1">{LANG.previous}</a></span>
	</li>
	<li><span class="next_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=3">{LANG.next_step}</a></span>
	</li>
</ul>
<!-- END: automatic -->
<!-- END: step2 -->

<!-- BEGIN: step3 -->
<!-- BEGIN: error -->
<div class="infoerror">
	{LANG.update_substep3_error_file}<br />
	<strong><a href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=3" title="{LANG.update_substep3_moved}">{LANG.update_substep3_moved}</a></strong>
</div>
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=2">{LANG.previous}</a></span>
	</li>
</ul>
<!-- END: error -->
<!-- BEGIN: data -->
<table cellspacing="0" summary="summary" style="width: 100%;">
	<tr>
		<th scope="col" class="nobg boder center" style="width:60%">{LANG.update_all_work}</th>
		<th scope="col" class="nobg boder center">{LANG.update_current_work}</th>
	</tr>
	<tr>
		<td class="bleft nopadding">
			<div class="all-work">
				<!-- BEGIN: loop -->
				<div title="{ROW.status}" class="workitem{ROW.class}" id="{ROW.id}">{ROW.title}</div>
				<!-- END: loop -->
			</div>
		</td>
		<td class="nopadding">
			<div class="current-work">
				&nbsp;
				<div class="data">
					<!-- BEGIN: errorProcess -->
					<div class="infoerror">
						{ERROR_MESSAGE}
					</div>
					<!-- END: errorProcess -->
					<!-- BEGIN: AllPassed -->
					<div class="infook">
						{LANG.update_task_all_complete}.
					</div>
					<!-- END: AllPassed -->
					<!-- BEGIN: ConStart -->
					<div id="nv-message" class="center">
						{LANG.update_task_next} <strong>&quot;{DATA.nextftitle}&quot;</strong><br />
						<strong><a href="javascript:void(0);" onclick="NVU.Start();" title="{LANG.update_task_start}">{LANG.update_task_start}</a></strong>
					</div>
					<div id="nv-loading" class="hide center">
						
					</div>
					<script type="text/javascript">
					var NVU = {};
					NVU.IsStart = 0;
					NVU.IsAlert = 0;
					NVU.NextStepUrl = '{DATA.NextStepUrl}';
					NVU.NavigateConfirm = '{LANG.update_nav_confirm}';
					NVU.NextFuncs = '{DATA.nextfunction}';
					NVU.NextFuncsName = '{DATA.nextftitle}';
					NVU.NextUrl = '';
					NVU.Start = function(){
						$('#nv-message').hide();
						NVU.IsStart = 1;
						NVU.ShowLoad( NVU.NextFuncsName );
						$('#' + NVU.NextFuncs).removeClass('ierror').removeClass('iok').removeClass('iwarn').addClass('iload').attr('title', '{LANG.update_taskiload}');
						setTimeout( "NVU.load()", 1000 );
					}
					NVU.load = function(){
						var url;
						if( NVU.NextUrl == '' ){
							url = '{NV_BASE_SITEURL}install/update.php?step=2&substep=3&load=' + NVU.NextFuncs;
						}else{
							url = NVU.NextUrl;
						}
						
						// Dieu khien
						
						$.get( url, function(r){
							var check = r.split('|');
							NVU.HideLoad();
							
							if( check[0] == undefined || check[1] == undefined || check[2] == undefined || check[3] == undefined || check[4] == undefined || check[5] == undefined || check[6] == undefined || check[7] == undefined ){
								check[6] = '1';
							}
							
							// check[0] status
							// check[1] funcname
							// check[2] functitle
							// check[3] url
							// check[4]	lang
							// check[5] message
							// check[6] stop
							// check[7] allcomplete
							
							if( check[0] == '0' ){
								NVU.IsAlert = 1;
								if( check[6] == '1' ){
									$('#' + NVU.NextFuncs).removeClass('iload').removeClass('iok').removeClass('iwarn').addClass('ierror').attr('title', '{LANG.update_taskierror}');
								}else{
									$('#' + NVU.NextFuncs).removeClass('iload').removeClass('iok').removeClass('ierror').addClass('iwarn').attr('title', '{LANG.update_taskiwarn}');
								}
							}
							else
							{
								$('#' + NVU.NextFuncs).removeClass('iload').removeClass('iwarn').removeClass('ierror').addClass('iok').attr('title', '{LANG.update_taskiok}');
							}
							
							if( check[6] == '1' ){
								NVU.SetStop();
							}else if( check[7] == '1' ){
								NVU.SetComplete();
							}else{
								NVU.NextFuncs = check[1];
								NVU.NextFuncsName = check[2];
								NVU.NextUrl = '';
								var loadmessage = '';
								if( check[3] != 'NO' && check[3] != '' ) NVU.NextUrl = check[3];
								if( check[5] != 'NO' && check[5] != '' ){
									loadmessage = NVU.NextFuncsName + ' - ' + check[5];
								}else{
									loadmessage = NVU.NextFuncsName;
								}
								NVU.ShowLoad( loadmessage );
								$('#' + NVU.NextFuncs).removeClass('ierror').removeClass('iok').removeClass('iwarn').addClass('iload').attr('title', '{LANG.update_taskiload}');
								setTimeout( "NVU.load()", 1000 );
							}
						});
					}
					NVU.SetStop = function(){
						NVU.IsStart = 0;
						$('#nv-message').show().html('<div class="infoerror">{LANG.update_task_do1_error} <strong>&quot;' + NVU.NextFuncsName + '&quot;</strong> {LANG.update_task_do2_error}</div>');
					}
					NVU.SetComplete = function(){
						NVU.IsStart = 0;
						var DivClass = 'infook';
						if( NVU.IsAlert == 1 ){
							DivClass = 'infoalert';
						}
						$('#nv-message').show().html('<div class="' + DivClass + '">' + ( ( DivClass == 'infook' ) ? '{LANG.update_task_all_complete}' : '{LANG.update_task_all_complete_alert}' ) + '</div>');
						$('#control_t').append('<li><span class="next_step"><a href="' + NVU.NextStepUrl + '">{LANG.next_step}</a></span></li>');
					}
					NVU.ShowLoad = function(m){
						$('#nv-loading').html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt=""/><br />{LANG.update_task_load} <strong>' + m + '</strong><br />{LANG.update_task_load_message}.');
						$('#nv-loading').show();
					}
					NVU.HideLoad = function(){
						$('#nv-loading').html('');
						$('#nv-loading').hide();
					}
					NVU.ConfirmExit = function( event ){
						if( NVU.IsStart == 0 ) {  event.cancelBubble = true;  }  else  { return NVU.NavigateConfirm;  }
					}
					window.onbeforeunload = NVU.ConfirmExit;
					</script>
					<!-- END: ConStart -->
				</div>
			</div>
		</td>
	</tr>
</table>
<ul class="control_t fr" id="control_t">
	<li><span class="back_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=2">{LANG.previous}</a></span>
	</li>
	<!-- BEGIN: next_step -->
	<li><span class="next_step"><a
		href="{DATA.NextStepUrl}">{LANG.next_step}</a></span>
	</li>
	<!-- END: next_step -->
</ul>
<!-- END: data -->
<!-- END: step3 -->

<!-- BEGIN: step4 -->
<!-- BEGIN: win -->
<div class="infoalert">
	{LANG.update_file_info_win}<br />
	<strong><a href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=4&amp;manual=1" title="{LANG.update_file_info_win_manual}">{LANG.update_file_info_win_manual}</a></strong>
</div>
<!-- END: win -->
<!-- BEGIN: FTP_nosupport -->
<div class="infoalert">
	{LANG.update_ftp_nosupport}<br />
	<strong><a href="{NV_BASE_SITEURL}install/update.php?step=2&amp;substep=4&amp;manual=1" title="{LANG.update_file_info_win_manual}">{LANG.update_file_info_win_manual}</a></strong>
</div>
<!-- END: FTP_nosupport -->
<!-- BEGIN: check_FTP -->
<div class="infoalert">
	{LANG.update_ftp_config_info}
</div>
<form id="ftpconfigform" action="{ACTIONFORM}" method="post">
<table id="ftpconfig" cellspacing="0"
	summary="{LANG.checkftpconfig_detail}" style="width: 100%;">
	<tr>
		<th scope="col" class="nobg"></th>
		<th scope="col" abbr="{LANG.ftp}">{LANG.ftp}</th>
		<th scope="col">{LANG.note}</th>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_server}" class="spec">{LANG.ftp_server}</th>
		<td>
		<ul>
			<li style="display: inline;" class="fl"><input type="text"
				name="ftp_server" value="{DATA.ftpdata.ftp_server}" style="width: 300px;" /></li>
			<li style="display: inline;" class="fr"><span>{LANG.ftp_port}:</span><input
				type="text" name="ftp_port" value="{DATA.ftpdata.ftp_port}"
				style="width: 20px;" /></li>
		</ul>
		</td>
		<td><span class="highlight_green">{LANG.ftp_server_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_user}" class="specalt">{LANG.ftp_user}</th>
		<td><input type="text" name="ftp_user_name"
			value="{DATA.ftpdata.ftp_user_name}" style="width: 300px;" /></td>
		<td><span class="highlight_green">{LANG.ftp_user_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_pass}" class="spec">{LANG.ftp_pass}</th>
		<td><input type="password" name="ftp_user_pass" autocomplete="off"
			value="{DATA.ftpdata.ftp_user_pass}" /></td>
		<td><span class="highlight_green">{LANG.ftp_pass_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_path}" class="spec">{LANG.ftp_path}</th>
		<td><input type="text" name="ftp_path" value="{DATA.ftpdata.ftp_path}"
			style="width: 210px;" />
			<input class="button" type="button" id="find_ftp_path" value="{LANG.ftp_path_find}"/>
		</td>
		<td><span class="highlight_green">{LANG.ftp_path_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.refesh}" class="nobg"><input type="hidden"
			name="modftp" value="1" /></th>
		<td><input class="button" type="submit" value="{LANG.refesh}" /></td>
		<td></td>
	</tr>
</table>
</form>
<!-- BEGIN: errorftp -->
<span class="highlight_red">
{DATA.ftpdata.error}
</span>
<!-- END: errorftp -->
<script type="text/javascript">
$(document).ready(function(){
	$('#find_ftp_path').click(function(){
		var ftp_server = $('input[name="ftp_server"]').val();
		var ftp_user_name = $('input[name="ftp_user_name"]').val();
		var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
		var ftp_port = $('input[name="ftp_port"]').val();
		
		if( ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '' )
		{
			alert('{LANG.ftp_error_empty}');
			return;
		}
		
		$(this).attr('disabled', 'disabled');
		
		var data = 'ftp_server=' + ftp_server + '&ftp_port=' + ftp_port + '&ftp_user_name=' + ftp_user_name + '&ftp_user_pass=' + ftp_user_pass + '&tetectftp=1';
		var url = $('#ftpconfigform').attr('action');
		
		$.ajax({type:"POST", url:url, data:data, success:function(c){
			c = c.split('|');
			if( c[0] == 'OK' ){
				$('input[name="ftp_path"]').val(c[1]);
			}else{
				alert(c[1]);
			}
			$('#find_ftp_path').removeAttr('disabled');
		}});
	});
});
</script>
<!-- END: check_FTP -->
<table cellspacing="0" summary="summary" style="width: 100%;">
	<tr>
		<th scope="col" class="nobg boder center" style="width:50%">{LANG.update_file_list}</th>
		<th scope="col" class="nobg boder center">{LANG.update_file_info}</th>
	</tr>
	<tr>
		<td class="bleft nopadding">
			<div class="all-work">
				<!-- BEGIN: loop -->
				<div id="file-{ROW.id}" class="workitem{ROW.status}">{ROW.name}</div>
				<!-- END: loop -->
			</div>
		</td>
		<td class="nopadding">
			<div class="current-work">
				&nbsp;
				<div class="data">
					<!-- BEGIN: file_backup -->
					<div class="infoalert" id="infodetectedupg">
						{LANG.update_file_backup_info}<br />
						<strong><a class="update_dump" href="{URL_DUMPBACKUP}" title="{LANG.update_file_backup} sql">{LANG.update_file_backup}</a></strong>
						<script type="text/javascript">
						$(document).ready(function(){
							$('.update_dump').click(function(){
								$('#infodetectedupg').append('<div id="dpackagew"><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Waiting..."/></div>');
								$.get( $(this).attr('href') , function(e){
									$('#dpackagew').remove();
									$('#infodetectedupg').append('<br />' + e);
								});
								return !1;
							});
						});
						</script>
					</div>
					<!-- END: file_backup -->
				</div>
			</div>
		</td>
	</tr>
</table>
<!-- END: step4 -->

<!-- BEGIN: step5 -->
<!-- BEGIN: error -->
<div class="infoerror">
	{LANG.update_step5_info_error}
</div>
<!-- END: error -->
<!-- BEGIN: guide -->
<div class="infoalert">
	{LANG.update_step5_info}
</div>
<br />
{DATA.guide}
<!-- END: guide -->
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{DATA.BackStepUrl}">{LANG.previous}</a></span>
	</li>
	<li><span class="next_step"><a
		href="{NV_BASE_SITEURL}install/update.php?step=3">{LANG.next_step}</a></span>
	</li>
</ul>
<!-- END: step5 -->

<!-- END: main -->