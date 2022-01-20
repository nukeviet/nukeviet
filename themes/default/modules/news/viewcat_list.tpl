<!-- BEGIN: main -->
<!-- BEGIN: h1 -->
<h1 class="hidden d-none">{PAGE_TITLE}</h1>
<!-- END: h1 -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
    <div class="alert alert-info clearfix">
        <h1 class="h2"><strong>{CONTENT.title}</strong></h1>
        <!-- BEGIN: image -->
        <img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left imghome" />
        <!-- END: image -->
        <p class="text-justify">{CONTENT.description}</p>
    </div>
</div>
<!-- END: viewdescription -->

<div class="list-group">
    <!-- BEGIN: viewcatloop -->
    <a class="show" href="{CONTENT.link}" {CONTENT.target_blank} <!-- BEGIN: tooltip -->data-content="{CONTENT.hometext_clean}" data-img="{CONTENT.imghome}" data-rel="tooltip" data-placement="{TOOLTIP_POSITION}"<!-- END: tooltip --> title="{CONTENT.title}">
        <h2 class="list-group-item-heading h3 margin-bottom">
            <span class="label label-default">{NUMBER}</span>&nbsp;&nbsp;{CONTENT.title}
            <!-- BEGIN: newday -->
            <span class="icon_new">&nbsp;</span>
            <!-- END: newday -->
        </h2>
    </a>
    <!-- END: viewcatloop -->
</div>

<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
