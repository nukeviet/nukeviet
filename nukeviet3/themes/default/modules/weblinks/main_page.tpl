<!-- BEGIN: main -->
<div id="weblink">
    <!-- BEGIN: loop_tab_cate --> 
    <div class="div_boder">
      <div class="title_cate">
          <div class="par_title"><a href="{LINK_URL_CATE}">{CATE_TITLE}</a></div>
          <ul>
              <!-- BEGIN: loop_sub_title -->
              <li><a href ="{LINK_URL_CATE_SUB}">{CATE_TITLE_SUB}</a></li>
              <!-- END: loop_sub_title -->
          </ul>
          <!-- BEGIN: next -->
          <span style="float:right; margin-right:5px; font-size:11px"><a href="{LINK_URL_CATE}">{NEXT_TITLE}</a></span>
          <!-- END: next -->
    </div>
    <!-- BEGIN: have_data -->
    <div class="content">
        <h1><a href="{WEBLINK_VIEW}">{WEBLINK_TITLE}</a></h1>
        <div>
        	<p>
            <!-- BEGIN: img -->
         	<a href="{WEBLINK_VIEW}"><img src="{SRC_IMG}" alt="" border="0" /></a>
          	<!-- END: img -->
            {TEXT_HOME}<br />
            <a href="{LINK_VISIT}" target="_blank" title="{WEBLINK_TITLE}">{LINK_URL}</a> 
            <span style="color:#F90"> {VIEW_TILTE} {NUM_VIEW}</span>
            <span style="color:#CCC"> {DATE_UP}</span>
            </p>
            {ADMIN_LINK}
            <div style="clear:both"></div>
        </div>
    </div>
    <div style="clear:both"></div>
    <!-- END: have_data -->
    </div>
    <!-- END: loop_tab_cate --> 
</div>
<!-- END: main -->

<span class="edit_icon"></span>
<span class="delete_icon"></span>