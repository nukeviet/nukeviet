<!-- BEGIN: tree -->
<li class="{DIRTREE.class1}">
    <span {DIRTREE.style} class="{DIRTREE.class2}" title="{DIRTREE.title}"> &nbsp;{DIRTREE.titlepath}</span>
    <ul>
        <!-- BEGIN: tree_content -->
        {TREE_CONTENT}
        <!-- END: tree_content -->
    </ul>
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<ul id="foldertree" class="filetree">
    <li class="open collapsable">
        <span {DATA.style} class="{DATA.class}" title="{DATA.title}"> &nbsp; {DATA.titlepath}</span>
        <ul>
            <!-- BEGIN: main_content -->
            {CONTENT}
            <!-- END: main_content -->
        </ul>
    </li>
</ul>

<span class="upload-hide" id="path" title="{PATH}"></span>
<span class="upload-hide" id="foldervalue" title="{CURRENTPATH}"></span>
<span class="upload-hide" id="view_dir" title="{VIEW_DIR}"></span>
<span class="upload-hide" id="create_dir" title="{CREATE_DIR}"></span>
<span class="upload-hide" id="rename_dir" title="{RENAME_DIR}"></span>
<span class="upload-hide" id="delete_dir" title="{DELETE_DIR}"></span>
<span class="upload-hide" id="upload_file" title="{UPLOAD_FILE}"></span>
<span class="upload-hide" id="create_file" title="{CREATE_FILE}"></span>
<span class="upload-hide" id="rename_file" title="{RENAME_FILE}"></span>
<span class="upload-hide" id="delete_file" title="{DELETE_FILE}"></span>
<span class="upload-hide" id="move_file" title="{MOVE_FILE}"></span>
<span class="upload-hide" id="crop_file" title="{CROP_FILE}"></span>
<span class="upload-hide" id="rotate_file" title="{ROTATE_FILE}"></span>

<script type="text/javascript">
//<![CDATA[
// Init upload
NVUPLOAD.init();

$("#foldertree").treeview({
    collapsed : true,
    unique : true,
    persist : "location"
});

$('span.folder').bind('mouseup contextmenu', function(e){
    e.preventDefault();
    folderMouseup(this, e);
});
//]]>
</script>
<!-- END: main -->
