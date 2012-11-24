<!-- BEGIN: main -->
<div id="weblinks">
    <!-- BEGIN: intro -->
    <p id="intro">{intro}</p>
    <!-- END: intro -->
    <!-- BEGIN: cat -->
    <div class="cat {FLOAT}" style="width: {W}%;">
        <h2><a title="{CAT.title}" href="{CAT.link}" class="h2_title">{CAT.title}</a></h2>
        <!-- BEGIN: showdes -->
        <p>{CAT.description}</p>
        <!-- END: showdes -->
        <!-- BEGIN: sub -->
        <ul>
            <!-- BEGIN: loop -->
            <li>
                <!-- BEGIN: line -->
                <span>|</span>
                <!-- END:line -->
                <a title="{SUB.title}" href="{SUB.link}">{SUB.title}</a>
                <!-- BEGIN: count_link -->
                <em>({SUB.count_link})</em>
                <!-- END: count_link -->
            </li>
            <!-- END: loop -->
        </ul>
        <!-- END: sub -->
    </div>
    <!-- BEGIN: clear -->
    <div class="clear"></div>
    <!-- END: clear -->
    <!-- END: cat -->
</div>            
<!-- END: main -->