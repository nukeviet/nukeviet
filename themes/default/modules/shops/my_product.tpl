<!-- BEGIN: main -->
<div class="profile">
    <div style="margin-bottom:10px">
		<a class="profile_tab" href="{PROFILE_URL}" >{LANG.profile_manage_info}</a>
		<a class="profile_tab" href="{URL_MYPRO}">{LANG.profile_manage_myproducts}</a>
		<a class="profile_tab" href="{USER_LOGOUT}">{LANG.profile_user_logout}</a>
	</div>
	<table class="rows" style="border: 1px solid #f4f4f4; padding-top: 2px">
		<tr class="bgtop">
			<td class="checkbox" align="center" width="30px">{LANG.order_no_products}</td>
			<td class="prd" style="text-justify: auto;">{LANG.cart_products}</td>
			<td class="price" width="80px" align="right">{LANG.cart_price}({unit_config})</td>
			<td class="amount" width="80px">{LANG.profile_products_status}</td>
			<td class="" align="center" width="80px">{LANG.profile_action}</td>
		</tr>
		<tbody>
			<!-- BEGIN: rows -->
			<tr id="{id}"{bg}>
				<td class="checkbox" align="center">{no_pro}</td>
				<td class="prd"><a title="{title_pro}" href="{link_pro}">{title_pro}</a></td>
				<td class="money" align="right"><strong>{product_price}</strong></td>
				<td class="amount">{products_status}</td>
				<td class="" align="center">
					<!-- BEGIN: allow -->
					<a class="btn" title="{LANG.profile_edit_title}" href="{link_edit}">{LANG.profile_edit_title}</a> |
					<a class="btn0" title="{LANG.profile_del_title}" href="{link_del}">{LANG.profile_del_title}</a>
					<!-- END: allow -->
					<!-- BEGIN: not_allow -->
					<a class="btn" title="" href="#">-</a> |
					<a class="btn0" title="" href="#">-</a>
					<!-- END: not_allow -->
				</td>
			</tr>
			<!-- END: rows -->
		</tbody>
	</table>
	<div class="pages">{pages_pro}</div>
</div>
<script type="text/javascript">
    $(function(){
		$("a.btn0").click(function(event){
			event.preventDefault();
			if (!confirm("{LANG.shop_cat_do_want_del}")) 
            return false;
            var href = $(this).attr("href");
            $.ajax({
                type: "GET",
                url: href +'&nocache=' + new Date().getTime(),
                data: '',
                success: function(data){
                    var s = data.split('_');
                    if (s[0] == 'NO') {
                        alert(s[1]);
                    }
                    if (s[0] == 'OK') {
                       $("#" + s[1]).html('');
                    }
                }
        	});
       		return false;
        });
	});
</script>
<!-- END: main -->