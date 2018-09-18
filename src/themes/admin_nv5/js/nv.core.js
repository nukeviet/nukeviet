/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

var nvScrollbar = [];
var nvThemeCfg = {
    rSidebarToggleBtn: ".nv-toggle-right-sidebar",
    openSidebarDelay: 400,
    openRightSidebarClass: "open-right-sidebar",
    transitionClass: "nv-animate", // Class transition CSS 3
    closeRsOnClickOutside: true, // Đóng thanh bên phải khi click vào bên ngoài (nếu ở chế độ mobile thì tự động = true)
    leftSidebarSlideSpeed: 200,
    leftSidebarToggleSpeed: 300,
    collapsibleSidebarClass: "main-collapsible-sidebar",
    collapsibleSidebarCollapsedClass: "main-collapsible-sidebar-collapsed",
    toggleLeftSidebarBtnClass: "main-toggle-left-sidebar-btn",
    openLeftSidebarOnClick: true,
    scrollTop: true,
    scrollTopSpeed: 200
};
var nvBody = $("body");
var nvWraper = $("body>.wrapper");
var nvLBar = $(".main-left-sidebar");
var nvLBarScroll = $('.left-sidebar-scroll', nvLBar);
var nvLBarScroller;
var nvLBarSubsScroller = {};
var nvRBar = $(".main-right-sidebar");
var nvRBarIsSliding = false;
var nvTimerRegister = {};
var nvTimerHandler;

function setRightSidebarSliding() {
    nvRBarIsSliding = true;
    setTimeout(function() {
        nvRBarIsSliding = false;
    }, nvThemeCfg.openSidebarDelay);
}

function closeRightSidebar() {
    nvBody.removeClass(nvThemeCfg.openRightSidebarClass).addClass(nvThemeCfg.transitionClass);
    setRightSidebarSliding();
}

function isCollapsibleLeftSidebar() {
    return nvWraper.hasClass(nvThemeCfg.collapsibleSidebarCollapsedClass)
}

function updateLeftSidebarScrollbar() {
    if (nvWraper.hasClass("main-fixed-sidebar") && !$.isSm()) {
        nvLBarScroller.update();
    }
}

function closeLeftSidebarSub(subMenu, menu) {
    var li = $(subMenu).parent(),
        subMenuOpened = $("li.open", li), // Các menu con đang mở
        notInMenu = !menu.closest(nvLBar).length,
        speed = nvThemeCfg.leftSidebarSlideSpeed,
        isLev1 = menu.parents().eq(1).hasClass("sidebar-elements"); // Xác định có phải menu cấp 1 không

    if (!$.isSm() && isCollapsibleLeftSidebar() && (isLev1 || notInMenu)) {
        // Đóng menu dạng thu gọn
        li.removeClass("open");
        subMenu.removeClass("visible");
        subMenuOpened.removeClass("open").removeAttr("style");
        updateLBarSubsScroller();
    } else {
        // Đóng menu dạng đầy đủ
        subMenu.slideUp({
            duration: speed,
            complete: function() {
                li.removeClass("open");
                $(this).removeAttr("style");
                subMenuOpened.removeClass("open").removeAttr("style");
                updateLeftSidebarScrollbar();
                updateLBarSubsScroller();
            }
        });
    }

}

function openLeftSidebarSub(menu) {
    var li = $(menu).parent(), // Li item
        subMenu = $(menu).next(),
        speed = nvThemeCfg.leftSidebarSlideSpeed,
        isLev1 = menu.parents().eq(1).hasClass("sidebar-elements"), // Xác định có phải menu cấp 1 không
        menuOpened = li.siblings(".open"); // Các menu cùng cấp khác đang mở

    // Đóng các menu cùng cấp đang mở
    if (menuOpened) {
        closeLeftSidebarSub($("> ul", menuOpened), menu);
    }

    if (!$.isSm() && isCollapsibleLeftSidebar() && isLev1) {
        // Mở menu dạng thu gọn
        destroyLBarSubsScroller();
        li.addClass("open");
        subMenu.addClass("visible");

        var scroller = li.find(".nv-left-sidebar-scroller");
        scroller.each(function(k, v) {
            nvLBarSubsScroller[k] = new PerfectScrollbar(v, {
                wheelPropagation: false
            });
        });
    } else {
        // Mở menu dạng đầy đủ
        subMenu.slideDown({
            duration: speed,
            complete: function() {
                li.addClass("open");
                $(this).removeAttr("style");
                updateLeftSidebarScrollbar();
                updateLBarSubsScroller();
            }
        });
    }
}

