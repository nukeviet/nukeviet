{$i=0}
{foreach $DEPARTMENTS as $department}
{$i=$i+1}
{if $i > 1}
    <hr />
{/if}
{if !empty($department.image)}
    <div class="text-center m-bottom">
        <a href="{$department.url}"><img src="{$department.image}" class="img-thumbnail" alt="{$department.full_name}" /></a>
    </div>
{/if}
    <p class="text-center m-bottom"><strong>{$department.full_name}</strong></p>
    <ul class="list-none list-items">
{foreach $department.cd as $cd}
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
                                <a href="{$department.url}">{$email|escape:"hexentity"}</a>
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
                        <span class="me-2">{$cd.type}:</span>&nbsp;
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
{/foreach}