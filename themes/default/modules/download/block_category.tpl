<!-- BEGIN: main -->
<div class="col-sm-3 col-md-3 category">
	<div class="panel-group" id="accordion">
		<!-- BEGIN: catparent -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<span class="glyphicon glyphicon-th"></span><a title="{catparent.title}" href="{catparent.link}">{catparent.title_trim}</a>
					<!-- BEGIN: expand -->
					<a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapse{catparent.id}"><span class="glyphicon glyphicon-tasks"></a>
					<!-- END: expand -->
				</h4>
			</div>
			<div id="collapse{catparent.id}" class="panel-collapse collapse {catparent.in}">
				<div class="panel-body">
					<table class="table">
						<!-- BEGIN: subcatparent -->
						<!-- BEGIN: loopsubcatparent -->
						<tr>
							<td>
								<span class="glyphicon glyphicon-pencil text-primary"></span><a title="{loopsubcatparent.title}" href="{loopsubcatparent.link}">{loopsubcatparent.title_trim}</a>
							</td>
						</tr>
						<!-- END: loopsubcatparent -->
						<!-- END: subcatparent -->
					</table>
				</div>
			</div>
		</div>
		<!-- END: catparent -->
	</div>
</div>
<div class="clearfix"></div>
<!-- END: main -->