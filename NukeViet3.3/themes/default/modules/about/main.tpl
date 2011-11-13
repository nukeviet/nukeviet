<!-- BEGIN: main -->
    <h2 class="title_about" style="color: #0943ae;font-size:14px; margin-top:5px">{CONTENT.title}</h2>
    {CONTENT.bodytext}
    <!-- BEGIN: other -->
        <ul style="margin:10px;" >
            <!-- BEGIN: loop -->
            <li>
                - <a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
            </li>
            <!-- END: loop -->
        </ul>
    <!-- END: other -->
<!-- END: main -->