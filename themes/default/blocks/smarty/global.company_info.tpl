<ul class="company_info" itemscope itemtype="http://schema.org/LocalBusiness">
    <li class="hide hidden">
        <span itemprop="image">{$SITE_LOGO}</span>
        <span itemprop="priceRange">N/A</span>
    </li>
{if !empty($DATA.company_name)}
    <li class="company_name"><span itemprop="name">{$DATA.company_name}</span>{if !empty($DATA.company_sortname)} (<span itemprop="alternateName">{$DATA.company_sortname}</span>){/if}</li>
{/if}
{if !empty($DATA.company_regcode)}
    <li><em class="fa fa-file-text"></em><span>{$DATA.company_regcode}</span></li>
{/if}
{if !empty($DATA.company_responsibility)}
    <li><em class="fa fa-flag"></em><span>{$LANG->get('company_responsibility')}: <span itemprop="founder" itemscope itemtype="http://schema.org/Person"><span itemprop="name">{$DATA.company_responsibility}</span></span></span></li>
{/if}
{if !empty($DATA.company_address)}
    <li><a href="#"{if !empty($DATA.company_showmap)} class="pointer" data-toggle="modal" data-target="#company-map-modal-{$DATA.bid}"{/if}><em class="fa fa-map-marker"></em><span>{$LANG->get('company_address')}: <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="addressLocality" class="company-address">{$DATA.company_address}</span></span></span></a></li>
{/if}
{if !empty($DATA.company_phone)}
    <li><em class="fa fa-phone"></em><span>{$LANG->get('company_phone')}: {foreach $DATA.company_phone as $key=>$value}{if $key > 0}&nbsp; {/if}{if isset($value[1])}<a href="tel:{$value[1]}">{/if}<span itemprop="telephone">{$value[0]}</span>{if isset($value[1])}</a>{/if}{/foreach}</span></li>
{/if}
{if !empty($DATA.company_fax)}
    <li><em class="fa fa-fax"></em><span>{$LANG->get('company_fax')}: <span itemprop="faxNumber">{$DATA.company_fax}</span></span></li>
{/if}
{if !empty($DATA.company_email)}
    <li><em class="fa fa-envelope"></em><span>{$LANG->get('company_email')}: {foreach $DATA.company_email as $key=>$value}{if $key>0}&nbsp; {/if}<a href="mailto:{$value|escape:"hex"}"><span itemprop="email">{$value|escape:"hexentity"}</span></a>{/foreach}</span></li>
{/if}
{if !empty($DATA.company_website)}
    <li><em class="fa fa-globe"></em><span>{$LANG->get('company_website')}: {foreach $DATA.company_website as $key=>$value}{if $key>0}&nbsp; {/if}<a href="{$value}" target="_blank"><span itemprop="url">{$value}</span></a>{/foreach}</span></li>
{/if}
</ul>
{if !empty($DATA.company_address) && !empty($DATA.company_showmap)}
<!-- START FORFOOTER -->
<div class="modal fade company-map-modal" id="company-map-modal-{$DATA.bid}" data-src="{$DATA.company_mapurl}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
{/if}
