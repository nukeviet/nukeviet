/**
 * NUKEVIET Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var nv_aryDayName = "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),
    nv_aryDayNS = "Sun Mon Tue Wed Thu Fri Sat".split(" "),
    nv_aryMonth = "January February March April May June July August September October November December".split(" "),
    nv_aryMS = "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),
    nv_admlogout_confirm = ["Are you sure to logout from administrator account?", "The entire login information has been deleted. You have been logout Administrator account successfully"],
    nv_is_del_confirm = ["Are you sure to delete? If you accept,all data will be deleted. You could not restore them", "Delete succesfully", "Delete data faile for some unknown reason"],
    nv_is_change_act_confirm = ["Are you sure to 'change'?", "Be 'Change' successfully", " 'Change' fail"],
    nv_is_empty_confirm = ["Are you sure to  be 'empty'?", "Be 'empty' succesful", "Be 'empty' fail for some unknown reason"],
    nv_is_recreate_confirm = ["Are you sure to 'repair'?", "Be 'Repair' succesfully", "'Repair' fail for some unknown reason"],
    nv_is_add_user_confirm = ["Are you sure to add new member into group?", "'Add' new member into group successfully", " 'Add ' fail for some unknown reason"],
    nv_is_exclude_user_confirm = ["Are you sure to exclude this member?", "'Exclude' member successfully", " 'Exclude  member fail for some unknown reason"],
    nv_formatString = "dd.mm.yyyy",
    nv_gotoString = "Go To Current Month",
    nv_todayString = "Today is",
    nv_weekShortString = "Wk",
    nv_weekString = "calendar week",
    nv_scrollLeftMessage = "Click to scroll to previous month. Hold mouse button to scroll automatically.",
    nv_scrollRightMessage = "Click to scroll to next month. Hold mouse button to scroll automatically.",
    nv_selectMonthMessage = "Click to select a month.",
    nv_selectYearMessage = "Click to select a year.",
    nv_selectDateMessage = "Select [date] as date.",
    nv_loadingText = "Loading...",
    nv_loadingTitle = "Click to cancel",
    nv_focusTitle = "Click to bring to front",
    nv_fullExpandTitle = "Expand to actual size (f)",
    nv_restoreTitle = "Click to close image, click and drag to move. Use arrow keys for next and previous.",
    nv_error_login = "Error: You haven't fill your account or account information incorrect. Account include Latin characters, numbers, underscore. Max characters is [max], min characters is [min]",
    nv_error_password = "Error: You haven't fill your password or password information incorrect. Password include Latin characters, numbers, underscore. Max characters is [max], min characters is [min]",
    nv_error_email = "Error: You haven't fill your email address or email address incorrect.",
    nv_error_seccode = "Error: You haven't fill Security Code or Security Code you fill incorrect. Security Code is a sequense of number [num] characters display in image.",
    nv_login_failed = "Error: For some reasons, system doesn't accept your account. Try again.",
    nv_content_failed = "Error: For some reasons, system doesn't accept content Try again.",
    nv_required = "This field is required.",
    nv_remote = "Please fix this field.",
    nv_email = "Please enter a valid email address.",
    nv_url = "Please enter a valid URL.",
    nv_date = "Please enter a valid date.",
    nv_dateISO = "Please enter a valid date (ISO).",
    nv_number = "Please enter a valid number.",
    nv_digits = "Please enter only digits.",
    nv_creditcard = "Please enter a valid credit card number.",
    nv_equalTo = "Please enter the same value again.",
    nv_accept = "Please enter a value with a valid extension.",
    nv_maxlength = "Please enter no more than {0} characters.",
    nv_minlength = "Please enter at least {0} characters.",
    nv_rangelength = "Please enter a value between {0} and {1} characters long.",
    nv_range = "Please enter a value between {0} and {1}.",
    nv_max = "Please enter a value less than or equal to {0}.",
    nv_min = "Please enter a value greater than or equal to {0}.",
    // Contact
    nv_fullname = "Full name entered is not valid.",
    nv_title = "Title not valid.",
    nv_content = "The content is not empty.",
    nv_code = "Capcha not valid.",
    // Message before unload
    nv_msgbeforeunload = "The data is unsaved. You will lose all data if you leave this page. Do you want to leave?",
    // ErrorMessage
    NVJL = [];
NVJL.errorRequest = "There was an error with the request.";
NVJL.errortimeout = "The request timed out.";
NVJL.errornotmodified = "The request was not modified but was not retrieved from the cache.";
NVJL.errorparseerror = "XML/Json format is bad.";
NVJL.error304 = "Not modified. The client requests a document that is already in its cache and the document has not been modified since it was cached. The client uses the cached copy of the document, instead of downloading it from the server.";
NVJL.error400 = "Bad Request. The request was invalid.";
NVJL.error401 = "Access denied.";
NVJL.error403 = "Forbidden. The request is understood, but it has been refused.";
NVJL.error404 = "Not Found. The URI requested is invalid or the resource requested does not exists.";
NVJL.error406 = "Not Acceptable. Client browser does not accept the MIME type of the requested page.";
NVJL.error500 = "Internal server error. You see this error message for a wide variety of server-side errors.";
NVJL.error502 = "Bad Gateway. Web server received an invalid response while acting as a gateway or proxy. You receive this error message when you try to run a CGI script that does not return a valid set of HTTP headers.";
NVJL.error503 = "Service Unavailable.";
