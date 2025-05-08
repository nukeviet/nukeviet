<!-- BEGIN: main -->
<!-- BEGIN: h1 -->
<h1 class="hidden d-none">{PAGE_TITLE}</h1>
<!-- END: h1 -->
<!-- BEGIN: catcontent -->
{CATCONTENT_HTML}
<!-- END: catcontent -->
<div class="row">
<!-- BEGIN: loopcat -->
<!-- BEGIN: block_topcat -->
<div class="block-top clear">
    {BLOCK_TOPCAT}
</div>
<!-- END: block_topcat -->
<div class="news_column two_column col-md-12">
    <div class="panel panel-default clearfix">
        <div class="panel-heading">
            <h2 class="h4 cat-icon"><a title="{CAT.title}" href="{CAT.link}"><strong>{CAT.title}</strong></a></h2>
        </div>
        <div class="panel-body">
            <!-- BEGIN: content -->
            <h3>
                <a href="{CONTENT.link}" title="{CONTENT.title}" {CONTENT.target_blank}>{CONTENT.title}</a>
                <!-- BEGIN: newday -->
                <span class="icon_new">&nbsp;</span>
                <!-- END: newday -->
            </h3>
            <div class="text-muted">
                <ul class="list-unstyled list-inline">
                    <li><em class="fa fa-clock-o">&nbsp;</em> {CONTENT.publtime}</li>
                    <li><em class="fa fa-eye">&nbsp;</em> {CONTENT.hitstotal}</li>
                    <!-- BEGIN: comment -->
                    <li><em class="fa fa-comment-o">&nbsp;</em> {CONTENT.hitscm}</li>
                    <!-- END: comment -->
                </ul>
            </div>
            <!-- BEGIN: image -->
            <a href="{CONTENT.link}" title="{CONTENT.title}" {CONTENT.target_blank}><img alt="{HOMEIMGALT01}" src="{HOMEIMG01}" width="{IMGWIDTH0}" class="img-thumbnail pull-left imghome" /></a>
            <!-- END: image -->
            <p>{CONTENT.hometext}</p>
            <!-- BEGIN: adminlink -->
            <p class="text-right">
                {ADMINLINK}
            </p>
            <!-- END: adminlink -->
            <!-- END: content -->
            <ul class="related list-items">
                <!-- BEGIN: other -->
                <li class="{CLASS}">
                    <a class="show h4" href="{CONTENT.link}" {CONTENT.target_blank} <!-- BEGIN: tooltip -->data-content="{CONTENT.hometext_clean}" data-img="{CONTENT.imghome}" data-rel="tooltip" data-placement="{TOOLTIP_POSITION}"<!-- END: tooltip --> title="{CONTENT.title}">{CONTENT.title}</a>
                </li>
                <!-- END: other -->
            </ul>
        </div>
    </div>
</div>
<!-- BEGIN: block_bottomcat -->
<div class="bottom-cat clear">
    {BLOCK_BOTTOMCAT}
</div>
<!-- END: block_bottomcat -->
<!-- END: loopcat -->
</div>
<div class="clear"></div>
<script type="text/javascript">
var cat2ColTimer;
$.scrollbarWidth=function(){var a,b,c;if(c===undefined){a=$('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body');b=a.children();c=b.innerWidth()-b.height(99).innerWidth();a.remove()}return c};
function fixColumnHeight(){
    var winW = $(document).width() + $.scrollbarWidth();
    if (winW < 992) {
        $('.two_column .panel-body').height('auto');
    } else {
        $.each($('.two_column .panel-body'), function(k,v) {
            if(k % 2 == 0) {
                $($('.two_column .panel-body')[k]).height('auto');
                $($('.two_column .panel-body')[k+1]).height('auto');
                var height1 = $($('.two_column .panel-body')[k]).height();
                var height2 = $($('.two_column .panel-body')[k+1]).height();
                var height = (height1 > height2 ? height1 : height2);
                $($('.two_column .panel-body')[k]).height(height);
                $($('.two_column .panel-body')[k+1]).height(height);
            }
        });
    }
}
$(window).on('load', function() {
    cat2ColTimer = setTimeout(function(){
       fixColumnHeight();
    }, 100)
});
$(function(){
    $(window).resize(function(){
        clearTimeout(cat2ColTimer)
        cat2ColTimer = setTimeout(function(){
           fixColumnHeight();
        }, 100)
    });
});
</script>
<!-- END: main -->
