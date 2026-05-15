/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
    const showAdvSearch = function() {
        const data = $('#search_query_mod').find('option:selected').data();
        if (data.adv === false) {
            $('a.advSearch').hide();
        } else {
            $('a.advSearch').show();
        }
    };

    showAdvSearch();

    $('#form_search [type=submit]').on('click', function(e) {
        e.preventDefault();

        const form = $(this).parents('form');
        let url = form.attr('action');
        let query = trim(strip_tags($('[name=q]', form).val()).replace(/['"<>\\]/g, ''));
        const mod = $('[name=m]', form).val();
        const lg = parseInt($('[name=l]:checked', form).val());
        const min = parseInt($('[name=q]', form).data('minlength'));
        const max = parseInt($('[name=q]', form).attr('maxlength'));

        form.on('submit', function(ev) { ev.preventDefault(); });
        $('[name=q]', form).val(query);

        if (!query.length || min > query.length || max < query.length) {
            $('[name=q]', form).focus();
            return false;
        }

        query = 'q=' + rawurlencode(query);
        if (mod !== '' && mod !== 'all') {
            query += '&m=' + rawurlencode(mod);
        }
        if (lg !== 1) {
            query += '&l=0';
        }
        url = url + ((url.indexOf('?') > -1) ? '&' : '?') + query;
        window.location.href = url;
    });

    $('#form_search [name=q]').on('input', function() {
        $(this).val($(this).val().replace(/['"<>\\]/gi, ''));
    });

    $('a.advSearch').on('click', function(e) {
        e.preventDefault();

        const form = $(this).parents('form');
        const b = $('[name=m]', form).val();
        let query = trim(strip_tags($('[name=q]', form).val()).replace(/['"<>\\]/gi, ''));
        const min = parseInt($('[name=q]', form).data('minlength'));
        const max = parseInt($('[name=q]', form).attr('maxlength'));

        if (b === 'all') {
            alert($('[name=m]', form).data('alert'));
            $('[name=m]', form).focus();
            return false;
        }

        $('[name=q]', form).val(query);

        if (!query.length || min > query.length || max < query.length) {
            $('[name=q]', form).focus();
            return false;
        }

        let url = $('[name=m] option:selected', form).data('url');
        url = url + ((url.indexOf('?') > -1) ? '&' : '?') + 'q=' + rawurlencode(query);
        window.location.href = url;
    });

    $('#search_query_mod').on('change', function() {
        showAdvSearch();
    });

    $('a.IntSearch').on('click', function(e) {
        e.preventDefault();
        $('i', this).toggleClass('fa-eye fa-eye-slash');
        $('#search-form, #gcse, #search_result').toggleClass('d-none');
    });
});
