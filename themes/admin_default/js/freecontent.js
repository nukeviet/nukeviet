/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

$(document).ready(function(){
	var $this;
	var cfg = {
		blockAddBtn: '.block-add-trigger',
		blockModal: '#block-data',
		blockModalDelete: '#block-delete',
		blockSubmitBtn: '.block-submit-trigger',
		blockDelBtn: '.block-delete-trigger',
		blockEditLink: '.block-edit',
		blockDelLink: '.block-delete',
		blockRow: '#block-row-',
		blockList: '#block-list-container'
	};
	
	// Add block click
	$(cfg.blockAddBtn).click(function(e){
		$(cfg.blockModal + ' .txt').val('').tooltip('destroy');
		$(cfg.blockModal + ' .has-error').removeClass('has-error');
		$(cfg.blockModal).modal('toggle');
	});

	// Edit block click
	$(cfg.blockEditLink).click(function(e){
		e.preventDefault();
		$this = $(this);
		$(cfg.blockModal + ' [name="bid"]').val($this.data('bid'));
		$(cfg.blockModal + ' .txt').attr('disabled', 'disabled');
		$(cfg.blockSubmitBtn).attr('disabled', 'disabled');
		
		$.ajax({
			type: 'POST',
			cache: false,
			url: script_name + '?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
			data: 'bid=' + $this.data('bid') + '&getinfo=1',
			dataType: 'json',
			success: function(e){
				if( e.status == 'success' ){
					$(cfg.blockSubmitBtn).removeAttr('disabled');
					$(cfg.blockModal + ' .txt').removeAttr('disabled');
					
					$.each(e.data, function(k, v){
						$(cfg.blockModal + ' [name=' + k + ']').val(v);
					});
				}else{
					alert(e.message);
				}
			}
		});
		
		$(cfg.blockModal).modal('toggle');
	});

	// Delete block click
	$(cfg.blockDelLink).click(function(e){
		e.preventDefault();
		$(cfg.blockModalDelete).find('[name="bid"]').val($(this).data('bid'));
		$(cfg.blockModalDelete).find('.confirm').show();
		$(cfg.blockModalDelete).find('.loading').hide();
		$(cfg.blockModalDelete).find('.message').hide();
		$(cfg.blockModalDelete).find('.success').hide();
		$(cfg.blockDelBtn).removeAttr('disabled');
		$(cfg.blockModalDelete).modal('toggle');
	});

	// Trigger submit block
	$(cfg.blockSubmitBtn).click(function(){
		$(cfg.blockModal + ' form').submit();
	});
	
	// Submit add/edit block
	$(cfg.blockModal + ' form').submit(function(e){
		e.preventDefault();
		$this = $(this);
		$(cfg.blockSubmitBtn).attr('disabled', 'disabled');
		var data = {
			bid: $this.find('[name="bid"]').val(),
			title: $this.find('[name="title"]').val(),
			description: $this.find('[name="description"]').val()
		}
		
		$.ajax({
			type: 'POST',
			cache: false,
			url: script_name + '?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
			data: $.param(data) + '&submit=1',
			dataType: 'json',
			success: function(e){
				$(cfg.blockSubmitBtn).removeAttr('disabled');
				if( e.status == 'success' ){
					alert(e.message);
					window.location.href = window.location.href;
				}else{
					$.each(e.error, function(k, v){
						if( v.name == '' ){
							alert( v.value );
						}else{
							$this.find('[name=' + v.name + ']').attr({
								'title': v.value,
								'data-trigger': 'focus'
							}).tooltip().parent().parent().addClass('has-error');
						}
					});
					
					$(cfg.blockModal + ' .has-error:first input').focus();
				}
			}
		});
	});
	
	// Delete block submit
	$(cfg.blockDelBtn).click(function(e){
		e.preventDefault();
		$(this).attr('disabled', 'disabled');
		$(cfg.blockModalDelete).find('.confirm').hide();
		$(cfg.blockModalDelete).find('.loading').show();
		
		$.ajax({
			type: 'POST',
			cache: false,
			url: script_name + '?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
			data: 'bid=' + $(cfg.blockModalDelete).find('[name="bid"]').val() + '&del=1',
			dataType: 'json',
			success: function(e){
				if( e.status == 'success' ){
					$(cfg.blockModalDelete).find('.loading').hide();
					$(cfg.blockModalDelete).find('.success').show();
					
					setTimeout(function(){
						$(cfg.blockModalDelete).modal('toggle');
						$(cfg.blockRow + $(cfg.blockModalDelete).find('[name="bid"]').val()).remove();
						if( $(cfg.blockList + ' tr').length < 1 ){
							window.location.href = window.location.href;
						}
					}, 1000);
				}else{
					alert(e.message);
				}
			}
		});
	});
});
