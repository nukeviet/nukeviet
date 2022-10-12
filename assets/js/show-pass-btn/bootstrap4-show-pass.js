var resetPass,
    togglePassHide = function() {
        $("[type=text][data-nv-type=password]").each(function() {
            $(this).removeAttr('data-nv-type').prop("type", "password");
            clearTimeout(resetPass)
        })
    },
    togglePassShow = function(input) {
        if ('password' == input.prop('type')) {
            input.attr('data-nv-type', 'password');
            input.prop("type", "text");
            clearTimeout(resetPass);
            resetPass = setTimeout(function() {
                togglePassHide()
            }, 2E4);
        } else {
            togglePassHide()
        }
    },
    addPassBtn = function() {
        $('[type=password].form-control:not(.btn-eye-added)').each(function() {
            $(this).addClass('btn-eye-added');
            var that = $(this),
                nxt = that.next(),
                $btn = $('<button type="button" class="btn btn-eye"></button>'),
                $igroup = $('<div class="input-group"></div>'),
                $copy;
            if (that.parent().is('.input-group')) {
                that.after($btn)
            } else {
                $copy = that.clone();
                $igroup.prepend($copy).append($btn);
                that.replaceWith($igroup);
                that = $copy
            }
            $btn.on('click', function() {
                togglePassShow(that)
            })
        })
    };

$(function() {
    addPassBtn();
});
