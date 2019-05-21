{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{elseif $DATA.status eq 'notlogin'}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('login_require', $LOGIN_LINK)}</div>
</div>
{elseif empty($DATA.data)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('empty_response')}</div>
</div>
{else}
<div class="card card-table card-footer-nav">
    <div class="card-body">
        <div class="row card-body-search-form pb-3">
            <div class="col-12">
                <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="form-inline">
                    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
                    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                    <input type="hidden" name="mode" value="search">
                    <label>{$LANG->get('search')}: &nbsp; </label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="q" value="{$REQUEST.q}" placeholder="{$LANG->get('search_key')}...">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <tbody>
                {foreach from=$ARRAY_ITEMS item=row}
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
                                <div class="float-left mr-2">
                                    <div class="img-thumbnail ext-thumb">
                                        <div style="background-image: url('{$row.image_small}');">
                                            <img src="{$row.image_small}" width="100" alt="{$row.title}">
                                        </div>
                                    </div>
                                </div>
                                <h4 class="mt-0">{$row.title}</h4>
                                <p class="mb-1">{$row.introtext}</p>
                                <p class="{$row.compatible_class} mb-0">{$row.compatible_title}</p>
                            </div>
                            <div class="col-4 col-sm-6 col-md-3 col-lg-2 col-xl-2 mt-2 mt-md-0">
                                <p class="mb-1">{$LANG->get('author')}: <span class="text-primary">{$row.username}</span></p>
                                <p class="mb-0">{$LANG->get('ext_type')}: <span class="text-primary">{$row.type}</span></p>
                            </div>
                            <div class="col-8 col-sm-6 col-md-3 col-lg-2 col-xl-2 mt-2 mt-md-0">
                                <div class="ext-rating mb-1">
                                    {for $key=1 to 5}
                                    <span class="star{if $key <= $row.rating_avg} active{/if}"></span>
                                    {/for}
                                </div>
                                <a class="btn btn-space btn-secondary ex-detail" title="{$row.detail_title}" href="{$row.detail_link}"><i class="icon icon-left fas fa-share-square"></i> {$LANG->get('detail')}</a>
                                {if not empty($row.compatible) and $EXTENSION_SETUP}
                                <a class="btn btn-space btn-secondary" href="{$row.install_link}"><i class="icon icon-left fas fa-download"></i> {$LANG->get('install')}</a>
                                {/if}
                            </div>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    {if not empty($GENERATE_PAGE)}
    <div class="card-footer">
        <nav class="page-nav">
            {$GENERATE_PAGE}
        </nav>
    </div>
    {/if}
</div>

<div id="md-ext-detail" tabindex="-1" role="dialog" class="modal colored-header colored-header-primary">
    <div class="modal-dialog full-width">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title"></h3>
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button>
            </div>
            <div class="ext-detail-content"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.ex-detail').click(function(e) {
        e.preventDefault();
        $('#md-ext-detail').data('urlext', $(this).attr('href'));
        $('#md-ext-detail h3.modal-title').html($(this).attr('title'));
        $('#md-ext-detail .ext-detail-content').html('<div class="text-center p-4"><i class="fas fa-spinner fa-pulse fa-3x"></i></div>');
        $('#md-ext-detail').modal('show');
    });
});
</script>
{/if}
