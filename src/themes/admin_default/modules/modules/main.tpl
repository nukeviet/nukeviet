<!-- BEGIN: main -->
<div id="{CONTENT.div_id}">
	<p class="text-center"><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..." />
	</p>
</div>
<!-- START FORFOOTER -->
<div class="modal fade" id="modal-reinstall-module">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{LANG.reinstall_module}</h4>
			</div>
			<div class="modal-body">
				<div class="load text-center hidden">
					<i class="fa fa-circle-o-notch fa-spin fa-2x"></i>
				</div>
				<div class="content hidden">
					<p class="text-info message"></p>
					<div class="row form-horizontal showoption">
						<div class="col-xs-6 text-right">
							<label class="control-label">{LANG.reinstall_option}:</label>
						</div>
						<div class="col-xs-12">
							<select class="form-control option">
								<option value="0">{LANG.reinstall_option_0}</option>
								<option value="1">{LANG.reinstall_option_1}</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                <input type="hidden" name="checkss" value="" />
				<button type="button" class="btn btn-primary submit">{GLANG.submit}</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
			</div>
		</div>
	</div>
</div>
<!-- END FORFOOTER -->
<script type="text/javascript">
	{CONTENT.ajax}
</script>
<!-- END: main -->