<span class="visible-xs-inline-block"><a title="{$LANG->getModule('contactUs')}" class="pointer button" data-toggle="tip" data-target="#contactList" data-click="y"><em class="icon-old-phone icon-lg"></em><span class="hidden">{$LANG->getModule('contactUs')}</span></a></span>
<div id="contactList" class="content">
<strong class="visible-xs-inline-block margin-bottom">{$LANG->getModule('contactUs')}</strong>
<ul class="contactList">
{foreach $DATA as $cd}
{strip}<li>
{if $cd.type == 'phone'}
    <em class="fa fa-phone" title="{$LANG->getGlobal('phonenumber')}"></em>&nbsp;
    <span>
    {$i=0}
    {foreach $cd.value as $num}
        {$i=$i+1}
        {if $i > 1}, {/if}
        {if isset($num[1])}<a href="tel:{$num[1]}">{$num[0]}</a>{else}{$num[0]}{/if}
    {/foreach}
    </span>
{elseif $cd.type == 'fax'}
    <em class="fa fa-print" title="Fax"></em>&nbsp;
    <span>{$cd.value}</span>
{elseif $cd.type == 'email'}
    <em class="fa fa-envelope-o" title="{$LANG->getGlobal('email')}"></em>&nbsp;
    <span>
    {$i=0}
    {foreach $cd.value as $email}
        {$i=$i+1}
        {if $i > 1}, {/if}
        <a href="{$MODULE_URL}">{$email|escape:"hexentity"}</a>
    {/foreach}
    </span>
{elseif $cd.type == 'skype'}
    <em class="fa fa-skype" title="Skype"></em>&nbsp;
    <span>
    {$i=0}
    {foreach $cd.value as $skype}
        {$i=$i+1}
        {if $i > 1}, {/if}
        <a href="skype:{$skype}?call">{$skype}</a>
    {/foreach}
    </span>
{elseif $cd.type == 'viber'}
    <em class="icon-viber" title="Viber"></em>&nbsp;
    <span>
    {$i=0}
    {foreach $cd.value as $viber}
        {$i=$i+1}
        {if $i > 1}, {/if}
        <a href="viber://pa?chatURI={$viber}?call">{$viber}</a>
    {/foreach}
    </span>
{elseif $cd.type == 'whatsapp'}
    <em class="fa fa-whatsapp" title="WhatsApp"></em>&nbsp;
    <span>
    {$i=0}
    {foreach $cd.value as $whatsapp}
        {$i=$i+1}
        {if $i > 1}, {/if}
        <a href="https://wa.me/{$whatsapp}">{$whatsapp}</a>
    {/foreach}
    </span>
{elseif $cd.type == 'zalo'}
    <em class="icon-zalo" title="Zalo"></em>&nbsp;
    <span>
    {$i=0}
    {foreach $cd.value as $zalo}
        {$i=$i+1}
        {if $i > 1}, {/if}
        <a href="https://zalo.me/{$zalo}">{$zalo}</a>
    {/foreach}
    </span>
{else}
    <span>{$cd.type}:</span>&nbsp;
    <span>
    {if $cd.value.is_url}
    <a href="{$cd.value.content}">{$cd.value.content}</a>
    {else}
    {$cd.value.content}
    {/if}
    </span>
{/if}
</li>{/strip}
{/foreach}
</ul>
</div>
