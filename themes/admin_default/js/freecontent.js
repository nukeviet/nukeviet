/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var $this;
var cfg = {
    load: '.per-loading',

    blockAddBtn: '.block-add-trigger',
    blockModal: '#block-data',
    blockModalDelete: '#block-delete',
    blockSubmitBtn: '.block-submit-trigger',
    blockDelBtn: '.block-delete-trigger',
    blockEditLink: '.block-edit',
    blockDelLink: '.block-delete',
    blockRow: '#block-row-',
    blockList: '#block-list-container',

    ctAddBtn: '.content-add-trigger',
    ctModal: '#content-data',
    ctModalDelete: '#content-delete',
    ctSelectImg: '#content-select-image',
    ctEditor: 'content-description',
    ctSubmitBtn: '.content-submit-trigger',
    ctDelBtn: '.content-delete-trigger',
    ctEditLink: '.content-edit',
    ctDelLink: '.content-delete',
    ctRow: '#content-row-',
    ctList: '#content-list-container',
    ctStatusBtn: '.content-status',
    ctStatusPrefix: 'ct-status',
    ctCouter: '#content-couter'
};

function nv_pare_data(id, isEditor) {
    // Add content: Clear editor
    if (id == '') {
        if (isEditor) {
            CKEDITOR.instances[cfg.ctEditor].setData('');
        }
    } else {
        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manager&nocache=' + new Date().getTime(),
            data: 'id=' + id + '&getinfo=1',
            dataType: 'json',
            success: function(e) {
                $(cfg.ctModal).find(cfg.load).hide();
                if (e.status == 'success') {
                    $(cfg.ctSubmitBtn).removeAttr('disabled');
                    $(cfg.ctModal + ' .ip').removeAttr('disabled');

                    $.each(e.data, function(k, v) {
                        $this = $(cfg.ctModal + ' [name=' + k + ']');
                        var t = $this.prop('type');

                        if (t == 'text') {
                            $this.val(v);
                        } else if (t == 'checkbox') {
                            $this.prop('checked', v);
                        } else if (t == 'select-one') {
                            $this.val(v);
                        } else if (t == 'textarea') {
                            $this.val(v);

                            if (isEditor) {
                                CKEDITOR.instances[cfg.ctEditor].setData(v);
                            }
                        }
                    });
                } else {
                    alert(e.message);
                }
            }
        });
    }
}

