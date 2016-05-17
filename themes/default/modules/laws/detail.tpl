<!-- BEGIN: main -->
<h3 class="lawh3">{DATA.title}</h3>
<p>{DATA.introtext}</p>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr class="hoatim">
				<td style="width:200px" class="text-right">{LANG.code}</td>
				<td>{DATA.code}</td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.publtime}</td>
				<td>{DATA.publtime}</td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.startvalid}</td>
				<td>{DATA.startvalid}</td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.exptime}</td>
				<td>{DATA.exptime}</td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.cat}</td>
				<td><a href="{DATA.cat_url}" title="{DATA.cat}">{DATA.cat}</a></td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.area}</td>
				<td>
				    <!-- BEGIN: area -->
				    <a href="{AREA.url}" title="{AREA.title}">{AREA.title}</a><br />
				    <!-- END: area -->
			    </td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.subject}</td>
				<td><a href="{DATA.subject_url}" title="{DATA.subject}">{DATA.subject}</a></td>
			</tr>
			<tr class="hoatim">
				<td class="text-right">{LANG.signer}</td>
				<td><a href="{DATA.signer_url}" title="{DATA.signer}">{DATA.signer}</a></td>
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
</div>

<!-- BEGIN: bodytext -->
<h3 class="lawh3">{LANG.bodytext}</h3>
<p class="m-bottom">{DATA.bodytext}</p>
<!-- END: bodytext -->

<!-- BEGIN: files -->
<h3 class="lawh3"><em class="fa fa-download">&nbsp;</em>{LANG.files}</h3>
<ul class="list-item m-bottom">
	<!-- BEGIN: loop -->
	<li><a href="{FILE.url}" title="{FILE.titledown}{FILE.title}">{FILE.titledown} <strong>{FILE.title}</strong></a></li>
	<!-- END: loop -->
</ul>
<!-- END: files -->

<!-- BEGIN: nodownload -->
<h3 class="lawh3">{LANG.files}</h3>
<p class="text-center m-bottom">{LANG.info_download_no}</p>
<!-- END: nodownload -->

<!-- BEGIN: other_cat -->
<h3 class="subtitle">{LANG.other_cat} <a href="{DATA.cat_url}" title="{DATA.cat}">"{DATA.cat}"</a></h3>
{OTHER_CAT}
<!-- END: other_cat -->

<!-- BEGIN: other_area -->
<h3 class="subtitle">{LANG.other_area}</h3>
{OTHER_AREA}
<!-- END: other_area -->

<!-- BEGIN: other_subject -->
<h3 class="subtitle">{LANG.other_subject} <a href="{DATA.subject_url}" title="{DATA.subject}">"{DATA.subject}"</a></h3>
{OTHER_SUBJECT}
<!-- END: other_subject -->

<!-- BEGIN: other_signer -->
<h3 class="subtitle">{LANG.other_signer} <a href="{DATA.signer_url}" title="{DATA.signer}">"{DATA.signer}"</a></h3>
{OTHER_SIGNER}
<!-- END: other_signer -->

<!-- END: main -->