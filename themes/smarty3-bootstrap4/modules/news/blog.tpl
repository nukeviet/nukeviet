<div class="row masonry-container">
{foreach $rows as $row}
    <div class="col-md-6 masonry-item wow fadeInUp animation-delay-2">
        <article class="card card-success mb-4 wow materialUp animation-delay-5">
            <figure class="ms-thumbnail ms-thumbnail-left">
                <img src="{$row.imghome}" alt="" class="">
                <figcaption class="ms-thumbnail-caption text-center">
                    <div class="ms-thumbnail-caption-content">
                        <h3 class="ms-thumbnail-caption-title">Lorem ipsum dolor sit</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <div class="mt-2">
                            <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-circle-sm mr-1 btn-circle-white color-danger"> <i class="zmdi zmdi-favorite"></i>
                            </a> <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-circle-sm ml-1 mr-1 btn-circle-white color-warning"> <i class="zmdi zmdi-star"></i>
                            </a> <a href="javascript:void(0)" class="btn-circle btn-circle-raised btn-circle-sm ml-1 btn-circle-white color-success"> <i class="zmdi zmdi-share"></i>
                            </a>
                        </div>
                    </div>
                </figcaption>
            </figure>
            <div class="card-body">
                <h2>
                    <a href={$row.link}>{$row.title}</a>
                    
                </h2>
                <p>{$row.hometext}</p>
                <div class="row">
                    <div class="col-lg-6 col-md-4">
                        <div class="mt-05">
                            <a href="javascript:void(0)" class="ms-tag ms-tag-success">Multimedia</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8">
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-block animate-icon">Read more <i class="ml-1 no-mr zmdi zmdi-long-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </div>
 {/foreach}    
</div>