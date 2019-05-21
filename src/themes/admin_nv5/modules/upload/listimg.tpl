<ul>
    {foreach from=$ARRAY_FILES item=file}
    <li>
        <div class="file{if in_array($file.title, $SELECTFILE)} file-selected{/if}" data-file="{$file.title}" data-fdata="{$file.data}" data-alt="{$file.alt}">
            <div class="img">
                <div class="bg" style="background-image: url('{$file.src}');"><img src="{$file.src}" alt="{$file.alt}"></div>
            </div>
            <div class="name" title="{$file.title}"><i class="icon fas fa-file-image"></i>{$file.title}</div>
            <div class="info">{$file.size}</div>
        </div>
    </li>
    {/foreach}
</ul>

{*
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

//]]>
</script>

<!-- BEGIN: imgsel -->
<script type="text/javascript">
$(".imgcontent.imgsel:first").attr('id', 'nv_imgsel_{NV_CURRENTTIME}');
window.location.href = "#nv_imgsel_{NV_CURRENTTIME}";
</script>
<!-- END: imgsel -->
<!-- END: main -->
*}
