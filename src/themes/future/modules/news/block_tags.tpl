{foreach from=$ARRAY item=row}
<a class="badge text-bg-secondary text-truncate mw-100" href="{$row.link}" title="{$row.title}"><i class="fa-solid fa-tag"></i> {$row.title}</a>
{/foreach}
