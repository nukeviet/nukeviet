<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->
<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post" autocomplete="off">
    <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_view}</caption>
            <tbody>
                <tr>
                    <th>{LANG.setting_indexfile}</th>
                    <td>
                    <select class="form-control" name="indexfile">
                        <!-- BEGIN: indexfile -->
                        <option value="{INDEXFILE.key}"{INDEXFILE.selected}>{INDEXFILE.title}</option>
                        <!-- END: indexfile -->
                    </select></td>
                </tr>
                <tr>
                    <th>{LANG.setting_mobile_indexfile}</th>
                    <td>
                    <select class="form-control" name="mobile_indexfile">
                        <!-- BEGIN: mobile_indexfile -->
                        <option value="{MOBILE_INDEXFILE.key}"{MOBILE_INDEXFILE.selected}>{MOBILE_INDEXFILE.title}</option>
                        <!-- END: mobile_indexfile -->
                    </select></td>
                </tr>
                <tr>
                    <th>{LANG.setting_homesite}</th>
                    <td><input class= "form-control" type="text" value="{DATA.homewidth}" name="homewidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.homeheight}" name="homeheight" /></td>
                </tr>
                <tr>
                    <th>{LANG.setting_thumbblock}</th>
                    <td><input class= "form-control" type="text" value="{DATA.blockwidth}" name="blockwidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.blockheight}" name="blockheight" /></td>
                </tr>
                <tr>
                    <th>{LANG.setting_imagefull}</th>
                    <td><input class= "form-control" type="text" value="{DATA.imagefull}" name="imagefull" /></td>
                </tr>
                <tr>
                    <th>{LANG.setting_per_page}</th>
                    <td>
                    <select class="form-control" name="per_page">
                        <!-- BEGIN: per_page -->
                        <option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
                        <!-- END: per_page -->
                    </select></td>
                </tr>
                <tr>
                    <th>{LANG.setting_st_links}</th>
                    <td>
                    <select class="form-control" name="st_links">
                        <!-- BEGIN: st_links -->
                        <option value="{ST_LINKS.key}"{ST_LINKS.selected}>{ST_LINKS.title}</option>
                        <!-- END: st_links -->
                    </select></td>
                </tr>
                <tr>
                    <th>{LANG.showtooltip}</th>
                    <td>
                        <input type="checkbox" value="1" name="showtooltip"{SHOWTOOLTIP}/>
                        &nbsp;&nbsp;&nbsp;<span class="text-middle">{LANG.showtooltip_position}</span>
                        <select name="tooltip_position" class="form-control">
                            <!-- BEGIN: tooltip_position -->
                            <option value="{TOOLTIP_P.key}"{TOOLTIP_P.selected}>{TOOLTIP_P.title}</option>
                            <!-- END: tooltip_position -->
                        </select>
                        &nbsp;&nbsp;&nbsp;<span class="text-middle">{LANG.showtooltip_length}</span>
                        <input type="text" name="tooltip_length" class="form-control" value="{DATA.tooltip_length}" style="width: 100px" />
                    </td>
                </tr>
                <tr>
                    <th>{LANG.showhometext}</th>
                    <td><input type="checkbox" value="1" name="showhometext"{SHOWHOMETEXT}/></td>
                </tr>
                <tr>
                    <th>{LANG.htmlhometext}</th>
                    <td><input type="checkbox" value="1" name="htmlhometext"{HTMLHOMETEXT}/></td>
                </tr>
                <tr>
                    <th>{LANG.socialbutton}</th>
                    <td>
                        <!-- BEGIN: socialbutton -->
                        <div><label><input type="checkbox" name="socialbutton[]" value="{SOCIALBUTTON.key}"{SOCIALBUTTON.checked}> {SOCIALBUTTON.title}</label></div>
                        <!-- END: socialbutton -->
                    </td>
                </tr>
                <tr>
                    <th>{LANG.content_allowed_rating}</th>
                    <td><input type="checkbox" value="1" name="allowed_rating"{ALLOWED_RATING}/></td>
                </tr>
                <tr>
                    <th>{LANG.allowed_rating_point}</th>
                    <td>
                        <select class="form-control" name="allowed_rating_point">
                            <!-- BEGIN: allowed_rating_point -->
                            <option value="{RATING_POINT.key}"{RATING_POINT.selected}>{RATING_POINT.title}</option>
                            <!-- END: allowed_rating_point -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{LANG.show_no_image}</th>
                    <td><input class="form-control" name="show_no_image" id="show_no_image" value="{SHOW_NO_IMAGE}" style="width:340px;" type="text"/> <input id="select-img-setting" value="{GLANG.browse_image}" name="selectimg" type="button" class="btn btn-info"/></td>
                </tr>
                <tr>
                    <th>{LANG.config_source}</th>
                    <td>
                    <select class="form-control" name="config_source">
                        <!-- BEGIN: config_source -->
                        <option value="{CONFIG_SOURCE.key}"{CONFIG_SOURCE.selected}>{CONFIG_SOURCE.title}</option>
                        <!-- END: config_source -->
                    </select></td>
                </tr>
                <tr>
                    <th>{LANG.setting_copyright}</th>
                    <td>{COPYRIGHTHTML}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_post}</caption>
            <tbody>
                <tr>
                    <th>{LANG.facebookAppID}</th>
                    <td><input class="form-control w150" name="facebookappid" value="{DATA.facebookappid}" type="text"/><span class="text-middle">{LANG.facebookAppIDNote}</span></td>
                </tr>
                <tr>
                    <th>{LANG.structure_image_upload}</th>
                    <td>
                    <select class="form-control" name="structure_upload" id="structure_upload">
                        <!-- BEGIN: structure_upload -->
                        <option value="{STRUCTURE_UPLOAD.key}"{STRUCTURE_UPLOAD.selected}>{STRUCTURE_UPLOAD.title}</option>
                        <!-- END: structure_upload -->
                    </select></td>
                </tr>
                <tr>
                    <th>{LANG.imgpositiondefault}</th>
                    <td>
                        <select class="form-control" name="imgposition">
                            <!-- BEGIN: looppos -->
                            <option value="{id_imgposition}" {posl}>{title_imgposition}</option>
                            <!-- END: looppos -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{LANG.setting_alias_lower}</th>
                    <td><input type="checkbox" value="1" name="alias_lower"{ALIAS_LOWER}/></td>
                </tr>
                <tr>
                    <th>{LANG.tags_alias}</th>
                    <td><input type="checkbox" value="1" name="tags_alias"{TAGS_ALIAS}/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_auto_tags}</th>
                    <td><input type="checkbox" value="1" name="auto_tags"{AUTO_TAGS}/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_tags_remind}</th>
                    <td><input type="checkbox" value="1" name="tags_remind"{TAGS_REMIND}/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_keywords_tag}</th>
                    <td><input type="checkbox" value="1" name="keywords_tag"{KEYWORDS_TAG}/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_copy_news}</th>
                    <td><input type="checkbox" value="1" name="copy_news"{COPY_NEWS}/></td>
                </tr>
                <tr>
                    <th>{LANG.order_articles}</th>
                    <td>
                    <select class="form-control" name="order_articles" id="order_articles">
                        <!-- BEGIN: order_articles -->
                        <option value="{ORDER_ARTICLES.key}" {ORDER_ARTICLES.selected}>{ORDER_ARTICLES.title}</option>
                        <!-- END: order_articles -->
                    </select></td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_elasticseach}</caption>
            <tbody>
                <tr>
                    <td colspan="2">
                        {LANG.use_setup_elasticseach}: <a href="http://wiki.nukeviet.vn/web_server:install-and-configure-elasticsearch-on-centos-7" target="_blank">http://wiki.nukeviet.vn/web_server:install-and-configure-elasticsearch-on-centos-7</a><br />
                        {LANG.use_dev_elasticseach}: <a href="http://wiki.nukeviet.vn/web_server:use-elasticsearch-in-nukeviet" target="_blank">http://wiki.nukeviet.vn/web_server:use-elasticsearch-in-nukeviet</a><br />
                    </td>
                </tr>
                <tr>
                    <th>{LANG.setting_elas_use}</th>
                    <td><input type="checkbox" value="1" name="elas_use" {ELAS_USE}/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_elas_host}</th>
                    <td><input class="form-control" type="text" value="{DATA.elas_host}" name="elas_host"/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_elas_port}</th>
                    <td><input class="form-control" type="text" value="{DATA.elas_port}" name="elas_port"/></td>
                </tr>
                <tr>
                    <th>{LANG.setting_elas_index}</th>
                    <td><input class="form-control" type="text" value="{DATA.elas_index}" name="elas_index"/></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_insart_caption}</caption>
            <tbody>
                <tr>
                    <th class="w400">{LANG.setting_active_instant_articles}</th>
                    <td><input type="checkbox" name="instant_articles_active" value="1"{INSTANT_ARTICLES_ACTIVE}/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_auto}</th>
                    <td><input type="checkbox" name="instant_articles_auto" value="1"{INSTANT_ARTICLES_AUTO}/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_template}</th>
                    <td><input type="text" value="{DATA.instant_articles_template}" name="instant_articles_template" class="form-control"/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_httpauth}</th>
                    <td><input type="checkbox" name="instant_articles_httpauth" value="1"{INSTANT_ARTICLES_HTTPAUTH}/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_username}</th>
                    <td><input type="text" value="{DATA.instant_articles_username}" name="instant_articles_username" class="form-control"/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_password}</th>
                    <td>
                        <div class="input-group">
                            <input type="password" value="{INSTANT_ARTICLES_PASSWORD}" name="instant_articles_password" class="form-control" autocomplete="new-password">
                            <span class="input-group-btn">
                                <a href="#" class="showhidepass btn btn-default" data-target="[name='instant_articles_password']" data-toggle="tooltip" title="{LANG.show_hide_pass}"><i class="fa fa-key"></i></a>
                                <a href="#" class="genrandpass btn btn-default" data-target="[name='instant_articles_password']" data-toggle="tooltip" title="{LANG.gen_rand_pass}"><i class="fa fa-refresh"></i></a>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_livetime}&nbsp;(<a href="javascript:void(0);" data-toggle="tooltip" title="{LANG.setting_instant_articles_livetime1}"><i class="fa fa-info-circle"></i></a>)</th>
                    <td><input type="text" value="{DATA.instant_articles_livetime}" name="instant_articles_livetime" class="form-control"/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_gettime}&nbsp;(<a href="javascript:void(0);" data-toggle="tooltip" title="{LANG.setting_instant_articles_gettime1}"><i class="fa fa-info-circle"></i></a>)</th>
                    <td><input type="text" value="{DATA.instant_articles_gettime}" name="instant_articles_gettime" class="form-control"/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.setting_instant_articles_defaulturl}</th>
                    <td>
                        <input type="text" class="form-control display-inline-block w500 m-bottom" value="{INSTANT_ARTICLES_URL_DEFAULT}" data-toggle="selectall"/>
                        <p><em class="text-info">{LANG.setting_instant_articles_defaulturl1}</em></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><i class="fa fa-file-text-o"></i> {LANG.setting_sys}</caption>
            <tbody>
                <tr>
                    <th class="w400">{LANG.setting_identify_cat_change}</th>
                    <td>
                        <div class="checkbox">
                            <label><input type="checkbox" name="identify_cat_change" value="1"{IDENTIFY_CAT_CHANGE}> {LANG.setting_identify_cat_change_help}</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>{LANG.setting_active_history}</th>
                    <td>
                        <div class="checkbox">
                            <label><input type="checkbox" name="active_history" value="1"{ACTIVE_HISTORY}> {LANG.setting_active_history_help}</label>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2">
                        <input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" />
                        <input type="hidden" value="1" name="savesetting" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<script type="text/javascript">
