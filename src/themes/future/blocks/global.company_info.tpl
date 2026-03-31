<ul class="list-unstyled vstack gap-1 mb-0">
    {if !empty($DATA.company_name)}
    <li class="h4">
        {$DATA.company_name}{if !empty($DATA.company_sortname)} ({$DATA.company_sortname}){/if}
    </li>
    {/if}
    {if !empty($DATA.company_regcode)}
    <li>
        <i class="fa-solid fa-file-lines fa-fw text-center"></i> {$DATA.company_regcode}
    </li>
    {/if}
    {if !empty($DATA.company_responsibility)}
    <li>
        <i class="fa-solid fa-flag fa-fw text-center"></i> {$LANG->get('company_responsibility')}: {$DATA.company_responsibility}
    </li>
    {/if}
    {if !empty($DATA.company_address)}
    <li>
        <i class="fa-solid fa-map-location-dot fa-fw text-center"></i>
        {if not empty($DATA.company_showmap)}
        <a href="#" data-bs-toggle="modal" data-bs-target="#company-map-modal-{$DATA.bid}">{$LANG->get('company_address')}: {$DATA.company_address}</a>
        {else}
        {$LANG->get('company_address')}: {$DATA.company_address}
        {/if}
    </li>
    {/if}
    {if !empty($DATA.company_phone)}
    <li>
        <i class="fa-solid fa-phone-volume fa-fw text-center"></i>
        {$LANG->get('company_phone')}: {foreach $DATA.company_phone as $key => $value}{if $key > 0}&nbsp; {/if}{if isset($value[1])}<a href="tel:{$value[1]}">{/if}{$value[0]}{if isset($value[1])}</a>{/if}{/foreach}
    </li>
    {/if}
    {if !empty($DATA.company_fax)}
    <li>
        <i class="fa-solid fa-fax fa-fw text-center"></i> {$LANG->get('company_fax')}: {$DATA.company_fax}
    </li>
    {/if}
    {if !empty($DATA.company_email)}
    <li>
        <i class="fa-solid fa-envelope fa-fw text-center"></i>
        {$LANG->get('company_email')}: {foreach $DATA.company_email as $key=>$value}{if $key>0}&nbsp; {/if}<a href="mailto:{$value|escape:"hex"}">{$value|escape:"hexentity"}</a>{/foreach}
    </li>
    {/if}
    {if !empty($DATA.company_website)}
    <li>
        <i class="fa-solid fa-globe fa-fw text-center"></i> {$LANG->get('company_website')}: {foreach $DATA.company_website as $key=>$value}{if $key>0}&nbsp; {/if}<a href="{$value}" target="_blank">{$value}</a>{/foreach}
    </li>
    {/if}
</ul>
{if not empty($DATA.company_address) and not empty($DATA.company_showmap)}
<!-- START FORFOOTER -->
<div class="modal fade company-map-modal" tabindex="-1" aria-labelledby="company-map-modal-{$DATA.bid}-label" aria-hidden="true" id="company-map-modal-{$DATA.bid}" data-src="{$DATA.company_mapurl}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5" id="company-map-modal-{$DATA.bid}-label">{$LANG->getGlobal('company_map')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
{/if}
<script type="application/ld+json">{$LD_JSON}</script>
