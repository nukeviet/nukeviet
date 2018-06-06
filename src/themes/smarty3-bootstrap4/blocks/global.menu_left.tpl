<!-- ms-site-container -->
    <div class="ms-slidebar sb-slidebar sb-left sb-style-overlay" id="ms-slidebar">
      <div class="sb-slidebar-container">
        <header class="ms-slidebar-header">
          <div class="ms-slidebar-login">
            <a href="javascript:void(0)" class="withripple">
              <i class="zmdi zmdi-account"></i> Login</a>
            <a href="javascript:void(0)" class="withripple">
              <i class="zmdi zmdi-account-add"></i> Register</a>
          </div>
          <div class="ms-slidebar-title">
            <form class="search-form">
              <input id="search-box-slidebar" type="text" class="search-input" placeholder="Search..." name="q" />
              <label for="search-box-slidebar">
                <i class="zmdi zmdi-search"></i>
              </label>
            </form>
            <div class="ms-slidebar-t">
              <span class="ms-logo ms-logo-sm">M</span>
              <h3>Material
                <span>Style</span>
              </h3>
            </div>
          </div>
        </header>
        <ul class="ms-slidebar-menu" id="slidebar-menu" role="tablist" aria-multiselectable="true">
        
        {foreach $title_menu_left as $title_menu_lefts}
        	
          <li class="card" role="tab" id="sch{$title_menu_lefts@key+1}">
            <a class="collapsed" role="button" data-toggle="collapse" href="#sc{$title_menu_lefts@key+1}" aria-expanded="false" aria-controls="sc{$title_menu_lefts@key+1}">
              <i class="zmdi zmdi-home"></i> {$title_menu_lefts} </a>
             {foreach $menu_left as $menu_lefts}
             	{if $title_menu_lefts@key == $menu_lefts@key}
            <ul id="sc{$title_menu_lefts@key+1}" class="card-collapse collapse" role="tabpanel" aria-labelledby="sch{$title_menu_lefts@key+1}" data-parent="#slidebar-menu">            
             
             		{foreach $menu_lefts as $menu_leftss}
	              <li>
	                <a href="{$menu_leftss.link}">{$menu_leftss.title}</a>
	              </li>
	              	{/foreach}
              	
            </ul>
            {/if}
              {/foreach}
            
          </li>
          	
         {/foreach}
         
         {foreach $menuleftanimate as $menuleftanimates}
         	
          <li>
            <a class="link" href="{$menuleftanimates.link}">
              <i class="zmdi zmdi-view-compact"></i>{$menuleftanimates.title}</a>
          </li>
          {/foreach}
          
        </ul>
        <div class="ms-slidebar-social ms-slidebar-block">
          <h4 class="ms-slidebar-block-title">Social Links</h4>
          <div class="ms-slidebar-social">
            <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-facebook">
              <i class="zmdi zmdi-facebook"></i>
              <span class="badge-pill badge-pill-pink">12</span>
              <div class="ripple-container"></div>
            </a>
            <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-twitter">
              <i class="zmdi zmdi-twitter"></i>
              <span class="badge-pill badge-pill-pink">4</span>
              <div class="ripple-container"></div>
            </a>
            <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-google">
              <i class="zmdi zmdi-google"></i>
              <div class="ripple-container"></div>
            </a>
            <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-instagram">
              <i class="zmdi zmdi-instagram"></i>
              <div class="ripple-container"></div>
            </a>
          </div>
        </div>
      </div>
    </div>