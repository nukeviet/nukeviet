<div class="card">
    <div class="card-body pt-4">
        {if $REQUEST.mode eq 'getfile'}
        {* Không tìm được phiên bản thích hợp *}
        <div class="stepper d-flex flex-column">
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-danger mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5 class="mb-1">{$LANG->getModule('install_getfile')}</h5>
                    <div class="slead text-danger">{$LANG->getModule('install_getfile_error')}</div>
                </div>
            </div>
        </div>
        {else}
        <div class="stepper d-flex flex-column">
            {if not empty($REQUEST.getfile)}
            {* Đã tìm được phiên bản thích hợp *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-success mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_getfile')}</h5>
                    <p class="text-success slead">{$LANG->getGlobal('success_level')}</p>
                </div>
            </div>
            {/if}
            {if empty($DATA.compatible) or empty($DATA.compatible.id)}
            {* Kiểm tra tương thích thất bại *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-danger mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_compatible')}</h5>
                    <p class="text-danger slead">{$LANG->getModule('install_check_compatible_error')}</p>
                </div>
            </div>
            {else}
            {* Kiểm tra tương thích thành công *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-success mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_compatible')}</h5>
                    <p class="text-success slead">{$LANG->getGlobal('success_level')}</p>
                </div>
            </div>
            {if not $ALLOW_CONTINUE}
            {* Kiểm tra ứng dụng bắt buộc thất bại *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-danger mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_require')}</h5>
                    <p class="text-danger slead">
                        {$LANG->getModule('install_check_require_fail', $DATA.require.title, "{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=detail&amp;id={$DATA.require.id}")}
                    </p>
                </div>
            </div>
            {else}
            {if $HAS_REQUIRE}
            {* Kiểm tra ứng dụng bắt buộc thành công *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-success mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_require')}</h5>
                    <p class="text-success slead">{$LANG->getGlobal('success_level')}</p>
                </div>
            </div>
            {/if}
            {if $DATA.compatible.type neq 1 or not in_array($DATA.tid, [1, 2, 3, 4])}
            {* Phải cài thủ công *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-danger mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_auto_install')}</h5>
                    <div class="slead">
                        <p class="text-danger mb-2">
                            {$LANG->getModule(empty($DATA.documentation) ? 'install_manual_install_danger' : 'install_manual_install')}
                        </p>
                        <div class="mb-2">
                            <a target="_blank" class="btn btn-primary" href="{$DATA.compatible.origin_link}"><i class="fa-solid fa-file-export"></i> {$LANG->getModule('download')}</a>
                        </div>
                        {if not empty($DATA.documentation)}
                        <div>
                            {$DATA.documentation}
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
            {else}
            {* Ứng dụng cài được tự động *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-success mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_auto_install')}</h5>
                    <p class="text-success slead">{$LANG->getGlobal('success_level')}</p>
                </div>
            </div>
            {if $HAS_INSTALLED eq 1}
            {* Ứng dụng đã được cài rồi *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-danger mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_installed')}</h5>
                    <p class="text-danger slead">
                        {$LANG->getModule('install_check_installed_error', "{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}")}
                    </p>
                </div>
            </div>
            {else}
            {* Ứng dụng chưa được cài trước đó *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-success mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_installed')}</h5>
                    <p class="text-success slead">{$LANG->getGlobal('success_level')}</p>
                </div>
            </div>
            {if $DATA.compatible.status eq 'paid'}
            {* Loại ứng dụng không chắc chắn, click để tải về $HAS_INSTALLED eq 2 còn không sẽ tự động download *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-success mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_paid')}</h5>
                    <p class="text-success slead">{$LANG->getModule(empty($DATA.compatible.price) ? 'free' : 'already_paid')}</p>
                </div>
            </div>
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div data-toggle="checkExtIndicate" class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-{$HAS_INSTALLED eq 2 ? 'warning' : 'primary'} mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_file_download')}</h5>
                    <div class="slead">
                        {if $HAS_INSTALLED eq 2}
                        <div data-toggle="checkExtWarning">
                            <p class="text-warning mb-2">
                                {$LANG->getModule('install_check_installed_unsure')}
                            </p>
                            <div>
                                <button data-toggle="checkExtConfirm" type="button" class="btn btn-primary"><i class="fa-solid fa-play"></i> {$LANG->getModule('install_continue')}</button>
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" class="btn btn-secondary"><i class="fa-solid fa-circle-xmark text-muted"></i> {$LANG->getModule('install_cancel')}</a>
                            </div>
                        </div>
                        {else}
                        <div data-toggle="checkExtAutoDownload"></div>
                        {/if}
                        <div data-toggle="checkExtCtnDownload" class="mt-2{if $HAS_INSTALLED eq 2} d-none{/if}" data-jsonencode="{$STRING_DATA}" data-lang-ok="{$LANG->getModule('download_ok')}">
                            <i class="fa-solid fa-spinner fa-spin-pulse"></i> <span>{$LANG->getModule('install_file_downloading')}</span>
                        </div>
                        <div data-toggle="checkExtCtnDownloadRes"></div>
                    </div>
                </div>
            </div>
            {elseif $DATA.compatible.status eq 'await'}
            {* Ứng dụng có phí, đang mua và chưa thanh toán xong *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-warning mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_paid')}</h5>
                    <p class="text-warning slead">
                        {$LANG->getModule('install_check_paid_await')}
                    </p>
                </div>
            </div>
            {elseif $DATA.compatible.status eq 'notlogin'}
            {* Cần đăng nhập để kiểm tra *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-warning mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_paid')}</h5>
                    <p class="text-warning slead">
                        {$LANG->getModule('install_check_paid_nologin', "{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=login&amp;redirect={$REDIRECT}")}
                    </p>
                </div>
            </div>
            {else}
            {* Ứng dụng có phí nhưng chưa mua *}
            <div class="d-flex mb-1">
                <div class="d-flex flex-column pe-3 align-items-center">
                    <div class="rounded-circle indicate d-inline-flex align-items-center justify-content-center text-bg-warning mb-1">
                        <span></span>
                    </div>
                    <div class="line h-100 bg-body-secondary"></div>
                </div>
                <div>
                    <h5>{$LANG->getModule('install_check_paid')}</h5>
                    <p class="text-warning slead">
                        {$LANG->getModule('install_check_paid_unpaid', {$DATA.compatible.origin_link})}
                    </p>
                </div>
            </div>
            {/if}{/if}{/if}{/if}{/if}
        </div>
        {/if}
    </div>
</div>
