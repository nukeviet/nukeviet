<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.js"></script>
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.css">

<div class="config_theme">
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1" style="width: 100%">
	    <caption>{LANG.config_general}</caption>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input name="submit" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td width="150"><strong>{LANG.config_allpages}</strong></td>
                <td>
                    <label>{LANG.config_property_color}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.color}" name="body_color" id="picker" /><br />
                    
                    <label>{LANG.config_property_font_size}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.font_size}" name="body_font_size" style="width: 50px" /><br />
                    
                    <label>{LANG.config_property_font_family}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.font_family}" name="body_font_family" /><br />
                    
                    <label>{LANG.config_property_font_style}</label>
                    <input type="checkbox" name="body_font_weight" {CONFIG_THEME_BODY.font_weight} /><strong>{LANG.config_property_font_style_bold}</strong>
                    <input type="checkbox" name="body_font_italic" {CONFIG_THEME_BODY.font_style} /><em>{LANG.config_property_font_style_italic}</em><br />
                    
                    <label>{LANG.config_property_margin}</label>
                    <input type="text" name="body_margin" value="{CONFIG_THEME_BODY.margin}" placeholder="{LANG.config_property_margin_all}" style="width: 80px" />
                    <input type="text" name="body_margin_top" value="{CONFIG_THEME_BODY.margin_top}" placeholder="{LANG.config_property_margin_top}" style="width: 80px" />
                    <input type="text" name="body_margin_bottom" value="{CONFIG_THEME_BODY.margin_bottom}" placeholder="{LANG.config_property_margin_bottom}" style="width: 80px" />
                    <input type="text" name="body_margin_left" value="{CONFIG_THEME_BODY.margin_left}" placeholder="{LANG.config_property_margin_left}" style="width: 80px" />
                    <input type="text" name="body_margin_right" value="{CONFIG_THEME_BODY.margin_right}" placeholder="{LANG.config_property_margin_right}" style="width: 80px" />
                    <br />
                    
                    <label>{LANG.config_property_padding}</label>
                    <input type="text" name="body_padding" placeholder="{LANG.config_property_padding_all}" style="width: 80px" />
                    <input type="text" name="body_padding_top" placeholder="{LANG.config_property_padding_top}" style="width: 80px" />
                    <input type="text" name="body_padding_bottom" placeholder="{LANG.config_property_padding_bottom}" style="width: 80px" />
                    <input type="text" name="body_padding_left" placeholder="{LANG.config_property_padding_left}" style="width: 80px" />
                    <input type="text" name="body_padding_right" placeholder="{LANG.config_property_padding_right}" style="width: 80px" />
                    <br />
                    
                    <label>{LANG.config_property_customcss}</label>
                    <textarea name="body_customcss" style="width: 300px; height: 50px">{CONFIG_THEME_BODY.customcss}</textarea><br />
                </td>
			</tr>
		</tbody>
	</table>
</form>
</div>

<script type="text/javascript">
    $('#picker').colpick({
        layout:'hex',
        submit:0,
        colorScheme:'dark',
        onChange:function(hsb,hex,rgb,el,bySetColor) {
            $(el).css('border-color','#'+hex);
            if(!bySetColor) $(el).val('#' + hex);
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
    });
</script>
<!-- END:main -->