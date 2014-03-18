			</div>
		</section>
		<footer id="footer">
			<div class="footer-copyright">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="panel-body">
								[FOOTER_SITE]
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="panel-body">
								<ul class="menu">
									<!-- BEGIN: footer_menu -->
									<li>
										<a href="{FOOTER_MENU.link}"{FOOTER_MENU.current}>{FOOTER_MENU.title}</a>
										<!-- BEGIN: defis --><span>|</span><!-- END: defis -->
									</li>
									<!-- END: footer_menu -->
									<li class="top"><a href="#" id="totop"><em class="fa fa-angle-up">&nbsp;</em></a><li>
								</ul>
								<!-- BEGIN: theme_type -->
								<div class="theme-change">
									{LANG.theme_type_select}:
									<!-- BEGIN: loop -->
									<!-- BEGIN: other -->
									<a href="{STHEME_TYPE}" title="{STHEME_INFO}">{STHEME_TITLE}</a>
									<!-- END: other -->
									<!-- BEGIN: current -->
									{STHEME_TITLE}
									<!-- END: current -->
									<!-- BEGIN: space -->
									-
									<!-- END: space -->
									<!-- END: loop -->
								</div>
								<!-- END: theme_type -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
</body>