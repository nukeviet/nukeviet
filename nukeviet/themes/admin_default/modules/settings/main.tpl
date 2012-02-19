<!-- BEGIN: main -->
<form action="" method="post">
    <table class="tab1" summary="">
        <col width="150" />
        <tfoot>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.sitename}</strong>
                </td>
                <td>
                    <input type="text" name="site_name" value="{VALUE.sitename}" style="width: 450px"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.description}</strong>
                </td>
                <td>
                    <input type="text" name="site_description" value="{VALUE.description}" style="width: 450px"/>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.site_keywords}</strong>
                </td>
                <td>
                    <input type="text" name="site_keywords" value="{VALUE.site_keywords}" style="width: 450px"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.site_logo}</strong>
                </td>
                <td>
                    <input type="text" name="site_logo" id="site_logo" value="{VALUE.site_logo}" style="width: 350px"/><input style="width:100px" type="button" value="{LANG.browse_image}" name="selectimg"/>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.theme}</strong>
                </td>
                <td>
                    <select name="site_theme">
                        <!-- BEGIN: site_theme --><option value="{SITE_THEME}"{SELECTED}>{SITE_THEME}  </option>
                        <!-- END: site_theme -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.default_module}</strong>
                </td>
                <td>
                    <select name="site_home_module">
                        <!-- BEGIN: module --><option value="{MODULE.title}"{SELECTED}>{MODULE.custom_title}  </option>
                        <!-- END: module -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.allow_switch_mobi_des}</strong>
                </td>
                <td>
                    <input type="checkbox" name="switch_mobi_des" value="1"{VALUE.switch_mobi_des}/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.disable_content}</strong>
                </td>
                <td>
                    {DISABLE_SITE_CONTENT}
                </td>
            </tr>
        </tbody>
    </table>
</form>
<!-- END: main -->
