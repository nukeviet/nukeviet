<!-- BEGIN: main -->

<!-- BEGIN: warning -->
<div class="alert alert-danger">{LANG.warning}</div>
<!-- END: warning -->

<div class="page panel panel-default" itemtype="http://schema.org/Article" itemscope>
    <div class="panel-body">
        <h1 class="title margin-bottom-lg" itemprop="headline">{CONTENT.title}</h1>
        <div class="hidden hide d-none" itemprop="author" itemtype="http://schema.org/Organization" itemscope>
            <span itemprop="name">{SCHEMA_ORGNAME}</span>
        </div>
        <span class="hidden hide d-none" itemprop="datePublished">{SCHEMA_DATEPUBLISHED}</span>
        <span class="hidden hide d-none" itemprop="dateModified">{SCHEMA_DATEPUBLISHED}</span>
        <span class="hidden hide d-none" itemprop="mainEntityOfPage">{SCHEMA_URL}</span>
        <span class="hidden hide d-none" itemprop="image">{SCHEMA_IMAGE}</span>
        <div class="hidden hide d-none" itemprop="publisher" itemtype="http://schema.org/Organization" itemscope>
            <span itemprop="name">{SCHEMA_ORGNAME}</span>
            <span itemprop="logo" itemtype="http://schema.org/ImageObject" itemscope>
                <span itemprop="url">{SCHEMA_ORGLOGO}</span>
            </span>
        </div>
        <!-- BEGIN: socialbutton -->
        <div class="margin-bottom-lg">
            <div class="social-share-container">
                <div class="social-share-label"><i class="fa fa-share-alt" aria-hidden="true"></i> {GLANG.share}:</div>
                <div class="social-share-buttons">
                    <!-- BEGIN: facebook --><button type="button" class="social-share-facebook" data-toggle="nv-social-share" data-platform="facebook" data-url="{CONTENT.link}" data-title="{CONTENT.title}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> Facebook</button><!-- END: facebook -->
                    <!-- BEGIN: twitter --><button type="button" class="social-share-twitter" data-toggle="nv-social-share" data-platform="twitter" data-url="{CONTENT.link}" data-title="{CONTENT.title}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg> Tweet</button><!-- END: twitter -->
                </div>
            </div>
        </div>
        <!-- END: socialbutton -->

        <!-- BEGIN: imageleft -->
        <figure class="article left pointer" onclick="modalShowByObj('#imgpreview');">
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
        <div class="hometext margin-bottom-lg" itemprop="description">{CONTENT.description}</div>
        <!-- END: description -->

        <!-- BEGIN: imagecenter -->
        <figure class="article center pointer" onclick="modalShowByObj(this);">
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
    <a class="btn btn-danger" href="javascript:void(0);" onclick="nv_del_content({CONTENT.id}, '{ADMIN_CHECKSS}','{NV_BASE_ADMINURL}')"><em class="fa fa-trash-o fa-lg">&nbsp;</em>{GLANG.delete}</a>
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
