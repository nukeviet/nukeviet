<!-- BEGIN: add -->
<div id="pageContent">
    <form id="addCat" method="post" action="{ACTION_URL}">
        <h3 class="myh3">{PTITLE}</h3>
        <table class="tab1">
            <col style="width:200px" />
            <tbody class="second">
                <tr>
                    <td>{LANG.title} <span style="color:red">*</span>:</td>
                    <td><input title="{LANG.title}" class="txt" type="text" name="title" value="{DATA.title}" maxlength="255" /></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.exp_time}:</td>
                    <td>
                        <input type="text" name="exp_time" id="exp_time" value="{DATA.exp_time}" style="width: 150px;" maxlength="10" />
                        <img class="popCal" src="{NV_BASE_SITEURL}images/calendar.jpg" alt="" width="18" height="17" style="cursor: pointer; vertical-align: middle;" />
                        &nbsp;&nbsp;&nbsp;{LANG.emptyIsUnlimited}
                    </td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>{LANG.public}:</td>
                    <td><input title="{LANG.public}" type="checkbox" name="public" value="1"{DATA.public} /></td>
                </tr>
            </tbody>
        </table>
        <div>{LANG.content}</div>
        <div>{CONTENT}</div>
        <input type="hidden" name="save" value="1" />
        <input name="submit" type="submit" value="{LANG.save}" />
    </form>
</div>
<script type="text/javascript">
//<![CDATA[
function isDate() {
  var a = $("input[name=exp_time]").val();
  if(a == "") {
    return true
  }
  a = a.match(/^([\d]{1,2})\.([\d]{1,2})\.([\d]{4,4})$/m);
  if(a == null) {
    return alert("{LANG.dateError}"), $("input[name=exp_time]").select(), false
  }
  day = intval(a[1]);
  month = intval(a[2]);
  year = intval(a[3]);
  if(month < 1 || month > 12) {
    return alert("{LANG.dateError}"), $("input[name=exp_time]").select(), false
  }
  if(day < 1 || day > 31) {
    return alert("{LANG.dateError}"), $("input[name=exp_time]").select(), false
  }
  if(year < 1900) {
    return alert("{LANG.dateError}"), $("input[name=exp_time]").select(), false
  }
  if((month == 4 || month == 6 || month == 9 || month == 11) && day == 31) {
    return alert("{LANG.dateError}"), $("input[name=exp_time]").select(), false
  }
  if(month == 2 && (day > 29 || day == 29 && !(year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)))) {
    return alert("{LANG.dateError}"), $("input[name=exp_time]").select(), false
  }
  day < 10 && (day = "0" + day);
  month < 10 && (month = "0" + month);
  $("input[name=exp_time]").val(day + "." + month + "." + year);
  return true
}
$("img.popCal").click(function(a) {
  popCalendar.show(this, "exp_time", "dd.mm.yyyy", true, a.pageX - 20, a.pageY - 20);
  return false
});
$("form#addCat").submit(function() {
  var a = $("input[name=title]").val(), a = trim(a);
  $("input[name=title]").val(a);
  if(a == "") {
    return alert("{LANG.title_empty}"), $("input[name=title]").select(), false
  }
  if(!isDate()) {
    return false
  }
  <!-- BEGIN: is_editor -->
  $("textarea[name=content]").val(CKEDITOR.instances.content.getData());
  <!-- END: is_editor -->
  var a = $(this).serialize(), b = $(this).attr("action");
  $("input[name=submit]").attr("disabled", "disabled");
  $.ajax({type:"POST", url:b, data:a, success:function(a) {
    a == "OK" ? window.location.href = "{MODULE_URL}={OP}" : (alert(a), $("input[name=submit]").removeAttr("disabled"))
  }});
  return false
});
//]]>
</script>
<!-- END: add -->
<!-- BEGIN: list -->
<table class="tab1" style="width:100%">
    <col width="50" />
    <thead>
        <tr>
            <td>
                {LANG.weight}
            </td>
            <td>
                {LANG.title}
            </td>
            <td>
                {LANG.add_time}
            </td>
            <td>
                {LANG.exp_time}
            </td>
            <td>
                {LANG.public}
            </td>
            <td>
                {LANG.users}
            </td>
            <td>
                {GLANG.active}
            </td>
            <td>
                {GLANG.actions}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody{CLASS}>
        <tr>
            <td>
                <select name="w_{LOOP.id}" class="newWeight">
                    <!-- BEGIN: option -->
                    <option value="{NEWWEIGHT.value}"{NEWWEIGHT.selected}>{NEWWEIGHT.value}</option>
                    <!-- END: option -->
                </select>
            </td>
            <td>
                {LOOP.title}
            </td>
            <td>
                {LOOP.add_time}
            </td>
            <td>
                {LOOP.exp_time}
            </td>
            <td>
                <input name="p_{LOOP.id}" type="checkbox" class="public" value="1"{LOOP.public} />
            </td>
            <td>
                {LOOP.users}
            </td>
            <td>
                <input name="a_{LOOP.id}" type="checkbox" class="act" value="1"{LOOP.act} />
            </td>
            <td>
            <a href="{MODULE_URL}={OP}&edit&id={LOOP.id}">{GLANG.edit}</a> | <a class="del" href="{LOOP.id}">{GLANG.delete}</a> | <a href="{MODULE_URL}={OP}&userlist={LOOP.id}">{LANG.users}</a>
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<script type="text/javascript">
//<![CDATA[
$("a.del").click(function() {
  confirm("{LANG.delConfirm} ?") && $.ajax({type:"POST", url:"{MODULE_URL}={OP}", data:"del=" + $(this).attr("href"), success:function(a) {
    a == "OK" ? window.location.href = window.location.href : alert(a)
  }});
  return false
});
$("select.newWeight").change(function() {
  var a = $(this).attr("name").split("_"), b = $(this).val(), c = this, a = a[1];
  $("#pageContent input, #pageContent select").attr("disabled", "disabled");
  $.ajax({type:"POST", url:"{MODULE_URL}={OP}", data:"cWeight=" + b + "&id=" + a, success:function(a) {
    a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&list&random=" + nv_randomPassword(10)) : alert("{LANG.errorChangeWeight}");
    $("#pageContent input, #pageContent select").removeAttr("disabled")
  }});
  return false
});
$("input.public").change(function() {
  var a = $(this).attr("name").split("_"), a = a[1], b = this;
  $("#pageContent input, #pageContent select").attr("disabled", "disabled");
  $.ajax({type:"POST", url:"{MODULE_URL}={OP}", data:"pub=" + a + "&rand=" + nv_randomPassword(10), success:function(a) {
    a = a.split("|");
    $("#pageContent input, #pageContent select").removeAttr("disabled");
    a[0] == "ERROR" && (a[1] == "1" ? $(b).attr("checked", "checked") : $(b).removeAttr("checked"))
  }});
  return false
});
$("input.act").change(function() {
  var a = $(this).attr("name").split("_"), a = a[1], b = this;
  $("#pageContent input, #pageContent select").attr("disabled", "disabled");
  $.ajax({type:"POST", url:"{MODULE_URL}={OP}", data:"act=" + a + "&rand=" + nv_randomPassword(10), success:function(a) {
    a = a.split("|");
    $("#pageContent input, #pageContent select").removeAttr("disabled");
    a[0] == "ERROR" && (a[1] == "1" ? $(b).attr("checked", "checked") : $(b).removeAttr("checked"))
  }});
  return false
});
//]]>
</script>
<!-- END: list -->
<!-- BEGIN: main -->
<div id="ablist">
    <input name="addNew" type="button" value="{LANG.nv_admin_add}" />
