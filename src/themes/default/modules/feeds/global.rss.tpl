<!-- BEGIN: main  -->
<style>
.nv-block-rss li::after {
    display: block;
    content: "";
    clear: both;
}
.nv-block-rss img {
    float: left;
    width: 180px;
    height: 120px;
    background-color: #fff;
    background-position: center;
    background-repeat: no-repeat;
    background-size:cover;
}
@media (max-width: 499.98px) {
    .nv-block-rss img {
        width: 120px;
        height: 80px;
    }
}
</style>
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