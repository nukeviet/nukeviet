<!-- BEGIN: main -->
<div class="alert alert-info">{CONTENTS.info}</div>
<form method="post" action="{CONTENTS.action}">
    <input type="hidden" value="1" name="save" id="save" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w200"/>
            <col class="w20">
            <col>
            <tbody>
                <tr>
                    <td>{CONTENTS.title.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input class="w300 form-control" name="{CONTENTS.title.1}" id="{CONTENTS.title.1}" type="text" value="{CONTENTS.title.2}" maxlength="{CONTENTS.title.3}" /></td>
                </tr>
                <tr>
                    <td>{CONTENTS.size}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input name="{CONTENTS.width.1}" id="{CONTENTS.width.1}" type="text" value="{CONTENTS.width.2}" class="form-control w100 pull-left" maxlength="{CONTENTS.width.3}" placeholder="{CONTENTS.width.0}" /><span class="pull-left text-middle">&nbsp;x&nbsp;</span><input name="{CONTENTS.height.1}" id="{CONTENTS.height.1}" type="text" value="{CONTENTS.height.2}" class="form-control w100 pull-left" maxlength="{CONTENTS.height.3}" placeholder="{CONTENTS.height.0}" /></td>
                </tr>
                <tr>
                    <td>{CONTENTS.blang.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                    <select name="{CONTENTS.blang.1}" id="{CONTENTS.blang.1}" class="form-control w250">
                        <option value="">{CONTENTS.blang.2}</option>
                        <!-- BEGIN: blang -->
                        <option value="{BLANG.key}"{BLANG.selected}>{BLANG.title}</option>
                        <!-- END: blang -->
                    </select></td>
                </tr>
                <tr>
                    <td>{CONTENTS.form.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <!-- BEGIN: form -->
                        <div class="radio">
                            <label>
                                <input type="radio" name="{CONTENTS.form.1}" value="{FORM.key}"{FORM.checked}>
                                {FORM.title}
                            </label>
                        </div>
                        <!-- END: form -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.require_image}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <!-- BEGIN: require_image -->
                        <label class="margin-right"><input type="radio" value="{REQUIRE_IMAGE.key}" name="require_image"{REQUIRE_IMAGE.checked}/> {REQUIRE_IMAGE.title}</label>
                        <!-- END: require_image -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.uploadtype}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <!-- BEGIN: uploadtype -->
                        <label class="margin-right"><input name="uploadtype[]" type="checkbox" value="{UPLOADTYPE.key}"{UPLOADTYPE.checked}/>{UPLOADTYPE.title}</label>
                        <!-- END: uploadtype -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.plan_uploadgroup}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="grouppost-area">
                            <!-- BEGIN: uploadgroup -->
                            <div><label class="margin-right"><input name="uploadgroup[]" type="checkbox" value="{UPLOADGROUP.key}"{UPLOADGROUP.checked}/>{UPLOADGROUP.title}</label></div>
                            <!-- END: uploadgroup -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.plan_exp_time}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="clearfix">
                            <select class="form-control w300" name="exp_time" id="plan_exp_time">
                                <!-- BEGIN: exp_time -->
                                <option value="{EXP_TIME.key}"{EXP_TIME.selected}>{EXP_TIME.title}</option>
                                <!-- END: exp_time -->
                            </select>
                        </div>
                        <div class="clearfix" id="plan_exp_time_custom"{DISPLAY_CUSTOM_EXPTIME}>
                            <input type="text" name="exp_time_custom" class="form-control margin-top w300 pull-left" value="{CONTENTS.exp_time_custom}"/> <span class="display-inline-block margin-top-lg margin-left">({GLANG.day})</span>
                        </div>
                        <span class="help-block help-block-bottom">{LANG.plan_exp_time_note}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">{CONTENTS.description.0}:</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        {DESCRIPTION}
    </div>
    <div style="padding-top:10px" class="text-center">
        <input type="submit" value="{CONTENTS.submit}" class="btn btn-primary" />
    </div>
</form>
<!-- END: main -->
