<!-- BEGIN: main -->
<!-- BEGIN: isuser -->
<div class="row">
	<div class="button-faq">
	<a href="{LINKQA}"><button type="button" class="btn btn-primary">{LANG.faq_make_question}</button></a>
	<a href="{LINKLISTQA}"><button type="button" class="btn btn-primary">{LANG.faq_list_question}</button></a>
	</div>
</div>
<!-- END: isuser -->
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
<div class="page">
	{NV_GENERATE_PAGE}
</div>
<!-- BEGIN: is_show_row -->
<div class="row show_row">
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