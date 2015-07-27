<!-- BEGIN: main -->
<ul class="company_info" itemscope itemtype="http://schema.org/LocalBusiness">
<!-- BEGIN: company_name --><li class="company_name"><span itemprop="name">{DATA.company_name}</span><!-- BEGIN: company_sortname --> (<span itemprop="alternateName">{DATA.company_sortname}</span>)<!-- END: company_sortname --></li><!-- END: company_name -->
<!-- BEGIN: company_regcode --><li><em class="fa fa-file-text"></em><span>{LICENSE}</span></li><!-- END: company_regcode -->
<!-- BEGIN: company_responsibility --><li><em class="fa fa-flag"></em><span>{LANG.company_responsibility}: <span itemprop="founder" itemscope itemtype="http://schema.org/Person"><span itemprop="name">{DATA.company_responsibility}</span></span></span></li><!-- END: company_responsibility -->
<!-- BEGIN: company_address --><li><a data-toggle="modal" data-target="#company-map-modal"><em class="fa fa-map-marker"></em><span>{LANG.company_address}: <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="addressLocality" id="company-address">{DATA.company_address}</span></span></span></a></li><!-- END: company_address -->
<!-- BEGIN: company_phone --><li><a href="tel:{DATA.company_phone}"><em class="fa fa-phone"></em><span>{LANG.company_phone}: <span itemprop="telephone">{DATA.company_phone}</span></span></a></li><!-- END: company_phone -->
<!-- BEGIN: company_fax --><li><em class="fa fa-fax"></em><span>{LANG.company_fax}: <span itemprop="faxNumber">{DATA.company_fax}</span></span></li><!-- END: company_fax -->
<!-- BEGIN: company_email --><li><a href="mailto:{DATA.company_email}"><em class="fa fa-envelope"></em><span>{LANG.company_email}: <span itemprop="email">{DATA.company_email}</span></span></a></li><!-- END: company_email -->
<!-- BEGIN: company_website --><li><a href="{DATA.company_website}" target="_blank"><em class="fa fa-globe"></em><span>{LANG.company_website}: <span itemprop="url">{DATA.company_website}</span></span></a></li><!-- END: company_website -->
</ul>
<!-- BEGIN: company_address1 -->
<div class="modal fade" id="company-map-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
				<div id="company-map"></div>
            </div>
        </div>
    </div>
</div>
<!-- END: company_address1 -->
<!-- END: main -->