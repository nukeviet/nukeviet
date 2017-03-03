<!-- BEGIN: main -->
<div class="message" id="mesHide"></div>
    <div class="detailContent clearfix">
        <div class="videoTitle" id="videoTitle">{DETAILCONTENT.title}</div>
        <div class="videoplayer">
            <div class="cont"><div id="videoCont"></div></div>
        </div>
        <div class="clearfix"></div>
    </div>
<script type="text/javascript">
$(function() {
  videoPlay("{DETAILCONTENT.filepath}", "videoCont"), $("html,body").animate({scrollTop:$(".detailContent").offset().top}, 500)
});
</script>
    <div id="otherClipsAj">
        <div class="videoInfo marginbottom15 clearfix">
            <div class="cont">
                <div class="cont2">
                    <div class="fl">
                        <div class="shareFeelings">{LANG.shareFeelings}</div>
                        <a class="likeButton" href="{DETAILCONTENT.url}"><img class="like" src="{NV_BASE_SITEURL}images/pix.gif" alt="" width="15" height="14" /><span>{LANG.like}</span></a>
                        <a class="likeButton" href="{DETAILCONTENT.url}"><img class="unlike" src="{NV_BASE_SITEURL}images/pix.gif" alt="" width="15" height="14" /><span>{LANG.unlike}</span></a>
                        <a class="likeButton" href="{DETAILCONTENT.url}"><img class="broken" src="{NV_BASE_SITEURL}images/pix.gif" alt="" width="15" height="14" /><span>{LANG.broken}</span></a>
                    </div>
                    <div class="fr">
                        <div class="viewcount">
                            <!-- BEGIN: isAdmin -->
                            <a href="{DETAILCONTENT.editUrl}">{LANG.edit}</a>,&nbsp;
                            <!-- END: isAdmin -->
                            {LANG.viewHits}: <span>{DETAILCONTENT.view}</span><!-- BEGIN: ifComm -->,&nbsp;{LANG.commHits}: <span id="commHits">{DETAILCONTENT.comment}</span><!-- END: ifComm -->
                        </div>
                        <div style="float:right;">
                            <div class="image image0"><img id="imglike" src="{NV_BASE_SITEURL}images/pix.gif" alt="" width="1" /></div>
                            <div class="likeDetail">
                                <div class="likeLeft">{LANG.like}: <span class="strong" id="ilike">{DETAILCONTENT.like}</span><br /><span id="plike"></span></div>
                                <div class="likeRight">{LANG.unlike}: <span class="strong" id="iunlike">{DETAILCONTENT.unlike}</span><br /><span id="punlike"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="hometext">
                    {DETAILCONTENT.hometext}
                    <!-- BEGIN: bodytext -->
                    <div class="bodytext hide">{DETAILCONTENT.bodytext}</div>
                    <div class="bodybutton"><a href="open" class="bodybutton">{LANG.moreContent}</a></div>
                    <!-- END: bodytext -->
                </div>
            </div>
        </div>
<script type="text/javascript">
function addLikeImage(){var b=intval($("#ilike").text()),c=intval($("#iunlike").text()),d=$(".image").width();if(0==b&&0==c)$(".image").removeClass("imageunlike").addClass("image0"),$("#imglike").removeClass("like").width(1),$("#plike,#punlike").text("");else if($(".image").removeClass("image0").addClass("imageunlike"),0==b)$("#imglike").removeClass("like").width(1),$("#plike").text("0%"),$("#punlike").text("100%");else{var a=intval(100*b/(b+c)),b=intval(a*(d/100)),e=100-a;$("#imglike").addClass("like").animate({width:b},
1500,function(){$("#plike").text(a+"%");$("#punlike").text(e+"%")})}}$(function(){addLikeImage()});
$("a.likeButton").click(function(){var b=$(this).attr("href"),c=$("img",this).attr("class"),d=$(this).offset();$.ajax({type:"POST",url:b,data:"aj="+c,success:function(a){if("access forbidden"==a)return alert("{LANG.accessForbidden}"),!1;var a=a.split("_"),b="like"==a[0]||"unlike"==a[0]?"{LANG.thank}":"{LANG.thankBroken}";$("#i"+a[0]).text(a[1]);addLikeImage();$("#mesHide").text(b).css({left:d.left+50+"px",top:d.top-100+"px","z-index":1E4}).show("slow");setTimeout(function(){$("div#mesHide").css({"z-index":"-1"}).hide("slow")},
3E3)}});return!1});$("a.bodybutton").click(function(){"open"==$(this).attr("href")?($(".bodytext").slideDown("slow"),$(this).attr("href","close").text("{LANG.collapseContent}"),$("html,body").animate({scrollTop:$(".hometext").offset().top},500)):($(".bodytext").slideUp("slow"),$(this).attr("href","open").text("{LANG.moreContent}"),$("html,body").animate({scrollTop:$(".detailContent").offset().top},500));return!1});
</script>

