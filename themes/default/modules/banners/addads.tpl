<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
    <li><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
    <li class="active"><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
    <li><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<form id="frm" action="{FORM_ACTION}" method="post" enctype="multipart/form-data" role="form" class="form-horizontal" data-toggle="afSubmit"<!-- BEGIN: captcha --> data-captcha="captcha"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <div class="form-group">
        <label for="block" class="col-sm-6 control-label">{LANG.plan_title}:</label>
        <div class="col-sm-18">
            <select name="block" id="banner_plan" class="form-control">
                <!-- BEGIN: blockitem -->
                <option value="{blockitem.id}" data-image="{blockitem.typeimage}" data-uploadtype="{blockitem.uploadtype}">{blockitem.title}</option>
                <!-- END: blockitem -->
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-6 control-label">{LANG.addads_title}:</label>
        <div class="col-sm-18">
            <input class="required form-control" type="text" name="title" id="title" value="{DATA.title}" maxlength="240" data-toggle="errorHidden" data-event="keypress" data-mess="{LANG.title_empty}" />
        </div>
    </div>
    <div id="banner_uploadimage" style="display: none;">
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.addads_adsdata}:</label>
            <div class="col-sm-18">
                <input type="file" name="image" id="image" value="" class="file form-control" data-toggle="errorHidden" data-event="change" data-mess="{LANG.file_upload_empty}" />
                <div id="banner_uploadtype"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-6 control-label">{LANG.addads_description}:</label>
            <div class="col-sm-18">
                <input type="text" name="description" id="description" value="{DATA.description}" class="form-control" maxlength="240" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="url" class="col-sm-6 control-label">{LANG.addads_url}:</label>
        <div class="col-sm-18">
            <input class="url form-control" type="text" name="url" id="url" value="{DATA.url}" maxlength="240" data-toggle="errorHidden" data-event="keypress" data-mess="{LANG.click_url_invalid}" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-6 col-sm-18">
            <input type="hidden" name="confirm" value="1" />
            <input type="submit" value="{LANG.add_banner}" class="btn btn-primary"/>
        </div>
    </div>
</form>
<!-- END: main -->