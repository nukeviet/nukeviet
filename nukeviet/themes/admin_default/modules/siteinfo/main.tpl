<!-- BEGIN: main -->
<!-- BEGIN: pendinginfo -->
<table class="tab1">
	<caption>{LANG.pendingInfo}</caption>
	<col valign="top" width="20%" />
	<col valign="top" />
	<col valign="top" width="10%" />
	<thead>
		<tr>
			<td>{LANG.moduleName}</td>
			<td>{LANG.moduleContent}</td>
			<td class="aright">{LANG.moduleValue}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{MODULE}</td>
			<td>
				<!-- BEGIN: link -->
				<a class="link" href="{LINK}" title="{KEY}">{KEY}</a>
				<!-- END: link -->
				<!-- BEGIN: text -->
				{KEY}
				<!-- END: text -->
			</td>
			<td class="aright">{VALUE}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: pendinginfo -->
<!-- BEGIN: info -->
<table class="tab1">
	<caption>{LANG.moduleInfo}</caption>
	<col valign="top" width="20%" />
	<col valign="top" />
	<col valign="top" width="10%" />
	<thead>
		<tr>
			<td>{LANG.moduleName}</td>
			<td>{LANG.moduleContent}</td>
			<td class="aright">{LANG.moduleValue}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{MODULE}</td>
			<td>
				<!-- BEGIN: link -->
				<a class="link" href="{LINK}" title="{KEY}">{KEY}</a>
				<!-- END: link -->
				<!-- BEGIN: text -->
				{KEY}
				<!-- END: text -->
			</td>
			<td class="aright">{VALUE}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: info -->
<!-- BEGIN: version -->
<table class="tab1">
	<caption>{LANG.version} <span style="font-weight:400">(<a href="{ULINK}">{CHECKVERSION}</a>)</span></caption>
	<thead>
		<tr>
			<td>{LANG.moduleContent}</td>
			<td class="aright">{LANG.moduleValue}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{KEY}</td>
			<td class="aright">{VALUE}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: inf --><div class="newVesionInfo">{INFO}</div><!-- END: inf -->
<!-- END: version -->
<!-- END: main -->