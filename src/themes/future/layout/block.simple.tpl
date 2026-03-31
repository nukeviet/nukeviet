<section class="nv-block nv-block-simple">
    <div class="block-heading">
        {if not empty($BLOCK.title)}
        {assign var="openTag" value='<div class="block-title">'}
        {assign var="closeTag" value="</div>"}
        {if not empty($BLOCK.heading)}
        {assign var="openTag" value="<h`$BLOCK.heading`>"}
        {assign var="closeTag" value="</h`$BLOCK.heading`>"}
        {/if}
        {$openTag}{if not empty($BLOCK.link)}<a href="{$BLOCK.link}">{$BLOCK.title}</a>{else}{$BLOCK.title}{/if}{$closeTag}
        {/if}
    </div>
    <div class="block-body">
        {$CONTENT}
    </div>
</section>
