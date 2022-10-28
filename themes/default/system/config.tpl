<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.js"></script>
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.css">
<div class="alert alert-warning"><i class="fa fa-fw fa-info-circle"></i>{LANG.note}</div>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <ul class="nav nav-tabs" role="tablist" id="cfgThemeTabs">
        <li role="presentation" class="{TAB0_ACTIVE}"><a href="#configThemeAll" aria-controls="configThemeAll" aria-offsets="0" role="tab" data-toggle="tab">{LANG.allpages}</a></li>
        <li role="presentation" class="{TAB1_ACTIVE}"><a href="#configThemeContent" aria-controls="configThemeContent" aria-offsets="1" role="tab" data-toggle="tab">{LANG.content}</a></li>
        <li role="presentation" class="{TAB2_ACTIVE}"><a href="#configThemeBlock" aria-controls="configThemeBlock" aria-offsets="2" role="tab" data-toggle="tab">{LANG.block}</a></li>
        <li role="presentation" class="{TAB3_ACTIVE}"><a href="#configThemeHeader" aria-controls="configThemeHeader" aria-offsets="3" role="tab" data-toggle="tab">{LANG.header}</a></li>
        <li role="presentation" class="{TAB4_ACTIVE}"><a href="#configThemeFooter" aria-controls="configThemeFooter" aria-offsets="4" role="tab" data-toggle="tab">{LANG.footer}</a></li>
        <li role="presentation" class="{TAB5_ACTIVE}"><a href="#configThemeCSS" aria-controls="configThemeCSS" aria-offsets="5" role="tab" data-toggle="tab">CSS</a></li>
        <li role="presentation" class="{TAB6_ACTIVE}"><a href="#configThemeGFont" aria-controls="configThemeGFont" aria-offsets="6" role="tab" data-toggle="tab">Google Fonts</a></li>
    </ul>
    <div class="tab-content theme-config-tabpanel">
        <div role="tabpanel" id="configThemeAll" class="tab-pane{TAB0_ACTIVE}">
            <div class="panel-body theme-config-basic">
                <div class="form-group">
                    <label class="cname">{LANG.color}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.color}" name="body_color" id="picker_body_color" class="form-control input-sm sizem" />
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.font_size}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.font_size}" name="body_font_size" class="form-control input-sm sizem" />
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.font_family}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.font_family}" name="body_font_family" class="form-control input-sm" />
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.font_style}</label>
                    <label class="cval"><input type="checkbox" name="body_font_weight" {CONFIG_THEME_BODY.font_weight} /><strong>{LANG.font_style_bold}</strong></label>
                    <label class="cval"><input type="checkbox" name="body_font_italic" {CONFIG_THEME_BODY.font_style} /><em>{LANG.font_style_italic}</em></label>
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.background}</label>
                    <input type="text" value="{CONFIG_THEME_BODY.background_color}" name="body_background_color" id="picker_body_background" class="form-control input-sm sizem"/>
                    <div class="input-group-wrap">
                        <div class="input-group input-group-sm">
                            <input type="text" id="body_bg_image" value="{CONFIG_THEME_BODY.background_image}" name="body_background_image" placeholder="{LANG.background_imgage}" class="form-control input-sm" />
                            <div class="input-group-btn">
                                <button onclick="nv_open_filemanage( 'body_bg_image' ); return false;" class="btn btn-default btn-sm"><i class="fa fa-folder-open-o"></i></button>
                            </div>
                        </div>
                    </div>
                    <input type="text" value="{CONFIG_THEME_BODY.background_repeat}" name="body_background_repeat" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_repeat}" />
                    <input type="text" value="{CONFIG_THEME_BODY.background_position}" name="body_background_position" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_postion}" />
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.margin}</label>
                    <input type="text" name="body_margin" value="{CONFIG_THEME_BODY.margin}" placeholder="{LANG.margin_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_margin_top" value="{CONFIG_THEME_BODY.margin_top}" placeholder="{LANG.margin_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_margin_bottom" value="{CONFIG_THEME_BODY.margin_bottom}" placeholder="{LANG.margin_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_margin_left" value="{CONFIG_THEME_BODY.margin_left}" placeholder="{LANG.margin_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_margin_right" value="{CONFIG_THEME_BODY.margin_right}" placeholder="{LANG.margin_right}" class="form-control input-sm sizem"/>
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.padding}</label>
                    <input type="text" name="body_padding" value="{CONFIG_THEME_BODY.padding}" placeholder="{LANG.padding_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_padding_top" value="{CONFIG_THEME_BODY.padding_top}" placeholder="{LANG.padding_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_padding_bottom" value="{CONFIG_THEME_BODY.padding_bottom}" placeholder="{LANG.padding_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_padding_left" value="{CONFIG_THEME_BODY.padding_left}" placeholder="{LANG.padding_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="body_padding_right" value="{CONFIG_THEME_BODY.padding_right}" placeholder="{LANG.padding_right}" class="form-control input-sm sizem"/>
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.link}</label>
                    <input type="text" value="{CONFIG_THEME_A_LINK.color}" name="link_a_color" id="picker_link_color" class="form-control input-sm sizem"placeholder="{LANG.color}" />
                    <label class="cval"><input type="checkbox" name="link_a_font_weight" {CONFIG_THEME_A_LINK.font_weight} /><strong>{LANG.font_style_bold}</strong></label>
                    <label class="cval"><input type="checkbox" name="link_a_font_italic" {CONFIG_THEME_A_LINK.font_style} /><em>{LANG.font_style_italic}</em></label>
                </div>
                <div class="form-group">
                    <label class="cname">{LANG.link} (hover)</label>
                    <input type="text" value="{CONFIG_THEME_A_LINK_HOVER.color}" name="link_a_hover_color" id="picker_link_hover_color" class="form-control input-sm sizem"placeholder="{LANG.color}" />
                    <label class="cval"><input type="checkbox" name="link_a_hover_font_weight" {CONFIG_THEME_A_LINK_HOVER.font_weight} /><strong>{LANG.font_style_bold}</strong></label>
                    <label class="cval"><input type="checkbox" name="link_a_hover_font_italic" {CONFIG_THEME_A_LINK_HOVER.font_style} /><em>{LANG.font_style_italic}</em></label>
                </div>
                <div class="clearfix rowTextarea">
                    <label class="cname">{LANG.customcss}</label>
                    <div class="wrp">
                        <textarea name="body_customcss" class="form-control" rows="4">{CONFIG_THEME_BODY.customcss}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="configThemeContent" class="tab-pane{TAB1_ACTIVE}">
            <div class="panel-body theme-config-basic">
                <div class="form-group">
                    <label class="cname">{LANG.margin}</label>
                    <input type="text" name="content_margin" value="{CONFIG_THEME_CONTENT.margin}" placeholder="{LANG.margin_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_margin_top" value="{CONFIG_THEME_CONTENT.margin_top}" placeholder="{LANG.margin_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_margin_bottom" value="{CONFIG_THEME_CONTENT.margin_bottom}" placeholder="{LANG.margin_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_margin_left" value="{CONFIG_THEME_CONTENT.margin_left}" placeholder="{LANG.margin_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_margin_right" value="{CONFIG_THEME_CONTENT.margin_right}" placeholder="{LANG.margin_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.padding}</label>
                    <input type="text" name="content_padding" value="{CONFIG_THEME_CONTENT.padding}" placeholder="{LANG.padding_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_padding_top" value="{CONFIG_THEME_CONTENT.padding_top}" placeholder="{LANG.padding_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_padding_bottom" value="{CONFIG_THEME_CONTENT.padding_bottom}" placeholder="{LANG.padding_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_padding_left" value="{CONFIG_THEME_CONTENT.padding_left}" placeholder="{LANG.padding_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_padding_right" value="{CONFIG_THEME_CONTENT.padding_right}" placeholder="{LANG.padding_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.size}</label>
                    <input type="text" name="content_width" value="{CONFIG_THEME_CONTENT.width}" placeholder="{LANG.size_width}" class="form-control input-sm sizem"/>
                    <input type="text" name="content_height" value="{CONFIG_THEME_CONTENT.height}" placeholder="{LANG.size_height}" class="form-control input-sm sizem"/>
                </div>

                <div class="clearfix rowTextarea">
                    <label class="cname">{LANG.customcss}</label>
                    <div class="wrp">
                        <textarea name="content_customcss" class="form-control" rows="4">{CONFIG_THEME_CONTENT.customcss}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="configThemeBlock" class="tab-pane{TAB2_ACTIVE}">
            <div class="panel-body theme-config-basic">
            	<p><i>{LANG.block_note}</i></p>

                <div class="form-group">
                    <label class="cname">{LANG.background}</label>
                    <input type="text" value="{CONFIG_THEME_BLOCK.background_color}" name="block_background_color" id="picker_block_header_bg" class="form-control input-sm sizem"/>
                    <div class="input-group-wrap">
                        <div class="input-group input-group-sm">
                            <input type="text" id="block_bg_image" value="{CONFIG_THEME_BLOCK.background_image}" name="block_background_image" placeholder="{LANG.background_imgage}" class="form-control input-sm"/>
                            <div class="input-group-btn">
                                <button onclick="nv_open_filemanage( 'block_bg_image' ); return false;" class="btn btn-default btn-sm"><i class="fa fa-folder-open-o"></i></button>
                            </div>
                        </div>
                    </div>
                    <input type="text" value="{CONFIG_THEME_BLOCK.background_repeat}" name="block_background_repeat" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_repeat}" />
                    <input type="text" value="{CONFIG_THEME_BLOCK.background_position}" name="block_background_position" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_postion}" />
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.margin}</label>
                    <input type="text" name="block_margin" value="{CONFIG_THEME_BLOCK.margin}" placeholder="{LANG.margin_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_margin_top" value="{CONFIG_THEME_BLOCK.margin_top}" placeholder="{LANG.margin_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_margin_bottom" value="{CONFIG_THEME_BLOCK.margin_bottom}" placeholder="{LANG.margin_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_margin_left" value="{CONFIG_THEME_BLOCK.margin_left}" placeholder="{LANG.margin_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_margin_right" value="{CONFIG_THEME_BLOCK.margin_right}" placeholder="{LANG.margin_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.padding}</label>
                    <input type="text" name="block_padding" value="{CONFIG_THEME_BLOCK.padding}" placeholder="{LANG.padding_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_padding_top" value="{CONFIG_THEME_BLOCK.padding_top}" placeholder="{LANG.padding_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_padding_bottom" value="{CONFIG_THEME_BLOCK.padding_bottom}" placeholder="{LANG.padding_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_padding_left" value="{CONFIG_THEME_BLOCK.padding_left}" placeholder="{LANG.padding_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_padding_right" value="{CONFIG_THEME_BLOCK.padding_right}" placeholder="{LANG.padding_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.border}</label>
                    <input type="text" value="{CONFIG_THEME_BLOCK.border_color}" name="block_border_color" id="picker_block_background" class="form-control input-sm sizem"/>
                    <select name="block_border_style" class="form-control input-sm sizem">
                    	<option value="">&nbsp;</option>
                    	<!-- BEGIN: block_border_style -->
                    	<option value="{BLOCK_BORDER_STYLE.key}" {BLOCK_BORDER_STYLE.selected}>{BLOCK_BORDER_STYLE.value}</option>
                    	<!-- END: block_border_style -->
                    </select>
                    <input type="text" name="block_border_width" value="{CONFIG_THEME_BLOCK.border_width}" placeholder="{LANG.size_width}" class="form-control input-sm sizem"/>
                    <input type="text" name="block_border_radius" value="{CONFIG_THEME_BLOCK.border_radius}" placeholder="{LANG.radius}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.heading}</label>
                    <input type="text" value="{CONFIG_THEME_BLOCK_HEADING.background_color}" name="block_heading_background_color" id="picker_block_header_bg" class="form-control input-sm sizem"/>
                    <div class="input-group-wrap">
                        <div class="input-group input-group-sm">
                            <input type="text" id="block_heading_bg_image" value="{CONFIG_THEME_BLOCK_HEADING.background_image}" name="block_heading_background_image" placeholder="{LANG.background_imgage}" class="form-control input-sm"/>
                            <div class="input-group-btn">
                                <button onclick="nv_open_filemanage( 'block_heading_bg_image' ); return false;" class="btn btn-default btn-sm"><i class="fa fa-folder-open-o"></i></button>
                            </div>
                        </div>
                    </div>
                    <input type="text" value="{CONFIG_THEME_BLOCK_HEADING.background_repeat}" name="block_heading_background_repeat" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_repeat}" />
                    <input type="text" value="{CONFIG_THEME_BLOCK_HEADING.background_position}" name="block_heading_background_position" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_postion}" />
                </div>

                <div class="clearfix rowTextarea">
                    <label class="cname">{LANG.customcss}</label>
                    <div class="wrp">
                        <textarea name="block_customcss" class="form-control" rows="4">{CONFIG_THEME_BLOCK.customcss}</textarea>
                    </div>
                </div>
            </div>
		</div>

        <div role="tabpanel" id="configThemeHeader" class="tab-pane{TAB3_ACTIVE}">
            <div class="panel-body theme-config-basic">
                <div class="form-group">
                    <label class="cname">{LANG.background}</label>
                    <input type="text" value="{CONFIG_THEME_HEADER.background_color}" name="header_background_color" id="picker_header_background" class="form-control input-sm sizem"/>
                    <div class="input-group-wrap">
                        <div class="input-group input-group-sm">
                            <input type="text" id="header_bg_image" value="{CONFIG_THEME_HEADER.background_image}" name="header_background_image" placeholder="{LANG.background_imgage}" class="form-control input-sm"/>
                            <div class="input-group-btn">
                                <button onclick="nv_open_filemanage( 'header_bg_image' ); return false;" class="btn btn-default btn-sm"><i class="fa fa-folder-open-o"></i></button>
                            </div>
                        </div>
                    </div>
                    <input type="text" value="{CONFIG_THEME_HEADER.background_repeat}" name="header_background_repeat" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_repeat}" />
                    <input type="text" value="{CONFIG_THEME_HEADER.background_position}" name="header_background_position" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_postion}" />
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.margin}</label>
                    <input type="text" name="header_margin" value="{CONFIG_THEME_HEADER.margin}" placeholder="{LANG.margin_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_margin_top" value="{CONFIG_THEME_HEADER.margin_top}" placeholder="{LANG.margin_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_margin_bottom" value="{CONFIG_THEME_HEADER.margin_bottom}" placeholder="{LANG.margin_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_margin_left" value="{CONFIG_THEME_HEADER.margin_left}" placeholder="{LANG.margin_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_margin_right" value="{CONFIG_THEME_HEADER.margin_right}" placeholder="{LANG.margin_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.padding}</label>
                    <input type="text" name="header_padding" value="{CONFIG_THEME_HEADER.padding}" placeholder="{LANG.padding_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_padding_top" value="{CONFIG_THEME_HEADER.padding_top}" placeholder="{LANG.padding_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_padding_bottom" value="{CONFIG_THEME_HEADER.padding_bottom}" placeholder="{LANG.padding_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_padding_left" value="{CONFIG_THEME_HEADER.padding_left}" placeholder="{LANG.padding_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_padding_right" value="{CONFIG_THEME_HEADER.padding_right}" placeholder="{LANG.padding_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.size}</label>
                    <input type="text" name="header_width" value="{CONFIG_THEME_HEADER.width}" placeholder="{LANG.size_width}" class="form-control input-sm sizem"/>
                    <input type="text" name="header_height" value="{CONFIG_THEME_HEADER.height}" placeholder="{LANG.size_height}" class="form-control input-sm sizem"/>
                </div>

                <div class="clearfix rowTextarea">
                    <label class="cname">{LANG.customcss}</label>
                    <div class="wrp">
                        <textarea name="header_customcss" class="form-control" rows="4">{CONFIG_THEME_HEADER.customcss}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="configThemeFooter" class="tab-pane{TAB4_ACTIVE}">
            <div class="panel-body theme-config-basic">
                <div class="form-group">
                    <label class="cname">{LANG.background}</label>
                    <input type="text" value="{CONFIG_THEME_FOOTER.background_color}" name="footer_background_color" id="picker_footer_background" class="form-control input-sm sizem"/>
                    <div class="input-group-wrap">
                        <div class="input-group input-group-sm">
                            <input type="text" id="footer_bg_image" value="{CONFIG_THEME_FOOTER.background_image}" name="footer_background_image" placeholder="{LANG.background_imgage}" class="form-control input-sm"/>
                            <div class="input-group-btn">
                                <button onclick="nv_open_filemanage( 'footer_bg_image' ); return false;" class="btn btn-default btn-sm"><i class="fa fa-folder-open-o"></i></button>
                            </div>
                        </div>
                    </div>
                    <input type="text" value="{CONFIG_THEME_FOOTER.background_repeat}" name="footer_background_repeat" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_repeat}" />
                    <input type="text" value="{CONFIG_THEME_FOOTER.background_position}" name="footer_background_position" class="form-control input-sm sizem" placeholder="{LANG.background_imgage_postion}" />
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.margin}</label>
                    <input type="text" name="footer_margin" value="{CONFIG_THEME_FOOTER.margin}" placeholder="{LANG.margin_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_margin_top" value="{CONFIG_THEME_FOOTER.margin_top}" placeholder="{LANG.margin_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_margin_bottom" value="{CONFIG_THEME_FOOTER.margin_bottom}" placeholder="{LANG.margin_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_margin_left" value="{CONFIG_THEME_FOOTER.margin_left}" placeholder="{LANG.margin_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_margin_right" value="{CONFIG_THEME_FOOTER.margin_right}" placeholder="{LANG.margin_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.padding}</label>
                    <input type="text" name="footer_padding" value="{CONFIG_THEME_FOOTER.padding}" placeholder="{LANG.padding_all}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_padding_top" value="{CONFIG_THEME_FOOTER.padding_top}" placeholder="{LANG.padding_top}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_padding_bottom" value="{CONFIG_THEME_FOOTER.padding_bottom}" placeholder="{LANG.padding_bottom}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_padding_left" value="{CONFIG_THEME_FOOTER.padding_left}" placeholder="{LANG.padding_left}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_padding_right" value="{CONFIG_THEME_FOOTER.padding_right}" placeholder="{LANG.padding_right}" class="form-control input-sm sizem"/>
                </div>

                <div class="form-group">
                    <label class="cname">{LANG.size}</label>
                    <input type="text" name="footer_width" value="{CONFIG_THEME_FOOTER.width}" placeholder="{LANG.size_width}" class="form-control input-sm sizem"/>
                    <input type="text" name="footer_height" value="{CONFIG_THEME_FOOTER.height}" placeholder="{LANG.size_height}" class="form-control input-sm sizem"/>
                </div>

                <div class="clearfix rowTextarea">
                    <label class="cname">{LANG.customcss}</label>
                    <div class="wrp">
                        <textarea name="footer_customcss" class="form-control" rows="4">{CONFIG_THEME_FOOTER.customcss}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="configThemeCSS" class="tab-pane{TAB5_ACTIVE}">
            <div class="panel-body">
                <label><i>{LANG.general_css_note}:</i></label>
                <textarea name="generalcss" class="form-control" rows="20">{CONFIG_THEME_GENERCSS}</textarea>
            </div>
        </div>

        <div role="tabpanel" id="configThemeGFont" class="tab-pane{TAB6_ACTIVE}">
            <div class="panel-body theme-config-gfont">
                <p>{LANG.gfont_note}</p>
                <div class="form-group">
                    <label class="cname">Family</label>
                    <input type="text" name="gfont_family" value="{CONFIG_THEME_GFONT.family}" placeholder="family" class="form-control input-sm" />
                    <span>({LANG.exp}, Roboto)</span>
                </div>
                <div class="form-group">
                    <label class="cname">Styles</label>
                    <input type="text" name="gfont_styles" value="{CONFIG_THEME_GFONT.styles}" placeholder="styles" class="form-control input-sm" />
                    <span>({LANG.exp}, 400,400italic)</span>
                </div>
                <div class="clearfix">
                    <label class="cname">Subset</label>
                    <input type="text" name="gfont_subset" value="{CONFIG_THEME_GFONT.subset}" placeholder="subset" class="form-control input-sm" />
                    <span>({LANG.exp}, latin,vietnamese)</span>
                </div>
            </div>
        </div>

    </div>
    <div class="theme-config-submit-area">
        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
        <input type="hidden" name="save" value="1">
        <button type="submit" value="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i>{LANG.save}</button>
    </div>
