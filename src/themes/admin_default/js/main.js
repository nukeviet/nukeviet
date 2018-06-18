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
        		second : "time_login",
        		nocache : (new Date).getTime()
        	}).done(function(json) {
        		if (json.showtimeoutsess == 1) {
                	$.get(nv_base_siteurl + "index.php?second=admin_logout&js=1&nocache=" + (new Date).getTime(), function(re) {
                        window.location.reload();
        			});
        		}
        		else {
					myTimerPage = setTimeout(function() {timeoutsessrun();}, json.check_pass_time);
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

    // Bootstrap tooltip
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
});