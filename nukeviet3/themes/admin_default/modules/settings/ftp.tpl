<!-- BEGIN: ftp--><!-- BEGIN: error-->{ERRORCONTENT}<!-- END: error -->
<form action="" method="post">
    <table class="tab1" summary="">
        <tr>
            <td>
                <strong>{LANG.server}</strong>
            </td>
            <td>
                <input type="text" name="ftp_server" value="{VALUE.ftp_server}" style="width: 250px"/><span>{LANG.port}</span>
                <input type="text" value="{VALUE.ftp_port}" name="ftp_port" style="width: 30px;"/>
            </td>
        </tr>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.username}</strong>
                </td>
                <td>
                    <input type="text" name="ftp_user_name" id="ftp_user_name_iavim" value="{VALUE.ftp_user_name}" style="width: 250px;"/>
                </td>
            </tr>
        </tbody>
        <tr>
            <td>
                <strong>{LANG.password}</strong>
            </td>
            <td>
                <input type="password" name="ftp_user_pass" id="ftp_user_pass_iavim" value="{VALUE.ftp_user_pass}" style="width: 250px"/>
            </td>
        </tr>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.ftp_path}</strong>
                </td>
                <td>
                    <input type="text" name="ftp_path" id="ftp_path_iavim" value="{VALUE.ftp_path}" style="width: 250px;"/>
                </td>
            </tr>
        </tbody>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="{LANG.submit}" style="padding: 2px 10px;"/>
            </td>
        </tr>
    </table><!-- END: ftp -->