</form>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
    $('#picker_body_color').css({
        'background-color': $('#picker_body_color').val()
    });
    $('#picker_body_background').css({
        'background-color': $('#picker_body_background').val()
    });
    $('#picker_content_background').css({
        'background-color': $('#picker_content_background').val()
    });
    $('#picker_link_color').css({
        'background-color': $('#picker_link_color').val()
    });
    $('#picker_link_hover_color').css({
        'background-color': $('#picker_link_hover_color').val()
    });
    $('#picker_header_background').css({
        'background-color': $('#picker_header_background').val()
    });
    $('#picker_footer_background').css({
        'background-color': $('#picker_footer_background').val()
    });
    $('#picker_block_background').css({
        'background-color': $('#picker_block_background').val()
    });
    $('#picker_block_header_bg').css({
        'background-color': $('#picker_block_header_bg').val()
    });
    $('#cfgThemeTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
    });
});

$('#picker_block_header_bg, #picker_body_color, #picker_body_background, #picker_content_background, #picker_link_color, #picker_link_hover_color, #picker_header_background, #picker_footer_background, #picker_block_background').colpick({
    layout: 'hex',
    submit: 0,
    colorScheme: 'dark',
    onChange: function(hsb, hex, rgb, el, bySetColor) {
        $(el).css('background-color', '#' + hex);
        if (!bySetColor) $(el).val('#' + hex);
    }
}).keyup(function() {
    $(this).colpickSetColor(this.value);
});

function nv_open_filemanage(area) {
    var alt = "backgroundimgalt";
    var path = "{UPLOADS_DIR}";
    var type = "image";
    nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    return false;
}
//]]>
</script>
<!-- END:main -->