<!-- BEGIN: otherClips -->
<div class="otherClips marginbottom15 clearfix">
<!-- BEGIN: otherClipsContent -->
<div class="otherClipsContent">
    <div class="ctn1">
        <div class="ctn2">
            <div style="background:transparent url({OTHERCLIPSCONTENT.img}) no-repeat center center">
                <a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
                <img src="{NV_BASE_SITEURL}images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="80" /></a>
            </div>
            <div class="vtitle"><a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.sortTitle}</a></div>
            <div class="viewHits">{LANG.viewHits} <span>{OTHERCLIPSCONTENT.view}</span></div>
            <div class="play">
                <a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
                <img src="{NV_BASE_SITEURL}images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="32" /></a>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: clearfix --><div class="clearfix"></div><!-- END: clearfix -->
<!-- END: otherClipsContent -->
</div>
<script type="text/javascript">
$("a.otcl").click(function(){var b=$(this).attr("href");return $.ajax({type:"POST",url:b,data:"aj=1",success:function(a){if("access forbidden"==a)return alert("{LANG.accessForbidden}"),!1;$("#videoDetail").html(a)}}),!1});
</script>
<!-- END: otherClips -->

</div>
<!-- BEGIN: commentSector -->
<div id="commentSector">
{COMMENTSECTOR}
</div>
<!-- END: commentSector -->
<!-- END: main -->

<!-- BEGIN: listComm -->
<!-- BEGIN: listComm2 -->
<div class="listComm marginbottom10">
<div class="userComm">
<div class="top">
<div class="userName"><a href="{USER.userView}">{USER.full_name}</a></div>
<div class="posttime">{USER.posttime}<!-- BEGIN: unchecked --> <a class="broken" title="{LANG.reportBadContent}" href="{USER.id}"><img alt="{LANG.reportBadContent}" class="brokenIcon" src="{NV_BASE_SITEURL}images/pix.gif" width="14" height="14" /></a><!-- END: unchecked --><!-- BEGIN: delcomm --> <a class="delcomm" title="{LANG.deleteComm}" href="{USER.id}"><img alt="{LANG.deleteComm}" class="delcommIcon" src="{NV_BASE_SITEURL}images/pix.gif" width="14" height="14" /></a><!-- END: delcomm --></div>
<div class="clearfix"></div>
</div>
<div class="commContent">{USER.content}</div>
</div>
<div class="userPhoto"><a href="{USER.userView}" title="{USER.full_name}"><img src="{USER.photo}" width="50" alt="{USER.full_name}" /></a></div>
<div class="clearfix"></div>
</div>
<!-- END: listComm2 -->
<!-- BEGIN: ifNext -->
<div class="next">
<a class="inext" href="{NEXTID}">{LANG.next}</a>
<input name="nextid" id="nextid" value="{NEXTID}" type="hidden" />
</div>
<!-- END: ifNext -->
<script type="text/javascript">
$("a.inext").click(function(){$.ajax({type:"POST",url:"{MODULEURL}",data:"cpgnum="+$("#nextid").val(),success:function(a){$("div.next").remove();$("#listComm").append(a)}});return!1});
$("a.broken").click(function(){var a=$(this).offset();$.ajax({type:"POST",url:"{MODULEURL}",data:"mbroken="+$(this).attr("href"),success:function(){$("#mesHide").text("{LANG.thankBroken}").css({left:a.left-180+"px",top:a.top-100+"px","z-index":1E4}).show("slow");setTimeout(function(){$("div#mesHide").css({"z-index":"-1"}).hide("slow")},3E3)}});return!1});
<!-- BEGIN: ifDelComm -->
$("a.delcomm").click(function(){var b=$(this).offset();$.ajax({type:"POST",url:"{MODULEURL}",data:"delcomm="+$(this).attr("href"),success:function(a){$("#mesHide").text("{LANG.isdeletedComm}").css({left:b.left-180+"px",top:b.top-100+"px","z-index":1E4}).show("slow");setTimeout(function(){$("div#mesHide").css({"z-index":"-1"}).hide("slow");a=a.split("|");$("#commHits").text(a[1]);$.ajax({type:"POST",url:"{MODULEURL}",data:"commentReload=1",success:function(a){$("#commentSector").html(a)}})},3E3)}});
return!1});
<!-- END: ifDelComm -->
</script>
<!-- END: listComm -->

