<!-- BEGIN: messages -->
<!-- BEGIN: message -->
<div class="message" id="mess-{MESSAGE.message_id}">
    <div class="message-{MESSAGE.src}">
        <div class="avatar">
            <!-- BEGIN: avatar -->
            <img src="{MESSAGE.avatar}" alt="" />
            <!-- END: avatar -->
        </div>
        <div class="content">
            <!-- BEGIN: text -->
            <div class="text">
                <!-- BEGIN: auto_mess -->
                <div class="auto-mess"><i class="fa fa-bell-o"></i> {LANG.auto_response_message}</div>
                <!-- END: auto_mess -->
                {MESSAGE.message}
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: text -->
            <!-- BEGIN: photo -->
            <div class="photo">
                <div class="text-center"><a href="#" data-toggle="viewimg" data-img="{MESSAGE.url}"><img src="{MESSAGE.thumb}" alt="" /></a></div>
                <!-- BEGIN: description -->
                <div class="photo-description">{MESSAGE.description}</div>
                <!-- END: description -->
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: photo -->
            <!-- BEGIN: GIF -->
            <div class="gif">
                <a href="#" data-toggle="viewimg" data-img="{MESSAGE.url}"><img src="{MESSAGE.thumb}" alt="" /></a>
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: GIF -->
            <!-- BEGIN: link -->
            <div class="link">
                <a href="{LINK.url}" target="_blank" data-toggle="other_link">
                    <!-- BEGIN: title --><span class="link-title">{LINK.title}</span><!-- END: title -->
                    <!-- BEGIN: thumb --><span class="link-thumb"><img src="{LINK.thumb}" alt="" /></span><!-- END: thumb -->
                    <!-- BEGIN: description --><span class="link-description">{LINK.description}</span><!-- END: description -->
                </a>
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: link -->
            <!-- BEGIN: links -->
            <div class="text links">
                <div class="list-group">
                    <!-- BEGIN: element -->
                    <div class="list-group-item"<!-- BEGIN: action --> data-toggle="action_open_modal" data-title="{ACTION.action_title}" data-content="{ACTION.action_content}" style="cursor: pointer;"<!-- END: action -->>
                        <div class="links-title">{ELEMENT.title}</div>
                        <!-- BEGIN: subtitle --><div class="links-subtitle">{ELEMENT.subtitle}</div><!-- END: subtitle -->
                    </div>
                    <!-- END: element -->
                </div>
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: links -->
            <!-- BEGIN: buttons -->
            <div class="text buttons">
                <div class="attach-message">{MESSAGE.message}</div>
                <!-- BEGIN: btn -->
                <button class="btn btn-default btn-block active" data-toggle="action_open_modal" data-title="{BTN.action_title}" data-content="{BTN.action_content}">{BTN.title}</button>
                <!-- END: btn -->
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: buttons -->
            <!-- BEGIN: sticker -->
            <div class="sticker">
                <img src="{MESSAGE.url}" alt="" />
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: sticker -->
            <!-- BEGIN: location -->
            <div class="location">
                <iframe title="map" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={MESSAGE.latitude},{MESSAGE.longitude}&amp;hl=es;z=14&amp;output=embed" width="100%" frameborder="0"></iframe>
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: location -->
            <!-- BEGIN: voice -->
            <div class="voice">
                <button type="button" data-toggle="voice_play" data-file="{MESSAGE.playfile}"><i class="fa fa-play fa-fw"></i> {LANG.voice} <span class="playing"><span class="playing_bar playing_bar1"></span><span class="playing_bar playing_bar2"></span><span class="playing_bar playing_bar3"></span></span></button>
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: voice -->
            <!-- BEGIN: file -->
            <div class="text file">
                <a href="{MESSAGE.url}"><!-- BEGIN: pdf --><i class="fa fa-file-pdf-o"></i><!-- END: pdf --><!-- BEGIN: doc --><i class="fa fa-file-text-o"></i><!-- END: doc --> {MESSAGE.description}</a>
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: file -->
            <!-- BEGIN: nosupport -->
            <div class="text nosupport">
                <i class="fa fa-exclamation-triangle"></i> {LANG.nosupport}
                <div class="time">{MESSAGE.time_format}</div>
            </div>
            <!-- END: nosupport -->
            <!-- BEGIN: tool -->
            <div class="tool"><button type="button" class="btn btn-default btn-sm" data-toggle="mess_reply" data-message-id="{MESSAGE.message_id}" title="{LANG.mess_reply}"><i class="fa fa-reply-all fa-lg"></i></button></div>
            <!-- END: tool -->
        </div>
    </div>
</div>
<!-- END: message -->
<!-- END: messages -->