<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
	    <thead>
	        <tr>
	            <!-- BEGIN: is_cat1 -->
	            <th class="w100">
	                {LANG.faq_pos}
	            </th>
	            <!-- END: is_cat1 -->
	            <th>
	                {LANG.faq_title_faq}
	            </th>
	            <th>
	                {LANG.faq_catid_faq}
	            </th>
	            <th class="w200 text-center">
	                {LANG.faq_status}
	            </th>
	            <th class="w200 text-center">
	                {LANG.faq_feature}
	            </th>
	        </tr>
	    </thead>
	    <tbody>
	    <!-- BEGIN: row -->
	        <tr>
	            <!-- BEGIN: is_cat2 -->
	            <td>
	                <select class="form-control" name="weight" id="weight{ROW.id}" onchange="nv_change_row_weight({ROW.id});">
	                    <!-- BEGIN: weight -->
	                    <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
	                    <!-- END: weight -->
	                </select>
	            </td>
	            <!-- END: is_cat2 -->
	            <td>
	                {ROW.title}
	            </td>
	            <td>
	                <a href="{ROW.catlink}">{ROW.cattitle}</a>
	            </td>
	            <td class="text-center">
	                <select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');" class="form-control">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
					<!-- END: status -->
					</select>
	            </td>
	            <td class="text-center">
	                <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a>
	                &nbsp;&nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_row_del({ROW.id});">{GLANG.delete}</a>
	            </td>
	        </tr>
	    <!-- END: row -->
	    <tbody>
	    <!-- BEGIN: generate_page -->
	    <tr class="footer">
	        <td colspan="8">
	            {GENERATE_PAGE}
	        </td>
	    </tr>
	    <!-- END: generate_page -->
	</table>
</div>
<a class="btn btn-primary" href="{ADD_NEW_FAQ}">{LANG.faq_addfaq}</a>
<!-- END: main -->