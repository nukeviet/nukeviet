            </div>
        </section>
        <nav class="footerNav2">
            
        </nav>
        <!-- Footer fixed -->
        <footer id="footer">
            <div class="footer display-table">
                <div>
                    <div>
                        <span data-toggle="winHelp"><em class="fa fa-info-circle fa-lg pointer mbt"></em></span>
                    </div>
                    <div class="text-right">
                        <div class="fr">
                        [SOCIAL_ICONS]
                        <a class="bttop pointer"><em class="fa fa-lg mbt"></em></a>
                        </div>
                    </div>
                </div>
                <div class="ftip">
                    <div id="ftip" data-content=""></div>
                </div>
            </div>
        </footer>
        {ADMINTOOLBAR}
    </div>
</div>
<!-- Help window -->
<div id="winHelp">
    <div class="winHelp">
        <div class="logo-small padding"></div>
        [MENU_FOOTER]
        [COMPANY_INFO]
        <div class="padding">
        [FOOTER_SITE]
        </div>
        <!-- BEGIN: theme_type -->
        <div class="theme-change margin-bottom-lg">
            <!-- BEGIN: loop -->
            <!-- BEGIN: other --><a href="{STHEME_TYPE}" title="{STHEME_INFO}"><em class="fa fa-{STHEME_ICON} fa-lg mbt-circle"></em></a><!-- END: other --><!-- BEGIN: current --><span title="{LANG.theme_type_select}: {STHEME_TITLE}"><em class="fa fa-{STHEME_ICON} fa-lg"></em></span><!-- END: current -->
            <!-- END: loop -->
        </div>
        <!-- END: theme_type -->
    </div>
</div>
<!-- Search form -->
<div id="headerSearch" class="hidden">
<div class="headerSearch container-fluid margin-bottom">
    <div class="input-group">
        <input type="text" onkeypress="headerSearchKeypress(event);" class="form-control" maxlength="{NV_MAX_SEARCH_LENGTH}" placeholder="{LANG.search}...">
        <span class="input-group-btn"><button type="button" onclick="headerSearchSubmit(this);" class="btn btn-info" data-url="{THEME_SEARCH_URL}" data-minlength="{NV_MIN_SEARCH_LENGTH}" data-click="y"><em class="fa fa-search fa-lg"></em></button></span>
    </div>
</div>
</div>
<!-- SiteModal Required!!! -->
<div id="sitemodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">&nbsp;</h3>
            </div>
            <div class="modal-body">
                <em class="fa fa-spinner fa-spin">&nbsp;</em>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>