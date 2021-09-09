<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
    <li><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
    <li class="active"><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
    <li><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<form id="frm" action="{FORM_ACTION}" method="post" enctype="multipart/form-data" role="form" class="form-horizontal" onsubmit="return afSubmit(event, this)"<!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
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
            <input class="required form-control" type="text" name="title" id="title" value="{DATA.title}" maxlength="240" onkeypress="errorHidden(this);" data-mess="{LANG.title_empty}" />
        </div>
    </div>
    <div id="banner_uploadimage" style="display: none;">
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.addads_adsdata}:</label>
            <div class="col-sm-18">
                <input type="file" name="image" id="image" value="" class="file form-control" onchange="errorHidden(this);" data-mess="{LANG.file_upload_empty}" />
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
            <input class="url form-control" type="text" name="url" id="url" value="{DATA.url}" maxlength="240" onkeypress="errorHidden(this);" data-mess="{LANG.click_url_invalid}" />
        </div>
    </div>
    <!-- BEGIN: captcha -->
    <div class="form-group">
        <label class="col-sm-6 control-label">{N_CAPTCHA}:</label>
        <div class="col-sm-18">
            <input type="text" maxlength="{NV_GFX_NUM}" value="" id="fcode_iavim" name="captcha" class="form-control required pull-left margin-right" style="width: 150px;" onkeypress="errorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
            <img height="32" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" alt="{N_CAPTCHA}" class="captchaImg" />
            <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="change_captcha('#fcode_iavim');" />
        </div>
    </div>
    <!-- END: captcha -->
    <!-- BEGIN: recaptcha -->
    <div class="form-group">
        <div class="col-sm-offset-6 col-sm-18">
            <div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="3" data-btnselector="[type=submit]"></div>
        </div>
    </div>
    <!-- END: recaptcha -->
    <div class="form-group">
        <div class="col-sm-offset-6 col-sm-18">
            <input type="hidden" name="confirm" value="1" />
            <input type="submit" value="{LANG.add_banner}" class="btn btn-primary" onclick="btnClickSubmit(event,this.form);" />
        </div>
    </div>
</form>
<!-- END: main -->