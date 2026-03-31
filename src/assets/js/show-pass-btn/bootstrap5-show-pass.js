var resetPass,
    togglePassHide = function() {
        $("[type=text][data-nv-type=password]").each(function() {
            $(this).removeAttr('data-nv-type').prop("type", "password");
            clearTimeout(resetPass);
        });
    },
    togglePassShow = function(input) {
        if ('password' == input.prop('type')) {
            input.attr('data-nv-type', 'password');
            input.prop("type", "text");
            clearTimeout(resetPass);
            resetPass = setTimeout(function() {
                togglePassHide();
            }, 2E4);
        } else {
            togglePassHide();
        }
    },
    addPassBtn = function() {
        $('[type=password].form-control:not(.btn-eye-added)').each(function() {
            $(this).addClass('btn-eye-added');
            var that = $(this),
                $btn = $('<button type="button" class="btn btn-secondary btn-eye"></button>'),
                $igroup = $('<div class="input-group input-group-password"></div>'),
                $copy;
            if (that.parent().is('.input-group')) {
                that.after($btn);
                that.parent().addClass('input-group-password');
            } else {
                $copy = that.clone();
                $igroup.prepend($copy).append($btn);
                that.replaceWith($igroup);
                that = $copy;
            }
            $btn.on('click', function() {
                togglePassShow(that);
            });
        });
    };

$(function() {
    addPassBtn();
});
