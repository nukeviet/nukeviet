<!-- BEGIN: main -->
<ul class="company_info">
<!-- BEGIN: company_name --><li class="company_name">{DATA.company_name}<!-- BEGIN: company_sortname --> ({DATA.company_sortname})<!-- END: company_sortname --></li><!-- END: company_name -->
<!-- BEGIN: company_regcode --><li><em class="fa fa-file-text"></em><span>{LICENSE}</span></li><!-- END: company_regcode -->
<!-- BEGIN: company_responsibility --><li><em class="fa fa-flag"></em><span>{LANG.company_responsibility}: {DATA.company_responsibility}</span></li><!-- END: company_responsibility -->
<!-- BEGIN: company_address --><li><em class="fa fa-map-marker"></em><span>{LANG.company_address}: {DATA.company_address}</span></li><!-- END: company_address -->
<!-- BEGIN: company_phone --><li><a href="tel:{DATA.company_phone}"><em class="fa fa-phone"></em><span>{LANG.company_phone}: {DATA.company_phone}</span></a></li><!-- END: company_phone -->
<!-- BEGIN: company_fax --><li><em class="fa fa-fax"></em><span>{LANG.company_fax}: {DATA.company_fax}</span></li><!-- END: company_fax -->
<!-- BEGIN: company_email --><li><a href="mailto:{DATA.company_email}"><em class="fa fa-envelope"></em><span>{LANG.company_email}: {DATA.company_email}</span></a></li><!-- END: company_email -->
<!-- BEGIN: company_website --><li><a href="{DATA.company_website}" target="_blank"><em class="fa fa-globe"></em><span>{LANG.company_website}: {DATA.company_website}</span></a></li><!-- END: company_website -->
</ul>
<!-- END: main -->