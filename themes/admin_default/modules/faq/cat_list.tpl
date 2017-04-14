<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
    <caption>{TABLE_CAPTION}</caption>
    <thead>
        <tr>
            <th>
                {LANG.faq_category_cat_sort}
            </th>
            <th>
                {LANG.faq_category_cat_name}
            </th>
            <th>
                {LANG.faq_category_cat_parent}
            </th>
            <th class="w100 text-center">
                {LANG.faq_category_cat_active}
            </th>
            <th class="w200 text-center">
                {LANG.faq_category_cat_feature}
            </th>
        </tr>
    </thead>
    <tbody>
    	<!-- BEGIN: row -->
        <tr>
            <td class="w100">
                <select class="form-control" name="weight" id="weight{ROW.id}" onchange="nv_change_weight({ROW.id});">
                    <!-- BEGIN: weight -->
                    <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
                    <!-- END: weight -->
                </select>
            </td>
            <td>
                <strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub}
            </td>
            <td>
                {ROW.parentid}
            </td>
            <td class="w100 text-center">
                <input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_change_status({ROW.id});" />
            </td>
            <td class="w200 text-center">
                <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a>
                &nbsp;&nbsp;
				<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_cat_del({ROW.id});">{GLANG.delete}</a>
            </td>
        </tr>
    	<!-- END: row -->
    <tbody>
</table>
<p>
	<a class="btn btn-primary" href="{ADD_NEW_CAT}">{LANG.faq_addcat_titlebox}</a>
</p>
<!-- END: main -->