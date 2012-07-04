<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form id="votingcontent" method="post" action="{FORM_ACTION}">
	<table class="tab1">
		<tbody>
			<tr>
				<td>{LANG.voting_allowcm}</td>
				<td>
					<select name="who_view" id="who_view" onchange="nv_sh('who_view','groups_list')" style="width: 250px;">
						<!-- BEGIN: who_view -->
						<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
						<!-- END: who_view -->
					</select><br />
					<div id="groups_list" style="{SHOW_GROUPS_LIST}">
						{GLANG.groups_view}:
						<table style="margin-bottom:8px;width:250px;">
						<col valign="top" width="150px" />
							<tr>
								<td>
									<!-- BEGIN: groups_view -->
									<p><input name="groups_view[]" type="checkbox"{GROUPS_VIEW.checked} value="{GROUPS_VIEW.key}"/>{GROUPS_VIEW.title}</p>
									<!-- END: groups_view -->
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.voting_time}</td>
				<td>
					<input name="publ_date" id="publ_date" value="{PUBL_DATE}" style="width: 90px;" maxlength="10" readonly="readonly" type="text" />
					<select name="phour">
						<!-- BEGIN: phour -->
						<option value="{PHOUR.key}"{PHOUR.selected}>{PHOUR.title}</option>
						<!-- END: phour -->
					</select>:
					<select name="pmin">
						<!-- BEGIN: pmin -->
						<option value="{PMIN.key}"{PMIN.selected}>{PMIN.title}</option>
						<!-- END: pmin -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.voting_timeout}</td>
				<td>
					<input name="exp_date" id="exp_date" value="{EXP_DATE}" style="width: 90px;" maxlength="10" readonly="readonly" type="text" />
					<select name="ehour">
						<!-- BEGIN: ehour -->
						<option value="{EHOUR.key}"{EHOUR.selected}>{EHOUR.title}</option>
						<!-- END: ehour -->
					</select>:
					<select name="emin">
						<!-- BEGIN: emin -->
						<option value="{EMIN.key}"{EMIN.selected}>{EMIN.title}</option>
						<!-- END: emin -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.voting_maxoption}</td>
				<td><input type="text" name="maxoption" size="5" value="{DATA.acceptcm}" class="txt required" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.voting_question}</td>
				<td><input type="text" name="question" size="60" value="{DATA.question}" class="txt required" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.voting_link}</td>
				<td><input type="text" name="link" size="60" value="{DATA.link}" class="txt" /></td>
			</tr>
		</tbody>
	</table>
	<table class="tab1" id="items">
		<thead>
			<tr>
				<td></td>
				<td>{LANG.voting_answer}</td>
				<td>{LANG.voting_link}</td>
			</tr>
		</thead>
		<!-- BEGIN: item -->
		<tbody{ITEM.class}>
			<tr>
				<td style="text-align:right">{LANG.voting_question_num} {ITEM.stt}</td>
				<td><input type="text" value="{ITEM.title}" name="answervote[{ITEM.id}]" style="width:300px" /></td>
				<td><input type="text" value="{ITEM.link}" name="urlvote[{ITEM.id}]" style="width:350px"/></td>
			</tr>
		</tbody>
		<!-- END: item -->
		<tbody{NEW_CLASS}>
			<tr>
				<td style="text-align:right">{LANG.voting_question_num} {NEW_ITEM}</td>
				<td><input type="text" value="" name="answervotenews[]" style="width:300px" /></td>
				<td><input type="text" value="" name="urlvotenews[]" style="width:350px" /></td>
			</tr>
		</tbody>
	</table>
	<br />
	<div style="text-align:center">
		<input type="button" value="{LANG.add_answervote}" onclick="nv_vote_additem('{LANG.voting_question_num}');" />
		<input type="submit" name="submit" value="{LANG.voting_confirm}" />
	</div>
</form>
<script type="text/javascript">
	var items = {NEW_ITEM_NUM};
	$("#publ_date,#exp_date").datepicker({
		showOn : "button",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});					
</script>
<!-- END: main -->