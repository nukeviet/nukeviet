<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table summary="" class="tab1">
	<thead>
		<tr>
			<td width="10px" align="center"></td>
			<td><strong>{LANG.order_code}</strong></td>
			<td><strong>{LANG.order_time}</strong></td>
			<td><strong>{LANG.order_email}</strong></td>
			<td align="right"><strong>{LANG.order_total}</strong></td>
			<td><strong>{LANG.order_payment}</strong></td>
			<td width="100px" align="center"><strong>{LANG.comment_funcs}</strong></td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
    <tbody {bg}>
	<tr {bgview}>
		<td><input type="checkbox" class="ck" value="{order_id}" {DIS} /></td>
		<td>{DATA.order_code}</td>
		<td>{DATA.order_time}</td>
		<td><a href="{DATA.link_user}" style="text-decoration:underline" target="_blank">{DATA.order_email}</a></td>
		<td align="right">{DATA.order_total} {DATA.unit_total}</td>
		<td>{DATA.status_payment}</td>
		<td align="center"><span class="edit_icon"><a href="{link_view}" title="">{LANG.view}</a></span> <!-- BEGIN: delete --> &nbsp;-&nbsp;<span class="delete_icon"><a href="{link_del}" class="delete" title="">{LANG.del}</a></span> <!-- END: delete --></td>
	</tr>
    </tbody>
	<!-- END: row -->
	<tfoot>
		<tr>
			<td colspan="6"><a href="#" id="checkall">{LANG.prounit_select}</a> | <a href="#" id="uncheckall">{LANG.prounit_unselect}</a> |<a href="#" id="delall">{LANG.prounit_del_select}</a></td>
            <td colspan="2">{PAGES}</td>
		</tr>
	</tfoot>
</table>
<script type='text/javascript'>
    $(function(){
        $('#checkall').click(function(){
            $('input:checkbox').each(function(){
                $(this).attr('checked', 'checked');
            });
        });
        $('#uncheckall').click(function(){
            $('input:checkbox').each(function(){
                $(this).removeAttr('checked');
            });
        });
        $('#delall').click(function(){
            if (confirm("{LANG.prounit_del_confirm}")) {
                var listall = [];
                $('input.ck:checked').each(function(){
                    listall.push($(this).val());
                });
                if (listall.length < 1) {
                    alert("{LANG.prounit_del_no_items}");
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '{URL_DEL}',
                    data: 'listall=' + listall,
                    success: function(data){
                       window.location = '{URL_DEL_BACK}';
                    }
                });
            }
        });

        $('a.delete').click(function(event){
            event.preventDefault();
            if (confirm("{LANG.prounit_del_confirm}")) {
                var href = $(this).attr('href');
                $.ajax({
                    type: 'POST',
                    url: href,
                    data: '',
                    success: function(data){
                        window.location = '{URL_DEL_BACK}';
                    }
                });
            }
        });
    });
</script>
<!-- END: data -->
<!-- END: main -->