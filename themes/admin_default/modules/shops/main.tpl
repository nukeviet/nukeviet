<!-- BEGIN: main -->
<!-- BEGIN: modinfo -->
	<table class="divbor" style="margin-bottom:0">
    <tr>
    	<td width="200"><strong>{LANG.module_name} : {module}</strong></td>
        <td width="250"><strong>{LANG.module_version} : {module_version}</strong></td>
        <td align="right"><a target="_blank" href="{DATA.urlpost}?language=vi&nv=support&op=regis&key={DATA.key}&checkss={DATA.checkss}&info={DATA.info}&module={DATA.module}"><strong>{LANG.module_regis}</strong></a></td>
    </tr>
    </table>
<!-- END: modinfo -->
<table class="tab1">
<!-- BEGIN: loop -->
    <tbody {bg}>
        <tr>
            <td width="350" style="padding:10px"><a href="{KEY.link}">{KEY.title}</a></td>
            <td width="100" align="right">
                <span style="color:#993300; font-weight:bold">{KEY.value}</span>
            </td>
            <td>{KEY.unit}</td>
        </tr>
    </tbody>
<!-- END: loop -->
</table>