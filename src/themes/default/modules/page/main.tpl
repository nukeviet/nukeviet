<!-- BEGIN: main -->
<!-- BEGIN: warning -->
<div class="alert alert-danger">{LANG.warning}</div>
<!-- END: warning -->

<div class="page panel panel-default">
    <div class="panel-body">
        <h1 class="title margin-bottom-lg">{CONTENT.title}</h1>
        <!-- BEGIN: socialbutton -->
        <div class="margin-bottom-lg">
            <div class="social-share-container">
                <div class="social-share-label"><i class="fa fa-share-alt" aria-hidden="true"></i> {GLANG.share}:</div>
                <div class="social-share-buttons">
                    <!-- BEGIN: facebook --><button type="button" class="social-share-facebook" data-toggle="nv-social-share" data-platform="facebook" data-url="{CONTENT.link}" data-title="{CONTENT.title}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> Facebook</button><!-- END: facebook -->
                    <!-- BEGIN: twitter --><button type="button" class="social-share-x" data-toggle="nv-social-share" data-platform="x" data-url="{CONTENT.link}" data-title="{CONTENT.title}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"></path></svg> X</button><!-- END: twitter -->
                </div>
            </div>
        </div>
        <!-- END: socialbutton -->

        <!-- BEGIN: imageleft -->
        <figure class="article left pointer" data-toggle="modalShowByObj" data-obj="#imgpreview">
            <div style="width:{CONTENT.thumb.width}px;">
                <img alt="{CONTENT.title}" src="{CONTENT.thumb.src}" class="img-thumbnail" />
                <!-- BEGIN: alt --><figcaption>{CONTENT.imagealt}</figcaption><!-- END: alt -->
            </div>
        </figure>
        <div id="imgpreview" style="display:none">
            <p class="text-center"><img alt="{CONTENT.title}" src="{CONTENT.img.src}" srcset="{CONTENT.img.srcset}" class="img-thumbnail"/></p>
        </div>
        <!-- END: imageleft -->

        <!-- BEGIN: description -->
        <div class="hometext margin-bottom-lg">{CONTENT.description}</div>
        <!-- END: description -->

        <!-- BEGIN: imagecenter -->
        <figure class="article center pointer" data-toggle="modalShowByObj">
            <p class="text-center"><img alt="{CONTENT.title}" src="{CONTENT.img.src}" srcset="{CONTENT.img.srcset}" width="{CONTENT.img.width}" class="img-thumbnail" /></p>
            <!-- BEGIN: alt --><figcaption>{CONTENT.imagealt}</figcaption><!-- END: alt -->
        </figure>
        <!-- END: imagecenter -->

        <div class="clear"></div>

        <div id="page-bodyhtml" class="bodytext margin-bottom-lg">
            {CONTENT.bodytext}
        </div>
    </div>
</div>
<!-- BEGIN: adminlink -->
<p class="text-center margin-bottom-lg">
    <a class="btn btn-primary" href="{ADMIN_EDIT}"><em class="fa fa-edit fa-lg">&nbsp;</em>{GLANG.edit}</a>
    <a class="btn btn-danger" href="#" data-toggle="nv_del_content" data-id="{CONTENT.id}" data-ss="{ADMIN_CHECKSS}" data-adminurl="{NV_BASE_ADMINURL}"><em class="fa fa-trash-o fa-lg">&nbsp;</em>{GLANG.delete}</a>
</p>
<!-- END: adminlink -->
<!-- BEGIN: comment -->
<div class="page panel panel-default">
    <div class="panel-body">
    {CONTENT_COMMENT}
    </div>
</div>
<!-- END: comment -->
<!-- BEGIN: other -->
<div class="page panel panel-default">
    <div class="panel-body">
        <ul class="nv-list-item">
            <!-- BEGIN: loop -->
            <li><em class="fa fa-angle-double-right">&nbsp;</em><h3><a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a></h3></li>
            <!-- END: loop -->
       </ul>
    </div>
</div>
<!-- END: other -->
<!-- END: main -->
