<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<form id="votingcontent" method="post" action="{FORM_ACTION}">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td>{LANG.voting_time}</td>
							<td>
								<table>
									<tr>
										<td><input name="publ_date" id="publ_date" value="{PUBL_DATE}" class="form-control w100 pull-left" maxlength="10" readonly="readonly" type="text" />&nbsp;</td>
										<td>
											<select name="phour" class="form-control w100">
												<!-- BEGIN: phour -->
												<option value="{PHOUR.key}"{PHOUR.selected}>{PHOUR.title}</option>
												<!-- END: phour -->
											</select>
										</td>
										<td>&nbsp;:&nbsp;</td>
										<td>
											<select name="pmin" class="form-control w100">
												<!-- BEGIN: pmin -->
												<option value="{PMIN.key}"{PMIN.selected}>{PMIN.title}</option>
												<!-- END: pmin -->
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>{LANG.voting_timeout}</td>
							<td>
								<table>
									<tr>
										<td><input name="exp_date" id="exp_date" value="{EXP_DATE}" class="form-control w100 pull-left" maxlength="10" readonly="readonly" type="text" />&nbsp;</td>
										<td>
											<select name="ehour" class="form-control w100">
												<!-- BEGIN: ehour -->
												<option value="{EHOUR.key}"{EHOUR.selected}>{EHOUR.title}</option>
												<!-- END: ehour -->
											</select>
										</td>
										<td>&nbsp;:&nbsp;</td>
										<td>
											<select name="emin" class="form-control w100">
												<!-- BEGIN: emin -->
												<option value="{EMIN.key}"{EMIN.selected}>{EMIN.title}</option>
												<!-- END: emin -->
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>{LANG.voting_question}</td>
							<td><input class="form-control" type="text" name="question" size="60" value="{DATA.question}" class="txt" required placeholder="{LANG.voting_question}"  oninvalid="this.setCustomValidity(nv_required)" oninput="this.setCustomValidity('')"/></td>
						</tr>
						<tr>
							<td>{LANG.voting_link}</td>
							<td><input class="form-control" type="text" name="link" size="60" value="{DATA.link}" class="txt" /></td>
						</tr>
					</tbody>
				</table>
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="items">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th>{LANG.voting_answer}</th>
								<th>{LANG.voting_link}</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: item -->
							<tr>
								<td class="text-right">{LANG.voting_question_num} {ITEM.stt}</td>
								<td><input class="form-control" type="text" value="{ITEM.title}" name="answervote[{ITEM.id}]" /></td>
								<td><input  class="form-control"type="text" value="{ITEM.link}" name="urlvote[{ITEM.id}]"/></td>
							</tr>
							<!-- END: item -->
							<tr>
								<td class="text-right">{LANG.voting_question_num} {NEW_ITEM}</td>
								<td><input class="form-control" type="text" value="" name="answervotenews[]" /></td>
								<td><input class="form-control" type="text" value="" name="urlvotenews[]"/></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-3">
			<div class="row">
				<div class="col-sm-6 col-md-12">
					<div class="form-group">
						<label>{LANG.voting_maxoption}:</label>
						<input class="form-control w100" type="text" name="maxoption" size="5" value="{DATA.acceptcm}" class="txt" required pattern="^([0-9])+$" oninvalid="this.setCustomValidity(nv_digits)" oninput="this.setCustomValidity('')"/>
					</div>
				</div>
				<div class="col-sm-6 col-md-12">
					<div class="form-group">
						<label>{GLANG.groups_view}:</label>
						<!-- BEGIN: groups_view -->
						<div class="row">
							<label><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.value}" {GROUPS_VIEW.checked} />{GROUPS_VIEW.title}</label>
						</div>
						<!-- END: groups_view -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row text-center">
		<input type="button" value="{LANG.add_answervote}" onclick="nv_vote_add_item('{LANG.voting_question_num}');" class="btn btn-info" />
		<input type="submit" name="submit" value="{LANG.voting_confirm}" class="btn btn-primary" />
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
	var items = '{NEW_ITEM_NUM}';
</script>
<!-- END: main -->