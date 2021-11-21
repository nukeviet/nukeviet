<!--BEGIN: main-->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/slide_images/owl.carousel.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/slide_images/owl.theme.default.css">
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/slide_images/owl.carousel.min.js"></script>
<script>
$(document).ready(function() {
    $('.owl-carousel').owlCarousel({
        nav: true,  
        navText: ["<i class='fa fa-chevron-left' aria-hidden='true'></i>","<i class='fa fa-chevron-right' aria-hidden='true'></i>"],
        margin: 10,
        items: 3,
        loop:true,
        responsive:{
            0:{
                items:2,
                nav: true
            },
            500:{
                items:3
            }
        }
    });
});
</script>
<div style = "margin: 50px 0;" >
    <div class = "owl-carousel owl-theme">
        <!-- BEGIN: loop -->
            <img src = "{DATA.link}" alt = ""/>
        <!-- END: loop -->
    </div>
</div>
<!--END: main-->