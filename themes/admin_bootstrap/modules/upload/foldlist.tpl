<!-- BEGIN: tree -->
<li class="{DIRTREE.class1}">
	<span {DIRTREE.style} class="{DIRTREE.class2}" title="{DIRTREE.title}"> &nbsp;{DIRTREE.titlepath}</span>
	<ul>
		<!-- BEGIN: tree_content -->
		{TREE_CONTENT}
		<!-- END: tree_content -->
	</ul>
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<ul id="foldertree" class="filetree">
	<li class="open collapsable">
		<span {DATA.style} class="{DATA.class}" title="{DATA.title}"> &nbsp; {DATA.titlepath}</span>
		<ul>
			<!-- BEGIN: main_content -->
			{CONTENT}
			<!-- END: main_content -->
		</ul>
	</li>
</ul>

<span style="display:none" id="path" title="{PATH}"></span>
<span style="display:none" id="foldervalue" title="{CURRENTPATH}"></span>
<span style="display:none" id="view_dir" title="{VIEW_DIR}"></span>
<span style="display:none" id="create_dir" title="{CREATE_DIR}"></span>
<span style="display:none" id="rename_dir" title="{RENAME_DIR}"></span>
<span style="display:none" id="delete_dir" title="{DELETE_DIR}"></span>
<span style="display:none" id="upload_file" title="{UPLOAD_FILE}"></span>
<span style="display:none" id="create_file" title="{CREATE_FILE}"></span>
<span style="display:none" id="rename_file" title="{RENAME_FILE}"></span>
<span style="display:none" id="delete_file" title="{DELETE_FILE}"></span>
<span style="display:none" id="move_file" title="{MOVE_FILE}"></span>
<span style="display:none" id="crop_file" title="{CROP_FILE}"></span>
<span style="display:none" id="rotate_file" title="{ROTATE_FILE}"></span>

<script type="text/javascript">
	//<![CDATA[
	is_allowed_upload();

	$("#foldertree").treeview({
		collapsed : true,
		unique : true,
		persist : "location"
	});

	$("span.folder").click(function() {
		folderClick(this)
	});

	$("span.menu").mouseup(function() {
		menuMouseup(this)
	});

	$("span.menu").contextMenu("contextMenu", {
		menuStyle : {
			width : "120px"
		},
		bindings : {
			renamefolder : function() {
				renamefolder()
			},
			createfolder : function() {
				createfolder()
			},
			deletefolder : function() {
				deletefolder()
			}
		}
	});
	//]]>
</script>
<!-- END: main -->