<!-- BEGIN: edit -->
<div class="quote" style="width:98%">
    <blockquote {CLASS}>
        <span>{INFO}</span>
    </blockquote>
</div>
<div class="clear"></div>
<form method="post" action="{ACTION}">
    <!-- BEGIN: position -->
    <table class="tab1 fixtab">
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
    <!-- END: position --><!-- BEGIN: editor -->
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
                    <!-- BEGIN: loop --><option value="{VALUE}" {SELECTED}>{VALUE}  </option>
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
                {ALLOW_FILES_TYPE}:
            </td>
            <td>
            </td>
            <td>
                <!-- BEGIN: loop -->
                <input name="allow_files_type[]" type="checkbox" value="{VALUE}" {CHECKED} />

                {VALUE}

                <br/>
                <!-- END: loop -->
            </td>
            <td>
            </td>
        </tr>
    </table>
    <!-- END: allow_files_type --><!-- BEGIN: allow_modify_files -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_MODIFY_FILES}:
            </td>
            <td>
            </td>
            <td>
                <input name="allow_modify_files" type="checkbox" value="1"{CHECKED} />
            </td>
            <td>
            </td>
        </tr>
    </table>
    <!-- END: allow_modify_files --><!-- BEGIN: allow_create_subdirectories -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_CREATE_SUBDIRECTORIES}:
            </td>
            <td>
            </td>
            <td>
                <input name="allow_create_subdirectories" type="checkbox" value="1"{CHECKED} />
            </td>
            <td>
            </td>
        </tr>
    </table>
    <!-- END: allow_create_subdirectories --><!-- BEGIN: allow_modify_subdirectories -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {ALLOW_MODIFY_SUBDIRECTORIES}:
            </td>
            <td>
            </td>
            <td>
                <input name="allow_modify_subdirectories" type="checkbox" value="1"{CHECKED} />
            </td>
            <td>
            </td>
        </tr>
    </table>
    <!-- END: allow_modify_subdirectories --><!-- BEGIN: lev -->
    <table class="tab1 fixtab">
        <col valign="top" width="150px" /><col valign="top" width="10px" /><col valign="top" /><col valign="top" width="300px" />
        <tr>
            <td>
                {LEV0}:
            </td>
            <td>
            </td>
            <td colspan="2">
                <!-- BEGIN: if -->
                <label>
	                <input name="lev" type="radio" value="2" onclick="nv_show_hidden('modslist',0);"{CHECKED2} />
					&nbsp;{LEV4}
                </label>
                &nbsp;&nbsp;&nbsp;
				 <label>
	                <input name="lev" type="radio" value="3" onclick="nv_show_hidden('modslist',1);"{CHECKED3} />
					&nbsp; {LEV5}
				</label>
                <br/>
                <div id="modslist" style="margin-top:10px;{STYLE}">
                {LEV1}:
                <br/>
                <!-- END: if --><!-- BEGIN: else -->
                <div>
                    <!-- END: else --><!-- BEGIN: loop -->
                    <p>
                        <input name="modules[]" type="checkbox" value="{VALUE}" {CHECKED} />&nbsp;
                        {CUSTOM_TITLE}
                    </p>
                    <!-- END: loop -->
                </div>
            </td>
        </tr>
    </table>
    <!-- END: lev -->
    <table class="tab1 fixtab">
        <col valign="top" width="160px" />
        <tr>
            <td>
                <input name="save" id="save" type="hidden" value="1" />
            </td>
            <td>
                <input name="go_edit" type="submit" value="{SUBMIT}" />
            </td>
        </tr>
    </table>
</form>
<!-- END: edit --><!-- BEGIN: edit_resuilt -->
<table summary="{TITLE}" class="tab1">
    <caption>
        {TITLE}:
    </caption>
    <col valign="top" width="30%" /><col span="2" valign="top" width="35%" />
    <thead>
        <tr>
            <td>
                {THEAD0}
            </td>
            <td>
                {THEAD1}
            </td>
            <td>
                {THEAD2}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>
                {VALUE0}
            </td>
            <td>
                {VALUE1}
            </td>
            <td>
                {VALUE2}
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<br/>
<form name="form_go" id="form_go" method="post" action="{ACTION}">
    <!-- BEGIN: loop1 --><!-- BEGIN: if -->
    <input name="{KEY}" id="{KEY}" type="hidden" value="{VALUE1}" />
    <!-- END: if --><!-- BEGIN: else -->
    <input name="{KEY}" id="{KEY}" type="hidden" value="{VALUE1}" />
    <!-- END: else --><!-- END: loop1 --><a class="button2" href="{EDIT_HREF}"><span><span>{EDIT_NAME}</span></span></a>
    <a class="button2" href="{HOME_HREF}"><span><span>{HOME_NAME}</span></span></a>
</form>
<!-- END: edit_resuilt -->
