<!-- BEGIN: main -->
<div style="height:27px;margin-top:3px;position:absolute;right:10px;text-align:right;">
	<a class="btn btn-default btn-xs" href="{CONTENTS.edit.0}">{CONTENTS.edit.1}</a>
	<a class="btn btn-default btn-xs" href="javascript:void(0);" onclick="{CONTENTS.act.0}">{CONTENTS.act.1}</a>
	<a class="btn btn-default btn-xs" href="{CONTENTS.add.0}">{CONTENTS.add.1}</a>
	<a class="btn btn-default btn-xs" href="javascript:void(0);" onclick="{CONTENTS.del.0}">{CONTENTS.del.1}</a>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}</caption>
		<colgroup>
			<col style="width:50%;white-space:nowrap" />
		</colgroup>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.0}:</td>
				<td>{ROW.1}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->