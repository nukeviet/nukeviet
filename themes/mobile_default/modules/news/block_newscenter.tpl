<!-- BEGIN: main -->
<div id="hot-news">
    <div class="panel panel-default news_column">
        <div class="panel-body clearfix">
            <a href="{main.link}"><img src="{main.imgsource}" alt="{main.title}" class="img-thumbnail pull-left imghome" style="width:183px" /></a>
            <div class="h2 margin-bottom"><a href="{main.link}"><strong>{main.title}</strong></a></div>
            <p>
                {main.hometext}
            </p>
            <p class="text-right">
                <a href="{main.link}"><em class="fa fa-sign-out">&nbsp;</em>{lang.more}</a>
            </p>
            <div class="clear">&nbsp;</div>
        </div>

        <ul class="other-news clearfix">
            <!-- BEGIN: othernews -->
            <li>
                <div class="content-box clearfix">
                    <a href="{othernews.link}"><img src="{othernews.imgsource}" alt="{othernews.title}" class="img-thumbnail pull-left imghome" style="width:56px;" /></a>
                    <div class="h5"><a class="show" href="{othernews.link}" title="{othernews.title}"><strong>{othernews.title}</strong></a></div>
                    <div class="clear"></div>
                </div>
            </li>
            <!-- END: othernews -->
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- END: main -->