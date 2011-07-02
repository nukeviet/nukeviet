<!-- BEGIN: main -->
<div id="faq">
<div class="faq-header">
    <h4>{PAGE_TITLE}</h4>
</div>
<!-- BEGIN: welcome -->
<p>
    {WELCOME}.
</p>
<!-- END: welcome -->
<!-- BEGIN: subcats -->
<ul class="catlist box-border-shadow content-box">
    <!-- BEGIN: li -->
    <li >
		<h3 class="cat">{SUBCAT.name}</h3>
		<!-- BEGIN: description -->
		<p class="description">
			{SUBCAT.description}
		</p>
		<!-- END: description -->
    </li>
    
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
        <div class="title clearfix">
            <div class="gotop">
                <a href="#faqlist" title="{LANG.go_top}"><img alt="{LANG.go_top}" title="{LANG.go_top}" src="{IMG_GO_TOP_SRC}top.gif" width="16" height="16" /></a>
            </div>
            {ROW.title}
        </div>
        <div class="question">
            <strong>{LANG.faq_question}:</strong><br />
			<p><em>{ROW.question}</em></p>
        </div>
        <div class="answer">
            <strong>{LANG.faq_answer}:</strong><br />
            {ROW.answer}
        </div>
    </div>
    <!-- END: detail -->
</div>
<!-- END: is_show_row -->
</div>
<!-- END: main -->
