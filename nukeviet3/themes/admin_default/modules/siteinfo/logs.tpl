<!-- BEGIN: main -->
<table class="tab1">
    <thead>
        <tr>
            <td width="30" align="center">
                <input type="checkbox" name="all" id="check_all"/>
            </td>
            <td width="30" align="center">
                {LANG.log_lang}
            </td>
            <td>
                {LANG.log_module_name}
            </td>
            <td>
                {LANG.log_name_key}
            </td>
			<td>
                {LANG.log_note}
            </td>
			<td>
                {LANG.log_username}
            </td>
            <td>
                {LANG.log_time}
            </td>
            <td align="center">
                {LANG.log_feature}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: row -->
    <tbody {CLASS}>
        <tr>
            <td align="center">
                <input type="checkbox" name="all" class="list" value="{DATA.id}"/>
            </td>
			<td width="30" align="center">
                {DATA.lang}
            </td>
            <td>
                {DATA.module_name}
            </td>
            <td>
                {DATA.name_key}
            </td>
			<td>
                {DATA.note_action}
            </td>
			<td>
                {DATA.username}
            </td>
            <td>
                {DATA.time}
            </td>
            <td width="100" align="center">
				<span class="delete_icon">
					<a href="{DEL_URL}" class = "delete">{GLANG.delete}</a>
				</span>
            </td>
        </tr>
    </tbody>
    <!-- END: row -->
   	 <tr class="tfoot_box">
        <td colspan="2">
        	<input type="button" value="{GLANG.delete}" id="delall" />
        </td> 
        <td colspan="6">
            <!-- BEGIN: generate_page -->
			{GENERATE_PAGE}
			<!-- END: generate_page -->
		   </td> 
        </tr>   
    </table>
<script type='text/javascript'>
//<![CDATA[
	$(function(){
	$("#check_all").click(function(){
        if ($("#check_all").attr("checked")) {
        	$('input.list').attr("checked", "checked");//checked
        }
        else {
        	$('input.list').removeAttr("checked");//checked
        }
    });
	$('#delall').click(function(){
		var listall = [];
		$('input.list:checked').each(function(){
			listall.push($(this).val());
		});
		if (listall.length<1){
			alert("{LANG.log_del_no_items}");
			return false;
		}
		if (confirm("{LANG.log_del_confirm}"))
		{
			$.ajax({	
				type: 'POST',
				url: '{URL_DEL}',
				data:'listall='+listall,
				success: function(data){	
					var s = data.split('_');
					if (s[0] == 'OK') window.location='{BACK_URL}';
					alert(s[1]);		
				}
			});
		}
	});
	$('a.delete').click(function(event){
	event.preventDefault();
	if (confirm("{LANG.log_del_confirm}"))
	{
		var href= $(this).attr('href');
		$.ajax({	
			type: 'POST',
			url: href,
			data:'',
			success: function(data){				
				var s = data.split('_');
				if (s[0] == 'OK') window.location='{BACK_URL}';
				alert(s[1]);
			}
		});
	}
	});
	});
//]]>
</script>
<!-- END: main -->