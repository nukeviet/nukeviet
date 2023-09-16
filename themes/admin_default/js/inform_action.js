var ajax_url,
    formObj;

$(function() {
    formObj = $('#inform-action-form');
    ajax_url = formObj.attr('action');

    var $receiver_grs = $('#receiver_grs', formObj).select2();
    var $receiver_ids = $('#receiver_ids', formObj).select2({
        ajax: {
            type: 'POST',
            cache: false,
            url: ajax_url,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                if ($('[name=sender_role]', formObj).val() == 'group') {
                    return {
                        get_user_json: 1,
                        grid: $('[name=sender_group]', formObj).val(),
                        q: params.term, // search term
                        page: params.page
                    };
                }

                return {
                    get_user_json: 1,
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        minimumInputLength: 3,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            return '<div>' + repo.fullname + '<br/>(ID #' + repo.id + ', ' + repo.username + ', ' + repo.email + ')</div>';
        },
        templateSelection: function(repo) {
            return repo.fullname || repo.text;
        },
        language: {
            inputTooShort: function(args) {
                return $('#receiver_ids', formObj).data('input-too-short');
            }
        }
    });

    $('[name=receiver_type]', formObj).on('change', function() {
        var form = $(this).parents('form'),
            val = $(this).val();
        if (val == 'ids') {
            form.removeClass('receiver-grs-1').addClass('receiver-grs-0');
            $('#receiver_grs', form).prop('disabled', true);
            $('#receiver_ids', form).prop('disabled', false);
        } else {
            form.removeClass('receiver-grs-0').addClass('receiver-grs-1');
            $('#receiver_grs', form).prop('disabled', false);
            $('#receiver_ids', form).prop('disabled', true);
        }
    });

    $('[name=sender_role]', formObj).on('change', function() {
        var val = $(this).val();
        $('[name=sender_group], [name=sender_admin]', formObj).val('0');
        $receiver_ids.val(null).trigger("change");
        $receiver_grs.val(null).trigger("change");
        if (val == 'system') {
            formObj.removeClass('role-group role-admin').addClass('role-system');
            $('[name=sender_group], [name=sender_admin]', formObj).prop('disabled', true);
            $('#receiver_grs', formObj).prop('disabled', false);
            $('[name=receiver_type] option[value=ids]', formObj).text($('[name=receiver_type]', formObj).data('from-system-title'));
            $('[name=receiver_type] option', formObj).prop('disabled', false);
            $('[name=receiver_type]', formObj).val('ids');
            $('[name=receiver_type]', formObj).val('ids').trigger('change')
        } else if (val == 'group') {
            formObj.removeClass('role-system role-admin').addClass('role-group');
            $('[name=sender_admin]', formObj).prop('disabled', true);
            $('[name=sender_group]', formObj).prop('disabled', false);
            $('#receiver_grs', formObj).prop('disabled', true);
            $('[name=receiver_type] option[value=ids]', formObj).text($('[name=receiver_type]', formObj).data('from-group-title'));
            $('[name=receiver_type]', formObj).val('ids');
            $('[name=receiver_type] option[value=grs]', formObj).prop('disabled', true);
            $('[name=receiver_type]', formObj).trigger('change')
        } else if (val == 'admin') {
            formObj.removeClass('role-system role-group').addClass('role-admin');
            $('[name=sender_group]', formObj).prop('disabled', true);
            $('[name=sender_admin]', formObj).prop('disabled', false);
            $('#receiver_grs', formObj).prop('disabled', false);
            $('[name=receiver_type] option[value=ids]', formObj).text($('[name=receiver_type]', formObj).data('from-system-title'));
            $('[name=receiver_type] option', formObj).prop('disabled', false);
            $('[name=receiver_type]', formObj).val('ids');
            $('[name=receiver_type]', formObj).val('ids').trigger('change')
        }
    });

    $('#receiver_ids', formObj).on("select2:open", function(e) {
        if ($('[name=sender_role]', formObj).val() == 'group' && $('[name=sender_group]', formObj).val() == '0') {
            alert($('[name=sender_group]', formObj).data('error'));
            $receiver_ids.select2('close');
            $('[name=sender_group]', formObj).focus()
        }
    });

    $('[name=sender_group]', formObj).on('change', function() {
        $receiver_ids.val(null).trigger("change");
    });

    $('[name=add_time], [name=exp_time]', formObj).datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true
    });

    formObj.on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(result) {
                if ('error' == result.status) {
                    alert(result.mess);
                    return !1
                } else if ('OK' == result.status) {
                    window.location.href = window.location.href
                }
            }
        })
    })
});
