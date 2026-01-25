<ul class="list-group">
{if !empty($DEPARTMENT.image)}
    <li>
        <a href="{$DEPARTMENT.url}"><img src="{$DEPARTMENT.image}" class="img-thumbnail" style="border-bottom-left-radius:0;border-bottom-right-radius:0" alt="{$DEPARTMENT.full_name}" /></a>
    </li>
{/if}
    <li class="list-group-item"><strong>{$DEPARTMENT.full_name}</strong></li>
{if !empty($DEPARTMENT.note)}
    <li class="list-group-item">{$DEPARTMENT.note}</li>
{/if}
{if !empty($DEPARTMENT.address)}
    <li class="list-group-item">{$LANG->getGLobal('address')}: {$DEPARTMENT.address}</li>
{/if}
{foreach $DEPARTMENT.cd as $cd}
    {strip}<li class="list-group-item">
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
                        <a href="{$DEPARTMENT.url}">{$email|escape:"hexentity"}</a>
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