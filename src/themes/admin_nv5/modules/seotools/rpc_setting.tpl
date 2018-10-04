{if not $SERVER_SUPPORTED}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">System not support function php "curl_init"!</div>
</div>
{else}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <input name="savecat" type="hidden" value="1" />
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50%;" class="text-nowrap align-middle">{$LANG->get('rpc_linkname')}</th>
                            <th style="width: 50%;" class="text-nowrap align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-0 mr-2">
                                        <label class="custom-control custom-control-sm custom-checkbox m-0">
                                            <input class="custom-control-input" type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'prcservice[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                    <div class="flex-grow-1">{$LANG->get('rpc_ping')}</div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$SERVICES key=key item=service}
                        <tr>
                            <td>
                                <img src="{$IMGPATH}/{if empty($service.3)}link.png{else}{$service.3}{/if}" alt="{$service.1}" /> {$service.1}
                            </td>
                            <td>
                                <label class="custom-control custom-control-sm custom-checkbox m-0">
                                    <input class="custom-control-input" type="checkbox" name="prcservice[]" value="{$service.1}"{if $CHECKOPT1 or in_array($service.1, $PRCSERVICE)} checked="checked"{/if} onclick="nv_UncheckAll(this.form, 'prcservice[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <button class="btn btn-space btn-primary" type="submit" name="submitprcservice">{$LANG->get('submit')}</button>
        </div>
    </div>
</form>
{/if}
