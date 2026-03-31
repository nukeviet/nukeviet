<div class="row mb-3">
    <label for="config_catid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('catid')}">{$LANG->getModule('catid')}:</label>
    <div class="col-sm-5">
        <select name="config_catid" id="config_catid" class="form-select">
            <option value="0"> -- </option>
            {foreach from=$CATS item=cat}
            {if $cat.status == 1 || $cat.status == 2}
            {assign var="xtitle_i" value=""}
            {if $cat.lev > 0}
                {section name=i loop=$cat.lev}
                {assign var="xtitle_i" value=$xtitle_i|cat:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"}
                {/section}
            {/if}
            <option value="{$cat.catid}"{if $CONFIG.catid eq $cat.catid} selected{/if} data-link="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}&amp;{$smarty.const.NV_OP_VARIABLE}={$cat.alias}">{$xtitle_i}{$cat.title}</option>
            {/if}
            {/foreach}
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="config_title_length" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('title_length')}">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-5">
        <select name="config_title_length" id="config_title_length" class="form-select">
            {section name=i loop=100}
            <option value="{$smarty.section.i.index}"{if $CONFIG.title_length eq $smarty.section.i.index} selected{/if}>{$smarty.section.i.index}</option>
            {/section}
        </select>
    </div>
</div>
<script type="text/javascript">
$("select[name=config_catid]").change(function() {
    $("input[name=title]").val($.trim($("select[name=config_catid] option:selected").text()));
    $("input[name=link]").val($.trim($("select[name=config_catid] option:selected").data('link')));
});
</script>
