<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-12">
        <div class="list-group">
            <a href="{PAGE_LINK}&amp;type=plaintext" class="list-group-item"><strong>{LANG.plaintext}</strong></a>
            <a href="{PAGE_LINK}&amp;type=textlist" class="list-group-item"><strong>{LANG.textlist}</strong></a>
            <a href="{PAGE_LINK}&amp;type=btnlist" class="list-group-item"><strong>{LANG.btnlist}</strong></a>
            <a href="{PAGE_LINK}&amp;type=request" class="list-group-item"><strong>{LANG.info_request}</strong></a>
        </div>
    </div>
</div>
<!-- END: main -->
<!-- BEGIN: preview -->
<!-- BEGIN: plaintext -->
<div class="panel panel-primary">
    <div class="panel-heading">{PREVIEW.title}</div>
    <div class="panel-body">
        {PREVIEW.content}
    </div>
</div>
<!-- END: plaintext -->
<!-- BEGIN: textlist -->
<div class="panel panel-primary list" style="max-width: 350px;margin-left:auto;margin-right:auto">
    <div class="panel-image image-9-16" <!-- BEGIN: element0_action --> data-toggle="action_open_modal" data-title="{ELEMENT0.default_action_title}" data-content="{ELEMENT0.default_action_content}" style="cursor: pointer;"
        <!-- END: element0_action -->>
        <img class="panel-image" src="{ASSETS_STATIC_URL}/images/pix.svg" alt="" style="background-image: url({ELEMENT0.image_url});" />
    </div>
    <div class="panel-body" <!-- BEGIN: element0_action2 --> data-toggle="action_open_modal" data-title="{ELEMENT0.default_action_title}" data-content="{ELEMENT0.default_action_content}" style="cursor: pointer;"
        <!-- END: element0_action2 -->>
        <p><strong>{ELEMENT0.title}</strong></p>
        <div>{ELEMENT0.subtitle}</div>
    </div>
    <div class="list-group">
        <!-- BEGIN: other -->
        <a href="#" class="list-group-item" data-toggle="action_open_modal" data-title="{OTHER.default_action_title}" data-content="{OTHER.default_action_content}" style="cursor: pointer;">
            <span class="d-flex">
                <span class="flex-shrink-1" style="margin-right:5px; width:70px">
                    <span class="image-3-4">
                        <img class="panel-image" src="{ASSETS_STATIC_URL}/images/pix.svg" alt="" style="background-image: url({OTHER.image_url});" />
                    </span>
                </span>
                <span class="align-self-center" style="width:100%">
                    <strong>{OTHER.title}</strong><!-- BEGIN: subtitle --><br />{OTHER.subtitle}
                    <!-- END: subtitle -->
                </span>
            </span>
        </a>
        <!-- END: other -->
    </div>
</div>
<!-- END: textlist -->
<!-- BEGIN: btnlist -->
<div class="panel panel-primary list">
    <div class="panel-body">
        <h3><strong>{TEXT}</strong></h3>
        <!-- BEGIN: btn -->
        <div class="form-group">
            <button type="button" class="btn btn-default btn-block active" data-toggle="action_open_modal" data-title="{BTN.action_title}" data-content="{BTN.action_content}">{BTN.title}</button>
        </div>
        <!-- END: btn -->
    </div>
</div>
<!-- END: btnlist -->
<!-- END: preview -->