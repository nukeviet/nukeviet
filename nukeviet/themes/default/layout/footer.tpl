	<div class="clear"></div>
	<div id="footer" class="clearfix">
		<div class="fl">
			{THEME_STAT_IMG} {THEME_NUKEVIET_IMG} 
		</div>
	    <div class="fl">
	        [FOOTER_SITE]
	    </div>
	    <div class="fr">
	        <ul class="bottom-toolbar">
	            <li>
	                Powered by<a title="NukeViet" href="http://nukeviet.vn"><img alt="NukeViet" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/nukeviet.png" /></a>
	            </li>
	            <li>
	                Valid<a title="Validate html" href="http://validator.w3.org/check?uri=referer"><img alt="Validated HTML" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/xhtml.png" /></a><a title="Validate CSS" href="http://jigsaw.w3.org/css-validator/check/referer"><img alt="Validated CSS" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/css.png" /></a>
	            </li>
	            <li>
	                <br/><a title="NukeViet" href="http://nukeviet.vn">NukeViet</a> is a registered trademark of <a title="VINADES.,JSC" href="http://vinades.vn">VINADES.,JSC</a>
	            </li>
				<!-- BEGIN: theme_type -->
				<li>
					<br />
					{LANG.theme_type_select}: <!-- BEGIN: loop --><!-- BEGIN: other --><a href="{STHEME_TYPE}" title="{STHEME_INFO}">{STHEME_TITLE}</a><!-- END: other --><!-- BEGIN: current -->{STHEME_TITLE}<!-- END: current --><!-- BEGIN: space --> | <!-- END: space --><!-- END: loop -->
				</li><!-- END: theme_type -->
	        </ul>
	    </div>
	    <div class="clear">
	    </div>
	    <div id="run_cronjobs" style="visibility: hidden; display: none;">
	        <img alt="" title="" src="{THEME_IMG_CRONJOBS}" width="1" height="1" />
	    </div>
	</div>
</div>
</body>
</html>