</div>
<div class="myh3">{GLANG.mod_groups}</div>
<div id="pageContent"></div>
<script type="text/javascript">
//<![CDATA[
$(function() {
  $("div#pageContent").load("{MODULE_URL}={OP}&list&random=" + nv_randomPassword(10))
});
$("input[name=addNew]").click(function() {
  window.location.href = "{MODULE_URL}={OP}&add";
  return false
});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: listUsers -->
<h3 class="myh3">{PTITLE}</h3>
<!-- BEGIN: ifExists -->
<table class="tab1" style="width:100%">
    <col width="50" />
    <thead>
        <tr>
            <td>
                {LANG.userid}
            </td>
            <td>
                {LANG.account}
            </td>
            <td>
                {LANG.name}
            </td>
            <td>
                {LANG.email}
            </td>
            <td>
                {GLANG.actions}
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tbody{CLASS}>
        <tr>
            <td>
                {LOOP.userid}
            </td>
            <td>
                {LOOP.username}
            </td>
            <td>
                {LOOP.full_name}
            </td>
            <td>
                <a href="mailto:{LOOP.email}">{LOOP.email}</a>
            </td>
            <td>
            <a href="{MODULE_URL}=edit&userid={LOOP.userid}">{LANG.detail}</a> | <a class="del" href="{LOOP.userid}">{LANG.exclude_user2}</a>
            </td>
        </tr>
    </tbody>
    <!-- END: loop -->
</table>
<script type="text/javascript">
//<![CDATA[
$("a.del").click(function() {
  $.ajax({type:"POST", url:"{MODULE_URL}={OP}", data:"gid={GID}&exclude=" + $(this).attr("href"), success:function(a) {
    a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a)
  }});
  return false
});
//]]>
</script>
<!-- END: ifExists -->
<!-- END: listUsers -->
<!-- BEGIN: userlist -->
<div id="ablist">
    {LANG.search_id}: <input title="{LANG.search_id}" class="txt" type="text" name="uid" id="uid" value="" maxlength="11" style="width:50px" />
    <input name="searchUser" type="button" value="{GLANG.search}" />
    <input name="addUser" type="button" value="{LANG.addMemberToGroup}" />
</div>
<div id="pageContent"></div>
<script type="text/javascript">
//<![CDATA[
$(function() {
  $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10))
});
$("input[name=searchUser]").click(function() {
  var a = $(this).prev().attr("id");
  nv_open_browse_file("{MODULE_URL}=getuserid&area=" + a, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
  return false
});
$("input[name=addUser]").click(function() {
  var a = $("#ablist input[name=uid]").val(), a = intval(a);
  a == 0 && (a = "");
  $("#ablist input[name=uid]").val(a);
  if(a == "") {
    return alert("{LANG.choiceUserID}"), $("#ablist input[name=uid]").select(), false
  }
  $("#pageContent input, #pageContent select").attr("disabled", "disabled");
  $.ajax({type:"POST", url:"{MODULE_URL}={OP}", data:"gid={GID}&uid=" + a + "&rand=" + nv_randomPassword(10), success:function(a) {
    a == "OK" ? ($("#ablist input[name=uid]").val(""), $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10))) : alert(a)
  }});
  return false
});
//]]>
</script>
<!-- END: userlist -->