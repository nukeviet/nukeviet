<!-- BEGIN: add -->
<div class="quote" style="width:98%">
    <blockquote>
        <span>{INFO}</span>
    </blockquote>
</div>
<div class="clear"></div>
<form method="post" action="{ACTION}" id="addadmin">
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" width="400px"/><col valign="top"/>
        <tr>
            <td>{LANG.add_user}:</td>
            <td><sup class="required">&lowast;</sup></td>
            <td>
				<input name="userid" id="userid" type="text" value="{USERID}" style="width:300px" maxlength="20" />
				<input type="button" value="{LANG.add_select}" onclick="nv_open_browse_file( '{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&' + nv_fc_variable + '=getuserid&area=userid', 'NVImg', '850', '600', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' )" />
			</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" width="400px"/><col valign="top"/>
        <tr>
            <td>
                {POSITION0}:
            </td>
            <td>
                <sup class="required">
                    &lowast;
                </sup>
            </td>
            <td>
                <input name="position" id="position" type="text" value="{POSITION1}" style="width:300px" maxlength="250" />
            </td>
            <td>
                <span class="row">&lArr;</span>
                {POSITION2}
            </td>
        </tr>
    </table>
    <!-- BEGIN: editor -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {EDITOR0}:
            </td>
            <td>
            </td>
            <td>
                <select name="editor" id="editor">
                    <option value="">{EDITOR3}</option>
                    <!-- BEGIN: loop --><option value="{EDITOR}" {SELECTED}>{EDITOR}  </option>
                    <!-- END: loop -->
                </select>
            </td>
            <td>
            </td>
        </tr>
    </table>
    <!-- END: editor --><!-- BEGIN: allow_files_type -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_FILES_TYPE0}:
            </td>
            <td>
            </td>
            <td>
                <!-- BEGIN: loop -->
                <input name="allow_files_type[]" type="checkbox" value="{TP}" {CHECKED} />

                {TP}

                <br/>
                <!-- END: loop -->
            </td>
            <td>
            </td>
        </tr>
    </table>
    <!-- END: allow_files_type -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_MODIFY_FILES0}:
            </td>
            <td>
            </td>
            <td>
                <input name="allow_modify_files" type="checkbox" value="1" {MODIFY_CHECKED} />
            </td>
            <td>
            </td>
        </tr>
    </table>
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_CREATE_SUBDIRECTORIES0}:
            </td>
            <td>
            </td>
            <td>
                <input name="allow_create_subdirectories" type="checkbox" value="1" {CREATE_CHECKED} />
            </td>
            <td>
            </td>
        </tr>
    </table>
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_MODIFY_SUBDIRECTORIES}:
            </td>
            <td>
            </td>
            <td>
                <input name="allow_modify_subdirectories" type="checkbox" value="1" {ALLOW_MODIFY_SUBDIRECTORIES_CHECKED} />
            </td>
            <td>
            </td>
        </tr>
    </table>
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {LEV0}:
            </td>
            <td>
            </td>
            <td colspan="2">
				<!-- BEGIN: show_lev_2 -->	            
	                <label>
	                <input name="lev" type="radio" value="2" onclick="nv_show_hidden('modslist',0);"{LEV2_CHECKED} />
					&nbsp;
	                {LEV2}&nbsp;&nbsp;&nbsp;
	                </label>
				<!-- END: show_lev_2 --> 
				
				<label>               
                <input name="lev" type="radio" value="3" onclick="nv_show_hidden('modslist',1);"{LEV3_CHECKED} />
				&nbsp;
                {LEV3}
                </label>
                <br/>
                <div id="modslist" style="margin-top:10px;{STYLE_MODS}">
                    {MODS0}:
                    <br/>
                    <!-- BEGIN: lev_loop -->
                    <p>
                        <input name="modules[]" type="checkbox" value="{MOD_VALUE}" {LEV_CHECKED} />
						&nbsp;
                        {CUSTOM_TITLE}

                    </p>
                    <!-- END: lev_loop -->
                </div>
            </td>
        </tr>
    </table>
    <table class="tab1 fixtab">
        <col valign="top" width="160px" />
        <tr>
            <td>
                <input name="save" id="save" type="hidden" value="1" />
            </td>
            <td>
                <input name="go_add" type="submit" value="{SUBMIT}" />
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("form#addadmin").submit(function(){
		a = $(this).serialize();
		var b = $(this).attr("action");
		$("[type=submit]").attr("disabled", "disabled");  
		$.ajax({type:"POST", url:b, data:a, success:function(c){
			if(c == "OK") {
				window.location = '{RESULT_URL}';
			}else{
				alert(c);
			}
			$("[type=submit]").removeAttr("disabled")
		}});
		return!1
	});
});
//]]>
</script>
<!-- END: add -->
<!-- BEGIN: add_result -->
<table class="tab1 fixtab">
    <caption>{TITLE}:</caption>
    <col span="2" valign="top" width="50%" /><!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>{VALUE0}</td>
            <td>{VALUE1}</td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<br/>
<form name="form_go" id="form_go" method="post" action="{ACTION}">
    <a class="button2" href="{EDIT_HREF}"><span><span>{EDIT}</span></span></a>
    <a class="button2" href="{HOME_HREF}"><span><span>{HOME}</span></span></a>
</form>
<!-- END: add_result -->
