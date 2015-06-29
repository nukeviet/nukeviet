<!-- BEGIN: main -->
<div class="well">
	<form class="navbar-form" action="{NV_BASE_ADMINURL}index.php" method="get" class="form-inline">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		Copy bài viết từ chủ đề
		<select class="form-control" name="fcatid">
			<!-- BEGIN: fcatid -->
			<option value="{CAT_CONTENT.value}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
			<!-- END: fcatid -->
		</select>
		Sang chủ đề:
		<select class="form-control" name="tcatid">
			<!-- BEGIN: tcatid -->
			<option value="{CAT_CONTENT.value}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
			<!-- END: tcatid -->
		</select>
		<input class="btn btn-primary" type="submit" value="Thực hiện" />
		<input type="hidden" name="checkss" value="{CHECKSS}" /><br />
	</form>
</div>

<!-- END: main -->