<!-- BEGIN: main -->
<div id="users">
	<div class="page-header">
		<h3>{LANG.change_question_pagetitle}</h3>
	</div>
	<ul class="list-tab top-option clearfix">
		<li>
			<a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
		</li>
		<li>
			<a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
		</li>
		<li class="ui-tabs-selected">
			<a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
		</li>
		<!-- BEGIN: allowopenid -->
		<li>
			<a href="{URL_HREF}openid">{LANG.openid_administrator}</a>
		</li>
		<!-- END: allowopenid -->
		<!-- BEGIN: regroups -->
		<li>
			<a href="{URL_HREF}regroups">{LANG.in_group}</a>
		</li>
		<!-- END: regroups -->
		<!-- BEGIN: logout -->
		<li>
			<a href="{URL_HREF}logout">{LANG.logout_title}</a>
		</li>
		<!-- END: logout -->
	</ul>
	<div class="note m-bottom">
		<strong>{LANG.changequestion_info}</strong>
		<ol>
			<li>
				{LANG.changequestion_info1}
			</li>
			<li>
				{LANG.changequestion_info2}
			</li>
		</ol>
	</div>
	<!-- BEGIN: step1 -->
	<form id="changeQuestionForm" class="box-border-shadow content-box clearfix" action="{FORM1_ACTION}" method="post">
		<p>
			<strong>{DATA.info}</strong>
		</p>
		<div class="clearfix rows">
			<label> {LANG.password} </label>
			<input class="required password input" name="nv_password" id="nv_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" />
		</div>
		<div class="clearfix rows">
			<label> &nbsp; </label>
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input type="submit" value="{LANG.changequestion_submit1}" class="button" />
		</div>
	</form>
	<!-- END: step1 -->
	<!-- BEGIN: step2 -->
	<script type="text/javascript">
		function question_change() {
			var question_option = $("#question").val();
			$("#question").val('0');
			$("#your_question").val(question_option);
			$("#your_question").focus();
			return;
		}
	</script>
	<form id="changeQuestionForm" class="box-border-shadow content-box clearfix change-question" action="{FORM2_ACTION}" method="post">
		<p>
			<strong>{DATA.info}</strong>
		</p>
		<div class="clearfix rows">
			<label> {LANG.question} </label>
			<select name="question" id="question" onchange="question_change();">
				<!-- BEGIN: frquestion -->
				<option value="{QUESTIONVALUE}">{QUESTIONTITLE}</option>
				<!-- END: frquestion -->
			</select>
		</div>
		<div class="clearfix rows">
			<label> {LANG.your_question} </label>
			<input class="required input" name="your_question" id="your_question" value="{DATA.your_question}" />
		</div>
		<div class="clearfix rows">
			<label> {LANG.answer_your_question} </label>
			<input class="required input" name="answer" value="{DATA.answer}" />
		</div>
		<div class="clearfix rows">
			<label> &nbsp; </label>
			<input type="hidden" name="nv_password" value="{DATA.nv_password}" />
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input type="hidden" name="send" value="1" />
			<input type="submit" value="{LANG.changequestion_submit2}" class="button" />
		</div>

	</form>
	<!-- END: step2 -->
</div>
<!-- END: main -->