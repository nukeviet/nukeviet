<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption><em class="fa fa-file-text-o">&nbsp;</em> {LANG.total}: <span id="item-total">{TOTAL}</span></caption>
        <colgroup>
            <col style="width:1%">
            <col span="2">
            <col class="w150">
        </colgroup>
        <thead class="bg-primary">
            <tr>
                <th class="text-nowrap text-center">{LANG.weight}</th>
                <th>{LANG.name}</th>
                <th>{LANG.link}</th>
                <th class="w150"> &nbsp;</th>
            </tr>
        </thead>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="4">{GENERATE_PAGE}</td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <button type="button" class="btn btn-default btn-block" data-toggle="popover" data-container="body" data-title="{LANG.change_weight}" data-sourceid="{ROW.sourceid}" data-current-weight="{ROW.weight}">{ROW.weight}</button>
                </td>
                <td>{ROW.title}</td>
                <td>{ROW.link}</td>
                <td class="text-center">
                    <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_source({ROW.sourceid})">{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<div id="popover-content" class="hide">
    <div class="popover-content">
        <div class="input-group item">
            <input type="text" class="form-control number new-weight" value="" maxlength="10" data-max="{TOTAL}" style="height:inherit">
            <span class="input-group-btn">
                <button type="button" class="btn btn-default weight-down"><i class="fa fa-angle-down"></i></button>
                <button type="button" class="btn btn-default weight-up"><i class="fa fa-angle-up"></i></button>
                <button type="button" class="btn btn-primary topic_change_weight" data-sourceid="" data-current-weight="">OK</button>
            </span>
        </div>
        <div class="help-block mb-0">{LANG.type_new_weight} {TOTAL}</div>
    </div>
</div>
<script>
$(function() {
    //Thay d?i th? t?
    if (parseInt($('#item-total').text()) > 1) {
        var pp = $('#module_show_list [data-toggle=popover]'),
            ppc = $('.popover-content').clone();
        pp.on('shown.bs.popover', function () {
            $('body').on('click', function (e) {
                pp.each(function () {
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false;
                    }
                });
            })
        }).popover({
            html: true,
            sanitize: false,
            content: function() {
                $('.topic_change_weight', ppc).attr('data-sourceid', $(this).data('sourceid')).attr('data-current-weight', $(this).data('current-weight'));
                $('.new-weight', ppc).attr('value', $(this).data('current-weight'));
                return ppc.html()
            }
        });
        $('body').on('click', '.topic_change_weight', function() {
            var sourceid = $(this).data('sourceid'),
                obj = $(this).parents('.item'),
                ipt = $('.new-weight', obj),
                new_weight = trim(ipt.val()),
                maxweight = parseInt(ipt.data('max')),
                current_weight = parseInt($(this).data('current-weight'));
            $('.has-error').removeClass('has-error');
            new_weight = (new_weight == '') ? 0 : parseInt(new_weight);
            if (new_weight < 1 || new_weight > maxweight) {
                ipt.parent().addClass('has-error');
                return !1
            }

            $('body').trigger('click');
            if (new_weight != current_weight) {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&nocache=' + new Date().getTime(), 'sourceid=' + sourceid + '&mod=weight&new_vid=' + new_weight, function(res) {
                    nv_show_list_source()
                })
            }
        });
        $('body').on('click', '.weight-up, .weight-down', function() {
            var obj = $(this).parents('.item'),
                ipt = $('.new-weight', obj),
                weight = trim(ipt.val()),
                maxweight = parseInt(ipt.data('max')),
                new_weight = weight > maxweight ? maxweight : (weight < 1 ? 1 : weight);
            if ($(this).is('.weight-up')) {
                ++new_weight;
                if (new_weight > maxweight) {
                    new_weight = maxweight
                }
            } else {
                --new_weight;
                if (new_weight < 1) {
                    new_weight = 1
                }
            }
            ipt.val(new_weight)
        })
    }
})
</script>
<!-- END: main -->
