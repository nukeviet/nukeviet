<!-- BEGIN: main -->
<div class="page">
    <h1 class="margin-top margin-bottom">{PAGE_TITLE}</h1>
    <!-- BEGIN: bodytext -->
    <p class="margin-bottom">{CONTENT.bodytext}</p>
    <!-- END: bodytext -->
    <div class="row">
        <div class="col-sm-12 col-md-14">
            <!-- BEGIN: dep -->
            <div class="panel panel-default">
                <!-- BEGIN: header -->
                <div class="panel-heading">
                    <a href="{DEP.url}" class="pull-right text-dark"><i class="fa fa-arrow-right fa-fw"></i></a>
                    <h2 class="h3">{DEP.full_name}</h2>
                </div>
                <!-- END: header -->
                <div class="panel-body">
                    <!-- BEGIN: image -->
                    <div class="margin-bottom"><img src="{DEP.image}" srcset="{DEP.srcset}" class="img-thumbnail" alt="{DEP.full_name}" /></div>
                    <!-- END: image -->
                    <!-- BEGIN: note -->
                    <div class="margin-bottom">{DEP.note}</div>
                    <!-- END: note -->
                    <!-- BEGIN: address -->
                    <p>
                        <em class="fa fa-map-marker fa-horizon margin-right"></em>{LANG.address}: <span>{DEP.address}</span>
                    </p>
                    <!-- END: address -->
                    <!-- BEGIN: phone -->
                    <p>
                        <em class="fa fa-phone fa-horizon margin-right"></em>{LANG.phone}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma -->
                            <!-- BEGIN: href -->
                            <a href="tel:{PHONE.href}" class="black">
                                <!-- END: href -->{PHONE.number}<!-- BEGIN: href2 -->
                        </a>
                        <!-- END: href2 -->
                            <!-- END: item -->
                        </span>
                    </p>
                    <!-- END: phone -->
                    <!-- BEGIN: fax -->
                    <p>
                        <em class="fa fa-fax fa-horizon margin-right"></em>{LANG.fax}: <span>{DEP.fax}</span>
                    </p>
                    <!-- END: fax -->
                    <!-- BEGIN: email -->
                    <p>
                        <em class="fa fa-envelope fa-horizon margin-right"></em>{LANG.email}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma -->
                            <a href="mailto:{EMAIL}" class="black">{EMAIL}</a>
                        <!-- END: item -->
                        </span>
                    </p>
                    <!-- END: email -->
                    <!-- BEGIN: skype -->
                    <p>
                        <em class="fa fa-skype fa-horizon margin-right"></em>{SKYPE.name}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma -->
                            <a href="skype:{SKYPE.value}?call" class="black">{SKYPE.value}</a>
                        <!-- END: item -->
                        </span>
                    </p>
                    <!-- END: skype -->
                    <!-- BEGIN: viber -->
                    <p>
                        <em class="icon-viber fa-horizon margin-right"></em>{VIBER.name}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="viber://pa?chatURI={VIBER.value}" class="black">{VIBER.value}</a><!-- END: item -->
                        </span>
                    </p>
                    <!-- END: viber -->
                    <!-- BEGIN: icq -->
                    <p>
                        <em class="icon-icq fa-horizon margin-right"></em>{ICQ.name}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma -->
                            <a href="icq:message?uin={ICQ.value}" class="black">{ICQ.value}</a>
                        <!-- END: item -->
                        </span>
                    </p>
                    <!-- END: icq -->
                    <!-- BEGIN: whatsapp -->
                    <p>
                        <em class="fa fa-whatsapp fa-horizon margin-right"></em>{WHATSAPP.name}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma -->
                            <a href="https://wa.me/{WHATSAPP.value}" class="black">{WHATSAPP.value}</a>
                        <!-- END: item -->
                        </span>
                    </p>
                    <!-- END: whatsapp -->
                    <!-- BEGIN: zalo -->
                    <p>
                        <em class="icon-zalo fa-horizon margin-right"></em>{ZALO.name}: <span>
                            <!-- BEGIN: item -->
                            <!-- BEGIN: comma -->&nbsp; <!-- END: comma -->
                            <a href="https://zalo.me/{ZALO.value}" class="black">{ZALO.value}</a>
                        <!-- END: item -->
                        </span>
                    </p>
                    <!-- END: zalo -->
                    <!-- BEGIN: other -->
                    <p>
                        <!-- BEGIN: text -->
                        <span>{OTHER.name}: </span> <span>{OTHER.value}</span>
                        <!-- END: text -->
                        <!-- BEGIN: url -->
                        <em class="fa fa-globe fa-horizon margin-right"></em>{OTHER.name}: <span><a href="{OTHER.value}" title="">{OTHER.value}</a></span>
                        <!-- END: url -->
                    </p>
                    <!-- END: other -->
                </div>
            </div>
            <!-- END: dep -->
        </div>
        <div class="col-sm-12 col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">{GLANG.feedback}</div>
                <div class="panel-body loadContactForm">{FORM}</div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->