//<![CDATA[
var CFG = [];
CFG.path = '{PATH}';
CFG.currentpath = '{CURRENTPATH}';
$(document).ready(function() {
    $("#structure_upload").select2()
});
//]]>
</script>
<!-- BEGIN: admin_config_post -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.group_content}</caption>
            <thead>
                <tr class="text-center">
                    <th>{GLANG.mod_groups}</th>
                    <th>{LANG.group_addcontent}</th>
                    <th>{LANG.group_postcontent}</th>
                    <th>{LANG.group_editcontent}</th>
                    <th>{LANG.group_delcontent}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td><strong>{ROW.group_title}</strong><input type="hidden" value="{ROW.group_id}" name="array_group_id[]" /></td>
                    <td class="text-center"><input type="checkbox" value="1" name="array_addcontent[{ROW.group_id}]"{ROW.addcontent}/></td>
                    <td class="text-center"><input type="checkbox" value="1" name="array_postcontent[{ROW.group_id}]"{ROW.postcontent}/></td>
                    <td class="text-center"><input type="checkbox" value="1" name="array_editcontent[{ROW.group_id}]"{ROW.editcontent}/></td>
                    <td class="text-center"><input type="checkbox" value="1" name="array_delcontent[{ROW.group_id}]"{ROW.delcontent}/></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_frontend_post}</caption>
            <tbody>
                <tr>
                    <th class="w400">{LANG.frontend_edit_alias}</th>
                    <td><input type="checkbox" name="frontend_edit_alias" value="1"{FRONTEND_EDIT_ALIAS}/></td>
                </tr>
                <tr>
                    <th class="w400">{LANG.frontend_edit_layout}</th>
                    <td><input type="checkbox" name="frontend_edit_layout" value="1"{FRONTEND_EDIT_LAYOUT}/></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: center;" colspan="5"><input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" /><input type="hidden" value="1" name="savepost" /></td>
                </tr>
            </tfoot>
        </table>
   </div>
</form>
<!-- END: admin_config_post -->

<!-- END: main -->
