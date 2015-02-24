<!-- BEGIN: main -->
<div class="alert alert-info">
	Chức năng này đang được xây dựng nên một số cấu hình không hoat động
</div>
<form  class="form-inline" role="form" action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td class="text-center" colspan="3"><input class="btn btn-primary w100" type="submit" value="{LANG.save}" name="submit"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<th colspan="3">Thiết lập máy chủ CAS</th>
				</tr>
				<tr>
					<td align="right">Hostname:</td>
					<td><input name="cas_hostname" size="30" value="{DATA.cas_hostname}" type="text"></td>
					<td> Hostname of the CAS server
					<br>
					eg: cas.nukeviet.vn </td>
				</tr>
				<tr>
					<td align="right">Base URI:</td>
					<td><input name="cas_baseuri" size="30" value="{DATA.cas_baseuri}" type="text"></td>
					<td> URI of the server (nothing if no baseUri)
					<br>
					For example, if the CAS server responds to cas.nukeviet.vn/cas/ then
					<br>
					cas_baseuri = cas/ </td>
				</tr>
				<tr>
					<td align="right">Port:</td>
					<td><input name="cas_port" size="30" value="{DATA.cas_port}" type="text"></td>
					<td> Port of the CAS server </td>
				</tr>
				<tr>
					<td align="right">CAS protocol version:</td>
					<td>
					<select name="cas_version">
						<option value="1.0">CAS 1.0</option>
						<option selected="selected" value="2.0">CAS 2.0</option>
					</select></td>
					<td> CAS protocol version to use </td>
				</tr>
				<tr>
					<td align="right">Language:</td>
					<td>
					<select name="cas_language">
						<option selected="selected" value="CAS_Languages_English">English</option>
						<option value="CAS_Languages_French">French</option>
						<option value="CAS_Languages_Greek">Greek</option>
						<option value="CAS_Languages_German">German</option>
						<option value="CAS_Languages_Japanese">Japanese</option>
						<option value="CAS_Languages_Spanish">Spanish</option>
						<option value="CAS_Languages_Catalan">Catalan</option>
					</select></td>
					<td> Select language for authentication pages </td>
				</tr>
				<tr>
					<td align="right">Proxy mode: </td>
					<td>
					<select name="cas_proxy">
						<option selected="selected" value="0">Không</option>
						<option value="1">Có</option>
					</select></td>
					<td> Select 'yes' if you use CAS in proxy-mode </td>
				</tr>

				<tr>
					<td align="right">Multi-authentication:</td>
					<td>
					<select name="cas_multiauth">
						<option value="0">Không</option>
						<option selected="selected" value="1">Có</option>
					</select></td>
					<td> Select 'yes' if you want to have multi-authentication (CAS + other authentication) </td>
				</tr>
				<tr>
					<td align="right">Server validation:</td>
					<td>
					<select name="cas_certificate_check">
						<option selected="selected" value="0">Không</option>
						<option value="1">Có</option>
					</select></td>
					<td> Select 'yes' if you want to validate the server certificate </td>
				</tr>
				<tr>
					<td align="right">Certificate path:</td>
					<td><input name="cas_certificate_path" size="30" value="{DATA.cas_certificate_path}" type="text"></td>
					<td> Path of the CA chain file (PEM Format) to validate the server certificate </td>
				</tr>
				<tr>
					<th colspan="3">Các thiết lập máy chủ LDAP</th>
				</tr>
				<tr>
					<td align="right">Host URL</td>
					<td><input name="ldap_host_url" size="30" value="{DATA.ldap_host_url}" type="text"></td>
					<td> Chỉ ra máy chủ LDAP trong biểu mẫu URL giống như 'ldap://ldap.nukeviet.vn/' hoặc 'ldaps://ldap.nukeviet.vn/'. </td>
				</tr>
				<tr>
					<td align="right">Version</td>
					<td>
					<select name="ldap_version">
						<option value="2">2</option>
						<option selected="selected" value="3">3</option>
					</select></td>
					<td> Phiên bản của LDAP giao thức máy chủ của bạn đang được sử dụng. </td>
				</tr>
				<tr valign="top">
					<td align="right">Use TLS</td>
					<td>
					<select name="ldap_start_tls">
						<option selected="selected" value="0">Không</option>
						<option value="1">Có</option>
					</select></td>
					<td> Use regular LDAP service (port 389) with TLS encryption </td>
				</tr>
				<tr>
					<td align="right">LDAP encoding</td>
					<td><input name="ldap_encoding" value="{DATA.ldap_encoding}" type="text"></td>
					<td> Specify encoding used by LDAP server. Most probably utf-8, MS AD v2 uses default platform encoding such as cp1252, cp1250, etc. </td>
				</tr>
				<tr valign="top">
					<td align="right">Page Size</td>
					<td><input name="ldap_pagesize" value="{DATA.ldap_pagesize}" type="text"></td>
					<td> Make sure this value is smaller than your LDAP server result set size limit (the maximum number of entries that can be returned in a single query) </td>
				</tr>
				<tr>
					<th colspan="3">Các thiết lập ràng buộc</th>
				</tr>
				<tr>
					<td align="right">Distinguished name</td>
					<td><input name="ldap_bind_dn" size="30" value="{DATA.ldap_bind_dn}" type="text"></td>
					<td> Nếu bạn muốn sử dụng ràng buộc người dùng để tìm kiếm các người dùng, chỉ ra nó ở đây. Đôi khi nó giống như 'cn=ldapuser,ou=public,o=org' </td>
				</tr>
				<tr>
					<td align="right">Password</td>
					<td><input name="ldap_bind_pw" size="30" value="{DATA.ldap_bind_pw}" autocomplete="off" type="password">
					<div id="bind_pwunmaskdiv" class="unmask"><input id="bind_pwunmask" name="ldap_bind_pwunmask" type="checkbox">
						<label for="bind_pwunmask">Hiện lên</label>
					</div></td>
					<td> Mật khẩu đối với ràng buộc người dùng . </td>
				</tr>
				<tr>
					<th colspan="3">Các thiết lập tra cứu người dùng</th>
				</tr>
				<tr>
					<td align="right">User type</td>
					<td>
					<select name="user_type">
						<option selected="selected" value="default">Mặc định</option>
						<option value="edir">Novell Edirectory</option>
						<option value="rfc2307">posixAccount (rfc2307)</option>
						<option value="rfc2307bis">posixAccount (rfc2307bis)</option>
						<option value="samba">sambaSamAccount (v.3.0.7)</option>
						<option value="ad">MS ActiveDirectory</option>
					</select></td>
					<td> Chọn những người dùng thế nào được lưu trữ trong LDAP. Các thiết lập này cũng chỉ ra sự vô hiệu hoá đăng nhập như thế nào, tạo người dùng và các cuộc đăng nhập sẽ hoạt động như thế nào. </td>
				</tr>
				<tr>
					<td align="right">Contexts</td>
					<td><input name="user_contexts" size="30" value="{DATA.user_contexts}" type="text"></td>
					<td> Danh sách các ngữ cảnh mà ở đó những người sử dụng được xác định. Ngăn cách các ngữ cảnh khác nhau bởi dấu ';'. Ví dụ : 'ou=people,dc=nukeviet,dc=vn' </td>
				</tr>
				<tr>
					<td align="right">Search subcontexts</td>
					<td>
					<select name="user_search_sub">
						<option selected="selected" value="0">Không</option>
						<option value="1">Có</option>
					</select></td>
					<td> Đặt giá trị khác 0. Nếu bạn muốn tìm kiếm người dùng từ ngữ cảnh phụ. </td>
				</tr>
				<tr>
					<td align="right">Dereference aliases</td>
					<td>
					<select name="user_opt_deref">
						<option selected="selected" value="0">Không</option>
						<option value="3">Có</option>
					</select></td>
					<td> Quyết định bao nhiêu bí danh được sử dụng trong quá trình tìm kiếm. Chọn một cái trong số các giá trị sau: "Không" (LDAP_DEREF_NEVER) hoặc "Có" (LDAP_DEREF_ALWAYS) </td>
				</tr>
				<tr>
					<td align="right">User attribute</td>
					<td><input name="user_attribute" size="30" value="{DATA.user_attribute}" type="text"></td>
					<td> Các tuỳ chọn: Ghi đè thuộc tính sử dụng để chỉ ra/tìm kiếm người dùng. Thông thường 'cn'. </td>
				</tr>
				<tr>
					<td align="right">Member attribute</td>
					<td><input name="member_attribute" size="30" value="{DATA.member_attribute}" type="text"></td>
					<td> Tuỳ chọn: Ghi đè thuộc tính về người dùng, khi những người dùng có liên quan tới một nhóm. Thông thường là 'thành viên' </td>
				</tr>
				<tr>
					<td align="right">Member attribute uses dn</td>
					<td><input name="member_attribute_isdn" size="30" value="{DATA.member_attribute_isdn}" type="text"></td>
					<td> Optional: Overrides handling of member attribute values, either 0 or 1 </td>
				</tr>
				<tr>
					<td align="right">Object class</td>
					<td><input name="user_objectclass" size="30" value="{DATA.user_objectclass}" type="text"></td>
					<td> Tuỳ chọn: Ghi đè lớp đối tượng sử dụng để chỉ định/tìm kiếm người dùng trên kiểu người dùng ldap_user_type. Thông thường bạn không cần thay đổi điều này. </td>
				</tr>
				<tr>
					<th colspan="3">Cập nhật dữ liệu từ LDAP xuống website</th>
				</tr>
				<tr valign="top">
					<td align="right">Tên đệm và tên</td><td><input name="config_field[firstname]" size="30" value="{DATA.config_field.firstname}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[firstname]">
						<option value="oncreate" {FIELD_LOCK.firstname.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.firstname.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">Họ</td><td><input name="config_field[lastname]" size="30" value="{DATA.config_field.lastname}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[lastname]">
						<option value="oncreate" {FIELD_LOCK.lastname.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.lastname.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.email}</td><td><input name="config_field[email]" size="30" value="{DATA.config_field.email}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[email]">
						<option value="oncreate" {FIELD_LOCK.email.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.email.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.question}</td><td><input name="config_field[question]" size="30" value="{DATA.config_field.question}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[question]">
						<option value="oncreate" {FIELD_LOCK.question.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.question.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.answer}</td><td><input name="config_field[answer]" size="30" value="{DATA.config_field.answer}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[email]">
						<option value="oncreate" {FIELD_LOCK.answer.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.answer.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.gender}</td><td><input name="config_field[gender]" size="30" value="{DATA.config_field.gender}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[gender]">
						<option value="oncreate" {FIELD_LOCK.gender.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.gender.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.birthday}</td><td><input name="config_field[birthday]" size="30" value="{DATA.config_field.birthday}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[birthday]">
						<option value="oncreate" {FIELD_LOCK.birthday.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.birthday.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.show_email}</td><td><input name="config_field[show_email]" size="30" value="{DATA.config_field.show_email}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[show_email]">
						<option value="oncreate" {FIELD_LOCK.show_email.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.show_email.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.sig}</td><td><input name="config_field[sig]" size="30" value="{DATA.config_field.sig}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[sig]">
						<option value="oncreate" {FIELD_LOCK.sig.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.sig.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<!-- BEGIN: field -->
				<tr>
					<td align="right">{FIELD.lang}</td><td><input name="config_field[{FIELD.field}]" size="30" value="{FIELD.value}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[{FIELD.field}]">
						<option value="oncreate" {FIELD.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<!-- END: field -->
			</tbody>
		</table>
	</div>
</form>
<div class="alert alert-info">
	Cập nhật dữ liệu từ LDAP xuống website là tuỳ chọn. Bạn có thể chọn điền trước một số thông tin người dùng NukeViet với thông tin từ <b> các trường LDAP</b> được chỉ ra ở đây.
	<p>
		Nếu bạn để các trường này trống, thì không có cái gì được chuyển đổi từ LDAP và các giá trị mặc định của NukeViet sẽ được sử dụng để thay thế
	</p>
	<p>
		Trong trường hợp khác, người dùng sẽ có khả năng soạn thảo tất cả các trường này sau khi chúng bắt dầu.
	</p>
	<p>
		<b>Cập nhật site:</b> Nếu được kích hoạt, mục sẽ được cập nhật (từ xác thực ngoài) mỗi khi người dùng đăng nhập hoặc có đồng bộ hóa người dùng.
	</p>
	<hr>
	<p>
		<b>Chú ý:</b> Cập nhật dư liệu LDAP bên ngoại yêu cầu bạn thiết đặt binddn và bindpw cho một người dùng bind có quyền chỉnh sửa tất cả bản ghi người dùng. Hiện tại nó không lưu giữ các thuộc tính đa trị, và sẽ xóa các giá trị gia tăng khi cập nhật.
	</p>
</div>
<!-- END: main -->