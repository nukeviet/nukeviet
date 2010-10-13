<!-- BEGIN: main -->
<div class="box-border">
    <div class="page-header">
        <h2>{CONTENT.title}</h2>
        <span class="small">{LANG.add_time}: {CONTENT.add_time}</span>
        <div class="clear">
        </div>
    </div>
    <div class="content-box">
        <div class="content-page">
            {CONTENT.bodytext}
        </div>
        <div class="other-news">
            <ul style="margin:10px;">
                <!-- BEGIN: loop -->
                <li>
                    <a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
                </li>
                <!-- END: loop -->
            </ul>
        </div>
    </div>
</div>
<!-- END: main -->
