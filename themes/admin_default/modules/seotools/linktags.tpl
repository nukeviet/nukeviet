<!-- BEGIN: main -->
<!-- BEGIN: if_links -->
<div class="panel-group" id="linktags-accordion" role="tablist" aria-multiselectable="true">
    <!-- BEGIN: item -->
    <div class="panel panel-default">
        <a role="button" class="panel-heading btn-block" data-toggle="collapse" data-parent="#linktags-accordion" href="#collapse-linktag{LINK_TAGS.key}" aria-expanded="false" aria-controls="collapse-linktag{LINK_TAGS.key}">
            {LINK_TAGS.title}
        </a>
        <div id="collapse-linktag{LINK_TAGS.key}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="linktag{LINK_TAGS.key}">
            <div class="panel-body">
                <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" onsubmit="formSubmit(event, this)">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover m-bottom-none">
                            <colgroup>
                                <col style="width:200px" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>{LANG.linkTags_attribute}</th>
                                    <th>{LANG.linkTags_value}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <input type="hidden" name="key" value="l-{LINK_TAGS.key}" />
                                        <input type="hidden" name="add" value="1" />
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="button" class="btn btn-default" value="{LANG.linkTags_add_attribute}" onclick="addAttr(event, this.form)" />
                                        <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
                                        <input type="button" class="btn btn-danger" value="{GLANG.delete}" onclick="linkTagDel(event, this.form)" />
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="linktags_attribute[]" value="rel" readonly="readonly"/></td>
                                    <td><input type="text" class="form-control required rel-val" name="linktags_value[]" value="{LINK_TAGS.rel}" maxlength="255"/></td>
                                </tr>
                                <!-- BEGIN: attr -->
                                <tr>
                                    <td><input type="text" class="form-control" name="linktags_attribute[]" value="{ATTRIBUTE.k}" maxlength="100"/></td>
                                    <td><input type="text" class="form-control" name="linktags_value[]" value="{ATTRIBUTE.v}" maxlength="255"/></td>
                                </tr>
                                <!-- END: attr -->
                                <tr class="sample" style="display:none">
                                    <td><input type="text" class="form-control" name="linktags_attribute[]" value="" maxlength="100"/></td>
                                    <td><input type="text" class="form-control" name="linktags_value[]" value="" maxlength="255"/></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: item -->
</div>
<script>
function linkTagDel(event, form) {
    event.preventDefault();
    var r = confirm('{LANG.linkTags_del_confirm}');
    if (r == true) {
        $.ajax({
    		type: $(form).prop("method"),
    		cache: !1,
    		url: $(form).prop("action"),
    		data: {'key':$('[name=key]',form).val(), 'del':1, 'checkss': $('[name=checkss]',form).val()},
    		success: function() {
                window.location.href = window.location.href;
    		}
    	});
    }
}
$(function() {
    $('[id*=collapse-linktag]').on('show.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default panel-primary')
    }).on('hidden.bs.collapse', function () {
        $(this).parent().toggleClass('panel-default panel-primary')
    })
})
</script>
<!-- END: if_links -->
<h3><strong>{LANG.linkTags_add}</strong></h3>
<form class="m-bottom" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" onsubmit="formSubmit(event, this)">
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover m-bottom-none">
        <colgroup>
            <col style="width:200px" />
        </colgroup>
        <thead>
            <tr>
                <th>{LANG.linkTags_attribute}</th>
                <th>{LANG.linkTags_value}</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input type="hidden" name="key" value="" />
                    <input type="hidden" name="add" value="1" />
                    <input type="hidden" name="checkss" value="{CHECKSS}" />
                    <input type="button" class="btn btn-default" value="{LANG.linkTags_add_attribute}" onclick="addAttr(event, this.form)" />
                    <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td><input type="text" class="form-control" name="linktags_attribute[]" value="rel" readonly="readonly"/></td>
                <td><input type="text" class="form-control required rel-val" name="linktags_value[]" value="" maxlength="255"/></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" name="linktags_attribute[]" value="" maxlength="100"/></td>
                <td><input type="text" class="form-control" name="linktags_value[]" value="" maxlength="255"/></td>
            </tr>
            <tr class="sample" style="display:none">
                <td><input type="text" class="form-control" name="linktags_attribute[]" value="" maxlength="100"/></td>
                <td><input type="text" class="form-control" name="linktags_value[]" value="" maxlength="255"/></td>
            </tr>
        </tbody>
    </table>
</div>
</form>
<div class="well">
    <div><strong>{LANG.linkTags_acceptVars}</strong>:</div>
    <div>{ACCEPTVARS}</div>
</div>
<script>
function formSubmit(event, form) {
    event.preventDefault();
    var relVal = $('.rel-val', form).val();
    relVal = trim(relVal);
    $('.rel-val', form).val(relVal);
    if ('' == relVal) {
        alert('{LANG.linkTags_rel_val_required}');
        $('.rel-val', form).focus();
        return !1
    }
    var data = $(form).serialize();
    $('input,button', form).prop('disabled', true);
    $.ajax({
		type: $(form).prop("method"),
		cache: !1,
		url: $(form).prop("action"),
		data: data,
		dataType: "json",
		success: function(e) {
            if ("error" == e.status) {
                alert(e.mess);
                $('input,button', form).prop('disabled', false);
            } else {
                window.location.href = window.location.href;
            }
		}
	});
}
function addAttr(event, form) {
    event.preventDefault();
    $('tbody', form).append($('<tr>' + $('.sample', form).html() + '</tr>'))
}
</script>
<!-- END: main -->