{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs m-bottom">
    <li><a href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li class="active"><a href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li><a href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
<form id="frm" action="{$FORM_ACTION}" method="post" enctype="multipart/form-data" role="form" class="form-horizontal" data-toggle="afSubmit" data-precheck="afSubmit_precheck"{if $CAPTCHA == 'captcha'} data-captcha="captcha"{elseif $CAPTCHA == 'recaptcha'} data-recaptcha2="1"{elseif $CAPTCHA == 'recaptcha3'} data-recaptcha3="1"{elseif $CAPTCHA == 'turnstile'} data-turnstile="1"{/if}>
    <div class="form-group">
        <label for="block" class="col-sm-6 control-label">{$LANG->getModule('plan_title')}:</label>
        <div class="col-sm-18">
            <select name="block" id="banner_plan" class="form-control">
{foreach $PLANS as $blockitem}
                <option value="{$blockitem.id}" data-image="{$blockitem.typeimage}" data-uploadtype="{$blockitem.uploadtype}">{$blockitem.title}</option>
{/foreach}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-6 control-label">{$LANG->getModule('addads_title')}:</label>
        <div class="col-sm-18">
            <input class="required form-control" type="text" name="title" id="title" value="" maxlength="240" data-pattern="{literal}/^(.){3,}$/{/literal}" data-toggle="errorHidden" data-event="keypress" data-mess="{$LANG->getModule('title_empty')}" />
        </div>
    </div>
    <div id="banner_uploadimage" style="display: none;">
        <div class="form-group">
            <label class="col-sm-6 control-label">{$LANG->getModule('addads_adsdata')}:</label>
            <div class="col-sm-18">
                <input type="file" name="image" id="image" value="" class="file form-control" data-toggle="errorHidden" data-event="change" data-mess="{$LANG->getModule('file_upload_empty')}" />
                <div id="banner_uploadtype"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-6 control-label">{$LANG->getModule('addads_description')}:</label>
            <div class="col-sm-18">
                <input type="text" name="description" id="description" value="" class="form-control" maxlength="240" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="url" class="col-sm-6 control-label">{$LANG->getModule('addads_url')}:</label>
        <div class="col-sm-18">
            <input class="url form-control" type="text" name="url" id="url" value="" maxlength="240" data-toggle="errorHidden" data-event="keypress" data-pattern="{literal}/^(.){3,}$/{/literal}" data-mess="{$LANG->getModule('click_url_invalid')}" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-6 col-sm-18">
            <input type="hidden" name="confirm" value="1" />
            <input type="submit" value="{$LANG->getModule('add_banner')}" class="btn btn-primary"/>
        </div>
    </div>
</form>
