<!-- BEGIN: main -->
<div id="weblinks">
    <!-- BEGIN: top -->
        <h2>
        <!-- BEGIN: loop -->
            <span><a title="{CAT.title}" href="{CAT.link}">{CAT.title}</a> : </span>
        <!-- END: loop -->
        </h2>
    <!-- END: top -->
    <!-- BEGIN: cat -->
        <!-- BEGIN: showdes -->
        <div class="description">
            {CAT.description}
        </div>
        <!-- END: showdes -->
    <!-- END: cat -->
    <!-- BEGIN: sub -->
    <!-- BEGIN: loop -->
    <div class="cat {FLOAT}" style="width:{W}%;">
    <h2>
    <a title="{SUB.title}" href="{SUB.link}">{SUB.title}</a>
    <!-- BEGIN: count_link --><span style="font-weight:normal">[{SUB.count_link}]</span><!-- END: count_link -->
    </h2>
    </div>
    <!-- BEGIN: clear -->
    <div class="clear"></div>
    <!-- END: clear -->
    <!-- END: loop -->
    <div class="clear"></div>
    <!-- END: sub -->
    <!-- BEGIN: items -->
    <div id="items">
    <!-- BEGIN: loop -->
    <span style="font-weight:bold"><a title="{ITEM.title}" href="{ITEM.visit}" target="_blank">{ITEM.title}</a></span>
    <div class="item clearfix" id="items-{ITEM.id}">
        <div class="item-first fl">
            <a class="hits" href="{ITEM.link}" title="{LANG.visit}">{ITEM.hits_total}</a>
            <a class="more" title="{LANG.more}: {ITEM.title}" href="{ITEM.link}">{LANG.more}</a>
        </div>
        <div class="item-content fl">
            <div class="meta">
                <span class="author"><strong>{ITEM.author.login}</strong></span>
                <span class="time">{ITEM.add_time}</span>
                - <span class="category"><a title="{CAT.title}" href="{CAT.link}">{CAT.title}</a></span>
            </div>
            <p>{ITEM.description}<br /><span style="float:left; color:#999">{ITEM.url}</span></p>
        </div>
        <div class="item-thumb fr">
            <a href="{ITEM.link}" title="{LANG.visit}"><img src="{IMG}" alt="{ITEM.title}" class="fr" border="0" /></a>
        </div>
        <div class="clear"></div>
        <div align="right">{ADMIN_LINK}</div>
    </div><!-- END: loop -->
	</div>
    <!-- END: items -->
</div><!-- END: main -->
