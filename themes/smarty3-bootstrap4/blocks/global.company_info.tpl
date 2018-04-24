 <div class="ms-footbar-block">
                <div class="ms-footbar-title">
                  <img src="{$logo.src}" width= "{$logo.width}" height="{$logo.height}" alt="logo">
                </div>
                <address class="no-mb">
                  <p>
                    <i class="color-danger-light zmdi zmdi-pin mr-1"></i>{$row.company_address}</p>
                  <p>
                    <i class="color-warning-light zmdi zmdi-map mr-1"></i>{$row.company_headquarters}7</p>
                  <p>
                    <i class="color-info-light zmdi zmdi-email mr-1"></i>
                    <a href="mailto:joe@example.com">{$row.company_email}</a>
                  </p>
                  <p>
                    <i class="color-royal-light zmdi zmdi-phone mr-1"></i>{$row.company_cellphonenumber}</p>
                  <p>
                    <i class="color-success-light fa fa-fax mr-1"></i>{$row.company_deskphonenumber} </p>
                </address>
              </div>