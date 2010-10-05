<!-- BEGIN: main -->
<form action="" method="post">
    <table class="tab1" summary="">
        <tr>
            <td>
                <strong>{LANG.sitename}</strong>
            </td>
            <td>
                <input type="text" name="site_name" value="{VALUE.sitename}" style="width: 450px"/>
            </td>
        </tr>
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
                    <strong>{LANG.site_logo}</strong>
                </td>
                <td>
                    <input type="text" name="site_logo" value="{VALUE.site_logo}" style="width: 450px"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
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
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.default_module}</strong>
                </td>
                <td>
                    <select name="site_home_module">
                        <!-- BEGIN: module -->
						<option value="{MODULE.title}"{SELECTED}>{MODULE.custom_title}  </option>
                        <!-- END: module -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.site_disable}</strong>
                </td>
                <td>
                    <input type="checkbox" name="disable_site" value="1" {CHECKED3} />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.disable_content}</strong>
                </td>
                <td>
                    <textarea name="disable_site_content" cols="60" rows="5">{VALUE.disable_content}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="width: 200px; margin: 10px auto; text-align: center;">
        <input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/>
    </div>
</form><!-- END: main -->
