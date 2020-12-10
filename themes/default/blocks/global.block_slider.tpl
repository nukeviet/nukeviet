<!-- BEGIN: main -->
<style>
    #carousel .item{
        cursor:grab;
        cursor:-webkit-grab;
    }

    #carousel .item img {
        display: block;
        width: 100%;
        height: auto;
    }

    /* Styling Pagination*/
    .owl-theme .owl-controls .owl-page span{
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 5px;
        width: 10px;
        height: 10px;
        margin-left: 2px;
        margin-right: 2px;
        background: #ccc;
        border: none;
    }

    .owl-theme .owl-controls .owl-page.active span,
    .owl-theme .owl-controls.clickable .owl-page:hover span{
      background: #3F51B5;
    }
    .owl-prev, .owl-next {
        width: 0;
        height: 0;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: block !important;
        border:0px solid black;
    }
    .owl-prev { left: -20px; }
    .owl-next { right: -20px; }
    .owl-prev i, .owl-next i {transform : scale(3,4); color: #ccc;}
</style>

<div id="carousel" class="main-carousel owl-carousel owl-theme">
    <!-- BEGIN: slider_loop -->
    <div class="item">
        <a href="{ROW.click_url}">
            <img src="{NV_BASE_SITEURL}/{NV_UPLOADS_DIR}/banners/{ROW.file_name}" target="{ROW.target}" alt="" />            
        </a>
    </div>
    <div class="item">
        <a href="{ROW.click_url}">
            <img src="{NV_BASE_SITEURL}/{NV_UPLOADS_DIR}/banners/{ROW.file_name}" target="{ROW.target}" alt="" />            
        </a>
    </div>
    <!-- END: slider_loop -->
</div>

<script src="{NV_BASE_SITEURL}/{NV_ASSETS_DIR}/vendor/OwlCarousel2-2.3.4/docs/assets/vendors/jquery.min.js"></script>
<link rel="stylesheet" href="{NV_BASE_SITEURL}/{NV_ASSETS_DIR}/vendor/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}/{NV_ASSETS_DIR}/vendor/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css">
<script src="{NV_BASE_SITEURL}/{NV_ASSETS_DIR}/vendor/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>

<script>
    $(window).on('load', function(){
        $(".owl-carousel").owlCarousel({
            items: 1,
            loop: true,
            margin: 10,
            autoplay: 3e3,
            stopOnHover : true,
            nav: true,
            navText: [
              "<i class='fa fa-chevron-left'></i>",
              "<i class='fa fa-chevron-right'></i>"
            ],
            navClass: ['owl-prev', 'owl-next'],
            responsiveClass:true,
            responsiveRefreshRate: 10,
            responsive : {
                0: {
                    items : 2,
                },
                500: {
                    items : 3,
                },
                768: {
                    items : 5,
                    nav: true,
                    dots: true,
                }
            }
        });
    });
</script>
<!-- END: main -->