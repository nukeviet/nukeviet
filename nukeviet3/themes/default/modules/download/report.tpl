<!-- BEGIN: main -->
<!-- BEGIN: permission -->
<span style='color:red;padding:10px;display:inline-block'>{permission}</span>
<!-- END: permission -->
<!-- BEGIN: script -->
{script}
<!-- END: script -->
<!-- BEGIN: content -->
<div class="description1">
	<h3>{content.title}</h3>
	<p>
		<!-- BEGIN: msg -->
        <center> <font color="#FF0000">{MSG}</font></center>
        <br />
        <!-- END: msg -->
		<!-- BEGIN: content_n -->
        <form action="" method="post" style="padding:0; margin:0">
        <input type="hidden" value="1" name="action" />
        <textarea name="content" cols="70" rows="8">{CONENT_R}</textarea><br/>
        <input type="submit" name="confirm" value="{content.report_title_send}"/>
        </form>
        <!-- END: content_n -->
	</p>
</div>
{META}
<!-- END: content -->
<!-- BEGIN: scriptfoot -->
{scriptfoot}
<!-- END: scriptfoot -->
<!-- END: main -->