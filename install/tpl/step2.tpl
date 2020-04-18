<!-- BEGIN: step -->
<!-- BEGIN: winhost -->
<blockquote>
    {LANG.s2_winhost_info}
    <!-- BEGIN: infonext -->
    {LANG.s2_winhost_info1} <span class="highlight_green">OK</span> {LANG.s2_winhost_info2}.
    <!-- END: infonext -->
    <!-- BEGIN: inforeload -->
    {LANG.s2_winhost_info3} <a href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=2">{LANG.s2_winhost_info4}</a> {LANG.s2_winhost_info5}.
    <!-- END: inforeload -->
</blockquote>
<div class="clear"></div>
<!-- END: winhost -->
<!-- BEGIN: ftpconfig -->
<form action="{ACTIONFORM}" method="post">
<table id="ftpconfig" cellspacing="0"
    summary="{LANG.checkftpconfig_detail}" style="width: 100%;">
    <caption>{LANG.ftpconfig_note}</caption>
    <tr>
        <th scope="col" class="nobg"></th>
        <th scope="col" abbr="{LANG.ftp}">{LANG.ftp}</th>
        <th scope="col">{LANG.note}</th>
    </tr>
    <tr>
        <th scope="col" abbr="{LANG.ftp_server}" class="spec">{LANG.ftp_server}</th>
        <td>
        <ul>
            <li style="display: inline;" class="fl"><input type="text"
                name="ftp_server" value="{FTPDATA.ftp_server}" style="width: 300px;" /></li>
            <li style="display: inline;" class="fr"><span>{LANG.ftp_port}:</span><input
                type="text" name="ftp_port" value="{FTPDATA.ftp_port}"
                style="width: 20px;" /></li>
        </ul>
        </td>
        <td><span class="highlight_green">{LANG.ftp_server_note}</span></td>
    </tr>
    <tr>
        <th scope="col" abbr="{LANG.ftp_user}" class="specalt">{LANG.ftp_user}</th>
        <td><input type="text" name="ftp_user_name"
            value="{FTPDATA.ftp_user_name}" style="width: 300px;" /></td>
        <td><span class="highlight_green">{LANG.ftp_user_note}</span></td>
    </tr>
    <tr>
        <th scope="col" abbr="{LANG.ftp_pass}" class="spec">{LANG.ftp_pass}</th>
        <td><input type="password" name="ftp_user_pass" autocomplete="off"
            value="{FTPDATA.ftp_user_pass}" /></td>
        <td><span class="highlight_green">{LANG.ftp_pass_note}</span></td>
    </tr>
    <tr>
        <th scope="col" abbr="{LANG.ftp_path}" class="spec">{LANG.ftp_path}</th>
        <td><input type="text" name="ftp_path" value="{FTPDATA.ftp_path}"
            style="width: 210px;" />
            <input class="button" type="button" id="find_ftp_path" value="{LANG.ftp_path_find}"/>
        </td>
        <td><span class="highlight_green">{LANG.ftp_path_note}</span></td>
    </tr>
    <tr>
        <th scope="col" abbr="{LANG.refesh}" class="nobg"><input type="hidden"
            name="modftp" value="1" /></th>
        <td><input class="button" type="submit" value="{LANG.refesh}" /></td>
        <td></td>
    </tr>
</table>
</form>
<!-- BEGIN: errorftp -->
<span class="highlight_red">
{FTPDATA.error}
</span>
<!-- END: errorftp -->
<script type="text/javascript">
$(document).ready(function(){
    $('#find_ftp_path').click(function(){
        var ftp_server = $('input[name="ftp_server"]').val();
        var ftp_user_name = $('input[name="ftp_user_name"]').val();
        var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
        var ftp_port = $('input[name="ftp_port"]').val();

        if( ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '' )
        {
            alert('{LANG.ftp_error_empty}');
            return;
        }

        $(this).attr('disabled', 'disabled');

        var data = 'ftp_server=' + ftp_server + '&ftp_port=' + ftp_port + '&ftp_user_name=' + ftp_user_name + '&ftp_user_pass=' + ftp_user_pass + '&tetectftp=1';
        var url = '{ACTIONFORM}';

        $.ajax({type:"POST", url:url, data:data, success:function(c){
            c = c.split('|');
            if( c[0] == 'OK' ){
                $('input[name="ftp_path"]').val(c[1]);
            }else{
                alert(c[1]);
            }
            $('#find_ftp_path').removeAttr('disabled');
        }});
    });
});
</script>
<!-- END: ftpconfig -->
<table id="checkchmod" cellspacing="0"
    summary="{LANG.checkchmod_detail}" style="width: 100%;">
    <caption>{LANG.if_chmod} <span class="highlight_red">{LANG.not_compatible}</span>.
    {LANG.please_chmod}.</caption>
    <tr>
        <th scope="col" abbr="{LANG.listchmod}" class="nobg">{LANG.listchmod}</th>
        <th scope="col">{LANG.result}</th>
    </tr>
    <!-- BEGIN: loopdir -->
    <tr>
        <th scope="col" class="{DATAFILE.class}">{DATAFILE.dir}</th>
        <td><span class="{DATAFILE.classcheck}">{DATAFILE.check}</span></td>
    </tr>
    <!-- END: loopdir -->
</table>
<ul class="control_t fr">
    <li><span class="back_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=1">{LANG.previous}</a></span></li>
    <!-- BEGIN: nextstep -->
    <li><span class="next_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=3">{LANG.next_step}</a></span></li>
    <!-- END: nextstep -->
</ul>
<!-- END: step -->