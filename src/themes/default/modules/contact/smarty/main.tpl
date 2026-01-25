{* define the function *}
{function name=contact_list is_li = true}
{foreach $data as $cd}
        {strip}{if $is_li}<li class="list-group-item">{else}<p>{/if}
{if $cd.type == 'phone'}
            <em class="fa fa-phone fa-horizon margin-right"></em>{$LANG->getModule('phone')}:
            <span>
            {$i=0}
            {foreach $cd.value as $num}
                {$i=$i+1}
                {if $i > 1}, {/if}
                {if isset($num[1])}<a href="tel:{$num[1]}">{$num[0]}</a>{else}{$num[0]}{/if}
            {/foreach}
            </span>
{elseif $cd.type == 'fax'}
            <em class="fa fa-print fa-horizon margin-right"></em><span class="me-2">{$LANG->getModule('fax')}:</span>
            <span>{$cd.value}</span>
{elseif $cd.type == 'email'}
            <em class="fa fa-envelope-o fa-horizon margin-right"></em><span class="me-2">{$LANG->getModule('email')}:</span>
            <span>
            {$i=0}
            {foreach $cd.value as $email}
                {$i=$i+1}
                {if $i > 1}, {/if}
                <a href="mailto:{$email|escape:"hex"}">{$email|escape:"hexentity"}</a>
            {/foreach}
            </span>
{elseif $cd.type == 'skype'}
            <em class="fa fa-skype fa-horizon margin-right"></em><span class="me-2">Skype:</span>
            <span>
            {$i=0}
            {foreach $cd.value as $skype}
                {$i=$i+1}
                {if $i > 1}, {/if}
                <a href="skype:{$skype}?call">{$skype}</a>
            {/foreach}
            </span>
{elseif $cd.type == 'viber'}
            <em class="icon-viber fa-horizon margin-right"></em><span class="me-2">Viber:</span>
            <span>
            {$i=0}
            {foreach $cd.value as $viber}
                {$i=$i+1}
                {if $i > 1}, {/if}
                <a href="viber://pa?chatURI={$viber}?call">{$viber}</a>
            {/foreach}
            </span>
{elseif $cd.type == 'whatsapp'}
            <em class="fa fa-whatsapp fa-horizon margin-right"></em><span class="me-2">WhatsApp:</span>
            <span>
            {$i=0}
            {foreach $cd.value as $whatsapp}
                {$i=$i+1}
                {if $i > 1}, {/if}
                <a href="https://wa.me/{$whatsapp}">{$whatsapp}</a>
            {/foreach}
            </span>
{elseif $cd.type == 'zalo'}
            <em class="icon-zalo fa-horizon margin-right"></em><span class="me-2">Zalo:</span>
            <span>
            {$i=0}
            {foreach $cd.value as $zalo}
                {$i=$i+1}
                {if $i > 1}, {/if}
                <a href="https://zalo.me/{$zalo}">{$zalo}</a>
            {/foreach}
            </span>
{else}
            <span>{$cd.type}:</span>
            <span>
            {if $cd.value.is_url}
            <a href="{$cd.value.content}">{$cd.value.content}</a>
            {else}
            {$cd.value.content}
            {/if}
            </span>
{/if}
        {if $is_li}</li>{else}</p>{/if}{/strip}
{/foreach}
{/function}

<h1 class="hidden">{$THEME_PAGE_TITLE}</h1>
<div class="margin-bottom"><span class="h1"><strong>{$PAGE_TITLE}</strong></span></div>

{if !empty($BODYTEXT)}
<p class="margin-bottom">{$BODYTEXT}</p>
{/if}

<div class="row">
    <div class="col-sm-12 col-md-15">
{foreach $DEPARTMENTS as $dep}
        <div class="panel panel-default">
{if $IS_HOME}
            <a href="{$dep.url}" class="panel-heading" style="display:flex;align-items:center">
                <h2 class="pannel-title" style="flex-grow: 1">{$dep.full_name}</h2>
                <small class="text-dark">{$LANG->getModule('details')} <i class="fa fa-arrow-right fa-fw"></i></small>
            </a>
{else}
            <div class="panel-heading">
                <h2 class="pannel-title">{$LANG->getModule('contact_info')}</h2>
            </div>
{/if}
            <ul class="list-group">
{if !empty($dep.image)}
                <li class="list-group-item">
                    <img src="{$dep.image}" class="img-thumbnail" alt="{$dep.full_name}" />
                </li>
{/if}
{if !empty($dep.note)}
                <li class="list-group-item">{$dep.note}</li>
{/if}
{if !empty($dep.address)}
                <li class="list-group-item">
                    <em class="fa fa-map-marker fa-horizon margin-right"></em>{$LANG->getModule('address')}: <span>{$dep.address}</span>
                </li>
{/if}
{call name=contact_list data=$dep.cd is_li=true}
            </ul>
        </div>
{/foreach}
    </div>

    <div class="col-sm-12 col-md-9">
{if !empty($SUPPORTERS)}
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>{$LANG->getModule('supporters')}</h3>
            </div>
            <ul class="list-group">
{foreach $SUPPORTERS as $supporter}
                <li class="list-group-item">
                    <div style="display:flex">
                        <div><img src="{$supporter.image}" class="supporter-avatar" alt="" /></div>
                        <div style="flex-grow: 1">
                            <p><strong>{$supporter.full_name}</strong></p>
                            {call name=contact_list data=$supporter.cd is_li=false}
                        </div>
                    </div>
                </li>
{/foreach}
            </ul>
        </div>
{/if}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>{$LANG->getGlobal('feedback')}</h3>
            </div>
            <div class="panel-body text-center">
                <p class="margin-bottom-lg">{$LANG->getModule('feedback_form_note')}</p>
                <button class="btn btn-primary btn-lg show-feedback-form">{$LANG->getModule('feedback_form')}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="feedback-form" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title">{$LANG->getModule('feedback_form')}</div>
            </div>
            <div method="post" class="modal-body">
                <div class="loadContactForm">{$FORM}</div>
            </div>
        </div>
    </div>
</div>
