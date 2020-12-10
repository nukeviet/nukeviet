<!-- BEGIN: main -->
<div id="add">
	<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col class="w50"/>
					<col>
				</colgroup>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>{LANG.name}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td class="text-center"><input name="checkall" type="checkbox"/></td>
						<td>
						<select class="form-control" name="topicsid">
							<!-- BEGIN: topicsid -->
							<option value="{TOPICSID.key}">{TOPICSID.title}</option>
							<!-- END: topicsid -->
						</select> 
						<input class="btn btn-primary" name="update" id="update-topic" type="button" value="{LANG.save}" /></td>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td class="text-center"><input type="checkbox" value="{ROW.id}" name="idcheck"{ROW.checked}></td>
						<td>{ROW.title}</td>
					</tr>
					<!-- END: loop -->
				</tbody>
			</table>
		</div>
	</form>
</div>
<script type="text/javascript">
var LANG = [];
LANG.topic_nocheck = '{LANG.topic_nocheck}';
$(function() {
    $('input[name=checkall]').one("click", checkallfirst);
});
</script>
<!-- END: main -->