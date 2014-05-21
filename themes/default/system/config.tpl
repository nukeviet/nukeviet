<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.js"></script>
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.css">

<div class="container">
	<p><em>{LANG.note}</em></p>
    <ul class="tabs">
        <li><a href="#tab1">{LANG.allpages}</a></li>
        <li><a href="#tab2">{LANG.content}</a></li>
        <li><a href="#tab6">{LANG.block}</a></li>
        <li><a href="#tab3">{LANG.header}</a></li>
        <li><a href="#tab4">{LANG.footer}</a></li>
        <li><a href="#tab5">CSS</a></li>
    </ul>
    <div class="tab_container">
    <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <div id="tab1" class="tab_content">
                <label>{LANG.color}</label>
                <input type="text" value="{CONFIG_THEME_BODY.color}" name="body_color" id="picker_body_color" style="width: 80px" /><br />
                
                <label>{LANG.font_size}</label>
                <input type="text" value="{CONFIG_THEME_BODY.font_size}" name="body_font_size" style="width: 80px" /><br />
                
                <label>{LANG.font_family}</label>
                <input type="text" value="{CONFIG_THEME_BODY.font_family}" name="body_font_family" style="width: 300px" /><br />
                
                <label>{LANG.font_style}</label>
                <input type="checkbox" name="body_font_weight" {CONFIG_THEME_BODY.font_weight} /><strong>{LANG.font_style_bold}</strong>
                <input type="checkbox" name="body_font_italic" {CONFIG_THEME_BODY.font_style} /><em>{LANG.font_style_italic}</em><br />
                
                <label>{LANG.background}</label>
                <input type="text" value="{CONFIG_THEME_BODY.background_color}" name="body_background_color" id="picker_body_background" style="width: 80px" />
                <input type="text" id="body_bg_image" value="{CONFIG_THEME_BODY.background_image}" name="body_background_image" placeholder="{LANG.background_imgage}" style="width: 201px" />
                <button onclick="nv_open_filemanage( 'body_bg_image' ); return false;"><em class="fa fa-folder-open-o">&nbsp;</em></button>
                <input type="text" value="{CONFIG_THEME_BODY.background_repeat}" name="body_background_repeat" style="width: 80px" placeholder="{LANG.background_imgage_repeat}" />
                <input type="text" value="{CONFIG_THEME_BODY.background_position}" name="body_background_position" style="width: 80px" placeholder="{LANG.background_imgage_postion}" />
                <br />
                
                <label>{LANG.margin}</label>
                <input type="text" name="body_margin" value="{CONFIG_THEME_BODY.margin}" placeholder="{LANG.margin_all}" style="width: 80px" />
                <input type="text" name="body_margin_top" value="{CONFIG_THEME_BODY.margin_top}" placeholder="{LANG.margin_top}" style="width: 80px" />
                <input type="text" name="body_margin_bottom" value="{CONFIG_THEME_BODY.margin_bottom}" placeholder="{LANG.margin_bottom}" style="width: 80px" />
                <input type="text" name="body_margin_left" value="{CONFIG_THEME_BODY.margin_left}" placeholder="{LANG.margin_left}" style="width: 80px" />
                <input type="text" name="body_margin_right" value="{CONFIG_THEME_BODY.margin_right}" placeholder="{LANG.margin_right}" style="width: 80px" />
                <br />
                
                <label>{LANG.padding}</label>
                <input type="text" name="body_padding" value="{CONFIG_THEME_BODY.padding}" placeholder="{LANG.padding_all}" style="width: 80px" />
                <input type="text" name="body_padding_top" value="{CONFIG_THEME_BODY.padding_top}" placeholder="{LANG.padding_top}" style="width: 80px" />
                <input type="text" name="body_padding_bottom" value="{CONFIG_THEME_BODY.padding_bottom}" placeholder="{LANG.padding_bottom}" style="width: 80px" />
                <input type="text" name="body_padding_left" value="{CONFIG_THEME_BODY.padding_left}" placeholder="{LANG.padding_left}" style="width: 80px" />
                <input type="text" name="body_padding_right" value="{CONFIG_THEME_BODY.padding_right}" placeholder="{LANG.padding_right}" style="width: 80px" />
                <br />
                
                <label>{LANG.link}</label>
                <input type="text" value="{CONFIG_THEME_A_LINK.color}" name="link_a_color" id="picker_link_color" style="width: 80px" placeholder="{LANG.color}" />
                <input type="checkbox" name="link_a_font_weight" {CONFIG_THEME_A_LINK.font_weight} /><strong>{LANG.font_style_bold}</strong>
                <input type="checkbox" name="link_a_font_italic" {CONFIG_THEME_A_LINK.font_style} /><em>{LANG.font_style_italic}</em>
                <br />
                
                <label>{LANG.link} (hover)</label>
                <input type="text" value="{CONFIG_THEME_A_LINK_HOVER.color}" name="link_a_hover_color" id="picker_link_hover_color" style="width: 80px" placeholder="{LANG.color}" />
                <input type="checkbox" name="link_a_hover_font_weight" {CONFIG_THEME_A_LINK_HOVER.font_weight} /><strong>{LANG.font_style_bold}</strong>
                <input type="checkbox" name="link_a_hover_font_italic" {CONFIG_THEME_A_LINK_HOVER.font_style} /><em>{LANG.font_style_italic}</em>
                <br />
                
                <label>{LANG.customcss}</label>
                <textarea name="body_customcss" style="width: 300px; height: 50px">{CONFIG_THEME_BODY.customcss}</textarea><br />
        </div>
        
        <div id="tab2" class="tab_content">
            <label>{LANG.margin}</label>
            <input type="text" name="content_margin" value="{CONFIG_THEME_CONTENT.margin}" placeholder="{LANG.margin_all}" style="width: 80px" />
            <input type="text" name="content_margin_top" value="{CONFIG_THEME_CONTENT.margin_top}" placeholder="{LANG.margin_top}" style="width: 80px" />
            <input type="text" name="content_margin_bottom" value="{CONFIG_THEME_CONTENT.margin_bottom}" placeholder="{LANG.margin_bottom}" style="width: 80px" />
            <input type="text" name="content_margin_left" value="{CONFIG_THEME_CONTENT.margin_left}" placeholder="{LANG.margin_left}" style="width: 80px" />
            <input type="text" name="content_margin_right" value="{CONFIG_THEME_CONTENT.margin_right}" placeholder="{LANG.margin_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.padding}</label>
            <input type="text" name="content_padding" value="{CONFIG_THEME_CONTENT.padding}" placeholder="{LANG.padding_all}" style="width: 80px" />
            <input type="text" name="content_padding_top" value="{CONFIG_THEME_CONTENT.padding_top}" placeholder="{LANG.padding_top}" style="width: 80px" />
            <input type="text" name="content_padding_bottom" value="{CONFIG_THEME_CONTENT.padding_bottom}" placeholder="{LANG.padding_bottom}" style="width: 80px" />
            <input type="text" name="content_padding_left" value="{CONFIG_THEME_CONTENT.padding_left}" placeholder="{LANG.padding_left}" style="width: 80px" />
            <input type="text" name="content_padding_right" value="{CONFIG_THEME_CONTENT.padding_right}" placeholder="{LANG.padding_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.size}</label>
            <input type="text" name="content_width" value="{CONFIG_THEME_CONTENT.width}" placeholder="{LANG.size_width}" style="width: 80px" />
            <input type="text" name="content_height" value="{CONFIG_THEME_CONTENT.height}" placeholder="{LANG.size_height}" style="width: 80px" />
            <br />
            
            <label>{LANG.customcss}</label>
            <textarea name="content_customcss" style="width: 300px; height: 50px">{CONFIG_THEME_CONTENT.customcss}</textarea><br />
        </div>
        
        <div id="tab3" class="tab_content">
            <label>{LANG.background}</label>
            <input type="text" value="{CONFIG_THEME_HEADER.background_color}" name="header_background_color" id="picker_header_background" style="width: 80px" />
            <input type="text" id="header_bg_image" value="{CONFIG_THEME_HEADER.background_image}" name="header_background_image" placeholder="{LANG.background_imgage}" style="width: 201px" />
            <button onclick="nv_open_filemanage( 'header_bg_image' ); return false;"><em class="fa fa-folder-open-o">&nbsp;</em></button>
            <input type="text" value="{CONFIG_THEME_HEADER.background_repeat}" name="header_background_repeat" style="width: 80px" placeholder="{LANG.background_imgage_repeat}" />
            <input type="text" value="{CONFIG_THEME_HEADER.background_position}" name="header_background_position" style="width: 80px" placeholder="{LANG.background_imgage_postion}" />
            <br />
            
            <label>{LANG.margin}</label>
            <input type="text" name="header_margin" value="{CONFIG_THEME_HEADER.margin}" placeholder="{LANG.margin_all}" style="width: 80px" />
            <input type="text" name="header_margin_top" value="{CONFIG_THEME_HEADER.margin_top}" placeholder="{LANG.margin_top}" style="width: 80px" />
            <input type="text" name="header_margin_bottom" value="{CONFIG_THEME_HEADER.margin_bottom}" placeholder="{LANG.margin_bottom}" style="width: 80px" />
            <input type="text" name="header_margin_left" value="{CONFIG_THEME_HEADER.margin_left}" placeholder="{LANG.margin_left}" style="width: 80px" />
            <input type="text" name="header_margin_right" value="{CONFIG_THEME_HEADER.margin_right}" placeholder="{LANG.margin_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.padding}</label>
            <input type="text" name="header_padding" value="{CONFIG_THEME_HEADER.padding}" placeholder="{LANG.padding_all}" style="width: 80px" />
            <input type="text" name="header_padding_top" value="{CONFIG_THEME_HEADER.padding_top}" placeholder="{LANG.padding_top}" style="width: 80px" />
            <input type="text" name="header_padding_bottom" value="{CONFIG_THEME_HEADER.padding_bottom}" placeholder="{LANG.padding_bottom}" style="width: 80px" />
            <input type="text" name="header_padding_left" value="{CONFIG_THEME_HEADER.padding_left}" placeholder="{LANG.padding_left}" style="width: 80px" />
            <input type="text" name="header_padding_right" value="{CONFIG_THEME_HEADER.padding_right}" placeholder="{LANG.padding_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.size}</label>
            <input type="text" name="header_width" value="{CONFIG_THEME_HEADER.width}" placeholder="{LANG.size_width}" style="width: 80px" />
            <input type="text" name="header_height" value="{CONFIG_THEME_HEADER.height}" placeholder="{LANG.size_height}" style="width: 80px" />
            <br />
            
            <label>{LANG.customcss}</label>
            <textarea name="header_customcss" style="width: 300px; height: 50px">{CONFIG_THEME_HEADER.customcss}</textarea><br />
        </div>
        
        <div id="tab4" class="tab_content">
            <label>{LANG.background}</label>
            <input type="text" value="{CONFIG_THEME_FOOTER.background_color}" name="footer_background_color" id="picker_footer_background" style="width: 80px" />
            <input type="text" id="footer_bg_image" value="{CONFIG_THEME_FOOTER.background_image}" name="footer_background_image" placeholder="{LANG.background_imgage}" style="width: 201px" />
            <button onclick="nv_open_filemanage( 'footer_bg_image' ); return false;"><em class="fa fa-folder-open-o">&nbsp;</em></button>
            <input type="text" value="{CONFIG_THEME_FOOTER.background_repeat}" name="footer_background_repeat" style="width: 80px" placeholder="{LANG.background_imgage_repeat}" />
            <input type="text" value="{CONFIG_THEME_FOOTER.background_position}" name="footer_background_position" style="width: 80px" placeholder="{LANG.background_imgage_postion}" />
            <br />
            
            <label>{LANG.margin}</label>
            <input type="text" name="footer_margin" value="{CONFIG_THEME_FOOTER.margin}" placeholder="{LANG.margin_all}" style="width: 80px" />
            <input type="text" name="footer_margin_top" value="{CONFIG_THEME_FOOTER.margin_top}" placeholder="{LANG.margin_top}" style="width: 80px" />
            <input type="text" name="footer_margin_bottom" value="{CONFIG_THEME_FOOTER.margin_bottom}" placeholder="{LANG.margin_bottom}" style="width: 80px" />
            <input type="text" name="footer_margin_left" value="{CONFIG_THEME_FOOTER.margin_left}" placeholder="{LANG.margin_left}" style="width: 80px" />
            <input type="text" name="footer_margin_right" value="{CONFIG_THEME_FOOTER.margin_right}" placeholder="{LANG.margin_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.padding}</label>
            <input type="text" name="footer_padding" value="{CONFIG_THEME_FOOTER.padding}" placeholder="{LANG.padding_all}" style="width: 80px" />
            <input type="text" name="footer_padding_top" value="{CONFIG_THEME_FOOTER.padding_top}" placeholder="{LANG.padding_top}" style="width: 80px" />
            <input type="text" name="footer_padding_bottom" value="{CONFIG_THEME_FOOTER.padding_bottom}" placeholder="{LANG.padding_bottom}" style="width: 80px" />
            <input type="text" name="footer_padding_left" value="{CONFIG_THEME_FOOTER.padding_left}" placeholder="{LANG.padding_left}" style="width: 80px" />
            <input type="text" name="footer_padding_right" value="{CONFIG_THEME_FOOTER.padding_right}" placeholder="{LANG.padding_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.size}</label>
            <input type="text" name="footer_width" value="{CONFIG_THEME_FOOTER.width}" placeholder="{LANG.size_width}" style="width: 80px" />
            <input type="text" name="footer_height" value="{CONFIG_THEME_FOOTER.height}" placeholder="{LANG.size_height}" style="width: 80px" />
            <br />
            
            <label>{LANG.customcss}</label>
            <textarea name="footer_customcss" style="width: 300px; height: 50px">{CONFIG_THEME_FOOTER.customcss}</textarea><br />
        </div>
        
        <div id="tab5" class="tab_content">
            <label style="width: 100%; text-align: left"><em>{LANG.general_css_note}</em></label>
            <textarea name="generalcss" style="width: 100%; height: 300px">{CONFIG_THEME_GENERCSS}</textarea>
        </div>
        
        <div id="tab6" class="tab_content">
        	<span class="note">{LANG.block_note}</span>
        	
            <label>{LANG.background}</label>
            <input type="text" value="{CONFIG_THEME_BLOCK.background_color}" name="block_background_color" id="picker_block_header_bg" style="width: 80px" />
            <input type="text" id="block_bg_image" value="{CONFIG_THEME_BLOCK.background_image}" name="block_background_image" placeholder="{LANG.background_imgage}" style="width: 201px" />
            <button onclick="nv_open_filemanage( 'block_bg_image' ); return false;"><em class="fa fa-folder-open-o">&nbsp;</em></button>
            <input type="text" value="{CONFIG_THEME_BLOCK.background_repeat}" name="block_background_repeat" style="width: 80px" placeholder="{LANG.background_imgage_repeat}" />
            <input type="text" value="{CONFIG_THEME_BLOCK.background_position}" name="block_background_position" style="width: 80px" placeholder="{LANG.background_imgage_postion}" /><br />
            
            <label>{LANG.margin}</label>
            <input type="text" name="block_margin" value="{CONFIG_THEME_BLOCK.margin}" placeholder="{LANG.margin_all}" style="width: 80px" />
            <input type="text" name="block_margin_top" value="{CONFIG_THEME_BLOCK.margin_top}" placeholder="{LANG.margin_top}" style="width: 80px" />
            <input type="text" name="block_margin_bottom" value="{CONFIG_THEME_BLOCK.margin_bottom}" placeholder="{LANG.margin_bottom}" style="width: 80px" />
            <input type="text" name="block_margin_left" value="{CONFIG_THEME_BLOCK.margin_left}" placeholder="{LANG.margin_left}" style="width: 80px" />
            <input type="text" name="block_margin_right" value="{CONFIG_THEME_BLOCK.margin_right}" placeholder="{LANG.margin_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.padding}</label>
            <input type="text" name="block_padding" value="{CONFIG_THEME_BLOCK.padding}" placeholder="{LANG.padding_all}" style="width: 80px" />
            <input type="text" name="block_padding_top" value="{CONFIG_THEME_BLOCK.padding_top}" placeholder="{LANG.padding_top}" style="width: 80px" />
            <input type="text" name="block_padding_bottom" value="{CONFIG_THEME_BLOCK.padding_bottom}" placeholder="{LANG.padding_bottom}" style="width: 80px" />
            <input type="text" name="block_padding_left" value="{CONFIG_THEME_BLOCK.padding_left}" placeholder="{LANG.padding_left}" style="width: 80px" />
            <input type="text" name="block_padding_right" value="{CONFIG_THEME_BLOCK.padding_right}" placeholder="{LANG.padding_right}" style="width: 80px" />
            <br />
            
            <label>{LANG.border}</label>
            <input type="text" value="{CONFIG_THEME_BLOCK.border_color}" name="block_border_color" id="picker_block_background" style="width: 80px" />
            <select name="block_border_style">
            	<option value="">&nbsp;</option>
            	<!-- BEGIN: block_border_style -->
            	<option value="{BLOCK_BORDER_STYLE.key}" {BLOCK_BORDER_STYLE.selected}>{BLOCK_BORDER_STYLE.value}</option>
            	<!-- END: block_border_style -->
            </select>
            <input type="text" name="block_border_width" value="{CONFIG_THEME_BLOCK.border_width}" placeholder="{LANG.size_width}" style="width: 80px" />
            <input type="text" name="block_border_radius" value="{CONFIG_THEME_BLOCK.border_radius}" placeholder="{LANG.radius}" style="width: 80px" /><br />
            
            <label>{LANG.heading}</label>
            <input type="text" value="{CONFIG_THEME_BLOCK_HEADING.background_color}" name="block_heading_background_color" id="picker_block_header_bg" style="width: 80px" />
            <input type="text" id="block_heading_bg_image" value="{CONFIG_THEME_BLOCK_HEADING.background_image}" name="block_heading_background_image" placeholder="{LANG.background_imgage}" style="width: 201px" />
            <button onclick="nv_open_filemanage( 'block_heading_bg_image' ); return false;"><em class="fa fa-folder-open-o">&nbsp;</em></button>
            <input type="text" value="{CONFIG_THEME_BLOCK_HEADING.background_repeat}" name="block_heading_background_repeat" style="width: 80px" placeholder="{LANG.background_imgage_repeat}" />
            <input type="text" value="{CONFIG_THEME_BLOCK_HEADING.background_position}" name="block_heading_background_position" style="width: 80px" placeholder="{LANG.background_imgage_postion}" /><br />
            
            <label>{LANG.customcss}</label>
            <textarea name="block_customcss" style="width: 300px; height: 50px">{CONFIG_THEME_BLOCK.customcss}</textarea><br />
		</div>
        
        <label style="width: 150px; margin-bottom: 20px">&nbsp;</label>
        <input type="submit" name="submit" value="{LANG.save}" />    
    </form>

    </div>
