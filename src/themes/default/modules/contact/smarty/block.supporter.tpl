<div class="panel-group" id="block-supporter" role="tablist" aria-multiselectable="true">
{$a=0}
{foreach $SUPPORTERS as $did => $row}
{$a=$a+1}
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-department-{$did}">
            {strip}<a {if $a>1}class="small collapsed" {/if}role="button" data-toggle="collapse" data-parent="#block-supporter" href="#department-{$did}" aria-expanded="{if $a==1}true{else}false{/if}" aria-controls="department-{$did}">
                    <strong>{$row.full_name}</strong>
            </a>{/strip}
        </div>
        <div id="department-{$did}" class="panel-collapse collapse{if $a==1} in{/if}" role="tabpanel" aria-labelledby="heading-department-{$did}">
            <ul class="list-group">
{foreach $row.items as $supporter}
                <li class="list-group-item">
                    <div class="margin-bottom" style="display:flex;align-items:center;">
                        {strip}<img src="{$supporter.image}" style="width:40px;height:40px;border:1px solid #dadada;border-radius:50%;margin-right:7px;" alt="" />
                        <strong>{$supporter.full_name}</strong>{/strip}
                    </div>
                    <ul class="list-unstyled">
{foreach $supporter.cd as $cd}
                        {strip}<li style="display:flex;">
                        {if $cd.type == 'phone'}
                        <span><em class="fa fa-phone fa-fw" title="{$LANG->getModule('phone')}"></em>&nbsp;</span>
                        <span>
                            {$i=0}
                            {foreach $cd.value as $num}
                            {$i=$i+1}
                            {if $i > 1}, {/if}
                            {if isset($num[1])}<a href="tel:{$num[1]}">{$num[0]}</a>{else}{$num[0]}{/if}
                            {/foreach}
                        </span>
                        {elseif $cd.type == 'email'}
                        <span><em class="fa fa-envelope-o fa-fw" title="{$LANG->getModule('email')}"></em>&nbsp;</span>
                        <span>
                            {$i=0}
                            {foreach $cd.value as $email}
                            {$i=$i+1}
                            {if $i > 1}, {/if}
                            <a href="{$row.url}">{$email|escape:"hexentity"}</a>
                            {/foreach}
                        </span>
                        {elseif $cd.type == 'skype'}
                        <span><em class="fa fa-skype fa-fw" title="Skype"></em>&nbsp;</span>
                        <span>
                            {$i=0}
                            {foreach $cd.value as $skype}
                            {$i=$i+1}
                            {if $i > 1}, {/if}
                            <a href="skype:{$skype}?call">{$skype}</a>
                            {/foreach}
                        </span>
                        {elseif $cd.type == 'viber'}
                        <span><em class="icon-viber fa-fw" title="Viber"></em>&nbsp;</span>
                        <span>
                            {$i=0}
                            {foreach $cd.value as $viber}
                            {$i=$i+1}
                            {if $i > 1}, {/if}
                            <a href="viber://pa?chatURI={$viber}?call">{$viber}</a>
                            {/foreach}
                        </span>
                        {elseif $cd.type == 'whatsapp'}
                        <span><em class="fa fa-whatsapp fa-fw" title="WhatsApp"></em>&nbsp;</span>
                        <span>
                            {$i=0}
                            {foreach $cd.value as $whatsapp}
                                {$i=$i+1}
                                {if $i > 1}, {/if}
                                <a href="https://wa.me/{$whatsapp}">{$whatsapp}</a>
                            {/foreach}
                        </span>
                        {elseif $cd.type == 'zalo'}
                        <span><em class="icon-zalo fa-fw" title="Zalo"></em>&nbsp;</span>
                        <span>
                            {$i=0}
                            {foreach $cd.value as $zalo}
                            {$i=$i+1}
                            {if $i > 1}, {/if}
                            <a href="https://zalo.me/{$zalo}">{$zalo}</a>
                            {/foreach}
                        </span>
                        {else}
                        <span>{$cd.type}:&nbsp;</span>
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
                </li>
{/foreach}
            </ul>
        </div>
    </div>
{/foreach}
</div>
