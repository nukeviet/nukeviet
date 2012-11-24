<!-- BEGIN: main -->
<div id="users">
    <form action="{FORM_ACTION}" method="post">
        <table class="tab1">
            <tbody>
                <tr>
                    <td width="260">{LANG.config_is_addfile}</td>
                    <td>
                        <input name="is_addfile" value="1" type="checkbox"{DATA.is_addfile} />
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.config_whoaddfile}</td>
                    <td>
                        <select name="who_addfile">
                            <!-- BEGIN: who_addfile -->
                            <option value="{WHO_ADDFILE.key}"{WHO_ADDFILE.selected}> {WHO_ADDFILE.title}</option>
                            <!-- END: who_addfile -->
                        </select>
                        <!-- BEGIN: group3 -->
                        <br />
                        {LANG.groups_upload}<br />
                        <!-- BEGIN: groups_addfile -->
                        <input name="groups_addfile[]" value="{GROUPS_ADDFILE.key}" type="checkbox"{GROUPS_ADDFILE.checked} /> {GROUPS_ADDFILE.title}<br />
                        <!-- END: groups_addfile -->
                        <!-- END: group3 -->
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.config_is_uploadfile}</td>
                    <td>
                        <input name="is_upload" value="1" type="checkbox"{DATA.is_upload} />
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.config_whouploadfile}</td>
                    <td>
                        <select name="who_upload">
                            <!-- BEGIN: who_upload -->
                            <option value="{WHO_UPLOAD.key}"{WHO_UPLOAD.selected}> {WHO_UPLOAD.title}</option>
                            <!-- END: who_upload -->
                        </select>
                        <!-- BEGIN: group_empty -->
                        <br />
                        {LANG.groups_upload}<br />
                        <!-- BEGIN: groups_upload -->
                        <input name="groups_upload[]" value="{GROUPS_UPLOAD.key}" type="checkbox"{GROUPS_UPLOAD.checked} /> {GROUPS_UPLOAD.title}<br />
                        <!-- END: groups_upload -->
                        <!-- END: group_empty -->
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td valign="top">{LANG.config_allowfiletype}</td>
                    <td>
                        <!-- BEGIN: upload_filetype -->
                        <input name="upload_filetype[]" value="{UPLOAD_FILETYPE.ext}" type="checkbox"{UPLOAD_FILETYPE.checked} /> {UPLOAD_FILETYPE.title}<br />
                        <!-- END: upload_filetype -->
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.config_maxfilesize}</td>
                    <td>
                        <input name="maxfilesize" value="{DATA.maxfilesize}" type="text" maxlength="10" /> {LANG.config_maxfilebyte}<br />
                        {LANG.config_maxfilesizesys} {NV_UPLOAD_MAX_FILESIZE} {LANG.config_maxfilebyte}
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.config_uploadedfolder}</td>
                    <td>
                        <input name="upload_dir" value="{DATA.upload_dir}" type="text" maxlength="100" />
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.config_queuefolder}</td>
                    <td>
                        <input name="temp_dir" value="{DATA.temp_dir}" type="text" maxlength="100" />
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.file_who_autocomment}</td>
                    <td>
                        <select name="who_autocomment">
                            <!-- BEGIN: who_autocomment -->
                            <option value="{WHO_AUTOCOMMENT.key}"{WHO_AUTOCOMMENT.selected}> {WHO_AUTOCOMMENT.title}</option>
                            <!-- END: who_autocomment -->
                        </select>
                        <!-- BEGIN: group2 -->
                        <br />
                        {LANG.groups_upload}<br />
                        <!-- BEGIN: groups_autocomment -->
                        <input name="groups_autocomment[]" value="{GROUPS_AUTOCOMMENT.key}" type="checkbox"{GROUPS_AUTOCOMMENT.checked} /> {GROUPS_AUTOCOMMENT.title}<br />
                        <!-- END: groups_autocomment -->
                        <!-- END: group2 -->
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.is_resume}</td>
                    <td>
                        <input name="is_resume" value="1" type="checkbox"{DATA.is_resume} />
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.max_speed}</td>
                    <td>
                        <input name="max_speed" value="{DATA.max_speed}" type="text" style="width:50px" maxlength="4" /> {LANG.kb_sec}
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.is_zip}</td>
                    <td>
                        <input name="is_zip" value="1" type="checkbox"{DATA.is_zip} />
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td style="vertical-align: top">{LANG.zip_readme}</td>
                    <td>
                        <textarea name="readme" cols="20" rows="2" style="width: 100%; height: 300px; overflow: auto">{DATA.readme}</textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="textarea-align:center;padding-top:15px">
            <input type="submit" name="submit" value="{LANG.config_confirm}" />
        </div>
    </form>
</div><!-- END: main -->
