<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card"
             data-tokend="{$CHECKSS}"
             data-msgconfirm="{$LANG->getModule('clear_confirm')}">
            <div class="card-header">
                <h5 class="card-title mb-0">{$LANG->getModule('cleardata')}</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    {if $LANG_MULTI}
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_alllang')}</span>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="alllang" name="alllang" value="1"{if $ALLLANG} checked{/if}>
                        </div>
                    </li>
                    {/if}
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_bot')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="bot">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_browser')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="browser">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_country')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="country">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_os')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="os">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_referer')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="referer">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_hit')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="hit">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{$LANG->getModule('clear_all')}</span>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-toggle="confirm-clear"
                                data-type="all">
                            <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i> {$LANG->getModule('clear_submit')}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        {if $LANG_MULTI}
        <p class="text-muted mt-2">
            <i class="fa-solid fa-circle-info fa-fw"></i>
            <em>{$ALLLANG_MSG}</em>
        </p>
        {/if}
    </div>
</div>
