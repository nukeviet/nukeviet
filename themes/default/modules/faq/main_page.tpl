<!-- BEGIN: main -->
<div class="page_title" style="margin-bottom:5px">
    {PAGE_TITLE}
</div>
<!-- BEGIN: welcome -->
<div class="welcome">
    {WELCOME}.
</div>
<!-- END: welcome -->
<!-- BEGIN: subcats -->
<ul class="catlist">
    <!-- BEGIN: li -->
    <li class="main">
        {SUBCAT.name}
    </li>
    <!-- BEGIN: description -->
    <li class="description">
        {SUBCAT.description}
    </li>
    <!-- END: description -->
    <!-- END: li -->
</ul>
<!-- END: subcats -->
<!-- BEGIN: is_show_row -->
<div class="show_row">
    <a name="faqlist"></a>
    <!-- BEGIN: row -->
    <div class="block_faq">
        <div class="title">
            <a href="javascript:void(0);" onclick="faq_show_content({ROW.id});">{ROW.title}</a>
        </div>
    </div>
    <!-- END: row -->
</div>
<div class="show_detail">
    <!-- BEGIN: detail -->
    <a name="faq{ROW.id}"></a>
    <div class="detail_faq">
        <div class="title">
            <div class="gotop">
                <a href="#faqlist" title="{LANG.go_top}"><img alt="{LANG.go_top}" title="{LANG.go_top}" src="{IMG_GO_TOP_SRC}top.gif" width="16" height="16" /></a>
            </div>
            {ROW.title}
        </div>
        <div class="question">
            <strong>{LANG.faq_question}:</strong><br />
            {ROW.question}
        </div>
        <div class="answer">
            <strong>{LANG.faq_answer}:</strong><br />
            {ROW.answer}
        </div>
    </div>
    <!-- END: detail -->
</div>
<!-- END: is_show_row -->
<!-- END: main -->
