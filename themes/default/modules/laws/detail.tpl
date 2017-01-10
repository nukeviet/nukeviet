<!-- BEGIN: main -->
<h3 class="lawh3">{DATA.title}</h3>
<p>{DATA.introtext}</p>

<!-- BEGIN: field -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr class="hoatim">
                <td style="width:200px" class="text-right">{LANG.code}</td>
                <td>{DATA.code}</td>
            </tr>
            <!-- BEGIN: publtime -->
            <tr class="hoatim">
                <td class="text-right">{LANG.publtime}</td>
                <td>{DATA.publtime}</td>
            </tr>
            <!-- END: publtime -->
            <!-- BEGIN: startvalid -->
            <tr class="hoatim">
                <td class="text-right">{LANG.startvalid}</td>
                <td>{DATA.startvalid}</td>
            </tr>
            <!-- END: startvalid -->
            <!-- BEGIN: exptime -->
            <tr class="hoatim">
                <td class="text-right">{LANG.exptime}</td>
                <td>{DATA.exptime}</td>
            </tr>
            <!-- END: exptime -->
            <!-- BEGIN: cat -->
            <tr class="hoatim">
                <td class="text-right">{LANG.cat}</td>
                <td>
                    <!-- BEGIN: link --><a href="{DATA.cat_url}" title="{DATA.cat}">{DATA.cat}</a><!-- END: link -->
                    <!-- BEGIN: text -->{DATA.cat}<!-- END: text -->
                </td>
            </tr>
            <!-- END: cat -->
            <tr class="hoatim">
                <td class="text-right">{LANG.area}</td>
                <td>
                    <!-- BEGIN: area_link --><a href="{AREA.url}" title="{AREA.title}">{AREA.title}</a><br /><!-- END: area_link -->
                    <!-- BEGIN: area_text -->{AREA.title}<br /><!-- END: area_text -->
                </td>
            </tr>
            <!-- BEGIN: subject -->
            <tr class="hoatim">
                <td class="text-right">{LANG.subject}</td>
                <td>
                    <!-- BEGIN: link --><a href="{DATA.subject_url}" title="{DATA.subject}">{DATA.subject}</a><!-- END: link -->
                    <!-- BEGIN: text -->{DATA.subject}<!-- END: text -->
                </td>
            </tr>
            <!-- END: subject -->
            <!-- BEGIN: signer -->
            <tr class="hoatim">
                <td class="text-right">{LANG.signer}</td>
                <td>
                    <!-- BEGIN: link --><a href="{DATA.signer_url}" title="{DATA.signer}">{DATA.signer}</a><!-- END: link -->
                    <!-- BEGIN: text -->{DATA.signer}<!-- END: text -->
                </td>
            </tr>
            <!-- END: signer -->
            <!-- BEGIN: replacement -->
            <tr>
                <td>{LANG.replacement}</td>
                <td>
                    <ul class="list-item">
                        <!-- BEGIN: loop -->
                        <li><a href="{replacement.link}" title="{replacement.title}">{replacement.code}</a> - {replacement.title}</li>
                        <!-- END: loop -->
                    </ul>
                </td>
            </tr>
            <!-- END: replacement -->
            <!-- BEGIN: unreplacement -->
            <tr>
                <td>{LANG.unreplacement}</td>
                <td>
                    <ul class="list-item">
                        <!-- BEGIN: loop -->
                        <li><a href="{unreplacement.link}" title="{unreplacement.title}">{unreplacement.code}</a> - {unreplacement.title}</li>
                        <!-- END: loop -->
                    </ul>
                </td>
            </tr>
            <!-- END: unreplacement -->
            <!-- BEGIN: relatement -->
            <tr>
                <td>{LANG.relatement}</td>
                <td>
                    <ul class="list-item">
                        <!-- BEGIN: loop -->
                        <li><a href="{relatement.link}" title="{relatement.title}">{relatement.code}</a> - {relatement.title}</li>
                        <!-- END: loop -->
                    </ul>
                </td>
            </tr>
            <!-- END: relatement -->
        </tbody>
    </table>
</div>
<!-- END: field -->

<!-- BEGIN: bodytext -->
<h3 class="lawh3">{LANG.bodytext}</h3>
<p class="m-bottom">{DATA.bodytext}</p>
<!-- END: bodytext -->

<!-- BEGIN: files -->
<h3 class="lawh3"><em class="fa fa-download">&nbsp;</em>{LANG.files}</h3>
<div class="list-group laws-download-file">
    <!-- BEGIN: loop -->
    <div class="list-group-item">
        <!-- BEGIN: show_quick_view --><span class="badge"><a role="button" data-toggle="collapse" href="#pdf{FILE.key}" aria-expanded="false" aria-controls="pdf{FILE.key}"><i class="fa fa-file-pdf-o" data-toggle="tooltip" data-title="{LANG.quick_view_pdf}"></i></a></span><!-- END: show_quick_view -->
        <a href="{FILE.url}" title="{FILE.titledown}{FILE.title}">{FILE.titledown}: <strong>{FILE.title}</strong></a>
        <!-- BEGIN: content_quick_view -->
        <div class="clearfix"></div>
        <div class="collapse" id="pdf{FILE.key}" data-src="{FILE.urlpdf}" data-toggle="collapsepdf">
            <div style="height:10px"></div>
            <div class="well">
                <iframe frameborder="0" height="600" scrolling="yes" src="" width="100%"></iframe>
            </div>
        </div>
        <!-- END: content_quick_view -->
    </div>
    <!-- END: loop -->
</div>
<!-- END: files -->

<!-- BEGIN: nodownload -->
<h3 class="lawh3">{LANG.files}</h3>
<p class="text-center m-bottom">{LANG.info_download_no}</p>
<!-- END: nodownload -->

<!-- BEGIN: other_cat -->
<h3 class="subtitle">{LANG.other_cat} <a href="{DATA.cat_url}" title="{DATA.cat}">"{DATA.cat}"</a></h3>
{OTHER_CAT}
<!-- END: other_cat -->

<!-- BEGIN: other_area -->
<h3 class="subtitle">{LANG.other_area}</h3>
{OTHER_AREA}
<!-- END: other_area -->

<!-- BEGIN: other_subject -->
<h3 class="subtitle">{LANG.other_subject} <a href="{DATA.subject_url}" title="{DATA.subject}">"{DATA.subject}"</a></h3>
{OTHER_SUBJECT}
<!-- END: other_subject -->

<!-- BEGIN: other_signer -->
<h3 class="subtitle">{LANG.other_signer} <a href="{DATA.signer_url}" title="{DATA.signer}">"{DATA.signer}"</a></h3>
{OTHER_SIGNER}
<!-- END: other_signer -->

<!-- END: main -->