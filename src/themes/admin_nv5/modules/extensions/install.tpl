{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{elseif $REQUEST.mode eq 'getfile'}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('install_getfile_error')}</div>
</div>
{else}
<div class="card">
    <div class="card-body pt-4">
        {if $REQUEST.getfile}
        <p class="text-success">
            <i class="far fa-smile"></i> <strong>{$LANG->get('install_getfile')}</strong> <i class="fas fa-check"></i>
        </p>
        {/if}
        {if empty($DATA.compatible.id)}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_compatible')}</strong>
        </p>
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="far fa-times-circle"></i></div>
            <div class="message">{$LANG->get('install_check_compatible_error')}</div>
        </div>
        {else}
        <p class="text-success">
            <i class="far fa-smile"></i> <strong>{$LANG->get('install_check_compatible')}</strong> <i class="fas fa-check"></i>
        </p>
        {* Kiểm tra ứng dụng bắt buộc *}
        {if not empty($DATA.require)}
        {if isset($REQUIRE_MESSAGE)}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_require')}</strong>
        </p>
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="far fa-times-circle"></i></div>
            <div class="message"><a class="ex-detail" href="{$REQUIRE_LINK}" title="{$REQUIRE_TITLE}">{$REQUIRE_MESSAGE}</a></div>
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
        {else}
        <p class="text-success">
            <i class="far fa-smile"></i> <strong>{$LANG->get('install_check_require')}</strong> <i class="fas fa-check"></i>
        </p>
        {/if}
        {/if}
        {if $ALLOW_CONTINUE}
        {if isset($MANUAL_MESSAGE)}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_auto_install')}</strong>
        </p>
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="far fa-times-circle"></i></div>
            <div class="message">{$MANUAL_MESSAGE}</div>
        </div>
        <div class="clearfix">
            <div class="float-right ml-2 mb-2">
                <a target="_blank" class="btn btn-primary" href="{$DATA.compatible.origin_link}" title="{$LANG->get('download')}">{$LANG->get('download')}</a>
            </div>
            <h4 class="mt-1">{$LANG->get('install_documentation')}</h4>
            <div class="ext-detail-bodyhtml">
                {$DATA.documentation}
            </div>
        </div>
        {else}
        <p class="text-success">
            <i class="far fa-smile"></i> <strong>{$LANG->get('install_check_auto_install')}</strong> <i class="fas fa-check"></i>
        </p>
        {if isset($INSTALLED_MESSAGE)}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_installed')}</strong>
        </p>
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="far fa-times-circle"></i></div>
            <div class="message">{$INSTALLED_MESSAGE}</div>
        </div>
        {else}
        {if $DATA.compatible.status === 'paid'}
        <p class="text-success">
            <i class="far fa-smile"></i> <strong>{$LANG->get('install_check_paid')}</strong> <i class="fas fa-check"></i>
        </p>
        <div id="file-download" class="mb-2">
            <i class="far fa-meh"></i> <strong>{$LANG->get('install_file_download')} <span class="waiting">...</span></strong> <i class="fas fa-check complete"></i>
        </div>
        <div id="file-download-response"></div>
        {if $INSTALLED eq 2}
        <div id="extInstallWarning" role="alert" class="alert alert-warning alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="message">
                <div class="mb-1 mt-1"><strong>{$LANG->get('install_check_installed_unsure')}.</strong></div>
                <button onclick="EXT.startDownload();" type="button" class="btn btn-primary btn-space">{$LANG->get('install_continue')}</button>
                <button onclick="EXT.cancel();" type="button" class="btn btn-secondary btn-space">{$LANG->get('install_cancel')}</button>
            </div>
        </div>
        {else}
        <script type="text/javascript">
        $(document).ready(function() {
            EXT.startDownload();
        });
        </script>
        {/if}
        <script type="text/javascript">
        var LANG = [];
        var CFG = [];
        CFG.id = '{$DATA.tid}';
        CFG.string_data = '{$STRING_DATA}';
        CFG.cancel_link = '{$CANCEL_LINK}';
        LANG.download_ok = '{$LANG->get('download_ok')}';
        </script>
        {elseif $DATA.compatible.status === 'await'}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_paid')}</strong>
        </p>
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="far fa-times-circle"></i></div>
            <div class="message">{$LANG->get('install_check_paid_await')}</div>
        </div>
        {elseif $DATA.compatible.status === 'notlogin'}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_paid')}</strong>
        </p>
        <div role="alert" class="alert alert-danger alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="far fa-times-circle"></i></div>
            <div class="message"><a href="{$LOGIN_LINK}">{$LANG->get('install_check_paid_nologin')}</a></div>
        </div>
        {else}
        <p class="text-danger">
            <i class="far fa-frown"></i> <strong>{$LANG->get('install_check_paid')}</strong>
        </p>
        <div role="alert" class="alert alert-warning alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="message"><a href="{$DATA.compatible.origin_link}">{$LANG->get('install_check_paid_unpaid')}</a></div>
        </div>
        {/if}
        {/if}
        {/if}
        {/if}
        {/if}
    </div>
</div>
{/if}
