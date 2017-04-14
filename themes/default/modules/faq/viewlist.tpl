<!-- BEGIN: main -->
<!-- BEGIN: main_not_accept -->
<h3>{LANG.faq_cat_not_accept}</h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
	    <thead>
	        <tr>
	            <th>
	                {LANG.faq_title_faq}
	            </th>
	            <th>
	                {LANG.faq_catid_faq}
	            </th>
	            <th class="w200 text-center">
	                {LANG.faq_feature}
	            </th>
	        </tr>
	    </thead>
	    <tbody>
	    <!-- BEGIN: row_not_accept -->
	        <tr>
	            <td>
	               <a href="#">{ROW.title}</a>
	            </td>
	            <td>
	                <span>{ROW.cattitle}</span>
	            </td>
	            <td class="text-center">
	                <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a>
	                &nbsp;&nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_row_del({ROW.id},'{CHECKSS}');">{GLANG.delete}</a>
	            </td>
	        </tr>
	    <!-- END: row_not_accept -->
	    <tbody>
	    <!-- BEGIN: generate_page_not_accept -->
	    <tr class="footer">
	        <td colspan="8">
	            {GENERATE_PAGE}
	        </td>
	    </tr>
	    <!-- END: generate_page_not_accept -->
	</table>
</div>
<!-- END: main_not_accept -->
<!-- BEGIN: main_accept -->
<h3>{LANG.faq_cat_accept}</h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
	    <thead>
	        <tr>
	            <th>
	                {LANG.faq_title_faq}
	            </th>
	            <th>
	                {LANG.faq_catid_faq}
	            </th>
	        </tr>
	    </thead>
	    <tbody>
	    <!-- BEGIN: row_accept -->
	        <tr>
	            <td>
	                 <a href="{ROW.link}">{ROW.title}</a>
	            </td>
	            <td>
	                <span>{ROW.cattitle}</span>
	            </td>
	        </tr>
	    <!-- END: row_accept -->
	    <tbody>
	    <!-- BEGIN: generate_page_accept -->
	    <tr class="footer">
	        <td colspan="8">
	            {GENERATE_PAGE}
	        </td>
	    </tr>
	    <!-- END: generate_page_accept -->
	</table>
</div>
<!-- END: main_accept -->
<a class="btn btn-primary" href="{ADD_NEW_FAQ}">{LANG.faq_addfaq}</a>
<script>
	function nv_row_del( fid,checkss )
{
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list&nocache=' + new Date().getTime(), 'del=1&id=' + fid+'&checkss='+checkss, function(res) {
			alert(res);
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}
</script>
<!-- END: main -->