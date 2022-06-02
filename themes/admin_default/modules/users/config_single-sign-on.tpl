<!-- BEGIN: main -->
<form  class="form-inline" role="form" action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td class="text-center" colspan="3"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input type="hidden" name="save" value="1"><input class="btn btn-primary w100" type="submit" value="{LANG.save}"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<th colspan="3">{LANG.cas_config}</th>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_hostname}</td>
					<td><input name="cas_hostname" size="30" value="{DATA.cas_hostname}" type="text"></td>
					<td> {LANG.cas_config_hostname_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_baseUri}</td>
					<td><input name="cas_baseuri" size="30" value="{DATA.cas_baseuri}" type="text"></td>
					<td>{LANG.cas_config_baseUri_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_port}</td>
					<td><input name="cas_port" size="30" value="{DATA.cas_port}" type="text"></td>
					<td> {LANG.cas_config_port_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_version}</td>
					<td>
					<select name="cas_version">
						<!-- BEGIN: version -->
						<option {VERSION.select} value="{VERSION.value}">{VERSION.name}</option>
						<!-- END: version -->

					</select></td>
					<td> {LANG.cas_config_version_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_language}</td>
					<td>
					<select name="cas_language">
						<!-- BEGIN: language -->
						<option {LANGUAGE.select} value="{LANGUAGE.value}">{LANGUAGE.name}</option>
						<!-- END: language -->
					</select></td>
					<td> {LANG.cas_config_language_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_proxyMode} </td>
					<td>
					<select name="cas_proxy">
						<!-- BEGIN: proxy -->
						<option {PROXY.select} value="{PROXY.value}">{PROXY.name}</option>

						<!-- END: proxy -->
					</select></td>
					<td> {LANG.cas_config_proxyMode_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_multiAuthentication}</td>
					<td>
					<select name="cas_multiauth">
						<!-- BEGIN: multiauth -->
						<option {MULTIAUTH.select} value="{MULTIAUTH.value}">{MULTIAUTH.name}</option>
						<!-- END: multiauth -->
					</select></td>
					<td> {LANG.cas_config_multiAuthentication_info}</td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_serverValidation}:</td>
					<td>
					<select name="cas_certificate_check">
						<!-- BEGIN: cas_certificate_check -->
						<option {CERTIFICATE.select} value="{CERTIFICATE.value}">{CERTIFICATE.name}</option>
						<!-- END: cas_certificate_check -->
					</select></td>
					<td> {LANG.cas_config_serverValidation_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.cas_config_certificatePath}</td>
					<td><input name="cas_certificate_path" size="30" value="{DATA.cas_certificate_path}" type="text"></td>
					<td> {LANG.cas_config_certificatePath_info} </td>
				</tr>
				<tr>
					<th colspan="3">{LANG.ldap_config}</th>
				</tr>
				<tr>
					<td align="right">{LANG.ldap_config_hostURL}</td>
					<td><input name="ldap_host_url" size="30" value="{DATA.ldap_host_url}" type="text"></td>
					<td> {LANG.ldap_config_hostURL_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.ldap_config_version}</td>
					<td>
					<select name="ldap_version">
						<!-- BEGIN: ldap_version -->
						<option {LDAPVERSION.select} value="{LDAPVERSION.value}">{LDAPVERSION.value}</option>
						<!-- END: ldap_version -->
					</select></td>
					<td> {LANG.ldap_config_version_info} </td>
				</tr>
				<tr valign="top">
					<td align="right">{LANG.ldap_config_useTLS}</td>
					<td>
					<select name="ldap_start_tls">
						<!-- BEGIN: ldap_start_tls -->
						<option {START_TLS.select} value="{START_TLS.value}">{START_TLS.name}</option>
						<!-- END: ldap_start_tls -->
					</select></td>
					<td> {LANG.ldap_config_useTLS_info}</td>
				</tr>
				<tr>
					<td align="right">{LANG.ldap_config_LDAPencoding}</td>
					<td><input name="ldap_encoding" value="{DATA.ldap_encoding}" type="text"></td>
					<td> {LANG.ldap_config_LDAPencoding_info}</td>
				</tr>
				<tr valign="top">
					<td align="right">{LANG.ldap_config_PageSize}</td>
					<td><input name="ldap_pagesize" value="{DATA.ldap_pagesize}" type="text"></td>
					<td> {LANG.ldap_config_PageSize_info} </td>
				</tr>
				<tr>
					<th colspan="3">{LANG.rb_config}</th>
				</tr>
				<tr>
					<td align="right">{LANG.rb_config_dn}</td>
					<td><input name="ldap_bind_dn" size="30" value="{DATA.ldap_bind_dn}" type="text"></td>
					<td> {LANG.rb_config_dn_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.rb_config_pw}</td>
					<td><input name="ldap_bind_pw" size="30" value="{DATA.ldap_bind_pw}" autocomplete="off" type="password">
					<div id="bind_pwunmaskdiv" class="unmask"><input id="bind_pwunmask" name="ldap_bind_pwunmask" type="checkbox">
						<label for="bind_pwunmask">{LANG.show_password}</label>
					</div></td>
					<td> {LANG.rb_config_pw_info} </td>
				</tr>
				<tr>
					<th colspan="3">{LANG.user_config}</th>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_userType}</td>
					<td>
					<select name="user_type">
						<!-- BEGIN: user_type -->
						<option {USERTYPE.select} value="{USERTYPE.value}">{USERTYPE.name}</option>
						<!-- END: user_type -->
					</select></td>
					<td>{LANG.user_config_userType_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_context}</td>
					<td><input name="user_contexts" size="30" value="{DATA.user_contexts}" type="text"></td>
					<td> {LANG.user_config_context_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_searchSubcontexts}</td>
					<td>
					<select name="user_search_sub">
						<!-- BEGIN: user_search_sub -->
						<option {SEARCHSUB.select} value="{SEARCHSUB.value}">{SEARCHSUB.name}</option>
						<!-- END: user_search_sub -->
					</select></td>
					<td> {LANG.user_config_searchSubcontexts_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_dereferenceAliases}</td>
					<td>
					<select name="user_opt_deref">
						<!-- BEGIN: user_opt_deref -->
						<option {OPTDEREF.select} value="{OPTDEREF.value}">{OPTDEREF.name}</option>
						<!-- END: user_opt_deref -->
					</select></td>
					<td> {LANG.user_config_dereferenceAliases_info}</td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_userAttribute}</td>
					<td><input name="user_attribute" size="30" value="{DATA.user_attribute}" type="text"></td>
					<td>{LANG.user_config_userAttribute_info}</td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_memberAttribute}</td>
					<td><input name="member_attribute" size="30" value="{DATA.member_attribute}" type="text"></td>
					<td>{LANG.user_config_memberAttribute_info} </td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_memberAttributeUsesDn}</td>
					<td><input name="member_attribute_isdn" size="30" value="{DATA.member_attribute_isdn}" type="text"></td>
					<td> {LANG.user_config_memberAttributeUsesDn_info}</td>
				</tr>
				<tr>
					<td align="right">{LANG.user_config_objectClass}</td>
					<td><input name="user_objectclass" size="30" value="{DATA.user_objectclass}" type="text"></td>
					<td> {LANG.user_config_objectClass_info} </td>
				</tr>
				<tr>
					<th colspan="3">{LANG.update_LDAP_config}</th>
				</tr>
				<tr valign="top">
					<td align="right">{LANG.update_LDAP_config_name}</td>
					<td><input name="config_field[firstname]" size="30" value="{DATA.config_field.firstname}" type="text"></td>
					<td> {LANG.update_field}&nbsp;
					<select name="config_field_lock[firstname]">
						<option value="oncreate" {FIELD_LOCK.firstname.oncreate}>{LANG.update_field_oncreate}</option>
						<option value="onlogin" {FIELD_LOCK.firstname.onlogin}>{LANG.update_field_onlogin}</option>
					</select></td>
				</tr>
				<tr>
					<td align="right">{LANG.update_LDAP_config_lname}</td><td><input name="config_field[lastname]" size="30" value="{DATA.config_field.lastname}" type="text"></td>
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
					<select name="config_field_lock[answer]">
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
	{LANG.info}
</div>
<!-- END: main -->