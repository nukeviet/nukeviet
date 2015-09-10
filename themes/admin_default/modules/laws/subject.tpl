<!-- BEGIN: main -->
<div class="myh3">
	<span>{LANG.subject}</span>
</div>

<div id="pageContent">
	<div style="text-align: center"><em class="fa fa-spinner fa-spin fa-4x">&nbsp;</em><br />{LANG.wait}</div>
</div>

<input name="addNew" class="btn btn-default" type="button" value="{LANG.addSubject}" />

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("div#pageContent").load("{MODULE_URL}=subject&list&random=" + nv_randomPassword(10));
	});
	$("input[name=addNew]").click(function() {
		window.location.href = "{MODULE_URL}=subject&add";
		return !1;
	});
	//]]>
</script>
<!-- END: main -->

<!-- BEGIN: action -->
<div id="pageContent">
	<form class="form-inline" id="addCat" method="post" action="{ACTION_URL}">
		<h3 class="myh3">{PTITLE}</h3>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td>{LANG.title} <span style="color:red">*</span></td>
						<td>
							<input title="{LANG.title}" class="form-control" style="width:300px" type="text" name="title" value="{CAT.title}" maxlength="255" />
						</td>
					</tr>
					<tr>
						<td style="vertical-align:top">{LANG.introduction}</td>
						<td><textarea style="width:300px" class="form-control" name="introduction" id="introduction">{CAT.introduction}</textarea></td>
					</tr>
					<tr>
						<td>{LANG.keywords}</td>
						<td>
							<label><input title="{LANG.keywords}" class="form-control" style="width:300px" type="text" name="keywords" value="{CAT.keywords}" maxlength="255" />({LANG.keywordsNote})</label>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<input type="hidden" name="save" value="1" />
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</form>
</div>
<script type="text/javascript">
	//<![CDATA[
	$("form#addCat").submit(function() {
		var a = $("input[name=title]").val();
		a = trim(a);
		$("input[name=title]").val(a);
		if (a == "") {
			alert("{LANG.errorIsEmpty}: " + $("input[name=title]").attr("title"));
			$("input[name=title]").select();
			return !1;
		}
		a = $(this).serialize();
		var c = $(this).attr("action");
		$("input[name=submit]").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : c,
			data : a,
			success : function(b) {
				if (b == "OK") {
					window.location.href = "{MODULE_URL}=subject";
				} else {
					alert(b);
					$("input[name=submit]").removeAttr("disabled");
				}
			}
		});
		return !1;
	});
	//]]>
</script>
<!-- END: action -->

<!-- BEGIN: list -->
<table class="table table-striped table-bordered table-hover" summary="{PARENTID}">
	<thead>
		<tr>
			<th style="width:100px"> {LANG.pos} </th>
			<th> {LANG.title} </th>
			<th class="w100"> {LANG.numlink} </th>
			<th style="width:120px">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
				<select name="p_{LOOP.id}" class="form-control newWeight">
					<!-- BEGIN: option -->
					<option value="{NEWWEIGHT.value}"{NEWWEIGHT.selected}>{NEWWEIGHT.value}</option>
					<!-- END: option -->
				</select>
			</td>
			<td> {LOOP.title} </td>
			<td>
				<select name="numlink" id="id_numlinks_{LOOP.id}" class="form-control" onchange="nv_chang_cat('{LOOP.id}','numlinks');">
					<!-- BEGIN: numlinks -->
					<option value="{NUMLINKS.key}" {NUMLINKS.selected}>{NUMLINKS.title}</option>
					<!-- END: numlinks -->
				</select>
			</td>
			<td><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{MODULE_URL}=subject&edit&id={LOOP.id}">{GLANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="del" href="{LOOP.id}">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<script type="text/javascript">
	//<![CDATA[
	$("a.del").click(function() {
		confirm("{LANG.delConfirm} ?") && $.ajax({
			type : "POST",
			url : "{MODULE_URL}=subject",
			data : "del=" + $(this).attr("href"),
			success : function(a) {
				a == "OK" ? window.location.href = window.location.href : alert(a);
			}
		});
		return !1;
	});
	$("select.newWeight").change(function() {
		var a = $(this).attr("name").split("_"), b = $(this).val(), c = this, a = a[1];
		$(this).attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}=subject",
			data : "cWeight=" + b + "&id=" + a,
			success : function(a) {
				a == "OK" ? ( a = $("table.tab1").attr("summary"), $("div#pageContent").load("{MODULE_URL}=subject&list&random=" + nv_randomPassword(10))) : alert("{LANG.errorChangeWeight}");
				$(c).removeAttr("disabled");
			}
		});
		return !1;
	});
	//]]>
</script>
<!-- END: list -->