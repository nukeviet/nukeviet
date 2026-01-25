<!-- BEGIN: main -->
<div class="panel-body">
	<!-- BEGIN: error -->
	<div class="alert alert-danger">{ERROR}</div>
	<!-- END: error -->
	<!-- BEGIN: data -->
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#info" data-toggle="tab">{LANG.tab_info}</a></li>
		<li><a href="#guide" data-toggle="tab">{LANG.tab_guide}</a></li>
		<li><a href="#images" data-toggle="tab">{LANG.tab_images}</a></li>
		<li><a href="#files" data-toggle="tab">{LANG.tab_files}</a></li>
	</ul>
	<div class="tab-content">
		<div class="panel-body tab-pane active" id="info">
			<div class="pull-right ext-dinfo">
				<!-- BEGIN: install -->
				<div class="m-bottom">
					<a href="{DATA.install_link}" class="btn btn-primary btn-lg btn-block ext-install" role="button">{LANG.install}</a>
				</div>
				<!-- END: install -->
				<div class="table table-responsive">
					<table class="table table-striped table-bordered">
						<colgroup>
							<col class="w150"/>
						</colgroup>
						<tbody>
							<tr>
								<th colspan="2" class="{DATA.compatible_class}">
									{DATA.compatible_title}
								</th>
							</tr>
							<tr>
								<th>{LANG.newest_version}</th>
								<td>{DATA.newest_version}</td>
							</tr>
							<tr>
								<th>{LANG.updatetime}</th>
								<td>{DATA.updatetime}</td>
							</tr>
							<tr>
								<th>{LANG.view_hits}</th>
								<td>{DATA.view_hits}</td>
							</tr>
							<tr>
								<th>{LANG.download_hits}</th>
								<td>{DATA.download_hits}</td>
							</tr>
							<tr>
								<th>{LANG.rating_text}</th>
								<td>{DATA.rating_text}</td>
							</tr>
							<tr>
								<th>{LANG.license}</th>
								<td>{DATA.license}</td>
							</tr>
							<tr>
								<th>{LANG.author}</th>
								<td>{DATA.username}</td>
							</tr>
							<tr>
								<th>{LANG.ext_type}</th>
								<td>{DATA.types}</td>
							</tr>
							<tr>
								<th>{LANG.price}</th>
								<td>{DATA.price}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="ext-detail">
				{DATA.description}
			</div>
		</div>
		<div class="panel-body tab-pane" id="guide">
			<!-- BEGIN: empty_documentation -->
			<div class="alert alert-danger">{LANG.detail_empty_documentation}</div>
			<!-- END: empty_documentation -->
			{DATA.documentation}
		</div>
		<div id="images" class="panel-body tab-pane">
			<!-- BEGIN: empty_images -->
			<div class="alert alert-danger">{LANG.detail_empty_images}</div>
			<!-- END: empty_images -->
			<!-- BEGIN: demo_images -->
			<div class="rows">
				<!-- BEGIN: loop -->
				<div class="col-sm-6">
					<a href="{IMAGE}" target="_blank" class="thumbnail">
						<img src="{IMAGE}" alt="{DATA.title}" style="width:100%"/>
					</a>
				</div>
				<!-- END: loop -->
			</div>
			<!-- END: demo_images -->
		</div>
		<div class="panel-body tab-pane" id="files">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<td>{LANG.file_name}</td>
						<td class="w100">{LANG.file_version}</td>
						<td class="w100">{LANG.price}</td>
						<td class="w150">{LANG.compatible}</td>
						<td class="w100"></td>
					</thead>
					<tbody>
						<!-- BEGIN: file -->
						<tr>
							<td>{FILE.title}</td>
							<td>{FILE.ver}</td>
							<td>{FILE.price}</td>
							<td class="{FILE.compatible_class}">{FILE.compatible_title}</td>
							<td>
								<!-- BEGIN: install -->
								<a href="{FILE.install_link}" class="btn btn-primary btn-xs btn-block ext-tip ext-install" role="button" title="{LANG.install_note}" data-toggle="tooltip" data-placement="top">{LANG.install}</a>
								<!-- END: install -->
								<!-- BEGIN: download -->
								<a href="{FILE.origin_link}" class="btn btn-primary btn-xs btn-block ext-tip" role="button" target="_blank" title="{LANG.download_note}" data-toggle="tooltip" data-placement="top">{LANG.download}</a>
								<!-- END: download -->
							</td>
						</tr>
						<!-- END: file -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$('#tabs a').click(function(e){
		e.preventDefault();
		$(this).tab('show');
	});
	$(document).ready(function(){
		$('.ext-tip').tooltip();
		$('.ext-install').click(function(e){
			e.preventDefault();
			parent.location = $(this).attr('href');
		});
	});
	</script>
	<!-- END: data -->
</div>
<!-- END: main -->