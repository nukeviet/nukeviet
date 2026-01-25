<div class="panel-body">
    <div class="featured-products">
{foreach $LIST as $row}
        <div class="row clearfix">
{if empty($row.link)}
            <div class="bl-title">{$row.title}</div>
{else}
            <a class="bl-title" href="{$row.link}"{if !empty($row.target)} target="{$row.target}"{/if}>{$row.title}</a>
{/if}
{if !empty($row.image)}
            <div class="col-xs-24 col-sm-5 col-md-8">
                {if !empty($row.link)}<a href="{$row.link}" title="{$row.title}"{if !empty($row.target)} target="{$row.target}"{/if}>{/if}<img title="{$row.title}" alt="" src="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/{$row.image}" class="img-thumbnail">{if !empty($row.link)}</a>{/if}
            </div>
{/if}
            <div class="col-xs-24 col-sm-19 col-md-16">
                {$row.description}
            </div>
        </div>
{/foreach}
    </div>
</div>
