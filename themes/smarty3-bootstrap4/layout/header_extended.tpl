<div id="ms-preload" class="ms-preload">
      <div id="status">
        <div class="spinner">
          <div class="dot1"></div>
          <div class="dot2"></div>
        </div>
      </div>
    </div>
    <div class="ms-site-container">
      <!-- Modal -->
      <div class="modal modal-primary" id="ms-account-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog animated zoomIn animated-3x" role="document">
          <div class="modal-content">
            <div class="modal-header d-block shadow-2dp no-pb">
              <button type="button" class="close d-inline pull-right mt-2" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                  <i class="zmdi zmdi-close"></i>
                </span>
              </button>
              <div class="modal-title text-center">
                <span class="ms-logo ms-logo-white ms-logo-sm mr-1">M</span>
                <h3 class="no-m ms-site-title">Material
                  <span>Style</span>
                </h3>
              </div>
              <div class="modal-header-tabs">
                <ul class="nav nav-tabs nav-tabs-full nav-tabs-3 nav-tabs-primary" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a href="#ms-login-tab" aria-controls="ms-login-tab" role="tab" data-toggle="tab" class="nav-link active withoutripple">
                      <i class="zmdi zmdi-account"></i> Login</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a href="#ms-register-tab" aria-controls="ms-register-tab" role="tab" data-toggle="tab" class="nav-link withoutripple">
                      <i class="zmdi zmdi-account-add"></i> Register</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a href="#ms-recovery-tab" aria-controls="ms-recovery-tab" role="tab" data-toggle="tab" class="nav-link withoutripple">
                      <i class="zmdi zmdi-key"></i> Recovery Pass</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="modal-body">
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active show" id="ms-login-tab">
                  <form autocomplete="off">
                    <fieldset>
                      <div class="form-group label-floating">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <i class="zmdi zmdi-account"></i>
                          </span>
                          <label class="control-label" for="ms-form-user">Username</label>
                          <input type="text" id="ms-form-user" class="form-control"> </div>
                      </div>
                      <div class="form-group label-floating">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <i class="zmdi zmdi-lock"></i>
                          </span>
                          <label class="control-label" for="ms-form-pass">Password</label>
                          <input type="password" id="ms-form-pass" class="form-control"> </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-md-6">
                          <div class="form-group no-mt">
                            <div class="checkbox">
                              <label>
                                <input type="checkbox"> Remember Me </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <button class="btn btn-raised btn-primary pull-right">Login</button>
                        </div>
                      </div>
                    </fieldset>
                  </form>
                  <div class="text-center">
                    <h3>Login with</h3>
                    <a href="javascript:void(0)" class="wave-effect-light btn btn-raised btn-facebook">
                      <i class="zmdi zmdi-facebook"></i> Facebook</a>
                    <a href="javascript:void(0)" class="wave-effect-light btn btn-raised btn-twitter">
                      <i class="zmdi zmdi-twitter"></i> Twitter</a>
                    <a href="javascript:void(0)" class="wave-effect-light btn btn-raised btn-google">
                      <i class="zmdi zmdi-google"></i> Google</a>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="ms-register-tab">
                  <form>
                    <fieldset>
                      <div class="form-group label-floating">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <i class="zmdi zmdi-account"></i>
                          </span>
                          <label class="control-label" for="ms-form-user-r">Username</label>
                          <input type="text" id="ms-form-user-r" class="form-control"> </div>
                      </div>
                      <div class="form-group label-floating">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <i class="zmdi zmdi-email"></i>
                          </span>
                          <label class="control-label" for="ms-form-email-r">Email</label>
                          <input type="email" id="ms-form-email-r" class="form-control"> </div>
                      </div>
                      <div class="form-group label-floating">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <i class="zmdi zmdi-lock"></i>
                          </span>
                          <label class="control-label" for="ms-form-pass-r">Password</label>
                          <input type="password" id="ms-form-pass-r" class="form-control"> </div>
                      </div>
                      <div class="form-group label-floating">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <i class="zmdi zmdi-lock"></i>
                          </span>
                          <label class="control-label" for="ms-form-pass-rn">Re-type Password</label>
                          <input type="password" id="ms-form-pass-rn" class="form-control"> </div>
                      </div>
                      <button class="btn btn-raised btn-block btn-primary">Register Now</button>
                    </fieldset>
                  </form>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="ms-recovery-tab">
                  <fieldset>
                    <div class="form-group label-floating">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="zmdi zmdi-account"></i>
                        </span>
                        <label class="control-label" for="ms-form-user-re">Username</label>
                        <input type="text" id="ms-form-user-re" class="form-control"> </div>
                    </div>
                    <div class="form-group label-floating">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="zmdi zmdi-email"></i>
                        </span>
                        <label class="control-label" for="ms-form-email-re">Email</label>
                        <input type="email" id="ms-form-email-re" class="form-control"> </div>
                    </div>
                    <button class="btn btn-raised btn-block btn-primary">Send Password</button>
                  </fieldset>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <header class="ms-header ms-header-primary">
        <!--ms-header-primary-->
        <div class="container container-full">
          <div class="ms-title">
            <a href="material-style/index.html">
              <!-- <img src="/uploads/demo/logo-header.png" alt=""> -->
              <span class="ms-logo animated zoomInDown animation-delay-5">M</span>
              <h1 class="animated fadeInRight animation-delay-6">Material
                <span>Style</span>
              </h1>
            </a>
          </div>
          <div class="header-right">
            <div class="share-menu">
              <ul class="share-menu-list">
                <li class="animated fadeInRight animation-delay-3">
                  <a href="javascript:void(0)" class="btn-circle btn-google">
                    <i class="zmdi zmdi-google"></i>
                  </a>
                </li>
                <li class="animated fadeInRight animation-delay-2">
                  <a href="javascript:void(0)" class="btn-circle btn-facebook">
                    <i class="zmdi zmdi-facebook"></i>
                  </a>
                </li>
                <li class="animated fadeInRight animation-delay-1">
                  <a href="javascript:void(0)" class="btn-circle btn-twitter">
                    <i class="zmdi zmdi-twitter"></i>
                  </a>
                </li>
              </ul>
              <a href="javascript:void(0)" class="btn-circle btn-circle-primary animated zoomInDown animation-delay-7">
                <i class="zmdi zmdi-share"></i>
              </a>
            </div>
            <a href="javascript:void(0)" class="btn-circle btn-circle-primary no-focus animated zoomInDown animation-delay-8" data-toggle="modal" data-target="#ms-account-modal">
              <i class="zmdi zmdi-account"></i>
            </a>
            <form class="search-form animated zoomInDown animation-delay-9">
              <input id="search-box" type="text" class="search-input" placeholder="Search..." name="q" />
              <label for="search-box">
                <i class="zmdi zmdi-search"></i>
              </label>
            </form>
            <a href="javascript:void(0)" class="btn-ms-menu btn-circle btn-circle-primary ms-toggle-left animated zoomInDown animation-delay-10">
              <i class="zmdi zmdi-menu"></i>
            </a>
          </div>
        </div>
      </header>
      <nav class="navbar navbar-expand-md  navbar-static ms-navbar ms-navbar-primary">
        <div class="container container-full">
          <div class="navbar-header">
            <a class="navbar-brand" href="material-style/index.html">
              <!-- <img src="/uploads/demo/logo-navbar.png" alt=""> -->
              <span class="ms-logo ms-logo-sm">M</span>
              <span class="ms-title">Material
                <strong>Style</strong>
              </span>
            </a>
          </div>
          <div class="collapse navbar-collapse" id="ms-navbar">
            <ul class="navbar-nav">
              <li class="nav-item dropdown active">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="home">Home
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="ms-tab-menu">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs ms-tab-menu-left" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#tab-general" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-home"></i> General Purpose</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-landing" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-desktop-windows"></i> Landing pages</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-revolution" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-panorama-horizontal"></i> Slider Revolution</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-shop" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-store"></i> Shop</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-profile" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-account"></i> Professional Profile</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-blog" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-edit"></i> Blog Template</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-magazine" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-flip"></i> Magazine Template</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-app" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-smartphone-iphone"></i> App Pages</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-ads" data-hover="tab" data-toggle="tab" role="tab">
                          <i class="zmdi zmdi-search"></i> Classified Ads</a>
                      </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content ms-tab-menu-right">
                      <div class="tab-pane active" id="tab-general" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="index.html">Default Home</a>
                          </li>
                          <li>
                            <a href="material-style/home-generic-2.html">Home Black Slider</a>
                          </li>
                          <li>
                            <a href="material-style/home-generic-3.html">Home Browsers Intro</a>
                          </li>
                          <li>
                            <a href="material-style/home-generic-4.html">Home Mobile Intro</a>
                          </li>
                          <li>
                            <a href="material-style/home-generic-5.html">Home Material Icons</a>
                          </li>
                          <li>
                            <a href="material-style/home-generic-6.html">Home Typed Hero</a>
                          </li>
                          <li>
                            <a href="material-style/home-generic-7.html">Home Typed Hero 2</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-landing" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-landing.html">Home Landing Intro</a>
                          </li>
                          <li>
                            <a href="material-style/home-landing2.html">Home Landing Intro 2</a>
                          </li>
                          <li>
                            <a href="material-style/home-landing4.html">Home Landing Intro 3</a>
                          </li>
                          <li>
                            <a href="material-style/home-landing3.html">Home Landing Video</a>
                          </li>
                          <li>
                            <a href="material-style/home-cv3.html">Home Profile Landing 1</a>
                          </li>
                          <li>
                            <a href="material-style/home-cv4.html">Home Profile Landing 2</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Landing Video 2 (Next Update)</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-revolution" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a class="with-badge" href="material-style/home-revolution.html">Home Revolution Devices
                              <span class="badge badge-success pull-right">1.3</span>
                            </a>
                          </li>
                          <li>
                            <a class="with-badge" href="material-style/home-revolution2.html">Home Revolution App
                              <span class="badge badge-success pull-right">1.3</span>
                            </a>
                          </li>
                          <li>
                            <a class="with-badge" href="material-style/home-revolution3.html">Home Revolution Video
                              <span class="badge badge-success pull-right">1.3</span>
                            </a>
                          </li>
                          <li>
                            <a class="with-badge" href="material-style/home-revolution4.html">Home Revolution Idea
                              <span class="badge badge-success pull-right">1.3</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-shop" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-shop.html">Home Shop 1</a>
                          </li>
                          <li>
                            <a href="material-style/home-shop2.html">Home Shop 2</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Home Shop 3 (Next Update)</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Home Shop 4 (Next Update)</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-profile" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-cv.html">Home Profile 1</a>
                          </li>
                          <li>
                            <a href="material-style/home-cv2.html">Home Profile 2</a>
                          </li>
                          <li>
                            <a href="material-style/home-cv3.html">Home Profile Landing 1</a>
                          </li>
                          <li>
                            <a href="material-style/home-cv4.html">Home Profile Landing 2</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-blog" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-blog.html">Home Blog 1</a>
                          </li>
                          <li>
                            <a href="material-style/home-blog2.html">Home Blog 2</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Home Blog 3 (Next Update)</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Home Blog 4 (Next Update)</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-magazine" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-magazine.html">Home Magazine 1</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Magazine 2 (Next Update)</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Magazine 3 (Next Update)</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Magazine 4 (Next Update)</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-app" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-app.html">Home App 1</a>
                          </li>
                          <li>
                            <a href="material-style/home-app2.html">Home App 2</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Home App 3 (Next Update)</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Home App 4 (Next Update)</a>
                          </li>
                        </ul>
                      </div>
                      <div class="tab-pane" id="tab-ads" role="tabpanel">
                        <ul class="ms-tab-menu-right-container">
                          <li>
                            <a href="material-style/home-class.html">Home Classifieds 1</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Classifieds 2 (Next Update)</a>
                          </li>
                          <li class="disable">
                            <a href="javascript:void(0)">Classifieds 3 (Next Update)</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="page">Pages
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="dropdown-item has_children">About us &amp; Team</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-about.html">About us Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-about2.html">About us Option 2</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-about3.html">About us Option 3</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-about4.html">About us Option 4</a>
                      </li>
                      <li class="dropdown-divider"></li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-team.html">Our Team Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-team2.html">Our Team Option 2</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Form</a>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="material-style/page-contact.html">Contact Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-contact2.html">Contact Option 2</a>
                      </li>
                      <li class="dropdown-divider"></li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-login_register.html">Login &amp; Register</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-login.html">Login Full</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-login2.html">Login Integrated</a>
                      </li>
                      <li class="dropdown-divider"></li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-login_register2.html">Register Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-register2.html">Register Option 2</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Profiles</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-profile.html">User Profile Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-profile2.html">User Profile Option 2</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Error</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-404.html">Error 404 Full Page</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-404_2.html">Error 404 Integrated</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-500.html">Error 500 Full Page</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-500_2.html">Error 500 Integrated</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Bussiness &amp; Products</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-testimonial.html">Testimonials</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-clients.html">Our Clients</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-product.html">Products</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-services.html">Services</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Pricing</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-pricing.html">Pricing Box</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-pricing2.html">Pricing Box 2</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-princing_table.html">Pricing Mega Table</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">FAQ &amp; Support</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-support.html">Support Center</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-faq.html">FAQ Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-faq2.html">FAQ Option 2</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Coming Soon</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-coming.html">Coming Soon Option 1</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-coming2.html">Coming Soon Option 2</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-coming3.html">Coming Soon Option 3</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Timeline</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item" href="material-style/page-timeline_left.html">Timeline Left</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-timeline_left2.html">Timeline Left 2</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="material-style/page-timeline.html">Timeline Center</a>
                      </li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a href="javascript:void(0)" class="has_children dropdown-item">Email Templates</a>
                    <ul class="dropdown-menu dropdown-menu-left">
                      <li>
                        <a class="dropdown-item with-badge" href="material-style/page-email.html">Email Template 1
                          <span class="badge badge-success text-right">1.2</span>
                        </a>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item with-badge" href="material-style/page-email2.html">Email Template 2
                          <span class="badge badge-success text-right">1.2</span>
                        </a>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/page-all.html" class="dropdown-link">All Pages</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown dropdown-megamenu-container">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="component">UI Elements
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-megamenu animated fadeIn animated-2x">
                  <li class="container">
                    <div class="row">
                      <div class="col-sm-3 megamenu-col">
                        <div class="megamenu-block animated fadeInLeft animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-bold"></i> Bootstrap CSS</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-typography.html">
                                <i class="fa fa-font"></i> Typography</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-headers.html">
                                <i class="fa fa-header"></i> Headers</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-dividers.html">
                                <i class="fa fa-arrows-h"></i> Dividers</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-blockquotes.html">
                                <i class="fa fa-quote-right"></i> Blockquotes</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-forms.html">
                                <i class="fa fa-check-square-o"></i> Forms
                                <span class="badge badge-info pull-right">
                                  <i class="zmdi zmdi-long-arrow-up no-mr"></i> 1.5</span>
                              </a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-slider.html">
                                <i class="fa fa-sliders"></i> Sliders
                                <span class="badge badge-success pull-right">2.3</span>
                              </a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-tables.html">
                                <i class="fa fa-table"></i> Tables</a>
                            </li>
                          </ul>
                        </div>
                        <div class="megamenu-block animated fadeInLeft animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-hand-o-up"></i> Buttons</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-basic-buttons.html">
                                <i class="fa fa-arrow-circle-right"></i> Basic Buttons</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-buttons-components.html">
                                <i class="fa fa-arrow-circle-right"></i> Buttons Components</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-social-buttons.html">
                                <i class="fa fa-arrow-circle-right"></i> Social Buttons
                                <span class="badge badge-info pull-right">
                                  <i class="zmdi zmdi-long-arrow-up no-mr"></i> 1.3</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="col-sm-3 megamenu-col">
                        <div class="megamenu-block animated fadeInLeft animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-list-alt"></i> Basic Components</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-panels.html">
                                <i class="zmdi zmdi-view-agenda"></i> Panels</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-alerts.html">
                                <i class="zmdi zmdi-info"></i> Alerts &amp; Wells</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-badges.html">
                                <i class="zmdi zmdi-tag"></i> Badges &amp; Badges Pills</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-lists.html">
                                <i class="zmdi zmdi-view-list"></i> Lists</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-thumbnails.html">
                                <i class="zmdi zmdi-image-o"></i> Thumbnails</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-carousels.html">
                                <i class="zmdi zmdi-view-carousel"></i> Carousels</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-modals.html">
                                <i class="zmdi zmdi-window-maximize"></i> Modals</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-tooltip.html">
                                <i class="zmdi zmdi-pin-help"></i> Tooltip &amp; Popover</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-progress-bars.html">
                                <i class="zmdi zmdi-view-headline"></i> Progress Bars</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-pagination.html">
                                <i class="zmdi zmdi-n-2-square"></i> Pagination</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-breadcrumb.html">
                                <i class="zmdi zmdi-label-alt-outline"></i> Breadcrumb
                                <span class="badge badge-success pull-right">2.2</span>
                              </a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-dropdowns.html">
                                <i class="fa fa-info"></i> Dropdowns</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="col-sm-3 megamenu-col">
                        <div class="megamenu-block animated fadeInRight animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="zmdi zmdi-folder-star-alt"></i> Extra Components</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-cards.html">
                                <i class="zmdi zmdi-card-membership"></i> Cards</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-composite-cards.html">
                                <i class="zmdi zmdi-card-giftcard"></i> Composite Cards</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-counters.html">
                                <i class="zmdi zmdi-n-6-square"></i> Counters</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-audio-video.html">
                                <i class="zmdi zmdi-play-circle"></i> Audio &amp; Video
                                <span class="badge badge-info pull-right">
                                  <i class="zmdi zmdi-long-arrow-up no-mr"></i> 2.3</span>
                              </a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-masonry.html">
                                <i class="zmdi zmdi-view-dashboard"></i> Masonry Layer</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-snackbar.html">
                                <i class="zmdi zmdi-notifications-active"></i> SnackBar
                                <span class="badge badge-success pull-right">1.2</span>
                              </a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-lightbox.html">
                                <i class="zmdi zmdi-collection-image-o"></i> Lightbox
                                <span class="badge badge-success pull-right">1.5</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                        <div class="megamenu-block animated fadeInRight animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="zmdi zmdi-tab"></i> Collapses &amp; Tabs</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-collapses.html">
                                <i class="zmdi zmdi-view-day"></i> Collapses</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-horizontal-tabs.html">
                                <i class="zmdi zmdi-tab"></i> Horitzontal Tabs</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-vertical-tabs.html">
                                <i class="zmdi zmdi-menu"></i> Vertical Tabs</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="col-sm-3 megamenu-col">
                        <div class="megamenu-block animated fadeInRight animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-briefcase"></i> Icons</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-icons-basic.html">
                                <i class="fa fa-arrow-circle-right"></i> Basic Icons</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-icons-fontawesome.html">
                                <i class="fa fa-arrow-circle-right"></i> Font Awesome</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-icons-iconic.html">
                                <i class="fa fa-arrow-circle-right"></i> Material Design Iconic</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-icons-glyphicons.html">
                                <i class="fa fa-arrow-circle-right"></i> Glyphicons</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-icons-ionicons.html">
                                <i class="fa fa-arrow-circle-right"></i> Ionicons
                                <span class="badge badge-success pull-right">2.0</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                        <div class="megamenu-block animated fadeInRight animated-2x">
                          <h3 class="megamenu-block-title">
                            <i class="fa fa-area-chart"></i> Charts</h3>
                          <ul class="megamenu-block-list">
                            <li>
                              <a class="withripple" href="material-style/component-charts-circle.html">
                                <i class="zmdi zmdi-chart-donut"></i> Circle Charts</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-charts-bar.html">
                                <i class="fa fa-bar-chart"></i> Bars Charts</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-charts-line.html">
                                <i class="fa fa-line-chart"></i> Line Charts</a>
                            </li>
                            <li>
                              <a class="withripple" href="material-style/component-charts-more.html">
                                <i class="fa fa-pie-chart"></i> More Charts</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="blog">Blog
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="material-style/blog-sidebar.html">
                      <i class="zmdi zmdi-view-compact"></i> Blog Sidebar 1</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-sidebar2.html">
                      <i class="zmdi zmdi-view-compact"></i> Blog Sidebar 2</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-masonry.html">
                      <i class="zmdi zmdi-view-dashboard"></i> Blog Masonry 1</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-masonry2.html">
                      <i class="zmdi zmdi-view-dashboard"></i> Blog Masonry 2</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-full.html">
                      <i class="zmdi zmdi zmdi-view-stream"></i> Blog Full Page 1</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-full2.html">
                      <i class="zmdi zmdi zmdi-view-stream"></i> Blog Full Page 2</a>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-post.html">
                      <i class="zmdi zmdi-file-text"></i> Blog Post 1</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/blog-post2.html">
                      <i class="zmdi zmdi-file-text"></i> Blog Post 2</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-8" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="portfolio">Portfolio
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-filters_sidebar.html">
                      <i class="zmdi zmdi-view-compact"></i> Portfolio Sidebar Filters</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-filters_topbar.html">
                      <i class="zmdi zmdi-view-agenda"></i> Portfolio Topbar Filters</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-filters_sidebar_fluid.html">
                      <i class="zmdi zmdi-view-compact"></i> Portfolio Sidebar Fluid</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-filters_topbar_fluid.html">
                      <i class="zmdi zmdi-view-agenda"></i> Portfolio Topbar Fluid</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-cards.html">
                      <i class="zmdi zmdi-card-membership"></i> Porfolio Cards</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-masonry.html">
                      <i class="zmdi zmdi-view-dashboard"></i> Porfolio Masonry</a>
                  </li>
                  <li>
                    <a class="dropdown-item with-badge" href="material-style/portfolio-gallery.html">
                      <i class="zmdi zmdi-apps"></i> Picture Gallery
                      <span class="badge badge-success text-right">1.5</span>
                    </a>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-item.html">
                      <i class="zmdi zmdi-collection-item-1"></i> Portfolio Item 1</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/portfolio-item2.html">
                      <i class="zmdi zmdi-collection-item-2"></i> Portfolio Item 2</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-9" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="ecommerce">E-Commerce
                  <i class="zmdi zmdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="material-style/ecommerce-filters.html">E-Commerce Sidebar</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/ecommerce-filters-full.html">E-Commerce Sidebar Full</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/ecommerce-filters-full2.html">E-Commerce Topbar Full</a>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item" href="material-style/ecommerce-item.html">E-Commerce Item</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="material-style/ecommerce-cart.html">E-Commerce Cart</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <a href="javascript:void(0)" class="ms-toggle-left btn-navbar-menu">
            <i class="zmdi zmdi-menu"></i>
          </a>
        </div>
        <!-- container -->
      </nav>