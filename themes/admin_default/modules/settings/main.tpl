<!-- BEGIN: main -->
<form id="site-settings" class="form-horizontal" action="{FORM_ACTION}" method="post">
    <div class="panel-group" id="accordion-settings" role="tablist" aria-multiselectable="true">
        <div class="panel panel-primary">
            <a role="tab" id="headingOne" class="panel-heading" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                {LANG.general_settings}
            </a>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <ul class="list-group type2n1">
                    <!-- BEGIN: site_domain -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.site_domain}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <select name="site_domain" class="form-control">
                                    <option value=""> -- </option>
                                    <!-- BEGIN: loop -->
                                    <option value="{SITE_DOMAIN}" {SELECTED}>{SITE_DOMAIN} </option>
                                    <!-- END: loop -->
                                </select>
                            </div>
                        </div>
                    </li>
                    <!-- END: site_domain -->

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.sitename}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <input type="text" name="site_name" value="{VALUE.sitename}" class="form-control required" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.description}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <input type="text" name="site_description" value="{VALUE.description}" class="form-control required" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.site_keywords}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <input type="text" name="site_keywords" class="form-control" value="{VALUE.site_keywords}" />
                                <div class="help-block mb-0">{LANG.params_info}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.site_logo}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="site_logo" id="site_logo" value="{VALUE.site_logo}" />
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="selectfile" data-target="site_logo" data-path="" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.site_banner}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="site_banner" id="site_banner" value="{VALUE.site_banner}" />
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="selectfile" data-target="site_banner" data-path="" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.site_favicon}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="site_favicon" id="site_favicon" value="{VALUE.site_favicon}" />
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="selectfile" data-target="site_favicon" data-path="" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.default_module}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <select name="site_home_module" class="form-control">
                                    <!-- BEGIN: module -->
                                    <option value="{MODULE.title}" {SELECTED}>{MODULE.custom_title} </option>
                                    <!-- END: module -->
                                </select>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <a role="tab" id="headingTwo" class="panel-heading collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                {LANG.theme_settings}
            </a>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <ul class="list-group type2n1">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.allow_theme_type}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <!-- BEGIN: theme_type -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="theme_type[]" value="{THEME_TYPE}" {THEME_TYPE_CHECKED}> {THEME_TYPE_TXT}
                                </label>
                                <!-- END: theme_type -->
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.theme}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <select name="site_theme" class="form-control">
                                    <!-- BEGIN: site_theme -->
                                    <option value="{SITE_THEME}" {SELECTED}>{SITE_THEME} </option>
                                    <!-- END: site_theme -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item mobile_theme-wrap" style="<!-- BEGIN: if_not_mobile_type -->display:none<!-- END: if_not_mobile_type -->">
                        <div class="form-group mb-0">
                            <label class="col-sm-8 col-md-10 control-label"><strong>{LANG.mobile_theme}</strong></label>
                            <div class="col-sm-16 col-md-14">
                                <select name="mobile_theme" class="form-control">
                                    <option value="">{LANG.theme}</option>
                                    <!-- BEGIN: mobile_theme -->
                                    <option value="{SITE_THEME}" {SELECTED}>{SITE_THEME} </option>
                                    <!-- END: mobile_theme -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item switch_mobi_des-wrap" style="<!-- BEGIN: if_not_mobile_type2 -->display:none<!-- END: if_not_mobile_type2 -->">
                        <div class="form-group mb-0">
                            <div class="col-sm-16 col-md-14 col-md-offset-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="switch_mobi_des" value="1" {VALUE.switch_mobi_des} /> <strong>{LANG.allow_switch_mobi_des}</strong>
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <a role="tab" id="headingThree" class="panel-heading collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                {LANG.disable_content}
            </a>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div style="margin: 0 -1px -1px;padding-left:0;padding-right:2px;position:relative">
                    {DISABLE_SITE_CONTENT}
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <input type="hidden" name="checkss" value="{VALUE.checkss}" />
        <button type="submit" class="btn btn-primary w100">{LANG.submit}</button>
    </div>
</form>
<!-- END: main -->