</div>

<script type="text/javascript">
//<![CDATA[
    $(document).ready(function() {
        $('#picker_body_color').css({'background-color' : $('#picker_body_color').val()});
        $('#picker_body_background').css({'background-color' : $('#picker_body_background').val()});
        $('#picker_content_background').css({'background-color' : $('#picker_content_background').val()});
        $('#picker_link_color').css({'background-color' : $('#picker_link_color').val()});
        $('#picker_link_hover_color').css({'background-color' : $('#picker_link_hover_color').val()});
        $('#picker_header_background').css({'background-color' : $('#picker_header_background').val()});
        $('#picker_footer_background').css({'background-color' : $('#picker_footer_background').val()});
        $('#picker_block_background').css({'background-color' : $('#picker_block_background').val()});
        $('#picker_block_header_bg').css({'background-color' : $('#picker_block_header_bg').val()});
        
        //Default Action
        $(".tab_content").hide(); //Hide all content
        $("ul.tabs li:first").addClass("active").show(); //Activate first tab
        $(".tab_content:first").show(); //Show first tab content
         
        //On Click Event
        $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active"); //Remove any "active" class
            $(this).addClass("active"); //Add "active" class to selected tab
            $(".tab_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            $(activeTab).fadeIn(); //Fade in the active content
            return false;
        });
    
    });
    
    $('#picker_block_header_bg, #picker_body_color, #picker_body_background, #picker_content_background, #picker_link_color, #picker_link_hover_color, #picker_header_background, #picker_footer_background, #picker_block_background').colpick({
        layout:'hex',
        submit:0,
        colorScheme:'dark',
        onChange:function(hsb,hex,rgb,el,bySetColor) {
            $(el).css('background-color','#'+hex);
            if(!bySetColor) $(el).val('#' + hex);
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
    });
    
    function nv_open_filemanage( area )
    {
        var alt = "backgroundimgalt";
        var path = "{UPLOADS_DIR}";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    }
//]]>
</script>
<!-- END:main -->