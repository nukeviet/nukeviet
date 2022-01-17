<!-- BEGIN: main  -->
<ul class="list-unstyled nv-block-rss">
    <!-- BEGIN: loop -->
    <li class="padding-top padding-bottom {DATA.class}">
        <a class="list-title" {DATA.target} title="{DATA.title}" href="{DATA.link}">{DATA.text}</a>
        <!-- BEGIN: pubDate -->
        <p class="text-muted">
            <em class="fa fa-calendar">&nbsp;</em> <em>{DATA.pubDate}</em>
        </p>
        <!-- END: pubDate -->
        <!-- BEGIN: description -->
        {DATA.description}
        <!-- END: description -->
    </li>
    <!-- END: loop -->
</ul>
<!-- END: main -->