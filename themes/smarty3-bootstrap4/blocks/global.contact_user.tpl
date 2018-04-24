<div class="card animated fadeInUp animation-delay-7">
              <div class="ms-hero-bg-royal ms-hero-img-coffee">
                <h3 class="color-white index-1 text-center no-m pt-4">{$row.full_name}</h3>
                <div class="color-medium index-1 text-center np-m">@{$row.full_name}</div>
                <img src="/assets/contact/{$row.image}" alt="..." class="img-avatar-circle"> </div>
              <div class="card-body pt-4 text-center">
                <h3 class="color-primary">{$others.0.name}</h3>
                <p>{$others.0.value}</p>
                <a href="{$others.1.value}" class="btn-circle btn-circle-raised btn-circle-xs mt-1 mr-1 no-mr-md btn-facebook">
                  <i class="zmdi zmdi-facebook"></i>
                </a>
                <a href="{$others.2.value}" class="btn-circle btn-circle-raised btn-circle-xs mt-1 mr-1 no-mr-md btn-twitter">
                  <i class="zmdi zmdi-twitter"></i>
                </a>
                <a href="{$others.3.value}" class="btn-circle btn-circle-raised btn-circle-xs mt-1 mr-1 no-mr-md btn-instagram">
                  <i class="zmdi zmdi-instagram"></i>
                </a>
              </div>
            </div>