function updatePerfectScrollbar() {
    $.each(nvScrollbar, function(k, v) {
        nvScrollbar[k].update();
    });
}

nvTimerHandler = function(func, msec, key) {
    if (nvTimerRegister[key]) {
        clearTimeout(nvTimerRegister[key]);
    }
    nvTimerRegister[key] = setTimeout(func, msec);
}

function destroyLBarSubsScroller() {
    $.each(nvLBarSubsScroller, function(k, v) {
        nvLBarSubsScroller[k].destroy();
    });
    nvLBarSubsScroller = {};
}

function updateLBarSubsScroller() {
    $.each(nvLBarSubsScroller, function(k, v) {
        nvLBarSubsScroller[k].update();
    });
}

$(document).ready(function() {
    // Thanh cuộn
    $('.nv-scroller').each(function(k, v) {
        nvScrollbar[k] = new PerfectScrollbar(v, {
            wheelPropagation: $(this).data('wheel') ? true : false
        });
    });

    // Xử lý menu trái nếu có nút mở rộng, thu gon menu trái
    if (nvBody.hasClass(nvThemeCfg.collapsibleSidebarClass)) {
        // Chưa có gì cần xử lý
    }

    // Xử lý menu trái nếu menu trái cố định
    if (nvWraper.hasClass('main-fixed-sidebar')) {
        if (!$.isSm()) {
            nvLBarScroller = new PerfectScrollbar(nvLBarScroll[0], {
                wheelPropagation: false
            });
        }
        $(window).resize(function() {
            nvTimerHandler(function() {
                if ($.isSm()) {
                    nvLBarScroller.destroy();
                } else {
                    if (nvLBarScroll.hasClass('ps')) {
                        nvLBarScroller.update();
                    } else {
                        nvLBarScroller = new PerfectScrollbar(nvLBarScroll[0], {
                            wheelPropagation: false
                        });
                    }
                }
            }, 500, 'update_left_sidebar_scroller');
        });
    }

    // Cập nhật các thanh cuộn khi ấn menu đổ xuống
    $(".dropdown").on("shown.bs.dropdown", function() {
        updatePerfectScrollbar();
    });

    // Cập nhật các thanh cuộn thay đổi các tab
    $(".nav-tabs").on("shown.bs.tab", function(e) {
        updatePerfectScrollbar();
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('[data-toggle="popover"]').popover();

    $(".modal").on("show.bs.modal", function() {
        $("html").addClass("main-modal-open");
    });

    $(".modal").on("hidden.bs.modal", function() {
        $("html").removeClass("main-modal-open");
    });

    // Đóng mở menu phải
    $(nvThemeCfg.rSidebarToggleBtn).on('click', function(e) {
        e.preventDefault();
        if (nvRBarIsSliding && nvBody.hasClass(nvThemeCfg.openRightSidebarClass)) {
            closeRightSidebar();
        } else if (!nvRBarIsSliding) {
            nvBody.addClass(nvThemeCfg.openRightSidebarClass + " " + nvThemeCfg.transitionClass);
            nvRBarIsSliding = true;
        }
    });

    // Click vào nút mở rộng menu con ở menu trái
    var lBarItemArrow = $(".sidebar-elements li a .toggle", nvLBar);
    lBarItemArrow.on("click", function(e) {
        var menu = $(this).parent();
        var li = menu.parent();
        var subMenu = menu.next();
        e.preventDefault();
        e.stopPropagation();
        if (li.hasClass("open")) {
            closeLeftSidebarSub(subMenu, menu);
        } else {
            openLeftSidebarSub(menu);
        }
    });

    // Click vào link menu trái
    var lBarItem = $(".sidebar-elements li a", nvLBar);
    lBarItem.on("click", function(e) {
        var menu = $(this);
        var li = menu.parent();
        var subMenu = menu.next();
        if ((isCollapsibleLeftSidebar() && menu.parent().parent().is('.sidebar-elements') && li.is('.parent')) || menu.attr('href') == '#') {
            e.preventDefault();
            if (li.hasClass("open")) {
                closeLeftSidebarSub(subMenu, menu);
            } else {
                openLeftSidebarSub(menu);
            }
        }
    });

    // Đóng, mở thanh menu trái
    $('.' + nvThemeCfg.toggleLeftSidebarBtnClass).on('click', function(e) {
        e.preventDefault();
        if (nvWraper.hasClass(nvThemeCfg.collapsibleSidebarCollapsedClass)) {
            // Đóng menu thu gọn
            nvWraper.removeClass(nvThemeCfg.collapsibleSidebarCollapsedClass);
            $("li.open", nvLBar).removeClass("open");
            $("li.active", nvLBar).parents(".parent").addClass("active open");
            // Xóa bỏ các thanh cuộn ở menu con
            destroyLBarSubsScroller();
        } else {
            // Mở menu thu gọn
            nvWraper.addClass(nvThemeCfg.collapsibleSidebarCollapsedClass);
            $("li.active", nvLBar).parents(".parent").removeClass("open");
            $("li.open", nvLBar).removeClass("open");
        }
        updateLeftSidebarScrollbar();
    });

    // Đóng mở thanh menu trái ở dạng mobile
    $(".left-sidebar-toggle", nvLBar).on("click", function(t) {
        if ($(this).attr('href') == '#') {
            t.preventDefault();
        }
        $(this).toggleClass("open");
        nvBody.toggleClass('left-sidebar-open-sm');
        $(this).next(".left-sidebar-spacer").slideToggle(nvThemeCfg.leftSidebarToggleSpeed, function() {
            $(this).removeAttr("style").toggleClass("open");
        });
    });

    // Cuộn top
    if (nvThemeCfg.scrollTop) {
        var scrollTop = $('<div class="main-scroll-top"><i class="fas fa-angle-up"></i></div>');
        scrollTop.appendTo("body");
        $(window).on("scroll", function() {
            $(this).scrollTop() > 220 ? scrollTop.fadeIn(500) : scrollTop.fadeOut(500);
        });
        scrollTop.on("touchstart mouseup", function(e) {
            e.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            }, nvThemeCfg.scrollTopSpeed);
        })
    }

    // Event khi ấn vào bất kỳ cái gì
    $(document).on("mousedown touchstart", function(e) {
        // Đóng menu phải
        if (!$(e.target).closest(nvRBar).length && nvBody.hasClass(nvThemeCfg.openRightSidebarClass) && (nvThemeCfg.closeRsOnClickOutside || $.isSm())) {
            closeRightSidebar();
        }
        // Đóng menu trái
        if (!$(e.target).closest(nvLBar).length && !$.isSm()) {
            closeLeftSidebarSub($("ul.visible", nvLBar), $(e.currentTarget));
        }
    });

    // Chọn ngôn ngữ
    $('.nv-choose-lang:not(:checked)').click(function() {
        window.location = $(this).val();
    });
});

// Kích hoạt transition sau khi trang đã tải xong
$(window).on('load', function() {
    nvBody.addClass(nvThemeCfg.transitionClass);
});

/*
 * Kiểm tra loại màn hình
 */
+function(e) {
    e.isBreakpoint = function(t) {
        var i, a, o;
        switch (t) {
            case "xs":
                o = "d-none d-sm-block";
                break;
            case "sm":
                o = "d-none d-md-block";
                break;
            case "md":
                o = "d-none d-lg-block";
                break;
            case "lg":
                o = "d-none d-xl-block";
                break;
            case "xl":
                o = "d-none"
        }
        return a = (i = e("<div/>", {
            class: o
        }).appendTo("body")).is(":hidden"), i.remove(), a
    };
    e.extend(e, {
        isXs: function() {
            return e.isBreakpoint("xs")
        },
        isSm: function() {
            return e.isBreakpoint("sm")
        },
        isMd: function() {
            return e.isBreakpoint("md")
        },
        isLg: function() {
            return e.isBreakpoint("lg")
        },
        isXl: function() {
            return e.isBreakpoint("xl")
        }
    });
}(jQuery);
