/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var myTimerPage = '';
var myTimersecField = '';

function timeoutsesscancel() {
    clearInterval(myTimersecField);
    $.ajax({
        url: nv_base_siteurl + 'index.php?second=statimg',
        cache: false
    }).done(function() {
        $("#timeoutsess").hide();
        load_notification = 1;
        myTimerPage = setTimeout(function() {
            timeoutsessrun();
        }, nv_check_pass_mstime);
        if (typeof nv_get_notification === "function") {
            nv_get_notification();
        }
    });
}

function timeoutsessrun() {
    clearInterval(myTimerPage);
    var Timeout = 60;
    document.getElementById('secField').innerHTML = Timeout;
    $("#timeoutsess").show();
    var msBegin = new Date().getTime();
    myTimersecField = setInterval(function() {
        load_notification = 0;
        var msCurrent = new Date().getTime();
        var ms = Timeout - Math.round((msCurrent - msBegin) / 1000);
        if (ms >= 0) {
            document.getElementById('secField').innerHTML = ms;
        } else {
            clearInterval(myTimersecField);
            $("#timeoutsess").hide();
            $.getJSON(nv_base_siteurl + "index.php", {
                second: "time_login",
                nocache: (new Date).getTime()
            }).done(function(json) {
                if (json.showtimeoutsess == 1) {
                    $.get(nv_base_siteurl + "index.php?second=admin_logout&js=1&system=1&nocache=" + (new Date).getTime(), function(re) {
                        window.location.reload();
                    });
                } else {
                    myTimerPage = setTimeout(function() {
                        timeoutsessrun();
                    }, json.check_pass_time);
                }
            });
        }
    }, 1000);
}

// ModalShow
function modalShow(a, b, callback) {
    "" == a && (a = "&nbsp;");
    $("#sitemodal").find(".modal-title").html(a);
    $("#sitemodal").find(".modal-body").html(b);
    $("#sitemodal").modal();
    $('#sitemodal').on('shown.bs.modal', function(e) {
        if (typeof callback === "function") {
            callback(this);
            $(e.currentTarget).unbind('shown');
        };
    });
}

// locationReplace
function locationReplace(url) {
    var uri = window.location.href.substr(window.location.protocol.length + window.location.hostname.length + 2);
    if (url != uri && history.pushState) {
        history.pushState(null, null, url)
    }
}

function formXSSsanitize(form) {
    $(form).find("input, textarea").not(":submit, :reset, :image, :file, :disabled").not('[data-sanitize-ignore]').each(function(e) {
        $(this).val(DOMPurify.sanitize($(this).val(), {}))
    })
}

function btnClickSubmit(event, form) {
    event.preventDefault();
    if (XSSsanitize) {
        formXSSsanitize(form)
    }
    $(form).submit()
}

var NV = {
    menuBusy: false,
    menuTimer: null,
    menu: null,
    openMenu: function(menu) {
        this.menuBusy = true;
        this.menu = $(menu);
        this.menuTimer = setTimeout(function() {
            NV.menu.addClass('open');
        }, 300);
    },
    closeMenu: function(menu) {
        clearTimeout(this.menuTimer);
        this.menuBusy = false;
        this.menu = $(menu).removeClass('open');
    },
    fixContentHeight: function() {
        var wrap = $('.nvwrap');
        var vmenu = $('#left-menu');

        if (wrap.length > 0) {
            wrap.css('min-height', '100%');
            if (wrap.height() < vmenu.height() + vmenu.offset().top && vmenu.is(':visible')) {
                wrap.css('min-height', (vmenu.height() + vmenu.offset().top) + 'px')
            }
        }
    }
};

$(document).ready(function() {
    // Control content height
    NV.fixContentHeight();
    $(window).resize(function() {
        NV.fixContentHeight();
    });

    $('a[href^="http"]').not('a[href*="' + location.hostname + '"]').not('[rel*=dofollow]').attr({
        target: "_blank"
    });


    // Show submenu
    $('#menu-horizontal .dropdown, #left-menu .dropdown:not(.active)').hover(function() {
        NV.openMenu(this);
    }, function() {
        NV.closeMenu(this);
    });

    // Left menu handle
    $('#left-menu-toggle').click(function() {
        if ($('#left-menu').is(':visible')) {
            $('#left-menu, #left-menu-bg, #container, #footer').removeClass('open');
        } else {
            $('#left-menu, #left-menu-bg, #container, #footer').addClass('open');
        }
        NV.fixContentHeight();
    });

    // Show admin confirm
    myTimerPage = setTimeout(function() {
        timeoutsessrun();
    }, nv_check_pass_mstime);

    // Show confirm message on leave, reload page
    $('form.confirm-reload').change(function() {
        $(window).bind('beforeunload', function() {
            return nv_msgbeforeunload;
        });
    });

    // Disable confirm message on submit form
    $('form').submit(function() {
        $(window).unbind();
    });

    $('a[href="#"]').on('click', function(e) {
        e.preventDefault();
    });

    $('[data-btn="toggleLang"]').on('click', function(e) {
        e.preventDefault();
        $('.menu-lang').toggleClass('menu-lang-show');
    });

    //Change Localtion
    $("[data-location]").on("click", function() {
        locationReplace($(this).data("location"))
    });

    //XSSsanitize
    $('body').on('click', '[type=submit]:not([name])', function(e) {
        var form = $(this).parents('form');
        if (!$('[name=submit]', form).length) {
            btnClickSubmit(e,form)
        }
    });

    $(document).on('click', function(e) {
        if (
            $('[data-btn="toggleLang"]').is(':visible') &&
            !$(e.target).closest('.menu-lang').length &&
            !$(e.target).closest('[data-btn="toggleLang"]').length &&
            !$(e.target).closest('.dropdown-backdrop').length
        ) {
            $('.menu-lang').removeClass('menu-lang-show');
        }
    });

    // Bootstrap tooltip
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
