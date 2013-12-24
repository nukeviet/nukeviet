<!-- BEGIN: step -->
<table id="checkserver" cellspacing="0"
	summary="{LANG.checkserver_detail}">
	<caption>{LANG.if_server} <span class="highlight_red">{LANG.not_compatible}</span>.
	{LANG.please_checkserver}.</caption>
	<tr>
		<th scope="col" class="nobg">{LANG.server_request}</th>
		<th scope="col">{LANG.note}</th>
		<th scope="col">{LANG.result}</th>
	</tr>
	<tr>
		<th scope="row" class="spec">{LANG.php_version}</th>
		<td>{LANG.required_on} >= 5.2.0</td>
		<td><span class="highlight_green">{DATA_REQUEST.php_support}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">{LANG.pdo_support}</th>
		<td class="alt">{LANG.required_on}</td>
		<td class="alt"><span class="highlight_green">{DATA_REQUEST.pdo_support}</span></td>
	</tr>

	<tr>
		<th scope="row" class="spec">{LANG.opendir_support}</th>
		<td>{LANG.request}</td>
		<td><span class="highlight_green">{DATA_REQUEST.opendir_support}</span></td>
	</tr>

	<tr>
		<th scope="row" class="specalt">{LANG.gd_support}</th>
		<td class="alt">{LANG.request}</td>
		<td class="alt"><span class="highlight_green">{DATA_REQUEST.gd_support}</span></td>
	</tr>

	<tr>
		<th scope="row" class="specalt">{LANG.mcrypt_support}</th>
		<td class="alt">{LANG.request}</td>
		<td class="alt"><span class="highlight_green">{DATA_REQUEST.mcrypt_support}</span></td>
	</tr>

	<tr>
		<th scope="row" class="spec">{LANG.session_support}</th>
		<td>{LANG.request}</td>
		<td><span class="highlight_green">{DATA_REQUEST.session_support}</span></td>
	</tr>

	<tr>
		<th scope="row" class="specalt">{LANG.fileuploads_support}</th>
		<td class="alt">{LANG.request}</td>
		<td class="alt"><span class="highlight_green">{DATA_REQUEST.fileuploads_support}</span></td>
	</tr>



</table>
<table id="recommend" cellspacing="0" summary="{LANG.recommnet}">
	<tr>
		<th scope="col" class="nobg">{LANG.request_more}</th>
		<th scope="col">{LANG.note}</th>
		<th scope="col">{LANG.result}</th>
	</tr>
	<tr>
		<th scope="row" class="spec">{LANG.supports_rewrite}</th>
		<td>{LANG.is_support}</td>
		<td><span class="highlight_green">{DATA_SUPPORT.supports_rewrite}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">{LANG.safe_mode}</th>
		<td class="alt">{LANG.turnoff}</td>
		<td class="alt"><span class="highlight_green">{DATA_SUPPORT.safe_mode}</span></td>
	</tr>
	<tr>
		<th scope="row" class="spec">Register Global</th>
		<td>{LANG.turnoff}</td>
		<td><span class="highlight_green">{DATA_SUPPORT.register_globals}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">Magic Quotes Runtime</th>
		<td class="alt">{LANG.turnoff}</td>
		<td class="alt"><span class="highlight_green">{DATA_SUPPORT.magic_quotes_runtime}</span></td>
	</tr>
	<tr>
		<th scope="row" class="spec">Magic Quotes GPC</th>
		<td>{LANG.turnoff}</td>
		<td><span class="highlight_green">{DATA_SUPPORT.magic_quotes_gpc}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">Magic Quotes Sybase</th>
		<td class="alt">{LANG.turnoff}</td>
		<td class="alt"><span class="highlight_green">{DATA_SUPPORT.magic_quotes_sybase}</span></td>
	</tr>
	<tr>
		<th scope="row" class="spec">Output Buffering</th>
		<td>{LANG.turnoff}</td>
		<td><span class="highlight_green">{DATA_SUPPORT.output_buffering}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">Session Auto Start</th>
		<td class="alt">{LANG.turnoff}</td>
		<td class="alt"><span class="highlight_green">{DATA_SUPPORT.session_auto_start}</span></td>
	</tr>
	<tr>
		<th scope="row" class="spec">Display Errors</th>
		<td>{LANG.turnoff}</td>
		<td><span class="highlight_green">{DATA_SUPPORT.display_errors}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">Set_time_limit()</th>
		<td class="alt">{LANG.turnon}</td>
		<td class="alt"><span class="highlight_green">{DATA_SUPPORT.allowed_set_time_limit}</span></td>
	</tr>
	<tr>
		<th scope="row" class="spec">Zlib Compression Support</th>
		<td>{LANG.is_support}</td>
		<td><span class="highlight_green">{DATA_SUPPORT.zlib_support}</span></td>
	</tr>
	<tr>
		<th scope="row" class="specalt">Extension Zip Support</th>
		<td class="alt">{LANG.is_support}</td>
		<td class="alt"><span class="highlight_green">{DATA_SUPPORT.zip_support}</span></td>
	</tr>
</table>
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=3">{LANG.previous}</a></span></li>
	<!-- BEGIN: nextstep -->
	<li><span class="next_step"><a
		href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5">{LANG.next_step}</a></span></li>
	<!-- END: nextstep -->		
</ul>
<!-- END: step -->