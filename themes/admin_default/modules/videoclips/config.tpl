<!-- BEGIN: main -->
	<form action="{FORM_ACTION}" method="post" id="modConf">
        <table class="tab1" summary="">
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.NumberOfLinks}</strong>
                </td>
                <td>
                    <select name="otherClipsNum" id="otherClipsNum">
                        <!-- BEGIN: otherClipsNum -->
                        <option value="{NUMS.value}"{NUMS.select}>{NUMS.value}</option>
                        <!-- END: otherClipsNum -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.playerAutostart}</strong>
                </td>
                <td>
                    <input type="checkbox" value="1" name="playerAutostart" id="playerAutostart"{CONFIGMODULE.playerAutostart} />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.playerSkin}</strong>
                </td>
                <td>
                    <select name="playerSkin" id="playerSkin">
                        <option value="">{LANG.noSkin}</option>
                        <!-- BEGIN: playerSkin -->
                        <option value="{SKIN.value}"{SKIN.select}>{SKIN.value}</option>
                        <!-- END: playerSkin -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    <strong>{LANG.commnum}</strong>
                </td>
                <td>
                    <select name="commNum" id="commNum">
                        <!-- BEGIN: commNum -->
                        <option value="{COMMNUM.value}"{COMMNUM.select}>{COMMNUM.value}</option>
                        <!-- END: commNum -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.embedMaxWidth}</strong>
                </td>
                <td>
                    <input style="width: 50px;" type="text" name="playerMaxWidth" id="playerMaxWidth" value="{CONFIGMODULE.playerMaxWidth}" />
                </td>
            </tr>
        </tbody>
	</table>
    <div style="width: 200px; margin: 10px auto; text-align: center;">
        <input type="submit" name="submit" value="{LANG.save}" style="width: 100px;"/>
    </div>
	</form>
<script type="text/javascript">
$("#modConf").submit(function(){var a="submit=1&playerMaxWidth="+intval($("#playerMaxWidth").val())+"&commNum="+$("#commNum").val()+"&otherClipsNum="+$("#otherClipsNum").val()+"&playerAutostart="+(1==$("#playerAutostart:checked").length?1:0)+"&playerSkin="+$("#playerSkin").val();return $.ajax({type:"POST",url:window.location.href,data:a,success:function(){return alert("{LANG.successfullySaved}"),!1}}),!1});
</script>
<!-- END: main -->
