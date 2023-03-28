<!-- BEGIN: main -->
<h1 class="hidden">{THEME_PAGE_TITLE}</h1>
<div class="margin-bottom"><span class="h1"><strong>{PAGE_TITLE}</strong></span></div>

<!-- BEGIN: bodytext -->
<p class="margin-bottom">{CONTENT.bodytext}</p>
<!-- END: bodytext -->

<div class="row">
    <div class="col-sm-12 col-md-15">
        <!-- BEGIN: dep -->
        <div class="panel panel-default">
            <!-- BEGIN: header -->
            <a href="{DEP.url}" class="panel-heading" style="display:flex;align-items:center">
                <h2 class="pannel-title" style="flex-grow: 1">{DEP.full_name}</h2>
                <small class="text-dark">{LANG.details} <i class="fa fa-arrow-right fa-fw"></i></small>
            </a>
            <!-- END: header -->
            <!-- BEGIN: dep_header -->
            <div class="panel-heading">
                <h2 class="pannel-title">{LANG.contact_info}</h2>
            </div>
            <!-- END: dep_header -->
            <ul class="list-group">
                <!-- BEGIN: image -->
                <li class="list-group-item">
                    <img src="{DEP.image}" srcset="{DEP.srcset}" class="img-thumbnail" alt="{DEP.full_name}" />
                </li>
                <!-- END: image -->
                <!-- BEGIN: note -->
                <li class="list-group-item">{DEP.note}</li>
                <!-- END: note -->
                <!-- BEGIN: address -->
                <li class="list-group-item">
                    <em class="fa fa-map-marker fa-horizon margin-right"></em>{LANG.address}: <span>{DEP.address}</span>
                </li>
                <!-- END: address -->
                <!-- BEGIN: cd -->
                <li class="list-group-item">
                    <em class="fa {CD.icon} fa-horizon margin-right"></em>{CD.name}:
                    <span>{CD.value}</span>
                </li>
                <!-- END: cd -->
            </ul>
        </div>
        <!-- END: dep -->
    </div>

    <div class="col-sm-12 col-md-9">
        <!-- BEGIN: supporter_block -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>{LANG.supporters}</h3>
            </div>
            <ul class="list-group">
                <!-- BEGIN: supporter -->
                <li class="list-group-item">
                    <div style="display:flex">
                        <div><img src="{SUPPORTER.image}" class="supporter-avatar" alt="" /></div>
                        <div style="flex-grow: 1">
                            <p><strong>{SUPPORTER.full_name}</strong></p>
                            <!-- BEGIN: cd -->
                            <p>
                                <em class="fa {CD.icon} fa-horizon margin-right"></em>{CD.name}:
                                <span>{CD.value}</span>
                            </p>
                            <!-- END: cd -->
                        </div>
                    </div>
                </li>
                <!-- END: supporter -->
            </ul>
        </div>
        <!-- END: supporter_block -->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>{GLANG.feedback}</h3>
            </div>
            <div class="panel-body text-center">
                <p class="margin-bottom-lg">{LANG.feedback_form_note}</p>
                <button class="btn btn-primary btn-lg show-feedback-form">{LANG.feedback_form}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="feedback-form" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title">{LANG.feedback_form}</div>
            </div>
            <div method="post" class="modal-body">
                <div class="loadContactForm">{FORM}</div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->