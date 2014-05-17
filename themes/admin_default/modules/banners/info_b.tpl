<!-- BEGIN: main -->
<div class="pull-right">
	<a class="btn btn-default btn-xs" href="{CONTENTS.edit.0}">{CONTENTS.edit.1}</a>
	<!-- BEGIN: act -->
	<a class="btn btn-default btn-xs" href="javascript:void(0);" onclick="{CONTENTS.act.0}">{CONTENTS.act.1}</a>
	<!-- END: act -->
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}</caption>
		<col span="2" style="width:50%;white-space:nowrap" />
		<tbody>
			<!-- BEGIN: loop1 -->
			<tr>
				<td>{ROW1.0}:</td>
				<td>{ROW1.1}</td>
			</tr>
			<!-- END: loop1 -->
		</tbody>
	</table>
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.stat.0}</caption>
		<tbody>
			<tr>
				<td><span class="text-middle pull-left">{CONTENTS.stat.1}:</span>
				<select name="{CONTENTS.stat.2}" id="{CONTENTS.stat.2}" class="form-control w200 pull-left">
					<!-- BEGIN: stat1 -->
					<option value="{K}">{V}</option>
					<!-- END: stat1 -->
				</select>
				<select name="{CONTENTS.stat.4}" id="{CONTENTS.stat.4}" class="form-control w200 pull-left">
					<!-- BEGIN: stat2 -->
					<option value="{K}">{V}</option>
					<!-- END: stat2 -->
				</select>
				<input type="button" class="btn btn-primary" value="{CONTENTS.stat.6}" id="{CONTENTS.stat.7}" onclick="{CONTENTS.stat.8}" /></td>
			</tr>
		</tbody>
	</table>
</div>
<div id="{CONTENTS.containerid}"></div>
<script type="text/javascript">
	$(function() {
		$('a[class=delfile]').click(function(event) {
			event.preventDefault();
			if (confirm('{LANG.file_del_confirm}')) {
				var href = $(this).attr('href');
				$.ajax({
					type : 'POST',
					url : href,
					data : '',
					success : function(data) {
						alert(data);
						window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banner_list';
					}
				});
			}
		});
	});
</script>
<!-- END: main -->