<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 780px;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
        <tbody>
            <tr>
                <td>
                    {LANG.faq_title_faq}
                </td>
                <td style="white-space: nowrap">
                    <input class="txt" type="text" value="{DATA.title}" name="title" id="title" style="width:300px" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.faq_catid_faq}
                </td>
                <td style="white-space: nowrap">
                    <select name="catid">
                        <!-- BEGIN: catid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: catid -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="vertical-align:top">
                    {LANG.faq_question_faq}
                </td>
                <td style="white-space: nowrap">
                    <textarea name="question" id="question" style="width:300px;height:150px">{DATA.question}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div style="textarea-align:center;padding-top:15px">
        {LANG.faq_answer_faq}<br />
        {DATA.answer}
    </div>
    
    <div style="text-align:center;padding-top:15px">
        <input type="submit" name="submit" value="{LANG.faq_save}" />
    </div>
</form>
<!-- END: main -->
