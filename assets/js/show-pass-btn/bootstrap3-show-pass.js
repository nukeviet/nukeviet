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
                $btn = $('<button type="button" class="btn btn-default btn-eye"></button>'),
                $shape = $('<div class="input-group-btn"></div>'),
                $igroup = $('<div class="input-group"></div>'),
                $copy;
            if (nxt.is('.input-group-btn')) {
                nxt.prepend($btn)
            } else if (that.parent().is('.input-group')) {
                $shape.append($btn);
                that.after($shape)
            } else {
                $copy = that.clone();
                $shape.append($btn);
                $igroup.prepend($copy).append($shape);
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
