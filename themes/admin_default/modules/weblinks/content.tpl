<!-- BEGIN: main -->
<!-- BEGIN: error --> 
<div class="quote" style="width:98%">
	<blockquote class="error"><span>{error}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error --> 
<div id="list_mods">
    <form action="" method="post">
        <input type="hidden" name ="id" value="{id}" />
        <table class="tab1">
            <tbody>
                <tr>
                    <td style="width: 150px;">{LANG.weblink_add_title} : </td>
                    <td><input type="text" name="title" style="width:400px" value="{DATA.title}"/></td>
                </tr>
            <tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.alias} : </td>
                    <td><input style="width:400px" name="alias" type="text" value="{DATA.alias}" maxlength="255" /></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.weblink_add_url} : </td>
                    <td><input style="width: 550px" name="url" id= "url" type="text" value="{DATA.url}" maxlength="255" /></td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.weblink_add_parent} : </td>
                    <td>
                        <select name="catid">
                        	<!-- BEGIN: loopcat -->
                        	<option value="{CAT.catid}" {CAT.sl}>{CAT.title}</option>
                            <!-- END: loopcat -->
                        </select>
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.weblink_add_image} : </td>
                    <td>
                        <input style="width:380px" type="text" name="image" id="image" value="{DATA.urlimg}"/>
                        <input type="button" value="Browse server" name="selectimg"/>           
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.weblink_description} :</td>
                    <td></td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td colspan="2">
                        {NV_EDITOR}
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.weblink_inhome} : </td>
                    <td><label><input name="status" type="checkbox" value="1" checked="{checked}" />{LANG.weblink_yes}</label></td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td align="center" colspan="2"><input name="submit" style="width:80px;margin-left:110px" type="submit" value="{LANG.weblink_submit}" /></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
	//<![CDATA[
	$("input[name=selectimg]").click(function(){
		var area = "image";
		var path= "{PATH}";						
		var type= "image";
		nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- END: main -->