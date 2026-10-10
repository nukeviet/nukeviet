<form id="robots-manage" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post">
    <div class="table-responsive-lg">
        <table class="table table-striped table-bordered table-hover table-sticky table-counter">
            <thead>
                <tr class="text-center">
                    <th class="text-nowrap" style="width: 1%;">{$LANG->getModule('robots_number')}</th>
                    <th class="text-nowrap" style="width: 49%;">{$LANG->getModule('robots_filename')}</th>
                    <th class="text-nowrap" style="width: 50%;" colspan="2">{$LANG->getModule('robots_type')}</th>
                </tr>
            </thead>
            <tbody class="items">
                {foreach from=$STATIC_FILES item=file}
                <tr>
                    <td class="text-center counter-cell"></td>
                    <td class="text-break">{$file}</td>
                    <td colspan="2">
                        <select name="filename[{$file}]" class="form-select">
                            {for $type=0 to 2}
                            <option value="{$type}"{if ($ROBOTS_DATA[$file] ?? 1) eq $type} selected{/if}>{$LANG->getModule("robots_type_`$type`")}</option>
                            {/for}
                        </select>
                    </td>
                </tr>
                {/foreach}
                {foreach from=$ROBOTS_OTHER key=file item=value}
                <tr class="item">
                    <td class="text-center counter-cell"></td>
                    <td><input class="form-control" type="text" value="{$file}" name="fileother[]" /></td>
                    <td>
                        <select name="optionother[]" class="form-select">
                            {for $type=0 to 2}
                            <option value="{$type}"{if $type eq $value} selected{/if}>{$LANG->getModule("robots_type_`$type`")}</option>
                            {/for}
                        </select>
                    </td>
                    <td style="width: 1%;" class="text-nowrap">
                        <button type="button" class="btn btn-secondary" aria-label="{$LANG->getGlobal('add')}" title="{$LANG->getGlobal('add')}" data-toggle="robot_line_add"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" class="btn btn-secondary" aria-label="{$LANG->getGlobal('delete')}" title="{$LANG->getGlobal('delete')}" data-toggle="robot_line_delete"><i class="fa-solid fa-trash text-danger"></i></button>
                    </td>
                </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="4">
                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
