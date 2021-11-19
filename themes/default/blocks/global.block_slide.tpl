<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/theme_bkhdt/js/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/theme_bkhdt/js/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css">
<div class="carousel-wrap">
    <div class="owl-carousel owl-theme">
        <div class="item">
          <img src="https://picsum.photos" />
          <span class="img-text">nightlife</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=2" />
          <span class="img-text">abstract</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=3" />
          <span class="img-text">animals</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=4" />
          <span class="img-text">nature</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=5" />
          <span class="img-text">business</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=6" />
          <span class="img-text">cats</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=7" />
          <span class="img-text">city</span>
        </div>
        <div class="item">
          <img src="https://picsum.photos/640/480?pic=8" />
          <span class="img-text">food</span>
        </div>
        
    </div>
  </div>

<script src="{NV_BASE_SITEURL}themes/theme_bkhdt/js/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
<script>
$(document).ready(function(){
  $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    responsiveClass:true,
	navText:["<div class='nav-btn prev-slide'>‹</div>","<div class='nav-btn next-slide'>›</span></div>"],
    responsive:{
        0:{
            items:2,
            nav:true
        },
        500:{
            items:3,
            nav:false
        },
        768:{
            items:5,
            nav:true,
			dotsEach: 3,
            loop:true
        }
    }
})
});
</script>
<!-- END: main -->