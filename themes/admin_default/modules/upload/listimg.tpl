<!-- BEGIN: main -->
<!-- BEGIN: loopimg -->
<div class="imgcontent{IMG.sel}" title="{IMG.title}" data-img="{IMG.is_img}">
    <div class="imgIcon">
        <img class="previewimg" alt="{IMG.alt}" title="{IMG.title}" name="{IMG.data}" src="{IMG.src}" width="{IMG.srcwidth}" height="{IMG.srcheight}"/>
    </div>
    <div class="imgInfo">
        <span class="nameSort">{IMG.name}</span>
        <span class="nameLong">{IMG.nameLong}</span>
        <span>{IMG.size}</span>
    </div>
</div>
<!-- END: loopimg -->
<!-- BEGIN: generate_page -->
<div class="clearfix"></div>
<hr />
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
//<![CDATA[
$('img.previewimg').lazyload({
    placeholder : "{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/grey.gif",
    container : $(".filebrowse")
});

$('.imgcontent').bind("mouseup contextmenu", function(e) {
    e.preventDefault();
    fileMouseup(this, e);
});

$('.imgcontent').dblclick(function() {
    insertvaluetofield();
});

$( "#imglist" ).selectable({
    filter: '.imgcontent',
    delay: 90,
    start: function( e, ui ){
        NVCMENU.hide();
        KEYPR.isSelectable = true;
        KEYPR.isFileSelectable = true;
    },
    selecting: function( e, ui ){
        fileSelecting(e, ui);
    },
    stop: function( e, ui ){
        fileSelectStop(e, ui);

        setTimeout(function(){
            KEYPR.isSelectable = false;
            KEYPR.isFileSelectable = false;
        }, 50);
    },
    unselecting: function( e, ui ){
        fileUnselect(e, ui);
    },
});
//]]>
</script>

<!-- BEGIN: imgsel -->
<script type="text/javascript">
$(".imgcontent.imgsel:first").attr('id', 'nv_imgsel_{NV_CURRENTTIME}');
window.location.href = "#nv_imgsel_{NV_CURRENTTIME}";
</script>
<!-- END: imgsel -->
<!-- END: main -->