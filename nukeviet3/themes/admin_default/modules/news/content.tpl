<!-- BEGIN: main -->  
	<!-- BEGIN: error -->
   	<div class="newserror">
    	{error}
    </div>
	<!-- END: error -->
    <form action="" enctype="multipart/form-data" method="post">
    <input type="hidden" value="1" name="save">
    <input type="hidden" value="{rowcontent.id}" name="id">
    <div class="gray">
    	<table width="100%" style="margin-bottom:0">
        	<tr>
            	<td valign="top">
                    <table summary="" class="tab1">
                        <tbody>
                            <tr>
                                <td width="120px"><strong>{LANG.name}</strong></td>
                                <td>
                                	<input type="text" maxlength="255" value="{rowcontent.title}" id="idtitle" name="title" style="width:90%" />
                                    <input type="button" value="SET" onclick="get_alias();" style="border:1px solid #CCCCCC; font-size:11px"  /> 
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="second">
                            <tr>
                                <td><strong>{LANG.alias}: </strong></td>
                                <td>
                                	<input style="width:90%" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="255"/>
                                    <input type="button" value="GET" onclick="get_alias();" style="border:1px solid #CCCCCC;font-size:11px"  /> 
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td style="line-height:18px" valign="top">
                                	<strong>{LANG.content_cat}</strong>
                                    <br />
                                </td>
                                <td valign="top">
									<div style="padding:4px; height:130px;background:#FFFFFF; overflow:auto; text-align:left; border: 1px solid #CCCCCC">
                                    	<ul style="margin:0">
                                            {listcatid}
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                     </table>
                     <table summary="" class="tab1">
                        <tbody>
                            <tr>
                                <td valign="top"><strong>{LANG.content_topic}</strong></td>
                            </tr>
                        </tbody>
                        <tbody class="second">
                            <tr>
                                <td>
                                    <select name="topicid" style="width: 300px;">
                                        <!-- BEGIN: rowstopic -->
                                        <option value="{topicid}" {sl}>{topic_title}</option>
                                        <!-- END: rowstopic -->
                                    </select>
                                    <input type="text" maxlength="255" id="AjaxTopicText" value="{rowcontent.topictext}" name="topictext" style="width: 200px;"/>
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td><strong>{LANG.content_homeimg}</strong></td>
                            </tr>
                        </tbody>
                        <tbody class="second">
                            <tr>
                                <td>
                                    <input style="width:400px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/>
                                    <input type="button" value="Browse server" name="selectimg"/>
                                </td>
                            </tr>
                        </tbody>
                     </table>
                   	 <table summary="" class="tab1">
                        <tbody>
                            <tr>
                                <td width="180px"><strong>{LANG.content_homeimgalt}</strong></td>
                                <td>
                                	<input type="text" maxlength="255" value="{rowcontent.homeimgalt}" name="homeimgalt" style="width:98%" />
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="second">
                            <tr>
                                <td><strong>{LANG.imgposition}</strong></td>
                                <td>
                                	<select name="imgposition">
                                    <!-- BEGIN: looppos -->
                                    	<option value="{id_imgposition}" {posl}>{title_imgposition}</option>	
                                    <!-- END: looppos -->
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                     </table>
                     <table summary="" class="tab1">
                    	<tbody>
                            <tr>
                                <td><strong>{LANG.content_hometext}</strong> {LANG.content_notehome}</td>
                            </tr>
                        </tbody>
                        <tbody class="second">
                            <tr>
                                <td><textarea class="textareas" name="hometext" style="width: 98%; height:100px">{rowcontent.hometext}</textarea></td>
                            </tr>
                        </tbody>
                     </table>
                </td>
                <td valign="top" style="width: 250px">
                    <ul style="padding:4px; margin:0">
                    <!-- BEGIN:block_cat -->
                    <li>
                      <p class="message_head"><cite>{LANG.content_block}:</cite></p>
                        <div style="width: 260px; overflow: auto; text-align:left; margin:auto">
                          <table>
                          {row_block}
                          </table>
                        </div>
                    </li>
                    <!-- END:block_cat -->
                    <li>
                      <p class="message_head"><cite>{LANG.content_keywords}:</cite> <span class="timestamp"></span></p>
                      <div class="message_body">
                        <p>{LANG.content_keywords_note} <a onclick="create_keywords();" href="javascript:void(0);">{LANG.content_clickhere}</a></p>
                        <textarea rows="3" cols="20" id="keywords" name="keywords" style="width: 250px;">{rowcontent.keywords}</textarea>
                      </div>
                    </li>
                    <li>
                        <p class="message_head"><cite>{LANG.content_publ_date}</cite> <span class="timestamp">{LANG.content_notetime}</span></p>
                        <div class="message_body">
                          <center>
                            <input name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
                            <img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" 
                                onclick="popCalendar.show(this, 'publ_date', 'dd/mm/yyyy', false);" alt="" height="17">
                            <select name="phour">
                                {phour}
                            </select>:
                            <select name="pmin">
                                {pmin}
                            </select>
                          </center>
                        </div>
                    </li>
                    
                    <li>
                        <p class="message_head"><cite>{LANG.content_exp_date}:</cite> <span class="timestamp">{LANG.content_notetime}</span></p>
                        <div class="message_body"><center> 
                          <input name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
                          <img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" 
                          onclick="popCalendar.show(this, 'exp_date', 'dd/mm/yyyy', false);" alt="" height="17">
                          <select name="ehour">
                            {ehour}
                          </select>:
                          <select name="emin">
                            {emin}
                          </select>
                          </center>
                          <div style="margin-top: 5px;">
                            <input type="checkbox" value="1" name="archive" {archive_checked} /> 
                            <label>{LANG.content_archive}</label></div>
                        </div>
                    </li>
                    
                    <li>
                      <p class="message_head"><cite>{LANG.content_extra}:</cite></p>
                      <div class="message_body">
                        <div style="margin-bottom: 2px;">
                          <input type="checkbox" value="1" name="inhome" {inhome_checked}/>
                          <label>{LANG.content_inhome}</label>
                        </div>
                        <div style="margin-bottom: 2px;">
                            <label>{LANG.content_allowed_comm}</label> 
                            <select name="allowed_comm">
                                {allowed_comm}
                            </select>
                        </div>
                        <div style="margin-bottom: 2px;">
                            <input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked}/>
                            <label>{LANG.content_allowed_rating}</label>
                        </div>
                        <div style="margin-bottom: 2px;">
                            <input type="checkbox" value="1" name="allowed_send" {allowed_send_checked}/>
                            <label>{LANG.content_allowed_send}</label>
                        </div>
                        <div style="margin-bottom: 2px;">
                            <input type="checkbox" value="1" name="allowed_print" {allowed_print_checked} />
                            <label>{LANG.content_allowed_print}</label>
                        </div>
                        <div style="margin-bottom: 2px;">
                            <input type="checkbox" value="1" name="allowed_save" {allowed_save_checked} />
                            <label>{LANG.content_allowed_save}</label>
                        </div>
                      </div>
                    </li>
                </ul>
                </td>
            </tr>
        </table>
     </div>
     <div class="gray">
        <table summary="" class="tab1">
            <tbody>
                <tr>
                    <td><strong>{LANG.content_bodytext}</strong>{LANG.content_bodytext_note}</td>
                </tr>
            </tbody>
            <tbody class="second">
                <tr>
                    <td>
                    	<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
                        {edit_bodytext}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table summary="" class="tab1">
        	<tr>
                <td width="150px"><strong>{LANG.content_author}</strong></td>
                <td>
                    <input type="text" maxlength="255" value="{rowcontent.author}" name="author" style="width:225px;">
                </td>
            </tr>
            <tr>
                <td><strong>{LANG.content_sourceid}</strong></td>
                <td>
                    <select name="sourceid" style="width: 300px;">
                        {sourceid}
                    </select>
                    <input type="text" maxlength="255" id="AjaxSourceText" value="{rowcontent.sourcetext}" name="sourcetext" style="width: 255px;">
                </td>
            </tr>
            <tr>
            	<td><strong>{LANG.content_copyright}</strong></td>
                <td>
					<input type="checkbox" value="1" name="copyright" {checkcop}>
                </td>
            </tr>
        </table>
     </div> 
     <div class="gray">      
        <center>
        <!-- BEGIN:status -->
        <input name="statussave" type="submit" value="{LANG.save}" />
        <!-- END:status -->
        <!-- BEGIN:status0 -->
        <input name="status0" type="submit" value="{LANG.save_temp}" />
        <input name="status1" type="submit" value="{LANG.publtime}" />
        <!-- END:status0 -->
        </center>
     </div>
	</form>
<script type="text/javascript">
	$("input[name=selectimg]").click(function(){
		var area = "homeimg";
		var path= "{NV_UPLOADS_DIR}/{module_name}";	
		var currentpath= "{CURRENT}";						
		var type= "image";
		nv_open_browse_file("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area+"&path="+path+"&type="+type+"&currentpath="+currentpath, "NVImg", "850", "400","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	$(document).ready(function() {
		$("#AjaxSourceText").autocomplete(
			"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=sourceajax",
			{
				delay:10,
				minChars:2,
				matchSubset:1,
				matchContains:1,
				cacheLength:10,
				onItemSelect:selectItem,
				onFindValue:findValue,
				formatItem:formatItem,
				autoFill:true
			}
		);
	 
		$("#AjaxTopicText").autocomplete(
			"{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=topicajax",
			{
				delay:10,
				minChars:2,
				matchSubset:1,
				matchContains:1,
				cacheLength:10,
				onItemSelect:selectItem,
				onFindValue:findValue,
				autoFill:true
			}
		);
	 
	});
	<!-- BEGIN: getalias -->
	$("#idtitle").change(function () {
    	get_alias();
	});
	<!-- END: getalias -->
</script>
<!-- END:main -->