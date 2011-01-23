<!-- BEGIN: add -->
<div class="quote" style="width:780px;">
    <blockquote {ERROR}>
        <span>{INFO}</span>
    </blockquote>
</div>
<div class="clear">
</div>
<form method="post" action="{ACTION}">
    <table style="margin-bottom:8px;width:800px;">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
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
    <table style="margin-bottom:8px;width:800px;">
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
    <table style="margin-bottom:8px;width:800px;">
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
    <table style="margin-bottom:8px;width:800px;">
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
    <table style="margin-bottom:8px;width:800px;">
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
    <table style="margin-bottom:8px;width:800px;">
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
    <table style="margin-bottom:8px;width:800px;">
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
    <br/>
    <table style="margin-bottom:8px;width:800px;">
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
<!-- END: add --><!-- BEGIN: add_result -->
<table class="tab1">
    <caption>
        {TITLE}:
    </caption>
    <col span="2" valign="top" width="50%" /><!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>
                {VALUE0}
            </td>
            <td>
                {VALUE1}
            </td>
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
