<!-- BEGIN: main -->
<form class="form-inline m-bottom" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="confirm-reload">
	<div class="row">
		<div class="col-sm-24 col-md-6">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th colspan="2">{LANG.content_cat}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="2">
							<input name="update" type="submit" value="{LANG.save}" class="btn btn-primary"/>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN: catid -->
					<tr>
						<td><input style="margin-left: {CATS.space}px;" type="checkbox" value="{CATS.catid}" name="catids[]" class="news_checkbox" {CATS.checked}> {CATS.title} </td>
						<td><input id="catright_{CATS.catid}" style="{CATS.catiddisplay}" type="radio" name="catid" title="{LANG.content_checkcat}" value="{CATS.catid}" {CATS.catidchecked}/></td>
					</tr>
					<!-- END: catid -->
				</tbody>
			</table>
		</div>
		<div class="col-sm-24 col-md-18">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<colgroup>
						<col class="w50"/>
						<col>
					</colgroup>
					<thead>
						<tr>
							<th class="text-center"><input name="checkall" type="checkbox"/></th>
							<th>{LANG.name}</th>
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: loop -->
						<tr>
							<td class="text-center"><input type="checkbox" value="{ROW.id}" name="idcheck[]" {ROW.checked}></td>
							<td>{ROW.title}</td>
						</tr>
						<!-- END: loop -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	$(function() {
	    $('input[name=checkall]').click(function() {
	    	$("input[name='idcheck[]']").prop( "checked", $(this).is(':checked') );
	    });

		$("input[name='idcheck[]']").click(function() {
			if ( ! $(this).is(':checked') )
			{
				$('input[name=checkall]').prop( "checked", false );
			}
		});

		$("input[name='catids[]']").click(function() {
				var catid = $("input:radio[name=catid]:checked").val();
				var radios_catid = $("input:radio[name=catid]");
				var catids = [];
				$("input[name='catids[]']").each(function() {
					if ($(this).prop('checked')) {
						$("#catright_" + $(this).val()).show();
						catids.push($(this).val());
					} else {
						$("#catright_" + $(this).val()).hide();
						if ($(this).val() == catid) {
							radios_catid.filter("[value=" + catid + "]").prop("checked", false);
						}
					}
				});

				if (catids.length > 1) {
					for ( i = 0; i < catids.length; i++) {
						$("#catright_" + catids[i]).show();
					};
					catid = parseInt($("input:radio[name=catid]:checked").val() + "");
					if (!catid) {
						radios_catid.filter("[value=" + catids[0] + "]").prop("checked", true);
					}
				}
			});

		$('input[name=update]').click(function() {
			var listid = [];
			$("input[name='idcheck[]']:checked").each(function() {
				listid.push($(this).val());
			});

			if (listid.length < 1) {
				alert('{LANG.topic_nocheck}');
				return false;
			}

			var catids = [];
			$("input[name='catids[]']:checked").each(function() {
				catids.push($(this).val());
			});

			if (catids.length < 1) {
				alert('{LANG.content_cat}');
				return false;
			}
			return true;
		});
	});
</script>
<!-- END: main -->