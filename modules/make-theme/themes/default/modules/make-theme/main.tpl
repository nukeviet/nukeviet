<!-- BEGIN: main -->
Để có thể chuyển <b>html</b> sang giao diện của <b>NukeViet 4</b> bạn hãy  bố trí file html như sau
<div style="margin: 20px;">
	<p>
		css <span style="color: red">(*)</span>
	</p>
	<p>
		images <span style="color: red">(*)</span>
	</p>
	<p>
		uploads
	</p>
	<p>
		js
	</p>
	<p>
		index.html <span style="color: red">(*)</span>
	</p>
	<p>
		view.jpg
	</p>
</div>
<b>Trong đó thư mục</b>
<p style="margin-top: 10px;">
	- images: Chứa những ảnh có trong file css, những file bắt buộc cần có trong theme
</p>
<p>
	- uploads: sẽ chứa những file, sẽ bị thay thế trong quá trình người sử dụng (Mục đích để xóa đi sau này không sinh file thừa trong giao diện)
</p>
<p>
	- css: Chứa các file css
</p>
<p>
	- index.html file html của giao diện
</p>
<p>
	- view.jpg ảnh minh họa của giao diện, kích thước 300x145, Nếu lớn hơn sẽ bị resize về kích thước này.
</p>

<b>Chỉnh sửa file index.html</b>
<br>
<br>
<p>
	1) Hãy thêm lên đầu khối (Để tách ra file header.tpl, footer.tpl)
</p>
<p>
	&lt;!-- begin nv_body --&gt;
</p>
<p>
	Cuối khối
</p>
<p>
	&lt;!-- end nv_body --&gt;
</p>

<br>
<br>
<p>
	2) Hãy thêm lên đầu khối (Thay thế nội dung của moudle)
</p>
<p>
	&lt;!-- begin nv_content --&gt;
</p>
<p>
	Cuối khối
</p>
<p>
	&lt;!-- end nv_content --&gt;
</p>

<br>
<br>
<p>
	3) Hãy thêm lên đầu khối cần tách block bằng khối (Có thể có nhiều khối, nhưng các khối block không lồng nhau)
</p>
<p>
	&lt;!-- begin block:tên khối --&gt;
</p>
<p>
	Cuối khối
</p>
<p>
	&lt;!-- end block:tên khối --&gt;
</p>
<p>
	Ghi chú: tên khối dùng tiếng việt không dấu hoặc tiếng anh, có thể sử dụng dấu cách, không dùng ký tự đặc biệt, tên các khối không trùng nhau
</p>
<b>Sau đó Nén .ZIP các file này lại Upload hệ site, hệ thống sẽ trả về cấu trúc giao diện: </b>
<form action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}" method="post"  enctype="multipart/form-data">
	<table class="tab1">
		<tbody>
			<tr>
				<td>Chọn file upload:</td>
				<td><input type="file" name="zipfile"></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input name="submit" type="submit" value="Thực hiện" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->
