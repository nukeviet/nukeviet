<!-- BEGIN: main -->
<div class="filter_product">
	<div class="ajax-load-qa" style="display: none;">
		&nbsp;
	</div>
	<!-- BEGIN: group -->
	<div class="row" style="line-height: 25px; border-bottom: 1px dotted #ddd; border-top: 1px dotted #ddd; border-left: 1px dotted #ddd; border-right: 1px dotted #ddd; position: relative; <!--BEGIN: border_top --><!--END: border_top -->">
		<div class="col-sm-4">
			<strong>{MAIN_GROUP.title}</strong>
		</div>
		<!-- BEGIN: sub_group -->
		<div class="col-sm-20">
			<!-- BEGIN: loop -->
				<!-- BEGIN: checkbox -->
				<label><input type="checkbox" title="{SUB_GROUP.title}" data-alias="{MAIN_GROUP.alias}_{SUB_GROUP.alias}" name="groupid[]" value="{SUB_GROUP.groupid}" {SUB_GROUP.checked}>{SUB_GROUP.title}</label>
				<!-- END: checkbox -->
				<!-- BEGIN: label -->
				<label class="label_group <!-- BEGIN: active -->active<!-- END: active -->"><input type="checkbox" title="{SUB_GROUP.title}" data-alias="{MAIN_GROUP.alias}_{SUB_GROUP.alias}" name="groupid[]" value="{SUB_GROUP.groupid}" {SUB_GROUP.checked}>{SUB_GROUP.title}</label>
				<!-- END: label -->
				<!-- BEGIN: image -->
				<label class="label_group <!-- BEGIN: active -->active<!-- END: active -->" style="background-image: url('{SUB_GROUP.image}')"><input type="checkbox" title="{SUB_GROUP.title}" data-alias="{MAIN_GROUP.alias}_{SUB_GROUP.alias}" name="groupid[]" value="{SUB_GROUP.groupid}" {SUB_GROUP.checked}></label>
				<!-- END: image -->
			<!-- END: loop -->
		</div>
		<!-- END: sub_group -->
	</div>
	<!-- END: group -->
</div>

<script type="text/javascript">
	$('input[name="groupid[]"]').click(function() {
		$('.ajax-load-qa').show();

		var listid = '', url_group = '', i = 0;
		$(this).parent().css('border-color', '#ccc');
		$('input[name="groupid[]"]:checked').each(function() {
			url_group += $(this).attr('data-alias') + '/';
		    if( i > 0 )
		    {
		    	listid += ',' + $(this).val();
		    }
		    else
		    {
		    	listid += $(this).val();
		    }
		    $(this).parent().css('border-color', 'blue');
		    i++;
		});
		window.history.pushState("", "", '{MODULE_URL}{CAT_ALIAS}/' + url_group );

		$.ajax({
			type : "POST",
			url : script_name,
			data : 'ajax=1&catid={CATID}&listgroupid=' + listid,
			success : function(data) {
				$('#category').html(data);
				$(".ajax-load-qa").hide();
			},
			error : function() {
				$('.ajax-load-qa').hide();
			},
			timeout : 3000
		});
	});
</script>
<!-- END: main -->