<!-- BEGIN: commentList -->
<div class="commentList">
<div class="commTitle">{LANG.commList}</div>
<div class="cont">
<!-- BEGIN: ifNotGuest -->
<div class="commentForm marginbottom10">
{PLEASELOGIN}
</div>
<!-- END: ifNotGuest -->
<!-- BEGIN: commentForm -->
<div class="commentForm marginbottom10">
<div class="commTextarea">
<textarea id="myComment" maxlength="500">{LANG.yourComment}. {LANG.characters3}</textarea>
</div>
<div class="commSubmit">
<button id="commSubmit">&nbsp;OK&nbsp;</button>
</div>
<div class="contentLength" id="contentLength"></div>
</div>
<script type="text/javascript">
$(function(){$("#myComment").autoResize().focus(function(){$(this).css("background","#fff")}).blur(function(){""==$(this)[0].value&&$(this).css("background","#f3f3f3")});elementSupportsAttribute("textarea","placeholder")?$("#myComment").attr("placeholder",$("#myComment").text()).text(""):$("#myComment").data("originalText",$("#myComment").text()).css("color","#999").focus(function(){var a=$(this);a.css("color","#000");this.value==a.data("originalText")&&(this.value="")}).blur(function(){""==this.value&&
(this.value=$(this).data("originalText"))&&$(this).css("color","#999")});$("#myComment").bind("focus keydown keyup keypress change",function(){var a=500-$(this).val().length,b=a%10;$("#contentLength").show();b=1==b&&(11>a||20<a)?"{LANG.character1}":1<b&&5>b&&(11>a||20<a)?"{LANG.character2}":"{LANG.character5}";return $("#contentLength").html(0==a?'<span class="num l50">{LANG.characters2}</span>':500==a?"":50>a?'{LANG.characters} <span class="num l50">'+a+"</span> "+b:'{LANG.characters} <span class="num">'+
a+"</span> "+b)});$("#commSubmit").click(function(){var a=trim($("#myComment").val());if(""==a)return alert("{LANG.error1}"),!1;$.ajax({type:"POST",url:"{MODULEURL}",data:"savecomm="+a,success:function(a){if("OK"!=a)return a=explode("|",a,2),alert(a[1]),!1;$("#myComment").val("");$("#contentLength").text("");$.ajax({type:"POST",url:"{MODULEURL}",data:"commentReload=1",success:function(a){$("#commentSector").html(a);$("#commHits").text(intval($("#commHits").text())+1)}});return!1}});return!1})});
</script>
<!-- END: commentForm -->
<div id="listComm">
{LISTCOMM}
</div>
</div>
<div class="clearfix"></div>
</div>
<!-- END: commentList -->