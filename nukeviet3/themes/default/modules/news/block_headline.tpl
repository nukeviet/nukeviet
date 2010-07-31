<!-- BEGIN: main -->
<div id="topnews">
    <div class="indent clearfix">
        <!-- BEGIN: lastest_news_img -->
        <div class="headline span-8 last fl">
            <div id="slider1" class="sliderwrapper">
                <!-- BEGIN: loop -->
                <div class="contentdiv clearfix">
                    <a title="{LASTEST.title}" href="{LASTEST.link}"><img src="{LASTEST.image.url}" alt="{LASTEST.image.alt}" width="{LASTEST.image.width}" height="{LASTEST.image.height}" /></a><h3><a title="{LASTEST.title}" href="{LASTEST.link}">{LASTEST.title}</a></h3>
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
        <!-- END: lastest_news_img -->
        <div id="tabs" class="fr tabs" style="width:180px;">
            <ul>
                <li>
                    <a href="#tabs-1"><span><span>{LANG.topnews}</span></span></a>
                </li>
                <li>
                    <a href="#tabs-2"><span><span>{LANG.lastest}</span></span></a>
                </li>
            </ul>
            <div class="clear">
            </div>
            <div id="tabs-1">
                <!-- BEGIN: hot_news -->
                <ul class="lastest-news">
                    <!-- BEGIN: loop -->
                    <li>
                        <a title="{HOT.title}" href="{HOT.link}">{HOT.title}</a>
                    </li>
                    <!-- END: loop -->
                </ul>
                <!-- END: hot_news -->
            </div>
            <div id="tabs-2">
                <!-- BEGIN: lastest_news -->
                <ul class="lastest-news">
                    <!-- BEGIN: loop -->
                    <li>
                        <a title="{LASTEST.title}" href="{LASTEST.link}">{LASTEST.title}</a>
                    </li>
                    <!-- END: loop -->
                </ul>
                <!-- END: lastest_news -->
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
