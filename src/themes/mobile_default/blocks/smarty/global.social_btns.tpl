<span class="visible-xs-inline-block"><a title="{$LANG->get('joinnow')}" class="pointer button" data-toggle="tip" data-target="#social_btns" data-click="y"><em class="fa fa-share-alt fa-lg"></em><span class="hidden">{$LANG->get('joinnow')}</span></a></span>
<div id="social_btns" class="content">
    <ul class="social_btns">
{foreach $SOCIALS as $social}
        <li><a href="{$social.url}" title="{$social.name}"{if $social.name != 'feeds'} target="_blank"{/if} style="--hover-color:#{$social.color}"><i class="{$social.icon}"></i></a></li>
{/foreach}
    </ul>
</div>
