<!-- BEGIN: main -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.group_edit}</h2>
<form action="{FORM_ACTION}" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm" autocomplete="off" novalidate>
    <div class="nv-info" data-default="" style="display:none"></div>
    <div class="form-detail well-lg">
        <div class="form-group">
            <label for="group_title" class="control-label col-sm-7 col-md-6 text-normal">{LANG.group_title}</label>
            <div class="col-sm-17 col-md-18">
                <input type="text" class="form-control required" placeholder="{LANG.group_title}" value="{DATA.title}" name="group_title" id="group_title" maxlength="240" data-toggle="validErrorHidden" data-event="keypress" data-mess="">
            </div>
        </div>

        <div class="form-group">
            <label for="group_desc" class="control-label col-sm-7 col-md-6 text-normal">{LANG.group_desc}</label>
            <div class="col-sm-17 col-md-18">
                <input type="text" class="form-control" placeholder="{LANG.group_desc}" value="{DATA.description}" name="group_desc" id="group_desc" maxlength="240">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-24 text-normal">{LANG.group_content}</label>
            <div class="col-sm-24">{DATA.htmlbodyhtml}</div>
        </div>

        <div class="text-center">
            <input type="hidden" name="save" value="1" />
            <input type="hidden" name="checkss" value="{DATA.checkss}" />
            <input type="submit" class="btn btn-primary" value="{GLANG.save}" />
        </div>
    </div>
</form>
<!-- END: main -->
