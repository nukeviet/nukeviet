<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table tab1">
		<thead>
			<tr>
				<td width="20px">{LANG.select}</td>
				<td>{LANG.title}</td>
				<td width="100px" align="center">{LANG.function}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{class}>
			<tr>
				<td align="center"><input type='checkbox' class='filelist'
					value="{id}"></td>
				<td>{title}</td>
				<td align="center">
					<span class="edit_icon">
						<a class='editfile' href="{URL_EDIT}">{LANG.edit}</a>
					</span>
					&nbsp;-&nbsp; 
					<span class="delete_icon">
						<a class='delfile' href="{URL_DEL_ONE}">{LANG.del}</a>
					</span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
	</table>
	<table class="tab1">
		<tfoot>
			<tr>
				<td>
					<span>
						<a href='javascript:void(0);' id='checkall'>{LANG.checkall}</a>
						&nbsp;&nbsp;
						<a href='javascript:void(0);' id='uncheckall'>{LANG.uncheckall}</a>
						&nbsp;&nbsp;
					</span>
					<span class="add_icon">
						<a class='addfile' href="{LINK_ADD}">{LANG.add}</a>
						&nbsp;&nbsp;
					</span>
					<span class="delete_icon"><a id='delfilelist' href="javascript:void(0);">{LANG.del}</a>
					</span>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

<script type='text/javascript'>
	$(function()
	{
		$('#checkall').click(function()
		{
			$('input:checkbox').each(function()
			{
				$(this).attr('checked', 'checked');
			});
		});
		
		$('#uncheckall').click(function()
		{
			$('input:checkbox').each(function()
			{
				$(this).removeAttr('checked');
			});
		});
		
		$('#delfilelist').click(function()
		{
			if (confirm("{LANG.del_confirm}"))
			{
				var listall = [];
				$('input.filelist:checked').each(function()
				{
					listall.push($(this).val());
				});
				if (listall.length < 1)
				{
					alert("{LANG.error_file}");
					return false;
				}
				$.ajax(
				{
					type: 'POST',
					url: '{URL_DEL}',
					data: 'listall=' + listall,
					success: function(data)
					{
						alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('a[class="delfile"]').click(function(event)
		{
			event.preventDefault();
			if (confirm("{LANG.del_confirm}"))
			{
				var href = $(this).attr('href');
				$.ajax(
				{
					type: 'POST',
					url: href,
					data: '',
					success: function(data)
					{
						alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
	});
</script>
<!-- END: main -->
