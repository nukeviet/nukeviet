<section class="nv-block nv-block-default">
    <div class="block-heading fs-5 fw-medium text-uppercase">
        {if not empty($BLOCK.title)}
        {assign var="openTag" value='<div class="block-title">'}
        {assign var="closeTag" value="</div>"}
        {if not empty($BLOCK.heading)}
        {assign var="openTag" value="<h`$BLOCK.heading` class=\"mb-0\">"}
        {assign var="closeTag" value="</h`$BLOCK.heading`>"}
        {/if}
        {$openTag}{if not empty($BLOCK.link)}<a href="{$BLOCK.link}" class="link-body-emphasis">{$BLOCK.title}</a>{else}{$BLOCK.title}{/if}{$closeTag}
        {/if}
    </div>
    <div class="block-body">
        {$CONTENT}
    </div>
</section>
