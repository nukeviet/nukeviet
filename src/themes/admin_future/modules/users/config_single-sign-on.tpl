<form class="form-inline" role="form" action="{$FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
                <tr>
                    <td class="text-center" colspan="3">
                        <input type="hidden" name="checkss" value="{$DATA.checkss}" />
                        <input type="hidden" name="save" value="1">
                        <input class="btn btn-primary w100" type="submit" value="{$LANG->getModule('save')}">
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <th colspan="3">{$LANG->getModule('cas_config')}</th>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_hostname')}</td>
                    <td><input name="cas_hostname" size="30" value="{$DATA.cas_hostname}" type="text"></td>
                    <td> {$LANG->getModule('cas_config_hostname_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_baseUri')}</td>
                    <td><input name="cas_baseuri" size="30" value="{$DATA.cas_baseuri}" type="text"></td>
                    <td>{$LANG->getModule('cas_config_baseUri_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_port')}</td>
                    <td><input name="cas_port" size="30" value="{$DATA.cas_port}" type="text"></td>
                    <td> {$LANG->getModule('cas_config_port_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_version')}</td>
                    <td>
                        <select name="cas_version">
                            {foreach from=['1.0', '2.0', '3.0'] item=value}
                            <option value="{$value}"{if $value eq $DATA.cas_version} selected{/if}>CAS {$value}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td> {$LANG->getModule('cas_config_version_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_language')}</td>
                    <td>
                        <select name="cas_language">
                            {foreach from=$CAS_LANGUAGES item=value}
                            <option value="{$value}"{if $value eq $DATA.cas_language} selected{/if}>{$value|replace:'CAS_Languages_':''}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td> {$LANG->getModule('cas_config_language_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_proxyMode')} </td>
                    <td>
                        <select name="cas_proxy">
                            <option value="0"{if 0 eq $DATA.cas_proxy} selected{/if}>{$LANG->getGlobal('no')}</option>
                            <option value="1"{if 1 eq $DATA.cas_proxy} selected{/if}>{$LANG->getGlobal('yes')}</option>
                        </select>
                    </td>
                    <td> {$LANG->getModule('cas_config_proxyMode_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_multiAuthentication')}</td>
                    <td>
                        <select name="cas_multiauth">
                            <option value="0"{if 0 eq $DATA.cas_multiauth} selected{/if}>{$LANG->getGlobal('no')}</option>
                            <option value="1"{if 1 eq $DATA.cas_multiauth} selected{/if}>{$LANG->getGlobal('yes')}</option>
                        </select>
                    </td>
                    <td> {$LANG->getModule('cas_config_multiAuthentication_info')}</td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_serverValidation')}:</td>
                    <td>
                        <select name="cas_certificate_check">
                            <option value="0"{if 0 eq $DATA.cas_certificate_check} selected{/if}>{$LANG->getGlobal('no')}</option>
                            <option value="1"{if 1 eq $DATA.cas_certificate_check} selected{/if}>{$LANG->getGlobal('yes')}</option>
                        </select>
                    </td>
                    <td> {$LANG->getModule('cas_config_serverValidation_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('cas_config_certificatePath')}</td>
                    <td><input name="cas_certificate_path" size="30" value="{$DATA.cas_certificate_path}" type="text"></td>
                    <td> {$LANG->getModule('cas_config_certificatePath_info')} </td>
                </tr>
                <tr>
                    <th colspan="3">{$LANG->getModule('ldap_config')}</th>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('ldap_config_hostURL')}</td>
                    <td><input name="ldap_host_url" size="30" value="{$DATA.ldap_host_url}" type="text"></td>
                    <td> {$LANG->getModule('ldap_config_hostURL_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('ldap_config_version')}</td>
                    <td>
                        <select name="ldap_version">
                            {foreach from=[2, 3] item=value}
                            <option value="{$value}"{if $value eq $DATA.ldap_version} selected{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td> {$LANG->getModule('ldap_config_version_info')} </td>
                </tr>
                <tr valign="top">
                    <td align="right">{$LANG->getModule('ldap_config_useTLS')}</td>
                    <td>
                        <select name="ldap_start_tls">
                            <option value="0"{if 0 eq $DATA.ldap_start_tls} selected{/if}>{$LANG->getGlobal('no')}</option>
                            <option value="1"{if 1 eq $DATA.ldap_start_tls} selected{/if}>{$LANG->getGlobal('yes')}</option>
                        </select>
                    </td>
                    <td> {$LANG->getModule('ldap_config_useTLS_info')}</td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('ldap_config_LDAPencoding')}</td>
                    <td><input name="ldap_encoding" value="{$DATA.ldap_encoding}" type="text"></td>
                    <td> {$LANG->getModule('ldap_config_LDAPencoding_info')}</td>
                </tr>
                <tr valign="top">
                    <td align="right">{$LANG->getModule('ldap_config_PageSize')}</td>
                    <td><input name="ldap_pagesize" value="{$DATA.ldap_pagesize}" type="text"></td>
                    <td> {$LANG->getModule('ldap_config_PageSize_info')} </td>
                </tr>
                <tr>
                    <th colspan="3">{$LANG->getModule('rb_config')}</th>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('rb_config_dn')}</td>
                    <td><input name="ldap_bind_dn" size="30" value="{$DATA.ldap_bind_dn}" type="text"></td>
                    <td> {$LANG->getModule('rb_config_dn_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('rb_config_pw')}</td>
                    <td><input name="ldap_bind_pw" size="30" value="{$DATA.ldap_bind_pw}" autocomplete="off" type="password">
                        <div id="bind_pwunmaskdiv" class="unmask"><input id="bind_pwunmask" name="ldap_bind_pwunmask" type="checkbox">
                            <label for="bind_pwunmask">{$LANG->getModule('show_password')}</label>
                        </div>
                    </td>
                    <td> {$LANG->getModule('rb_config_pw_info')} </td>
                </tr>
                <tr>
                    <th colspan="3">{$LANG->getModule('user_config')}</th>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_userType')}</td>
                    <td>
                        <select name="user_type">
                            {foreach from=$USERTYPE item=value}
                            <option value="{$value.value}"{if $value.value eq $DATA.user_type} selected{/if}>{$value.name}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td>{$LANG->getModule('user_config_userType_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_context')}</td>
                    <td><input name="user_contexts" size="30" value="{$DATA.user_contexts}" type="text"></td>
                    <td> {$LANG->getModule('user_config_context_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_searchSubcontexts')}</td>
                    <td>
                        <select name="user_search_sub">
                            <option value="0"{if 0 eq $DATA.user_search_sub} selected{/if}>{$LANG->getGlobal('no')}</option>
                            <option value="1"{if 1 eq $DATA.user_search_sub} selected{/if}>{$LANG->getGlobal('yes')}</option>
                        </select>
                    </td>
                    <td> {$LANG->getModule('user_config_searchSubcontexts_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_dereferenceAliases')}</td>
                    <td>
                        <select name="user_opt_deref">
                            <option value="0"{if 0 eq $DATA.user_opt_deref} selected{/if}>{$LANG->getGlobal('no')}</option>
                            <option value="1"{if 1 eq $DATA.user_opt_deref} selected{/if}>{$LANG->getGlobal('yes')}</option>
                        </select>
                    </td>
                    <td> {$LANG->getModule('user_config_dereferenceAliases_info')}</td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_userAttribute')}</td>
                    <td><input name="user_attribute" size="30" value="{$DATA.user_attribute}" type="text"></td>
                    <td>{$LANG->getModule('user_config_userAttribute_info')}</td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_memberAttribute')}</td>
                    <td><input name="member_attribute" size="30" value="{$DATA.member_attribute}" type="text"></td>
                    <td>{$LANG->getModule('user_config_memberAttribute_info')} </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_memberAttributeUsesDn')}</td>
                    <td><input name="member_attribute_isdn" size="30" value="{$DATA.member_attribute_isdn}" type="text"></td>
                    <td> {$LANG->getModule('user_config_memberAttributeUsesDn_info')}</td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('user_config_objectClass')}</td>
                    <td><input name="user_objectclass" size="30" value="{$DATA.user_objectclass}" type="text"></td>
                    <td> {$LANG->getModule('user_config_objectClass_info')} </td>
                </tr>
                <tr>
                    <th colspan="3">{$LANG->getModule('update_LDAP_config')}</th>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('email')}</td>
                    <td><input name="config_field[email]" size="30" value="{$DATA.config_field.email}" type="text"></td>
                    <td> {$LANG->getModule('update_field')}&nbsp;
                        <select name="config_field_lock[email]">
                            <option value="oncreate" {$FIELD_LOCK.email.oncreate}>{$LANG->getModule('update_field_oncreate')}</option>
                            <option value="onlogin" {$FIELD_LOCK.email.onlogin}>{$LANG->getModule('update_field_onlogin')}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">{$LANG->getModule('show_email')}</td>
                    <td><input name="config_field[show_email]" size="30" value="{$DATA.config_field.show_email}" type="text"></td>
                    <td> {$LANG->getModule('update_field')}&nbsp;
                        <select name="config_field_lock[show_email]">
                            <option value="oncreate" {$FIELD_LOCK.show_email.oncreate}>{$LANG->getModule('update_field_oncreate')}</option>
                            <option value="onlogin" {$FIELD_LOCK.show_email.onlogin}>{$LANG->getModule('update_field_onlogin')}</option>
                        </select>
                    </td>
                </tr>
                {foreach from=$FIELDS item=field}
                <tr>
                    <td align="right">{$field.lang}</td>
                    <td><input name="config_field[{$field.field}]" size="30" value="{$field.value}" type="text"></td>
                    <td> {$LANG->getModule('update_field')}&nbsp;
                        <select name="config_field_lock[{$field.field}]">
                            <option value="oncreate" {$field.oncreate}>{$LANG->getModule('update_field_oncreate')}</option>
                            <option value="onlogin" {$field.onlogin}>{$LANG->getModule('update_field_onlogin')}</option>
                        </select>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</form>
<div class="alert alert-info">
    {$LANG->getModule('ldap_info')}
</div>
