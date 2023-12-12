<span class="visible-xs-inline-block"><a title="{$LANG->get('joinnow')}" class="pointer button" data-toggle="tip" data-target="#socialList" data-click="y"><em class="fa fa-share-alt fa-lg"></em><span class="hidden">{$LANG->get('joinnow')}</span></a></span>
<div id="socialList" class="content">
    <strong class="visible-xs-inline-block margin-bottom">{$LANG->get('joinnow')}</strong>
    <ul class="socialList">
{foreach $SOCIALS as $social}
        <li>
            <a href="{$social.href}"{if $social.target_blank} target="_blank" rel="noopener"{/if} title="{$social.title}"><i class="fa fa-{$social.icon}"></i></a>
        </li>
{/foreach}
    </ul>
</div>
