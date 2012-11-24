<!-- BEGIN: main -->
    <!-- BEGIN: is_addfile_allow --><a href="{UPLOAD}" class="link_upload">{LANG.upload}</a>
    <!-- END: is_addfile_allow -->
    <div class="sliver2">
        <h3 class="header">{LANG.categories}</h3>
        <div class="clearfix">
            <ul id="navmenu-v">
                <!-- BEGIN: catparent -->
                <li>
                    <a href="{catparent.link}">{catparent.title}</a>
                    <!-- BEGIN: subcatparent -->
                    <ul>
                        <!-- BEGIN: loopsubcatparent -->
                        <li>
                            <a href="{loopsubcatparent.link}">{loopsubcatparent.title}</a>
                        </li>
                        <!-- END: loopsubcatparent -->
                    </ul>
                    <!-- END: subcatparent -->
                </li>
                <!-- END: catparent -->
            </ul>
        </div>
    </div>
<!-- END: main -->