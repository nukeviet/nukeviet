<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/clipboard/clipboard.min.js"></script>
<!-- BEGIN: error -->
<div id="edit">&nbsp;</div>
<div class="alert alert-danger"><span id="message">ERROR! CONFIG FILE: {ERROR}</span></div>
<!-- END: error -->
<div class="row">
    <!-- BEGIN: loop -->
    <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="form-group">
            <div class="nv-themelist{THEME_ACTIVE}">
                <div class="themelistthumb">
                    <img alt="{ROW.name}" src="{NV_BASE_SITEURL}themes/{ROW.value}/{ROW.thumbnail}"/>
                    <!-- BEGIN: actions -->
                    <div class="actions">
                        <div class="ctn">
                            <!-- BEGIN: link_setting -->
                            <a href="javascript:void(0);" class="btn btn-default btn-block activate ellipsis" title="{ROW.value}"><i class="fa fa-fw fa-sun-o"></i>{LANG.theme_created_setting}</a>
                            <!-- END: link_setting -->
                            <!-- BEGIN: link_active -->
                            <a href="javascript:void(0);" class="btn btn-default btn-block activate ellipsis" title="{ROW.value}"><i class="fa fa-sun-o fa-fw"></i>{LANG.theme_created_activate}</a>
                            <!-- END: link_active -->
                            <!-- BEGIN: link_delete -->
                            <a href="javascript:void(0);" class="btn btn-default btn-block delete ellipsis" title="{ROW.value}"><i class="fa fa-trash-o fa-fw"></i>{LANG.theme_delete}</a>
                            <!-- END: link_delete -->
                        </div>
                    </div>
                    <!-- END: actions -->
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-14">
                            <h3 class="ellipsis"><span>{ROW.name}</span></h3>
                        </div>
                        <div class="col-xs-10">
                            <a href="#" data-toggle="viewthemedetail" data-target="#theme-detail-{ROW.value}" class="btn btn-sm btn-small btn-{BTN_ACTIVE} pull-right themedetail">{GLANG.detail}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="theme-detail-{ROW.value}" class="hidden" title="{GLANG.detail}">
                <div class="nv-theme-detail">
                    <!-- BEGIN: preview -->
                    <div class="form-group clearfix">
                        <div class="pull-right">
                            <a href="#" class="btn btn-primary" data-toggle="previewtheme" data-value="{ROW.value}"><i class="fa fa-fw fa-spinner fa-spin hidden"></i><span>{TEXT_PREVIEW}</span></a>
                        </div>
                        <label class="preview-label"{SHOW_PREVIEW2}>{LANG.preview_theme_link}:</label>
                    </div>
                    <div class="preview-link form-group{SHOW_PREVIEW1}">
                        <div class="input-group">
                            <input type="text" class="form-control selectedfocus" value="{LINK_PREVIEW}"/>
                            <div class="input-group-btn">
                                <a href="javascript:void(0);" class="btn btn-default preview-link-btn" data-clipboard-text="{LINK_PREVIEW}" data-title="{LANG.preview_theme_link_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="0"><i class="fa fa-copy"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END: preview -->
                    <div class="dthumb">
                        <img alt="{ROW.name}" src="{NV_BASE_SITEURL}themes/{ROW.value}/{ROW.thumbnail}"/>
                    </div>
                    <h1>{ROW.name}</h1>
                    <p class="author">{LANG.theme_created_by}: <a href="{ROW.website}" title="{LANG.theme_created_website}" target="_blank"><strong>{ROW.author}</strong></a></p>
                    <p class="tinfo">{ROW.description}</p>
                    <p class="tdir">{LANG.theme_created_folder} <code>/themes/{ROW.value}/</code></p>
                    <p class="tpos">{LANG.theme_created_position} <code>{POSITION}</code></p>
                </div>
            </div>
        </div>
    </div>
    <!-- END: loop -->
</div>
<script type="text/javascript">
//<![CDATA[
LANG.theme_delete_confirm = '{LANG.theme_delete_confirm}';
//]]>
</script>
<!-- END: main -->