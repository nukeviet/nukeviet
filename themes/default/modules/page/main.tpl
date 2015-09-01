<!-- BEGIN: main -->
<div class="page panel panel-default">
    <div class="panel-body">
        <h3 class="title margin-bottom-lg">{CONTENT.title}</h3>
    	<!-- BEGIN: socialbutton -->
    	<div class="well well-sm">
    		<ul class="nv-social-share">
    			<li class="facebook">
    				<div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
    			</li>
    			<li>
    				<div class="g-plusone" data-size="medium"></div>
    			</li>
    			<li>
    				<a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
    			</li>
    		</ul>
    	</div>
    	<!-- END: socialbutton -->
        <!-- BEGIN: description -->
        <div class="hometext margin-bottom-lg">{CONTENT.description}</div>
        <!-- END: description -->
    	<!-- BEGIN: image -->
        <figure class="article center pointer" onclick="modalShowByObj(this);">
    			<p class="text-center"><img alt="{CONTENT.title}" src="{CONTENT.image}" width="{CONTENT.imageWidth}" class="img-thumbnail" /></p>
    			<!-- BEGIN: alt --><figcaption>{CONTENT.imagealt}</figcaption><!-- END: alt -->
   		</figure>
    	<!-- END: image -->
	<div class="bodytext margin-bottom-lg">
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
    		<li><em class="fa fa-angle-double-right">&nbsp;</em><a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a></li>
    		<!-- END: loop -->
	   </ul>
    </div>
</div>
<!-- END: other -->
<!-- END: main -->