<div class="card" id="feedback_list">
    <div class="card-body pt-4">
        {if empty($ARRAY_ROW)}
        <div class="alert alert-info mb-0">{$LANG->getModule('no_row_contact')}</div>
        {else}
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-1">
                <colgroup>
                    <col style="width:1%;">
                    <col style="width:1%;">
                    <col style="width:1%;">
                    <col style="width:9%;">
                    <col style="width:26%;">
                    <col style="width:26%;">
                    <col style="width:26%;">
                    <col style="width:10%;">
                </colgroup>
                <thead class="bg-primary">
                    <tr>
                        <th class="text-nowrap"><input data-toggle="checkAll" name="checkAll[]" type="checkbox" class="form-check-input"></th>
                        <th class="text-nowrap" colspan="3">{$LANG->getModule('name_user_send_title')}</th>
                        <th class="text-nowrap">{$LANG->getModule('to_department')}</th>
                        <th class="text-nowrap">{$LANG->getModule('cat')}</th>
                        <th class="text-nowrap">{$LANG->getModule('title_send_title')}</th>
                        <th class="text-nowrap">{$LANG->getModule('send_time')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $ARRAY_ROW as $ROW}
                    <tr class="item" title="{if $ROW['is_processed']}{$LANG->getModule('tt3_row_title')}{elseif $ROW['is_read'] != 1}{$LANG->getModule('row_new')}{elseif $ROW['is_reply']}{$LANG->getModule('tt2_row_title')}{else}{$LANG->getModule('tt1_row_title')}{/if}" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;id={$ROW.id}"{if $ROW['is_processed']} style="color:#aaa"{/if}>
                        <td class="text-center" style="width:1%;">
                            <input data-toggle="checkSingle" name="sends[]" type="checkbox" value="{$ROW.id}" class="form-check-input m-0 align-middle"{if !($CONTACT_ALLOWED.exec[$ROW.cid]|isset)} disabled{/if}>
                        </td>
                        <td class="text-nowrap text-center view_feedback" style="width:1%{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">
                            {if !$ROW['is_processed']}
                            <span class="fa-solid fa-spinner fa-spin-pulse"></span>
                            {else}
                            <span class="fa-solid fa-check"></span>
                            {/if}
                        </td>
                        {if $ROW['is_read'] == 1}
                            {if $ROW['is_reply'] == 1}
                                {assign var='IMAGE' value='<i class="fa-solid fa-reply"></i>'}
                            {elseif $ROW['is_reply'] == 2}
                                {assign var='IMAGE' value='<i class="fa-solid fa-share"></i>'}
                            {else}
                                {assign var='IMAGE' value='<i class="fa-solid fa-envelope-open"></i>'}
                            {/if}
                        {else}
                            {assign var='IMAGE' value='<i class="fa-solid fa-envelope"></i>'}
                        {/if}

                        <td class="text-nowrap text-center view_feedback" style="width:1%;{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">{$IMAGE}</td>
                        <td class="text-nowrap view_feedback" style="{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">{$ROW.sender_name}</td>
                        <td class="text-nowrap view_feedback" style="{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">{$CONTACT_ALLOWED.view[$ROW.cid]}</td>
                        <td class="text-nowrap view_feedback" style="{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">{$ROW.cat}</td>
                        <td class="text-nowrap view_feedback" style="{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">{$ROW.title|nv_clean60:60}</td>
                        <td class="text-nowrap view_feedback" style="width:1%;{if !$ROW['is_read']}font-weight:bold;{/if}" role="button">{if $ROW.send_time > $CURRDAY}{$ROW.send_time|ddatetime:1}{else}{1|ddate:$ROW.send_time}{/if}</td>
                    </tr>
                    {/foreach}
                    <tr>
                        <td class="text-center" style="width:1%;"><input data-toggle="checkAll" name="checkAll[]" type="checkbox" class="form-check-input"></td>
                        <td colspan="7">
                            <div class="row row-cols-auto g-2">
                                <div class="col">
                                    <button type="button" class="btn btn-secondary feedback_del_sel"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getModule('bt_del_row_title')}</button>
                                </div>
                                {if !empty($smarty.const.NV_IS_SPADMIN)}
                                <div class="col">
                                    <button type="button" class="btn btn-secondary feedback_del_all"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getModule('delall')}</button>
                                </div>
                                {/if}
                                <div class="col">
                                    <button type="button" class="btn btn-secondary feedback_mark" data-mark="unread"><i class="fa-solid fa-bookmark"></i> {$LANG->getModule('mark_as_unread')}</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-secondary feedback_mark" data-mark="read"><i class="fa-regular fa-bookmark"></i> {$LANG->getModule('mark_as_read')}</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-secondary feedback_mark" data-mark="unprocess"><i class="fa-regular fa-circle"></i> {$LANG->getModule('mark_as_unprocess')}</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-secondary feedback_mark" data-mark="processed"><i class="fa-regular fa-circle-check"></i> {$LANG->getModule('mark_as_processed')}</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    {/if}
    </div>
    {if !empty($GENERATE_PAGE)}
    <div class="card-footer">
        <div class="d-flex flex-wrap justify-content-end align-items-center">
            <div class="pagination-wrap">{$GENERATE_PAGE}</div>
        </div>
    </div>
    {/if}
</div>
