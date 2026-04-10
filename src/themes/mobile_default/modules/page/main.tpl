<!-- BEGIN: main -->
<!-- BEGIN: warning -->
<div class="alert alert-danger">{LANG.warning}</div>
<!-- END: warning -->

<div class="page panel panel-default">
    <div class="panel-body">
        <h1 class="title margin-bottom-lg">{CONTENT.title}</h1>
        <!-- BEGIN: socialbutton -->
        <div class="margin-bottom">
            <div style="display:flex;align-items:flex-start;">
                <!-- BEGIN: facebook --><div class="margin-right"><div class="fb-like" style="float:left!important;margin-right:0!important" data-href="{CONTENT.link}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></div><!-- END: facebook -->
                <!-- BEGIN: twitter --><div class="margin-right"><a href="http://twitter.com/share" class="twitter-share-button">Tweet</a></div><!-- END: twitter -->
                <!-- BEGIN: zalo --><div><div class="zalo-share-button" data-href="" data-oaid="{ZALO_OAID}" data-layout="1" data-color="blue" data-customize=false></div></div><!-- END: zalo -->
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
