  <div class="card card-primary animated fadeInUp animation-delay-7">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="zmdi zmdi-apps"></i> {$title1}</h3>
              </div>
              <div class="card-tabs">
                <ul class="nav nav-tabs nav-tabs-transparent indicator-primary nav-tabs-full nav-tabs-4" role="tablist">
                  <li class="nav-item">
                    <a href="#favorite" aria-controls="favorite" role="tab" data-toggle="tab" class="nav-link withoutripple active">
                      <i class="no-mr zmdi zmdi-star"></i>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#categories" aria-controls="categories" role="tab" data-toggle="tab" class="nav-link withoutripple">
                      <i class="no-mr zmdi zmdi-folder"></i>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#archives" aria-controls="archives" role="tab" data-toggle="tab" class="nav-link withoutripple">
                      <i class="no-mr zmdi zmdi-time"></i>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#tags" aria-controls="tags" role="tab" data-toggle="tab" class="nav-link withoutripple">
                      <i class="no-mr zmdi zmdi-tag-more"></i>
                    </a>
                  </li>
                </ul>
              </div>
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active show" id="favorite">
                  <div class="card-body">
                    <div class="ms-media-list">
                    
                   {foreach $row as $ra}
                      <div class="media mb-2">
                        <div class="media-left media-middle">
                          <a href="{$ra.link}">
                            <img class="d-flex mr-3 media-object media-object-circle" src="/assets/news/{$ra.homeimgfile}" alt="..."> </a>
                        </div>
                        <div class="media-body">
                          <a href="{$ra.link}" class="media-heading">{$ra.title}</a>
                          <div class="media-footer text-small">
                            <span class="mr-1">
                              <i class="zmdi zmdi-time color-info mr-05"></i> {$ra.publtime|date_format}</span>
                            {if $ra.id%3==0}
                    		<span class ="mr-1"> <i class="zmdi zmdi-folder-outline color-warning-light"></i> <a href="{$ra.link1}">{$ra.cate}</a>
                    		</span>
                              {elseif $ra.id%3==1}
		                    <span> <i class="zmdi zmdi-folder-outline color-danger mr-05"></i> <a href="{$ra.link1}">{$ra.cate}</a>
		                    </span>
		                    {else}
		                    <span> <i class="zmdi zmdi-folder-outline color-royal-light mr-05"></i> <a href="{$ra.link1}">{$ra.cate}</a>
		                    </span>
		                    {/if}
                          </div>
                        </div>
                      </div>
                    {/foreach}
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="categories">
                  <div class="list-group">
                  {foreach $cates as $cate}
                  	 {if $cate@key%5==0}
                    <a href="{$cate.link}" class="list-group-item list-group-item-action withripple">
                      <i class=" color-info zmdi zmdi-cocktail"></i>{$cate.title}
                      <span class="ml-auto badge-pill badge-pill-info">{$cate.numcate}</span>
                    </a>
                    {elseif $cate@key%5==1}
                    <a href="{$cate.link}" class="list-group-item list-group-item-action withripple">
                      <i class="  color-warning zmdi zmdi-collection-image"></i>{$cate.title}
                      <span class="ml-auto badge-pill badge-pill-warning">{$cate.numcate}</span>
                    </a>
                    {elseif $cate@key%5==2}
                    <a href="{$cate.link}" class="list-group-item list-group-item-action withripple">
                      <i class="  color-danger zmdi zmdi-case-check"></i>{$cate.title}
                      <span class="ml-auto badge-pill badge-pill-danger">{$cate.numcate}</span>
                    </a>
                    {elseif $cate@key%5==3}
                    <a href="{$cate.link}" class="list-group-item list-group-item-action withripple">
                      <i class="  color-royal zmdi zmdi-folder-star-alt"></i>{$cate.title}
                      <span class="ml-auto badge-pill badge-pill-royal">{$cate.numcate}</span>
                    </a>
                    {else}
                    <a href="{$cate.link}" class="list-group-item list-group-item-action withripple">
                      <i class="  color-success zmdi zmdi-play-circle-outline"></i>{$cate.title}
                      <span class="ml-auto badge-pill badge-pill-success">{$cate.numcate}</span>
                    </a>
                    {/if}
                    
                    
                    {/foreach}
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="archives">
                  <div class="list-group">
                  {foreach $count as $counts}
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action withripple">
                      <i class="zmdi zmdi-calendar"></i>{$counts.c_val}
                      <span class="ml-auto badge-pill">{$counts.c_count}</span>
                    </a>
                   {/foreach}
                    
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tags">
                  <div class="card-body text-center">
                  {foreach $tags as $tag}
                    <a href="/tag/{$tag.alias}" class="ms-tag ms-tag-primary">{$tag.keywords}</a>
                  {/foreach}
                  </div>
                </div>
              </div>
            </div>
