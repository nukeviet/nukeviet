<nav class="navbar navbar-expand-md  navbar-static ms-navbar ms-navbar-primary">
        <div class="container container-full">
          <div class="navbar-header">
            <a class="navbar-brand" href="/material-style/index.html">
              <!-- <img src="/uploads/demo/logo-navbar.png" alt=""> -->
             <!--  <span class="ms-logo ms-logo-sm">M</span>
             <span class="ms-title">Material
               <strong>Style</strong>
             </span> -->
             <img src="{$logo.src}" width= "{$logo.width}" height="{$logo.height}"alt="logo">
            </a>
           
          </div>
         
          <div class="collapse navbar-collapse" id="ms-navbar">
            <ul class="navbar-nav">
           <!--  menu loại 1 -->
              <li class="nav-item dropdown active">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="home">Home
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="ms-tab-menu">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs ms-tab-menu-left" role="tablist">
                    
                    {foreach $menutab as $menutabs}
                    	
                    	<li class="nav-item">
                        <a class="nav-link {$test12.css}" href="#tab_{$menutabs@key}" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-home"></i> {$menutabs.title}</a>
                      	</li>
                      	
                      {/foreach}
                   
                    </ul>
                    <!-- Tab panes -->
                   
                    <div class="tab-content ms-tab-menu-right">
                    	{foreach $menu_tab as $menu_tabs}
                      	<div class="tab-pane " id="tab_{$menu_tabs@key}" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
	                        {foreach $menu_tabs as $menu_tabss}
	                          <li class= {$menu_tabss.css}>
	                            <a href="index.html">{$menu_tabss.title}</a>
	                          </li>
	                         {/foreach}
                          
                        </ul>
                      </div>
                      	{/foreach}
              
                    </div>
                   
                  </li>
                </ul>
              </li>
              
           <!--  menu loại 2 -->
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="page">Pages
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                {foreach $menudrop as $menudrops}
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="dropdown-item has_children">{$menudrops.title}</a>
                    {foreach $menu_drop as $menu_drops}
                    {if $menudrops@key == $menu_drops@key}
                    <ul class="dropdown-menu dropdown-menu-left">
                    	{foreach $menu_drops as $menu_dropss}
                      <li>
                        <a class="dropdown-item" href="/material-style/page-about.html">{$menu_dropss.title}</a>
                      </li>
                      	{/foreach}
                      
                    </ul>
                    {/if}
                    {/foreach}
                  </li>
                  {/foreach}
                  
                  <li>
                    <a class="dropdown-item" href="/material-style/page-all.html" class="dropdown-link">All Pages</a>
                  </li>
                </ul>
              </li>
           <!-- menu loại 3 -->
              <li class="nav-item dropdown dropdown-megamenu-container">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="component">UI Elements
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-megamenu animated fadeIn animated-2x">
                  <li class="container">
                    <div class="row">
                  
                    <div class="col-sm-3 megamenu-col">
                    {foreach $menuanimate as $menuanimates}
                    	{foreach $menu_animate as $menu_animates}
                            {if $menuanimates@key == $menu_animates@key and $menuanimates@key <2}	

                        <div class="megamenu-block animated fadeInLeft animated-2x">
                        	
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-bold"></i> {$menuanimates.title}</h3>
                            
                          <ul class="megamenu-block-list">
                          		{foreach $menu_animates as $menu_animatess}
                            <li>
                              <a class="withripple" href="/material-style/component-typography.html">
                                <i class="fa fa-font"></i> {$menu_animatess.title}</a>
                            </li>
                          		{/foreach}
                          </ul>    	
                        </div>
                        
							{/if}
                        {/foreach}
	
                    {/foreach}
                     </div>
                     
                     <div class="col-sm-3 megamenu-col">
                    {foreach $menuanimate as $menuanimates}
                    	{foreach $menu_animate as $menu_animates}
                            {if $menuanimates@key == $menu_animates@key and $menuanimates@key ==2}	

                        <div class="megamenu-block animated fadeInLeft animated-2x">
                        	
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-bold"></i> {$menuanimates.title}</h3>
                            
                          <ul class="megamenu-block-list">
                          		{foreach $menu_animates as $menu_animatess}
                            <li>
                              <a class="withripple" href="/material-style/component-typography.html">
                                <i class="fa fa-font"></i> {$menu_animatess.title}</a>
                            </li>
                          		{/foreach}
                          </ul>    	
                        </div>
                        
							{/if}
                        {/foreach}
	
                    {/foreach}
                     </div>
                     
                     <div class="col-sm-3 megamenu-col">
                    {foreach $menuanimate as $menuanimates}
                    	{foreach $menu_animate as $menu_animates}
                            {if $menuanimates@key == $menu_animates@key and $menuanimates@key ==3 or $menuanimates@key==4}	

                        <div class="megamenu-block animated fadeInLeft animated-2x">
                        	
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-bold"></i> {$menuanimates.title}</h3>
                            
                          <ul class="megamenu-block-list">
                          		{foreach $menu_animates as $menu_animatess}
                            <li>
                              <a class="withripple" href="/material-style/component-typography.html">
                                <i class="fa fa-font"></i> {$menu_animatess.title}</a>
                            </li>
                          		{/foreach}
                          </ul>    	
                        </div>
                        
							{/if}
                        {/foreach}
	
                    {/foreach}
                     </div>
                     
                      <div class="col-sm-3 megamenu-col">
                    {foreach $menuanimate as $menuanimates}
                    	{foreach $menu_animate as $menu_animates}
                            {if $menuanimates@key == $menu_animates@key and $menuanimates@key >2}	

                        <div class="megamenu-block animated fadeInLeft animated-2x">
                        	
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-bold"></i> {$menuanimates.title}</h3>
                            
                          <ul class="megamenu-block-list">
                          		{foreach $menu_animates as $menu_animatess}
                            <li>
                              <a class="withripple" href="/material-style/component-typography.html">
                                <i class="fa fa-font"></i> {$menu_animatess.title}</a>
                            </li>
                          		{/foreach}
                          </ul>    	
                        </div>
                        
							{/if}
                        {/foreach}
	
                    {/foreach}
                     </div>

                     
                     </div>
                  </li>
                </ul>
              </li>
              
             <!--  menu loai con lai -->
              {foreach $title_menu_rest as $title_menu_rests}
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="blog">{$title_menu_rests}
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                {foreach $menu_rest as $menu_rests}
                {if $title_menu_rests@key == $menu_rests@key}
                <ul class="dropdown-menu">
                	{foreach $menu_rests as $menu_restss}
                  <li>
                    <a class="dropdown-item" href="blog-sidebar.html">
                      <i class="zmdi zmdi-view-compact"></i> {$menu_restss.title}</a>
                  </li>
                  	{/foreach}
                 
                </ul>
                {/if}
                 {/foreach}
              </li>
              {/foreach}
              
            </ul>
          </div>
          <a href="javascript:void(0)" class="ms-toggle-left btn-navbar-menu">
            <i class="zmdi zmdi-menu"></i>
          </a>
        </div>
        <!-- container -->
      </nav>