<!-- BEGIN: main --><!-- BEGIN: upload -->
<div class="upload">
    <a href="{LINK_UP_FILE}"><b>UPLOAD FILE</b><img src="{BASE_SITE_THEMES_URL}/images/download/arrow_up_16.png" alt="" /></a>
</div><!-- END: upload --><!-- BEGIN: loop_tab_cate -->
<div class="download_column">
    <div class="title_cate">
        <div class="par_title">
            <a href="{LINK_URL_CATE}"><span><span>{CATE_TITLE}</span></span></a>
        </div>
        <ul>
            <!-- BEGIN: loop_sub_title -->
            <li>
                <a href ="{LINK_URL_CATE_SUB}">{CATE_TITLE_SUB}</a>
            </li>
            <!-- END: loop_sub_title -->
        </ul>
    </div>
    <div style="clear:both"></div>
    <!-- BEGIN: none_data -->
    <div class="dw_content" style="padding-top:5px;" align="center">
        {LANG.main_msg_none_data} 
    </div>
    <!-- END: none_data -->
	<!-- BEGIN: have_data -->
    <div class="dw_content">
        <div>
        	<h1><a href="{LINK_FILE_VIEW}">{FILE_TITLE}</a></h1>
        
            <!-- BEGIN: img --><img src="{SRC_IMG}" alt="{FILE_TITLE}"><!-- END: img -->
            <p>
                {FILE_HOME_TEXT} 
                <br style="margin-bottom:5px;">
                <font style="font-size:11px">
                    <b>{LANG.up_by_title}: 
                        <font class="color">
                            {AUTHOR}
                        </font>
                    </b>
                    -  {LANG.date_up_title}: {DATE_UP} 
                    <br>
                    <b>{LANG.count_view_title}: 
                        <font class="color">
                            {NUM_VIEW}
                        </font>
                        {LANG.download_title}: 
                        <font class="color">
                            {NUM_DOW}
                        </font>
                    </b>
                </font>
            </p>
            <span>{LANG.main_copyright}: {COPY_RIGHT} 
                <br>
                {LANG.main_filesize}: <b>{FILE_SIZE}</b>
                <br>
                <br>
                {ADMIN_LINK} <a href="{LINK_FILE_VIEW}">{LANG.main_view_title}</a>
            </span>
            <div style="clear:both"></div>
  		</p>
    </div>
    </div>
	<!-- END: have_data -->
</div>
<!-- END: loop_tab_cate -->
<!-- BEGIN: script -->
<script language="javascript">
    $('a[class="delfile"]').click(function(event){
        event.preventDefault();
        if (confirm('{LANG.file_del_confirm}')) {
            var href = $(this).attr('href');
            $.ajax({
                type: 'POST',
                url: href,
                data: '',
                success: function(data){
                    alert(data);
                    window.location = '{URL_RE}';
                }
            });
        }
    });
</script>
<!-- END: script -->
<!-- END: main -->
