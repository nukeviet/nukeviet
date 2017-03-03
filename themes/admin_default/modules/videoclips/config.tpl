<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" id="modConf">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col class="w300" />
		    <tbody>
		        <tr>
		            <td>
		                <strong>{LANG.homeclips}</strong>
		            </td>
		            <td>
		                <select style="width: 500px" class="form-control" name="idhomeclips" id="idhomeclips">
		                    <option value="0"> ----- </option>
		                    <!-- BEGIN: idhomeclips -->
		                    <option value="{VHOME.id}"{VHOME.select}>{VHOME.title}</option>
		                    <!-- END: idhomeclips -->
		                </select>
		            </td>
		        </tr>
		        <tr>
		            <td>
		                <strong>{LANG.NumberOfLinks}</strong>
		            </td>
		            <td>
		                <select class="form-control w200" name="otherClipsNum" id="otherClipsNum">
		                    <!-- BEGIN: otherClipsNum -->
		                    <option value="{NUMS.value}"{NUMS.select}>{NUMS.value}</option>
		                    <!-- END: otherClipsNum -->
		                </select>
		            </td>
		        </tr>
		        <tr>
		            <td>
		                <strong>{LANG.playerAutostart}</strong>
		            </td>
		            <td>
		                <input type="checkbox" value="1" name="playerAutostart" id="playerAutostart"{CONFIGMODULE.playerAutostart} />
		            </td>
		        </tr>
		         <!-- BEGIN: hidden -->
		        <tr>
		            <td>
		                <strong>{LANG.playerSkin}</strong>
		            </td>
		            <td>
		                <select class="form-control w200" name="playerSkin" id="playerSkin">
		                    <option value="">{LANG.noSkin}</option>
		                    <!-- BEGIN: playerSkin -->
		                    <option value="{SKIN.value}"{SKIN.select}>{SKIN.value}</option>
		                    <!-- END: playerSkin -->
		                </select>
		            </td>
		        </tr>
		        <tr>
		            <td>
		                <strong>{LANG.embedMaxWidth}</strong>
		            </td>
		            <td>
		                <input class="form-control w200" type="text" name="playerMaxWidth" id="playerMaxWidth" value="{CONFIGMODULE.playerMaxWidth}" />
		            </td>
		        </tr>
		        <!-- END: hidden -->
		    </tbody>
		</table>
	</div>
	<div style="width: 200px; margin: 10px auto; text-align: center;">
	    <input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}" style="width: 100px;"/>
	</div>
</form>
<script type="text/javascript">
$("#modConf").submit(function(){var a="submit=1&idhomeclips="+intval($("#idhomeclips").val())+"&playerMaxWidth="+intval($("#playerMaxWidth").val())+"&commNum="+$("#commNum").val()+"&otherClipsNum="+$("#otherClipsNum").val()+"&playerAutostart="+(1==$("#playerAutostart:checked").length?1:0)+"&playerSkin="+$("#playerSkin").val();return $.ajax({type:"POST",url:window.location.href,data:a,success:function(){return alert("{LANG.successfullySaved}"),!1}}),!1});
</script>
<!-- END: main -->