<!-- BEGIN: main -->
<div class="page_title" style="margin-bottom:5px">
    <!-- BEGIN: is_addfile_allow -->
    <div class="right">
        <a href="{UPLOAD}">{LANG.upload}</a>
    </div>
    <!-- END: is_addfile_allow -->
    {PAGE_TITLE}
</div>
<!-- BEGIN: subcats -->
<ul style="margin-bottom:15px;margin-top:10px">
    <!-- BEGIN: li -->
    <li>
        <img src="{IMG_FOLDER}folder.gif" alt="" width="22" height="22" style="margin-right:5px" /> 
        <strong>{SUBCAT.title}</strong> 
        <!-- BEGIN: description -->
        ({SUBCAT.description})
        <!-- END: description -->
    </li>
    <!-- END: li -->
</ul>
<!-- END: subcats -->
<!-- BEGIN: row -->
<div class="block_download">
    <div class="title_bar">
        <div class="right">
            {ROW.uploadtime}
        </div>
        <a href="{ROW.more_link}">{ROW.title}</a>
    </div>
    <div class="sub_bar">
        <!-- BEGIN: author_name -->
        <div class="right">
            {LANG.author_name}: <strong>{ROW.author_name}</strong>
        </div>
        <!-- END: author_name -->
        &raquo; {LANG.bycat}: {ROW.cattitle}
    </div>
    <!-- BEGIN: is_image -->
    <div class="image">
        <a title="{ROW.title}" href="{ROW.more_link}">
        <img alt="{ROW.title}" src="{FILEIMAGE.src}" width="{FILEIMAGE.width}" height="{FILEIMAGE.height}" /></a>
    </div>
    <!-- END: is_image -->
    <div class="introtext">
        {ROW.introtext}
    </div>
    <div class="more">
        <div class="right">
            <!-- BEGIN: is_admin -->
            <a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; 
            <a href="{ROW.del_link}" onclick="nv_del_row(this,{ROW.id});return false;">{GLANG.delete}</a> &divide; 
            <!-- END: is_admin -->
            <a href="{ROW.more_link}">{LANG.detail_or_download}</a>
        </div>
        {LANG.view_hits}: {ROW.view_hits} | {LANG.download_hits}: {ROW.download_hits}
        <!-- BEGIN: comment_allow -->
         | {LANG.comment_hits}: {ROW.comment_hits}
        <!-- END: comment_allow -->
    </div>
</div>
<!-- END: row -->
<!-- BEGIN: generate_page -->
<div style="margin-top:8px;">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
