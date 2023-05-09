<!-- BEGIN: main -->
<div class="page panel panel-default">
    <div class="panel-body">
        <h3 class="text-center margin-bottom-lg">{LANG.info_title}</h3>
        <div id="search-form" class="text-center">
            <form action="{DATA.full_action}" name="form_search" method="get" id="form_search" role="form">
                <div class="m-bottom">
                    <div class="form-group">
                        <label class="sr-only" for="search_query">{LANG.key_title}</label>
                        <input class="form-control" id="search_query" name="q" value="{DATA.key}" maxlength="{NV_MAX_SEARCH_LENGTH}" data-minlength="{NV_MIN_SEARCH_LENGTH}" placeholder="{LANG.key_title}" />
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="search_query_mod">{LANG.type_search}</label>
                        <select name="m" id="search_query_mod" class="form-control" data-alert="{LANG.chooseModule}">
                            <option value="all">{LANG.search_on_site}</option>
                            <!-- BEGIN: select_option -->
                            <option data-adv="{MOD.adv_search}" data-url="{MOD.url}" value="{MOD.value}" {MOD.selected}>{MOD.custom_title}</option>
                            <!-- END: select_option -->
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="{LANG.search_title}" class="btn btn-primary" />
                        <a href="#" class="advSearch">{LANG.search_title_adv}</a>
                    </div>
                </div>
                <div class="radio">
                    <label class="radio-inline"> <input name="l" id="search_logic_and" type="radio" {DATA.andChecked} value="1" /> {LANG.logic_and}</label>
                    <label class="radio-inline"> <input name="l" id="search_logic_or" type="radio" {DATA.orChecked} value="0" /> {LANG.logic_or}</label>
                </div>
            </form>
        </div>
        <!-- BEGIN: search_engine_unique_ID -->
        <script async src="//cse.google.com/cse.js?cx={SEARCH_ENGINE_UNIQUE_ID}"></script>
        <div class="text-center margin-bottom-lg search_adv">
            <a href="#" class="IntSearch"><i class="fa fa-eye" aria-hidden="true"></i> {LANG.search_adv_internet}</a>
        </div>
        <div id="gcse" class="hidden">
            <div class="gcse-search"></div>
        </div>
        <!-- END: search_engine_unique_ID -->
        <div id="search_result">
            <hr />
            {SEARCH_RESULT}
        </div>
    </div>
</div>
<script>
    function show_advSearch() {
        var data = $('#search_query_mod').find('option:selected').data();
        if (data.adv == true) {
            $("a.advSearch").show();
        } else if (data.adv == false) {
            $("a.advSearch").hide();
        } else {
            $("a.advSearch").show();
        }
    }
    $(function() {
        show_advSearch();

        $("#form_search [type=submit]").on('click', function(e) {
            e.preventDefault();

            var form = $(this).parents('form'),
                url = form.attr('action'),
                query = trim(strip_tags($('[name=q]', form).val()).replace(/[\'\"\<\>\\\\]/g,'')),
                mod = $('[name=m]', form).val(),
                lg = parseInt($('[name=l]:checked', form).val()),
                min = parseInt($('[name=q]', form).data('minlength')),
                max = parseInt($('[name=q]', form).attr('maxlength'));

            form.bind('submit',function(e){e.preventDefault();});
            $('[name=q]', form).val(query);
            leng = query.length;
            if (!leng || min > query.length || max < query.length) {
                $('[name=q]', form).focus();
                return !1
            }

            query = 'q=' + rawurlencode(query);
            if (mod != '' && mod != 'all') {
                query += '&m=' + rawurlencode(mod);
            }
            if (lg != 1) {
                query += '&l=0';
            }
            url = url + ((url.indexOf('?') > -1) ? '&' : '?') + query;

            window.location.href = url;
        });

        $("#form_search [name=q]").on('input', function() {
            return $(this).val($(this).val().replace(/[\'\"\<\>\\\\]/gi, ''))
        });

        $("a.advSearch").on('click', function(e) {
            e.preventDefault();

            var form = $(this).parents('form'),
                b = $('[name=m]', form).val(),
                query = trim(strip_tags($('[name=q]', form).val()).replace(/[\'\"\<\>\\\\]/gi, '')),
                min = parseInt($('[name=q]', form).data('minlength')),
                max = parseInt($('[name=q]', form).attr('maxlength')),
                url;
            if ("all" == b) {
                alert($('[name=m]', form).data('alert'));
                $('[name=m]', form).focus();
                return !1
            }

            $('[name=q]', form).val(query);
            leng = query.length;
            if (!leng || min > query.length || max < query.length) {
                $('[name=q]', form).focus();
                return !1
            }

            url = $('[name=m] option:selected', form).data('url');
            url = url + ((url.indexOf('?') > -1) ? '&' : '?') + 'q=' + rawurlencode(query);

            window.location.href = url;
        });

        $('#search_query_mod').on('change', function() {
            show_advSearch();
        });

        $("a.IntSearch").on('click', function(e) {
            e.preventDefault();
            $(".fa", this).toggleClass("fa-eye fa-eye-slash");
            $("#search-form, #gcse, #search_result").toggleClass("hidden")
        });
    });
</script>
<!-- END: main -->