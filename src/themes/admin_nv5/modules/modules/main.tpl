<div id="list_mods">
    <div class="text-center">
        <i class="fas fa-spinner fa-pulse fa-2x"></i>
    </div>
</div>
<!-- START FORFOOTER -->
<div id="modal-reinstall-module" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-primary">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">{$LANG->get('reinstall_module')}</h3>
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="load text-center d-none">
                    <i class="fas fa-spinner fa-pulse fa-2x"></i>
                </div>
                <div class="content d-none">
                    <p class="text-info message"></p>
                    <div class="showoption">
                        <div class="form-group row">
                            <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_user_name">{$LANG->get('reinstall_option')}</label>
                            <div class="col-12 col-sm-8 col-lg-6">
                                <select class="form-control form-control-sm option">
                                    <option value="0">{$LANG->get('reinstall_option_0')}</option>
                                    <option value="1">{$LANG->get('reinstall_option_1')}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit">{$LANG->get('submit')}</button>
                <button type="button" data-dismiss="modal" class="btn btn-secondary">{$LANG->get('cancel')}</button>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<script type="text/javascript">
nv_show_list_mods();
</script>
