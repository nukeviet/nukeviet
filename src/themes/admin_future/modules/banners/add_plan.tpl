<div class="alert alert-info">{$data.info}</div>
<form method="post" action="{$data.action}">
    <input type="hidden" value="1" name="save" id="save" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w200" />
            <col class="w20">
            <col>
            <tbody>
                <tr>
                    <td>{$data.title.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input class="w300 form-control" name="{$data.title.1}" id="{$data.title.1}" type="text" value="{$data.title.2}" maxlength="{$data.title.3}" /></td>
                </tr>
                <tr>
                    <td>{$data.size}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td >
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="{$data.width.1}" id="{$data.width.1}" type="text" value="{$data.width.2}" class="form-control fw-100" maxlength="{$data.width.3}" placeholder="{$data.width.0}" />
                                <span class="text-middle">&nbsp;x&nbsp;</span>
                                <input name="{$data.height.1}" id="{$data.height.1}" type="text" value="{$data.height.2}" class="form-control fw-100" maxlength="{$data.height.3}" placeholder="{$data.height.0}" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{$data.blang.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <select name="{$data.blang.1}" id="{$data.blang.1}" class="form-select fw-200">
                            <option value="">{$data.blang.2}</option>
                            {foreach $data.blang.3 as $key=>$val}
                            <option value="{$key}" {if $key==$data.blang.4} selected="selected" {/if}>{$val}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{$data.form.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        {foreach from=$data.form.2 item=form}
                        <div class="radio">
                            <label>
                                <input type="radio" name="{$data.form.1}" value="{$form}" {if $form==$data.form.3} checked="checked" {/if}>
                                {if $LANG->existsModule("form_`$form`")} {$LANG->getModule("form_`$form`")} {else} {$form} {/if}
                            </label>
                        </div>
                        {/foreach}
                    </td>
                </tr>
                <tr>
                    <td>{$LANG->getModule("require_image")}:</td>
                    <td>&nbsp;</td>
                    <td>
                        {for $var=1 to 0}
                        <label class="margin-right">
                            <input type="radio" value="{$var}" name="require_image" {if $var==$data.require_image} checked="checked" {/if} /> {$LANG->getModule("require_image`$var`")}
                        </label>
                        {/for}
                    </td>
                </tr>
                <tr>
                    <td>{$LANG->getModule("uploadtype")}:</td>
                    <td>&nbsp;</td>
                    <td>
                        {foreach from=$array_uploadtype item=uploadtype}
                        <label class="margin-right">
                            <input name="uploadtype[]" type="checkbox" value="{$uploadtype}" {if in_array($uploadtype, $data.uploadtype, true)} checked="checked" {/if} /> {$uploadtype}
                        </label>
                        {/foreach}
                    </td>
                </tr>
                <tr>
                    <td>{$LANG->getModule("plan_uploadgroup")}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="grouppost-area">
                            {foreach from=$groups_list key=_group_id item=_title}
                            <div>
                                <label class="margin-right">
                                    <input name="uploadgroup[]" type="checkbox" value="{$_group_id}" {if in_array((int) $_group_id, $uploadgroup, true)} checked="checked" {/if} /> {$_title}
                                </label>
                            </div>
                            {/foreach}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{$LANG->getModule("plan_exp_time")}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="clearfix">
                            <select class="form-control w300" name="exp_time" id="plan_exp_time">
                                {foreach from=$array_exp_time item=exp_time}
                                <option value="{$exp_time[0]}" {if $data.exp_time==$exp_time[0]} selected="selected" {/if}>{$exp_time[1]}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="clearfix" id="plan_exp_time_custom" {if $data.exp_time !=-1} style="display:none;" {/if}>
                            <input type="text" name="exp_time_custom" class="form-control margin-top w300 pull-left" value="{$data.exp_time_custom}" /> <span class="display-inline-block margin-top-lg margin-left">({$LANG->getGlobal("day")})</span>
                        </div>
                        <span class="help-block help-block-bottom">{$LANG->getModule("plan_exp_time_note")}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">{$data.description.0}:</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        {$DESCRIPTION}
    </div>
    <div style="padding-top:10px" class="text-center">
        <input type="submit" value="{$data.submit}" class="btn btn-primary" />
    </div>
</form>
