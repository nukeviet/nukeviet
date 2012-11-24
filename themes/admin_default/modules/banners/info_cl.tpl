<!-- BEGIN: main -->
<div style="height:27px;margin-top:3px;position:absolute;right:10px;text-align:right;">
	<a class="button2" href="{CONTENTS.edit.0}"><span><span>{CONTENTS.edit.1}</span></span></a>
	<a class="button2" href="javascript:void(0);" onclick="{CONTENTS.act.0}"><span><span>{CONTENTS.act.1}</span></span></a>
	<a class="button2" href="{CONTENTS.add.0}"><span><span>{CONTENTS.add.1}</span></span></a>
	<a class="button2" href="javascript:void(0);" onclick="{CONTENTS.del.0}"><span><span>{CONTENTS.del.1}</span></span></a>
</div>
<table summary="{CONTENTS.caption}" class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<col style="width:50%;white-space:nowrap" />
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>{ROW.0}:</td>
			<td>{ROW.1}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->