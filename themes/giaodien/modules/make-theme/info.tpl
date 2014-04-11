<!-- BEGIN: main -->
<form action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&step=2" method="post"  enctype="multipart/form-data">
	<table class="tab1">
		<caption> Thông tin về giao diện </caption>
		<colgroup>
			<col style="width:30%;">
		</colgroup>
		<tbody  class="second">
			<tr>
				<td> Thư mục chứa giao diện </td>
				<td><input type="text" class="required" value="{DATA.theme}" style="width:250px;" name="theme"></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td> Tên gọi giao diện </td>
				<td><input type="text" class="required" value="{DATA.info_name}" style="width:250px;" name="info_name"></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td> Tác giả </td>
				<td><input type="text" class="required" value="{DATA.info_author}" style="width:250px;" name="info_author"></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td> Website </td>
				<td><input type="text" class="required url" value="{DATA.info_website}" style="width:250px;" name="info_website"></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td> Mô tả </td>
				<td><input type="text" value="{DATA.info_description}" style="width:250px;" name="info_description"></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td> Phiên bản sử dụng </td>
				<td><input type="text" value="{DATA.version}" style="width:250px;" name="version"></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td> Xây dựng trên giao diện </td>
				<td>
				<select name="source_theme">
					<!-- BEGIN: source_theme -->
					<option value="{SOURCE_THEME}" {SELECT_THEME}>{SOURCE_THEME}</option>
					<!-- END: source_theme -->
				</select></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td> Copy giao diện các module </td>
				<td id="td_mod_theme">
				<!-- BEGIN: mod_theme -->
					&nbsp;<label><input type="checkbox" name="mod_theme[]" value="{MOD_THEME}"/> {MOD_THEME}</label>
				<!-- END: mod_theme -->
				</td>
			</tr>
		</tbody>
	</table>

	<table id="additem_position" class="tab1">
		<caption> Các khối giao diện </caption>
		<colgroup>
			<col style="width:10%;">
			<col style="width:30%;">
			<col style="width:30%;">
			<col style="width:30%;">
		</colgroup>
		<thead align="center">
			<tr>
				<td style="background: none repeat scroll 0% 0% transparent;"> Số thứ tự </td>
				<td> Mã khối giao diện </td>
				<td> Tên khối giao diện tiếng anh </td>
				<td> Tên khối giao diện tiếng việt </td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
				<button id="" onclick="theme_additem_position();return false;" class="btn">
					<span style="margin-top: -1px;">Thêm khối giao diện</span>
				</button></td>
			</tr>
		</tfoot>
		<!-- BEGIN: loop -->
		<tbody {POSITION.class}>
			<tr>
				<td align="center"> {POSITION.id} </td>
				<td><input type="text" value="{POSITION.tag}" style="width:220px;" name="position_tag[{POSITION.id}]" readonly="readonly"></td>
				<td><input type="text" value="{POSITION.name}" style="width:220px;" name="position_name[{POSITION.id}]"></td>
				<td><input type="text" value="{POSITION.name_vi}" style="width:220px;" name="position_name_vi[{POSITION.id}]"></td>
			</tr>
		</tbody>
		<!-- END: loop -->
	</table>
	<div style="text-align:center">
		<input name="submit" type="submit" value="Thực hiện" />
	</div>
</form>
<script type="text/javascript">
	var items_positions = '{ITEMS_POSITIONS}';
	var checkss = '{CHECKSS}';
	$("select[name=source_theme]").change(function() {
		var source_theme = $("select[name=source_theme]").val();
		$("#td_mod_theme").html("Loading...").load(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&get_module_theme=" + source_theme + "&checkss=" + checkss + "&nocache=" + new Date().getTime());

	});
	function theme_additem_position() {
		items_positions++;
		var nclass = (items_positions % 2 == 0) ? " class=\"second\"" : "";
		var newitem = '<tbody' + nclass + '>';
		newitem += '<tr>';
		newitem += '	<td align="center">' + items_positions + '</td>';
		newitem += '	<td><input type="text" name="position_tag[' + items_positions + ']" style="width:220px;" /></td>';
		newitem += '	<td><input type="text" name="position_name[' + items_positions + ']" style="width:220px;" /></td>';
		newitem += '	<td><input type="text" name="position_name_vi[' + items_positions + ']" style="width:220px;" /></td>';
		newitem += '</tr>';
		newitem += '</tbody>';
		$("#additem_position").append(newitem);
	}
</script>
<!-- END: main -->
