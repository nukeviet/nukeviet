		<script src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script src="{NV_BASE_SITEURL}js/global.js"></script>
		<script src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/main.js"></script>

		<script src="{NV_BASE_SITEURL}/js/ddsmoothmenu.js"></script>
		<script type="text/javascript">
			ddsmoothmenu.init({
				arrowimages : {
					down : ['downarrowclass', '{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/menu_down.png', 23],
					right : ['rightarrowclass', '{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/menu_right.png']
				},
				mainmenuid : "smoothmenu",
				orientation : 'h',
				classname : 'ddsmoothmenu',
				contentsource : "markup"
			})
		</script>
		<!-- BEGIN: module_js -->
		<script src="{NV_JS_MODULE}"></script>
		<!-- END: module_js -->
		<!-- BEGIN: nv_add_editor_js -->
		{NV_ADD_EDITOR_JS}
		<!-- END: nv_add_editor_js -->
	</body>
</html>