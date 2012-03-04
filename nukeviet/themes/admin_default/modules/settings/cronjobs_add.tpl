<!-- BEGIN: main -->
<div class="quote" style="width:98%">
	<blockquote<!-- BEGIN: error --> class="error"<!-- END: error -->><span>{DATA.title}</span></blockquote>
</div>
<div class="clear"></div>
<form method="post" action="{DATA.action}">
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.cron_name.0}:</td>
			<td><sup class="required">&lowast;</sup></td>
			<td><input name="cron_name" id="cron_name" type="text" value="{DATA.cron_name.1}" style="width:300px" maxlength="{DATA.cron_name.2}" /></td>
			<td></td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.run_file.0}:</td>
			<td></td>
			<td>
				<select name="run_file">
					<option value="">{DATA.run_file.1}</option>
					<!-- BEGIN: run_file -->
					<option value="{RUN_FILE.key}"{RUN_FILE.selected}>{RUN_FILE.key}</option>
					<!-- END: run_file -->
				</select>
			</td>
			<td><span class="row">&lArr;</span> {DATA.run_file.4}</td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.run_func.0}:</td>
			<td><sup class="required">&lowast;</sup></td>
			<td><input name="run_func_iavim" id="run_func_iavim" type="text" value="{DATA.run_func.1}" style="width:300px" maxlength="{DATA.run_func.2}" /></td>
			<td><span class="row">&lArr;</span> {DATA.run_func.3}</td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.params.0}:</td>
			<td></td>
			<td><input name="params_iavim" id="params_iavim" type="text" value="{DATA.params.1}" style="width:300px" maxlength="{DATA.params.2}" /></td>
			<td><span class="row">&lArr;</span> {DATA.params.3}</td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.start_time.0}:</td>
			<td></td>
			<td>
				<select name="hour">
					<!-- BEGIN: hour -->
					<option value="{HOUR.key}"{HOUR.selected}>{HOUR.key}</option>
					<!-- END: hour -->
				</select>
				{DATA.hour.0}
				<select name="min">
					<!-- BEGIN: min -->
					<option value="{MIN.key}"{MIN.selected}>{MIN.key}</option>
					<!-- END: min -->
				</select>
				{DATA.min.0}
				{DATA.start_time.1}
				<input name="start_date" id="start_date" value="{DATA.start_time.2}" style="width: 90px;" maxlength="10" readonly="readonly" type="text" />
			</td>
			<td></td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.interval.0}:</td>
			<td></td>
			<td><input name="interval_iavim" id="interval_iavim" type="text" value="{DATA.interval.1}" style="width:100px" maxlength="{DATA.interval.2}" /> {DATA.interval.3}</td>
			<td><span class="row">&lArr;</span> {DATA.interval.4}</td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<col valign="top" width="300px" />
		<tr>
			<td>{DATA.del.0}:</td>
			<td></td>
			<td><input name="del" type="checkbox" value="1"{DELETE} /></td>
			<td></td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<col valign="top" width="160px" />
		<tr>
			<td><input name="save" id="save" type="hidden" value="1" /></td>
			<td><input name="go_add" type="submit" value="{DATA.submit}" /></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#start_date").datepicker({
		showOn : "button",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});					
});
</script>
<!-- END: main -->