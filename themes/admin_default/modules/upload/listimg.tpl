<!-- BEGIN: main -->
<!-- BEGIN: loopimg -->
<div class="imgcontent{IMG.sel}" title="{IMG.title}">
    <div style="width:100px;height:96px;display:table-cell; vertical-align:middle;">
        <img class="previewimg" title="{IMG.title}" name="{IMG.name}" src="{IMG.src}" width="{IMG.srcWidth}" height="{IMG.srcHeight}" />
    </div>
    <div class="imgInfo">{IMG.name0}<br />{IMG.size}</div>
</div>
<!-- END: loopimg -->
<div style="clear:both"></div>
<div style="height:100px"></div>
<script type="text/javascript">
//<![CDATA[
$("img.previewimg").lazyload({
    placeholder : "{NV_BASE_SITEURL}images/grey.gif",
    container: $(".filebrowse")
});
$(".imgcontent").bind("mouseup", function() {
  fileMouseup(this)
});

$(".imgcontent").dblclick(function() {
  if($("input[name=CKEditorFuncNum]").val() > 0 || $("input[name=area]").val() != "") {
    insertvaluetofield()
  }
  else if (window.top.opener!=null){
      window.top.opener.SetUrl(nv_base_siteurl + $("span#foldervalue").attr("title") + "/" + $("input[name=selFile]").val());
      window.top.close();
      window.top.opener.focus();
  }
});

$(".imgcontent").contextMenu("contextMenu", {menuStyle:{width:"120px"}, bindings:{select:function() {
  insertvaluetofield()
}, download:function() {
  download()
}, filepreview:function() {
  preview()
}, fileaddlogo:function() {
  addlogo()  
}, create:function() {
  create()
}, move:function() {
  move()
}, rename:function() {
  filerename()
}, filedelete:function() {
  filedelete()
}}});

<!-- BEGIN: imgsel -->
    $(".imgcontent.imgsel").attr('id', 'nv_imgsel_{NV_CURRENTTIME}');
    window.location.href="#nv_imgsel_{NV_CURRENTTIME}";
<!-- END: imgsel -->
//]]>
</script>
<!-- END: main -->