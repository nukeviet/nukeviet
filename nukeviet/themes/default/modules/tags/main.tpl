<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="news_column">
    <div class="items clearfix">
        <!-- BEGIN: image -->
        <a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{CONTENT.title}" src="{CONTENT.image}" width="100" /></a>
        <!-- END: image -->
        <h4><a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a></h4>
        <p>
            {CONTENT.text}
        </p>
    </div>
</div>
<!-- END: loop -->
<!-- BEGIN: pages -->
<div class="news_column">
    {PAGES}
</div>
<!-- END: pages -->
<!-- END: main -->