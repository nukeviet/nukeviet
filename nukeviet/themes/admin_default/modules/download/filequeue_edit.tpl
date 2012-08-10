<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:250px">
                    {LANG.file_title}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.title}" name="title" id="title" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.category_cat_parent}
                </td>
                <td style="white-space: nowrap">
                    <select name="catid">
                        <!-- BEGIN: catid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: catid -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.file_author_name}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.author_name}" name="author_name" id="author_name" style="width:300px" maxlength="100" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.file_author_email}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.author_email}" name="author_email" id="author_email" style="width:300px" maxlength="60" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.file_author_homepage}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.author_url}" name="author_url" id="author_url" style="width:300px" maxlength="255" />
                    <input type="button" value="{LANG.file_checkUrl}" id="check_author_url" onclick="nv_checkfile('author_url',0, 'check_author_url');" />
                    <input type="button" value="{LANG.file_gourl}" id="go_author_url" onclick="nv_gourl('author_url',0, 'go_author_url');" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td style="vertical-align:top">
                    {LANG.file_myfile}
                </td>
                <td style="white-space: nowrap">
                    <!-- BEGIN: fileupload -->
                    <input class="txt" value="{FILEUPLOAD.value}" name="fileupload[]" id="fileupload{FILEUPLOAD.key}" style="width:300px" maxlength="255" disabled="disabled" />
                    <input type="button" value="{LANG.file_checkUrl}" id= "check_fileupload{FILEUPLOAD.key}" onclick="nv_checkfile('fileupload{FILEUPLOAD.key}',1, 'check_fileupload{FILEUPLOAD.key}');" />
                    <input type="button" value="{LANG.file_gourl}" id= "go_fileupload{FILEUPLOAD.key}" onclick="nv_gourl('fileupload{FILEUPLOAD.key}',1, 'go_fileupload{FILEUPLOAD.key}');" /><br />
                    <!-- END: fileupload -->
                    <div id="fileupload2_items">
                        <!-- BEGIN: if_fileupload -->
                        <div style="padding-top:10px">
                            {LANG.add_fileupload}:
                        </div>
                        <!-- END: if_fileupload -->
                        <!-- BEGIN: fileupload2 -->
                        <input readonly="readonly" class="txt" value="{FILEUPLOAD2.value}" name="fileupload2[]" id="fileupload2_{FILEUPLOAD2.key}" style="width:300px" maxlength="255" />&nbsp;
						<input type="button" value="{LANG.file_selectfile}" name="selectfile" onclick="nv_open_browse_file( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload2_{FILEUPLOAD2.key}&path={FILES_DIR}&type=file', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' );return false;" />&nbsp;
						<input type="button" value="{LANG.file_checkUrl}" id= "check_fileupload2_{FILEUPLOAD2.key}" onclick="nv_checkfile('fileupload2_{FILEUPLOAD2.key}',1, 'check_fileupload2_{FILEUPLOAD2.key}');" />&nbsp;
						<input type="button" value="{LANG.file_gourl}" id= "go_fileupload2_{FILEUPLOAD2.key}" onclick="nv_gourl('fileupload2_{FILEUPLOAD2.key}',1, 'go_fileupload2_{FILEUPLOAD2.key}');" /><br />
                        <!-- END: fileupload2 -->
                    </div>
                    <script type="text/javascript">
                    var file_items={DATA.fileupload2_num};
                    var file_selectfile='{LANG.file_selectfile}';
                    var nv_base_adminurl = '{NV_BASE_ADMINURL}';
                    var file_dir='{FILES_DIR}';
                    var file_checkUrl='{LANG.file_checkUrl}';
                    var file_gourl='{LANG.file_gourl}';
                    </script>
                    <input type="button" value="{LANG.add_file_items}" onclick="nv_file_additem2();" /> ({LANG.add_file_items_note})
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="vertical-align:top">
                    {LANG.file_linkdirect}<br />
                    (<em>{LANG.file_linkdirect_note}</em>)
                </td>
                <td style="white-space: nowrap">
                    <div id="linkdirect_items">
                    <!-- BEGIN: linkdirect -->
                    <textarea name="linkdirect[]" id="linkdirect{LINKDIRECT.key}" style="width:300px;height:150px">{LINKDIRECT.value}</textarea>&nbsp;<input type="button" value="{LANG.file_checkUrl}" id="check_linkdirect{LINKDIRECT.key}" onclick="nv_checkfile('linkdirect{LINKDIRECT.key}',0, 'check_linkdirect{LINKDIRECT.key}');" /><br />
                    <!-- END: linkdirect -->
                    </div>
                    <script type="text/javascript">
                    var linkdirect_items={DATA.linkdirect_num};
                    </script>
                    <input type="button" value="{LANG.add_linkdirect_items}" onclick="nv_linkdirect_additem();" /> ({LANG.add_linkdirect_items_note})
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.file_size}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.filesize}" name="filesize" id="filesize" style="width:100px" maxlength="11" /> {LANG.config_maxfilebyte}
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.file_version}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.version}" name="version" id="version" style="width:300px" maxlength="20" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td style="vertical-align:top">
                    {LANG.file_image}
                </td>
                <td style="white-space: nowrap">
                    <!-- BEGIN: fileimage -->
                    <input class="txt" value="{DATA.fileimage}" name="fileimage" id="fileimage" style="width:300px" maxlength="255" disabled="disabled" />
                    <input type="button" value="{LANG.file_checkUrl}" id= "check_fileimage" onclick="nv_checkfile('fileimage',1, 'check_fileimage');" />
                    <input type="button" value="{LANG.file_gourl}" id= "go_fileimage" onclick="nv_gourl('fileimage',1, 'go_fileimage');" /><br />
                    <!-- BEGIN: if_fileimage -->
                    <div style="padding-top:10px">
                        {LANG.add_new_img}:
                    </div>
                    <!-- END: if_fileimage -->
                    <!-- END: fileimage -->
                    <input class="txt" value="{DATA.fileimage2}" name="fileimage2" id="fileimage2" style="width:300px" maxlength="255" />
                    <input type="button" value="{LANG.file_selectfile}" name="selectimg" />
                    <input type="button" value="{LANG.file_checkUrl}" id="check_fileimage2" onclick="nv_checkfile('fileimage2',1, 'check_fileimage2');" />
                    <input type="button" value="{LANG.file_gourl}" id= "go_fileimage2" onclick="nv_gourl('fileimage2',1, 'go_fileimage2');" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="vertical-align: top">
                    {LANG.intro_title}
                </td>
                <td style="white-space: nowrap">
                    <textarea name="introtext" style="width:300px;height:150px">{DATA.introtext}</textarea>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.file_copyright}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" value="{DATA.copyright}" name="copyright" id="copyright" style="width:300px" maxlength="20" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.file_allowcomment}
                </td>
                <td style="white-space: nowrap">
                    <input name="comment_allow" value="1" type="checkbox"{DATA.comment_allow} />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.file_whocomment}
                </td>
                <td style="white-space: nowrap">
                    <select name="who_comment">
                        <!-- BEGIN: who_comment -->
                        <option value="{WHO_COMMENT.key}"{WHO_COMMENT.selected}> {WHO_COMMENT.title}</option>
                        <!-- END: who_comment -->
                    </select>
                </td>
            </tr>
        </tbody>
        <!-- BEGIN: group_empty -->
        <tbody>
            <tr>
                <td>{LANG.groups_upload}</td>
                <td style="white-space: nowrap">
                    <!-- BEGIN: groups_comment -->
                    <input name="groups_comment[]" value="{GROUPS_COMMENT.key}" type="checkbox"{GROUPS_COMMENT.checked} /> {GROUPS_COMMENT.title}<br />
                    <!-- END: groups_comment -->
                </td>
            </tr>
        </tbody>
        <!-- END: group_empty -->
        <tbody class="second">
            <tr>
                <td style="vertical-align:top">
                    {LANG.who_view}
                </td>
                <td>
                    <select name="who_view">
                        <!-- BEGIN: who_view -->
                        <option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
                        <!-- END: who_view -->
                    </select>
                </td>
            </tr>
        </tbody>
        <!-- BEGIN: group_empty_view -->
        <tbody>
            <tr>
                <td>{LANG.groups_upload}</td>
                <td style="white-space: nowrap">
                    <!-- BEGIN: groups_view -->
                    <input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}<br />
                    <!-- END: groups_view -->
                </td>
            </tr>
        </tbody>
        <!-- END: group_empty_view -->
        <tbody class="second">
            <tr>
                <td style="vertical-align:top">
                    {LANG.who_download}
                </td>
                <td>
                    <select name="who_download">
                        <!-- BEGIN: who_download -->
                        <option value="{WHO_DOWNLOAD.key}"{WHO_DOWNLOAD.selected}>{WHO_DOWNLOAD.title}</option>
                        <!-- END: who_download -->
                    </select>
                </td>
            </tr>
        </tbody>
        <!-- BEGIN: group_empty_download -->
        <tbody>
            <tr>
                <td>{LANG.groups_upload}</td>
                <td style="white-space: nowrap">
                    <!-- BEGIN: groups_download -->
                    <input name="groups_download[]" value="{GROUPS_DOWNLOAD.key}" type="checkbox"{GROUPS_DOWNLOAD.checked} /> {GROUPS_DOWNLOAD.title}<br />
                    <!-- END: groups_download -->
                </td>
            </tr>
        </tbody>
        <!-- END: group_empty_download -->
    </table>
    
    <div style="textarea-align:center;padding-top:15px">
        {LANG.file_description}<br />
        {DATA.description}
    </div>
    
    <div style="textarea-align:center;padding-top:15px">
        <input type="submit" name="submit" value="{LANG.confirm}" />
        <input type="button" value="{LANG.download_filequeue_del}" name="delfile" />
    </div>
</form>
<script type="text/javascript">
$( "input[name=selectimg]" ).click( function()
{
   nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=fileimage2&path={IMG_DIR}&type=image", "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
   return false;
}
 );
 $( "input[name=delfile]" ).click( function()
{
    nv_filequeue_del({DATA.id});
}
 );
</script>
<!-- END: main -->
