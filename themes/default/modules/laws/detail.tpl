<!-- BEGIN: main -->
<h3 class="lawh3">{DATA.title}</h3>
<p>{DATA.introtext}</p>
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr class="hoatim">
			<td style="width:200px">{LANG.code}</td>
			<td class="text-center">{DATA.code}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.publtime}</td>
			<td class="text-center">{DATA.publtime}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.startvalid}</td>
			<td class="text-center">{DATA.startvalid}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.exptime}</td>
			<td class="text-center">{DATA.exptime}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.cat}</td>
			<td class="text-center">{DATA.cat}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.area}</td>
			<td class="text-center">{DATA.area}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.subject}</td>
			<td class="text-center">{DATA.subject}</td>
		</tr>
		<tr class="hoatim">
			<td class="text-center">{LANG.signer}</td>
			<td class="text-center">{DATA.signer}</td>
		</tr>
	<!-- BEGIN: replacement -->
		<tr>
			<td>{LANG.replacement}</td>
			<td>
				<ul class="list-item">
					<!-- BEGIN: loop -->
					<li><a href="{replacement.link}" title="{replacement.title}">{replacement.code}</a> - {replacement.title}</li>
					<!-- END: loop -->
				</ul>
			</td>
		</tr>
	<!-- END: replacement -->
	<!-- BEGIN: unreplacement -->
		<tr>
			<td>{LANG.unreplacement}</td>
			<td>
				<ul class="list-item">
					<!-- BEGIN: loop -->
					<li><a href="{unreplacement.link}" title="{unreplacement.title}">{unreplacement.code}</a> - {unreplacement.title}</li>
					<!-- END: loop -->
				</ul>
			</td>
		</tr>
	<!-- END: unreplacement -->
	<!-- BEGIN: relatement -->
		<tr>
			<td>{LANG.relatement}</td>
			<td>
				<ul class="list-item">
					<!-- BEGIN: loop -->
					<li><a href="{relatement.link}" title="{relatement.title}">{relatement.code}</a> - {relatement.title}</li>
					<!-- END: loop -->
				</ul>
			</td>
		</tr>
	<!-- END: relatement -->
	</tbody>
</table>
<h3 class="lawh3">{LANG.bodytext}</h3>
{DATA.bodytext}
<div style="height:10px"></div>
<!-- BEGIN: files -->
<h3 class="lawh3">{LANG.files}</h3>
<ul class="list-item">
	<!-- BEGIN: loop -->
	<li><a href="{FILE.url}" title="{FILE.title}">{FILE.title}</a></li>
	<!-- END: loop -->
</ul>
<!-- END: files -->
<!-- BEGIN: logindownload -->
<h3 class="lawh3">{LANG.files}</h3>
<p class="text-center"><a href="{URLLOGIN}" title="">{LANG.info_download_login}</a></p>
<!-- END: logindownload -->
<!-- BEGIN: nodownload -->
<h3 class="lawh3">{LANG.files}</h3>
<p class="text-center">{LANG.info_download_no}</p>
<!-- END: nodownload -->
<!-- END: main -->