$(document).ready(function() {
    // Add block click
    $(cfg.blockAddBtn).click(function(e) {
        $(cfg.blockModal).find(cfg.load).hide();
        $(cfg.blockModal + ' .txt').val('').tooltip('destroy');
        $(cfg.blockModal + ' .has-error').removeClass('has-error');
        $(cfg.blockModal).modal('toggle');
    });

    // Edit block click
    $(cfg.blockEditLink).click(function(e) {
        e.preventDefault();
        $this = $(this);
        $(cfg.blockModal).find(cfg.load).show();
        $(cfg.blockModal + ' [name="bid"]').val($this.data('bid'));
        $(cfg.blockModal + ' .txt').attr('disabled', 'disabled');
        $(cfg.blockSubmitBtn).attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data: 'bid=' + $this.data('bid') + '&getinfo=1',
            dataType: 'json',
            success: function(e) {
                $(cfg.blockModal).find(cfg.load).hide();
                if (e.status == 'success') {
                    $(cfg.blockSubmitBtn).removeAttr('disabled');
                    $(cfg.blockModal + ' .txt').removeAttr('disabled');

                    $.each(e.data, function(k, v) {
                        $(cfg.blockModal + ' [name=' + k + ']').val(v);
                    });
                } else {
                    alert(e.message);
                }
            }
        });

        $(cfg.blockModal).modal('toggle');
    });

    // Delete block click
    $(cfg.blockDelLink).click(function(e) {
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
    $(cfg.blockSubmitBtn).click(function() {
        $(cfg.blockModal + ' form').submit();
    });

    // Submit add/edit block
    $(cfg.blockModal + ' form').submit(function(e) {
        e.preventDefault();
        $this = $(this);
        $(cfg.blockSubmitBtn).attr('disabled', 'disabled');
        $(cfg.blockModal).find(cfg.load).show();
        $(cfg.blockModal + ' .has-error').removeClass('has-error');
        $(cfg.blockModal + ' .txt').tooltip('destroy');

        var data = {
            bid: $this.find('[name="bid"]').val(),
            title: $this.find('[name="title"]').val(),
            description: $this.find('[name="description"]').val()
        }

        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data: $.param(data) + '&submit=1',
            dataType: 'json',
            success: function(e) {
                $(cfg.blockSubmitBtn).removeAttr('disabled');
                $(cfg.blockModal).find(cfg.load).hide();
                if (e.status == 'success') {
                    alert(e.message);
                    window.location.href = window.location.href;
                } else {
                    $.each(e.error, function(k, v) {
                        if (v.name == '') {
                            alert(v.value);
                        } else {
                            $this.find('[name=' + v.name + ']').attr({
                                'title': v.value,
                                'data-trigger': 'focus'
                            }).tooltip().parent().addClass('has-error');
                        }
                    });

                    $(cfg.blockModal + ' .has-error:first input').focus();
                }
            }
        });
    });

    // Delete block submit
    $(cfg.blockDelBtn).click(function(e) {
        e.preventDefault();
        $(this).attr('disabled', 'disabled');
        $(cfg.blockModalDelete).find('.confirm').hide();
        $(cfg.blockModalDelete).find('.loading').show();

        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data: 'bid=' + $(cfg.blockModalDelete).find('[name="bid"]').val() + '&del=1',
            dataType: 'json',
            success: function(e) {
                $(cfg.blockModalDelete).find('.loading').hide();

                if (e.status == 'success') {
                    $(cfg.blockModalDelete).find('.success').show();

                    setTimeout(function() {
                        $(cfg.blockModalDelete).modal('toggle');
                        $(cfg.blockRow + $(cfg.blockModalDelete).find('[name="bid"]').val()).remove();
                        if ($(cfg.blockList + ' tr').length < 1) {
                            window.location.href = window.location.href;
                        }
                    }, 1000);
                } else {
                    $(cfg.blockModalDelete).find('.message').addClass('text-danger').html(e.message).show();
                }
            }
        });
    });

    // Select image
    $(cfg.ctSelectImg).click(function() {
        nv_open_browse(script_name + '?' + nv_name_variable + '=upload&popup=1&area=' + $(this).data('area') + '&path=' + $(this).data('path') + '&type=image&currentpath=' + $(this).data('currentpath'), 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
    });

    // Add content click
    $(cfg.ctAddBtn).click(function(e) {
        $(cfg.ctModal + ' .txt').val('').tooltip('destroy');
        $(cfg.ctModal + ' select option').removeAttr('selected');
        $(cfg.ctModal + ' input[type="checkbox"]').prop('checked', true);
        $(cfg.ctModal + ' .has-error').removeClass('has-error');
        $(cfg.ctModal).find(cfg.load).hide();
        $(cfg.ctModal).modal('toggle');
    });

    // Trigger on modal shown
    $(cfg.ctModal).on('shown.bs.modal', function(e) {
        var id = $(cfg.ctModal + ' [name="id"]').val();
        var isEditor = (typeof CKEDITOR !== "undefined" && $(cfg.ctModal + ' [name="description"]').data('editor') == true) ? true : false;

        // Build/Rebuild editor
        if (isEditor) {
            var instance = CKEDITOR.instances[cfg.ctEditor];
            if (!instance) {
                CKEDITOR.replace(cfg.ctEditor, {
                    width: '100%',
                    height: $(cfg.ctModal + ' [name="description"]').height() - 30,
                    toolbar: [{
                        name: 'Tools',
                        items: ['Undo', 'Redo', '-', 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Source', '-', 'Maximize']
                    }],
                    toolbarLocation: 'bottom',
                    removePlugins: 'elementspath,resize',
                    on: {
                        instanceReady: function(e) {
                            nv_pare_data(id, isEditor);
                        }
                    }
                });
            } else {
                nv_pare_data(id, isEditor);
            }
        } else {
            nv_pare_data(id, isEditor);
        }
    });

    // Trigger submit content
    $(cfg.ctSubmitBtn).click(function() {
        $(cfg.ctModal + ' form').submit();
    });

    // Submit add/edit content
    $(cfg.ctModal + ' form').submit(function(e) {
        e.preventDefault();
        $this = $(this);
        var isEditor = (typeof CKEDITOR !== "undefined" && $(cfg.ctModal + ' [name="description"]').data('editor') == true) ? true : false;
        $(cfg.ctSubmitBtn).attr('disabled', 'disabled');
        $(cfg.ctModal).find(cfg.load).show();
        $(cfg.ctModal + ' .txt').tooltip('destroy');
        $(cfg.ctModal + ' .has-error').removeClass('has-error');

        if (isEditor) {
            var instance = CKEDITOR.instances[cfg.ctEditor];
            if (instance) {
                $this.find('[name="description"]').val(instance.getData());
            }
        }

        var data = {
            id: $this.find('[name="id"]').val(),
            bid: $this.find('[name="bid"]').val(),
            title: $this.find('[name="title"]').val(),
            description: $this.find('[name="description"]').val(),
            link: $this.find('[name="link"]').val(),
            target: $this.find('[name="target"]').val(),
            image: $this.find('[name="image"]').val(),
            status: $this.find('[name="status"]').is(':checked') ? 1 : 0,
            exptime: $this.find('[name="exptime"]').val()
        }

        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manager&nocache=' + new Date().getTime(),
            data: $.param(data) + '&submit=1',
            dataType: 'json',
            success: function(e) {
                $(cfg.ctSubmitBtn).removeAttr('disabled');
                $(cfg.ctModal).find(cfg.load).hide();
                if (e.status == 'success') {
                    alert(e.message);
                    window.location.href = window.location.href;
                } else {
                    $.each(e.error, function(k, v) {
                        if (v.name == '') {
                            alert(v.value);
                        } else {
                            $this.find('[name=' + v.name + ']').attr({
                                'title': v.value,
                                'data-trigger': 'focus'
                            }).tooltip().parent().addClass('has-error');
                        }
                    });

                    $(cfg.ctModal + ' .has-error:first input').focus();
                }
            }
        });
    });


    // Edit content click
    $(cfg.ctEditLink).click(function(e) {
        e.preventDefault();
        $this = $(this);
        $(cfg.ctModal).find(cfg.load).show();
        $(cfg.ctModal + ' [name="id"]').val($this.data('id'));
        $(cfg.ctModal + ' .ip').attr('disabled', 'disabled');
        $(cfg.ctSubmitBtn).attr('disabled', 'disabled');
        $(cfg.ctModal).modal('toggle');
    });

    // Delete content click
    $(cfg.ctDelLink).click(function(e) {
        e.preventDefault();
        $(cfg.ctModalDelete).find('[name="id"]').val($(this).data('id'));
        $(cfg.ctModalDelete).find('.confirm').show();
        $(cfg.ctModalDelete).find('.loading').hide();
        $(cfg.ctModalDelete).find('.message').hide();
        $(cfg.ctModalDelete).find('.success').hide();
        $(cfg.ctDelBtn).removeAttr('disabled');
        $(cfg.ctModalDelete).modal('toggle');
    });

    // Delete content submit
    $(cfg.ctDelBtn).click(function(e) {
        e.preventDefault();
        $(this).attr('disabled', 'disabled');
        $(cfg.ctModalDelete).find('.confirm').hide();
        $(cfg.ctModalDelete).find('.loading').show();

        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manager&nocache=' + new Date().getTime(),
            data: 'id=' + $(cfg.ctModalDelete).find('[name="id"]').val() + '&del=1',
            dataType: 'json',
            success: function(e) {
                $(cfg.ctModalDelete).find('.loading').hide();

                if (e.status == 'success') {
                    $(cfg.ctModalDelete).find('.success').show();

                    setTimeout(function() {
                        var total = $(cfg.ctCouter).data('total') - 1;
                        $(cfg.ctModalDelete).modal('toggle');
                        $(cfg.ctRow + $(cfg.ctModalDelete).find('[name="id"]').val()).remove();
                        $(cfg.ctCouter).data('total', total).html(total);
                        if (total <= 0) {
                            window.location.href = window.location.href;
                        }
                    }, 1000);
                } else {
                    $(cfg.ctModalDelete).find('.message').addClass('text-danger').html(e.message).show();
                }
            }
        });
    });

    // Change content status
    $(cfg.ctStatusBtn).click(function(e) {
        e.preventDefault();
        $this = $(this);

        if (!$this.is('.' + cfg.ctStatusPrefix + '-disabled')) {
            $this.removeClass(cfg.ctStatusPrefix + $this.data('status')).addClass(cfg.ctStatusPrefix + '-disabled');
            $.ajax({
                type: 'POST',
                cache: false,
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=manager&nocache=' + new Date().getTime(),
                data: 'id=' + $this.data('id') + '&changestatus=1',
                dataType: 'json',
                success: function(e) {
                    if (e.status == 'success') {
                        $this.data('status', '' + e.responCode);
                        $this.removeClass(cfg.ctStatusPrefix + '-disabled').addClass(cfg.ctStatusPrefix + e.responCode).prop('title', e.responText);
                    } else {
                        $this.removeClass(cfg.ctStatusPrefix + '-disabled').addClass(cfg.ctStatusPrefix + $this.data('status'));
                        alert(e.message);
                    }
                }
            });
        }
    });
});
