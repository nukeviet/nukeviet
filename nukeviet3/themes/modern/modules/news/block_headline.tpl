<!-- BEGIN: main -->
<div id="topnews" style="display:none">
    <div class="indent clearfix">
        <!-- BEGIN: hots_news_img -->
        <div class="headline last fl" style="width:310px;">
            <div id="slider1" class="sliderwrapper">
                <!-- BEGIN: loop -->
                <div class="contentdiv clearfix">
                    <a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}"><img src="{HOTSNEWS.image_url}" alt="{HOTSNEWS.image_alt}" /></a><h3><a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}">{HOTSNEWS.title}</a></h3>
                </div>
                <!-- END: loop -->
            </div>
            <div id="paginate-slider1" class="pagination">
            </div>
            <script type="text/javascript">
                featuredcontentslider.init({
                    id: "slider1", //id of main slider DIV
                    contentsource: ["inline", ""], //Valid values: ["inline", ""] or ["ajax", "path_to_file"]
                    toc: "#increment", //Valid values: "#increment", "markup", ["label1", "label2", etc]
                    nextprev: ["&nbsp;", "&nbsp;"], //labels for "prev" and "next" links. Set to "" to hide.
                    revealtype: "click", //Behavior of pagination links to reveal the slides: "click" or "mouseover"
                    enablefade: [true, 0.2], //[true/false, fadedegree]
                    autorotate: [true, 3000], //[true/false, pausetime]
                    onChange: function(previndex, curindex){ //event handler fired whenever script changes slide
                        //previndex holds index of last slide viewed b4 current (1=1st slide, 2nd=2nd etc)
                        //curindex holds index of currently shown slide (1=1st slide, 2nd=2nd etc)
                    }
                })
            </script>
        </div>
        <!-- END: hots_news_img -->
        <div id="tabs" class="fr tabs" style="width:300px;">
            <ul>
                <!-- BEGIN: loop_tabs_title -->
                <li>
                    <a href="#tabs-{TAB_TITLE.id}"><span><span>{TAB_TITLE.title}</span></span></a>
                </li>
                <!-- END: loop_tabs_title -->
            </ul>
            <div class="clear">
            </div>
			<!-- BEGIN: loop_tabs_content -->
            <div id="tabs-{TAB_TITLE.id}">
                <!-- BEGIN: content -->
                <ul class="lastest-news">
                    <!-- BEGIN: loop -->
                    <li>
                        <a title="{LASTEST.title}" href="{LASTEST.link}">{LASTEST.title}</a>
                    </li>
                    <!-- END: loop -->
                </ul>
                <!-- END: content -->
            </div>
			<!-- END: loop_tabs_content -->
        </div>
    </div>
</div>
<!-- END: main -->
