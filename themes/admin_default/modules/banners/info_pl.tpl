<!-- BEGIN: main -->
<div style="height:27px;margin-top:3px;position:absolute;right:10px;text-align:right;">
	<a class="button2" href="{CONTENTS.edit.0}"><span><span>{CONTENTS.edit.1}</span></span></a>
	<a class="button2" href="javascript:void(0);" onclick="{CONTENTS.act.0}"><span><span>{CONTENTS.act.1}</span></span></a>
	<a class="button2" href="{CONTENTS.add.0}"><span><span>{CONTENTS.add.1}</span></span></a>
	<a class="button2" href="javascript:void(0);" onclick="{CONTENTS.del.0}"><span><span>{CONTENTS.del.1}</span></span></a>
</div>
<table class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<colgroup>
		<col style="width:50%;white-space:nowrap" />
	</colgroup>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<!-- BEGIN: t1 -->
			<td>{ROW.0}:</td>
			<td>{ROW.1}</td>
			<!-- END: t1 -->
			<!-- BEGIN: t2 -->
			<td colspan="2">{ROW.0}:</td>
			<!-- END: t2 -->
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- BEGIN: description -->
<div style="border:1px solid #dadada;margin:10px 0px 0px 0px;padding:10px">
	<div>
		{CONTENTS.rows.description.1}
	</div>
</div>
<!-- END: description -->
<!-- END: main -->