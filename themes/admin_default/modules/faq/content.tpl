<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
    	<colgroup>
			<col class="w200"/>
		</colgroup>
        <tbody>
            <tr>
                <td>
                    {LANG.faq_title_faq} <sup class="required">(∗)</sup>
                </td>
                <td>
                    <input class="form-control" type="text" value="{DATA.title}" name="title" id="title" style="width:400px"/>
                </td>
            </tr>
            <tr>
                <td>
                    {LANG.faq_catid_faq} <sup class="required">(∗)</sup>
                </td>
                <td>
                    <select class="form-control" name="catid" style="width:400px">
                        <!-- BEGIN: catid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: catid -->
                    </select>
                </td>
            </tr>
            <tr>
            	<td>{LANG.group_post}</td>
				<td>
					<label><input type="checkbox" value="1" name="hot_post"{HOST_POST}/> {LANG.hot_post}</label>
				</td>
            </tr>
            <tr>
                <td style="vertical-align:top">
                    {LANG.faq_question_faq} <sup class="required">(∗)</sup>
                </td>
                <td><textarea name="question" id="question" class="form-control" rows="5" style="width:400px">{DATA.question}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="m-bottom">
        <h4>{LANG.faq_answer_faq} <sup class="required">(∗)</sup></h4>
        {DATA.answer}
    </div>
    <div class="text-center">
        <input class="btn btn-primary" type="submit" name="submit" value="{LANG.faq_save}" />
    </div>
</form>
<!-- END: main -->