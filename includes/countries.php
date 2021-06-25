<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$countries = [
    'AD' => ['AND', 'Andorra', 'Europe/Andorra', 'EUR', 'EU'],
    'AE' => ['ARE', 'United Arab Emirates', 'Asia/Dubai', 'AED', 'AS'],
    'AF' => ['AFG', 'Afghanistan', 'Asia/Kabul', 'AFN', 'AS'],
    'AG' => ['ATG', 'Antigua And Barbuda', 'America/Antigua', 'XCD', 'NA'],
    'AI' => ['AIA', 'Anguilla', 'America/Anguilla', 'XCD', 'NA'],
    'AL' => ['ALB', 'Albania', 'Europe/Tirane', 'ALL', 'EU'],
    'AM' => ['ARM', 'Armenia', 'Asia/Yerevan', 'AMD', 'AS'],
    'AN' => ['ANT', 'Netherlands Antilles', 'America/Curacao', 'ANG', 'NA'],
    'AO' => ['AGO', 'Angola', 'Africa/Luanda', 'AOA', 'AF'],
    'AQ' => ['ATA', 'Antarctica', 'Antarctica/Rothera', '', 'AN'],
    'AR' => ['ARG', 'Argentina', 'America/Argentina/Buenos_Aires', 'ARS', 'SA'],
    'AS' => ['ASM', 'American Samoa', 'Pacific/Pago_Pago', 'USD', 'OC'],
    'AT' => ['AUT', 'Austria', 'Europe/Vienna', 'EUR', 'EU'],
    'AU' => ['AUS', 'Australia', 'Australia/Sydney', 'AUD', 'OC'],
    'AW' => ['ABW', 'Aruba', 'America/Aruba', 'AWG', 'NA'],
    'AZ' => ['AZE', 'Azerbaijan', 'Asia/Baku', 'AZN', 'AS'],
    'BA' => ['BIH', 'Bosnia And Herzegovina', 'Europe/Sarajevo', 'BAM', 'EU'],
    'BB' => ['BRB', 'Barbados', 'America/Barbados', 'BBD', 'NA'],
    'BD' => ['BGD', 'Bangladesh', 'Asia/Dhaka', 'BDT', 'AS'],
    'BE' => ['BEL', 'Belgium', 'Europe/Brussels', 'EUR', 'EU'],
    'BF' => ['BUR', 'Burkina Faso', 'Africa/Ouagadougou', 'XOF', 'AF'],
    'BG' => ['BGR', 'Bulgaria', 'Europe/Sofia', 'BGN', 'EU'],
    'BH' => ['BHR', 'Bahrain', 'Asia/Bahrain', 'BHD', 'AS'],
    'BI' => ['BDI', 'Burundi', 'Africa/Bujumbura', 'BIF', 'AF'],
    'BJ' => ['BEN', 'Benin', 'Africa/Porto-Novo', 'XOF', 'AF'],
    'BM' => ['BMU', 'Bermuda', 'Atlantic/Bermuda', 'BMD', 'NA'],
    'BN' => ['BRN', 'Brunei Darussalam', 'Asia/Brunei', 'BND', 'AS'],
    'BO' => ['BOL', 'Bolivia', 'America/La_Paz', 'BOB', 'SA'],
    'BR' => ['BRA', 'Brazil', 'America/Sao_Paulo', 'BRL', 'SA'],
    'BS' => ['BHS', 'Bahamas', 'America/Nassau', 'BSD', 'NA'],
    'BT' => ['BTN', 'Bhutan', 'Asia/Thimphu', 'BTN', 'AS'],
    'BW' => ['BWA', 'Botswana', 'Africa/Gaborone', 'BWP', 'AF'],
    'BY' => ['BLR', 'Belarus', 'Europe/Minsk', 'BYR', 'EU'],
    'BZ' => ['BLZ', 'Belize', 'America/Belize', 'BZD', 'NA'],
    'CA' => ['CAN', 'Canada', 'America/Toronto', 'CAD', 'NA'],
    'CD' => ['COD', 'The Democratic Republic Of The Congo', 'Africa/Kinshasa', 'CDF', 'AF'],
    'CF' => ['CAF', 'Central African Republic', 'Africa/Bangui', 'XAF', 'AF'],
    'CG' => ['COG', 'Congo', 'Africa/Brazzaville', 'XAF', 'AF'],
    'CH' => ['CHE', 'Switzerland', 'Europe/Zurich', 'CHE', 'EU'],
    'CI' => ['CIV', 'Cote D\'ivoire', 'Africa/Abidjan', 'XOF', 'AF'],
    'CK' => ['COK', 'Cook Islands', 'Pacific/Rarotonga', 'NZD', 'OC'],
    'CL' => ['CHL', 'Chile', 'America/Santiago', 'CLF', 'SA'],
    'CM' => ['CMR', 'Cameroon', 'Africa/Douala', 'XAF', 'AF'],
    'CN' => ['CHN', 'China', 'Asia/Shanghai', 'CNY', 'AS'],
    'CO' => ['COL', 'Colombia', 'America/Bogota', 'COP', 'SA'],
    'CR' => ['CRI', 'Costa Rica', 'America/Costa_Rica', 'CRC', 'NA'],
    'CS' => ['SCG', 'Serbia And Montenegro', 'Europe/Belgrade', 'RSD', ''],
    'CU' => ['CUB', 'Cuba', 'America/Havana', 'CUC', 'NA'],
    'CV' => ['CPV', 'Cape Verde', 'Atlantic/Cape_Verde', 'CVE', 'AF'],
    'CY' => ['CYP', 'Cyprus', 'Asia/Nicosia', 'EUR', 'AS'],
    'CZ' => ['CZE', 'Czech Republic', 'Europe/Prague', 'CZK', 'EU'],
    'DE' => ['DEU', 'Germany', 'Europe/Berlin', 'EUR', 'EU'],
    'DJ' => ['DJI', 'Djibouti', 'Africa/Djibouti', 'DJF', 'AF'],
    'DK' => ['DNK', 'Denmark', 'Europe/Copenhagen', 'DKK', 'EU'],
    'DM' => ['DMA', 'Dominica', 'America/Dominica', 'XCD', 'NA'],
    'DO' => ['DOM', 'Dominican Republic', 'America/Santo_Domingo', 'DOP', 'NA'],
    'DZ' => ['DZA', 'Algeria', 'Africa/Algiers', 'DZD', 'AF'],
    'EC' => ['ECU', 'Ecuador', 'America/Guayaquil', 'USD', 'SA'],
    'EE' => ['EST', 'Estonia', 'Europe/Tallinn', 'EUR', 'EU'],
    'EG' => ['EGY', 'Egypt', 'Africa/Cairo', 'EGP', 'AF'],
    'ER' => ['ERI', 'Eritrea', 'Africa/Asmara', 'ERN', 'AF'],
    'ES' => ['ESP', 'Spain', 'Europe/Madrid', 'EUR', 'EU'],
    'ET' => ['ETH', 'Ethiopia', 'Africa/Addis_Ababa', 'ETB', 'AF'],
    'EU' => ['EUR', 'European Union', 'Europe/Brussels', 'EUR', 'EU'],
    'FI' => ['FIN', 'Finland', 'Europe/Helsinki', 'EUR', 'EU'],
    'FJ' => ['FJI', 'Fiji', 'Pacific/Fiji', 'FJD', 'OC'],
    'FK' => ['FLK', 'Falkland Islands (Malvinas)', 'Atlantic/Stanley', 'FKP', 'SA'],
    'FM' => ['FSM', 'Federated States Of Micronesia', 'Pacific/Ponape', 'USD', 'OC'],
    'FO' => ['FRO', 'Faroe Islands', 'UTC', 'DKK', 'EU'],
    'FR' => ['FRA', 'France', 'Europe/Paris', 'EUR', 'EU'],
    'GA' => ['GAB', 'Gabon', 'Africa/Libreville', 'XAF', 'AF'],
    'GB' => ['GBR', 'United Kingdom', 'Europe/London', 'GBP', 'EU'],
    'GD' => ['GRD', 'Grenada', 'America/Grenada', 'XCD', 'NA'],
    'GE' => ['GEO', 'Georgia', 'Asia/Tbilisi', 'GEL', 'AS'],
    'GF' => ['GUF', 'French Guiana', 'America/Cayenne', 'EUR', 'SA'],
    'GH' => ['GHA', 'Ghana', 'Africa/Accra', 'GHS', 'AF'],
    'GI' => ['GIB', 'Gibraltar', 'Europe/Gibraltar', 'GIP', 'EU'],
    'GL' => ['GRL', 'Greenland', 'America/Godthab', 'DKK', 'NA'],
    'GM' => ['GMB', 'Gambia', 'Africa/Banjul', 'GMD', 'AF'],
    'GN' => ['GIN', 'Guinea', 'Africa/Conakry', 'GNF', 'AF'],
    'GP' => ['GLP', 'Guadeloupe', 'America/Guadeloupe', 'EUR', 'NA'],
    'GQ' => ['GNQ', 'Equatorial Guinea', 'Africa/Malabo', 'XAF', 'AF'],
    'GR' => ['GRC', 'Greece', 'Europe/Athens', 'EUR', 'EU'],
    'GS' => ['SGS', 'South Georgia And The South Sandwich Islands', 'Atlantic/South_Georgia', '', 'AN'],
    'GT' => ['GTM', 'Guatemala', 'America/Guatemala', 'GTQ', 'NA'],
    'GU' => ['GUM', 'Guam', 'Pacific/Guam', 'USD', 'OC'],
    'GW' => ['GNB', 'Guinea-Bissau', 'Africa/Bissau', 'XOF', 'AF'],
    'GY' => ['GUY', 'Guyana', 'America/Guyana', 'GYD', 'SA'],
    'HK' => ['HKG', 'Hong Kong', 'Asia/Hong_Kong', 'HKD', 'AS'],
    'HN' => ['HND', 'Honduras', 'America/Tegucigalpa', 'HNL', 'NA'],
    'HR' => ['HRV', 'Croatia', 'Europe/Zagreb', 'HRK', 'EU'],
    'HT' => ['HTI', 'Haiti', 'America/Port-au-Prince', 'HTG', 'NA'],
    'HU' => ['HUN', 'Hungary', 'Europe/Budapest', 'HUF', 'EU'],
    'ID' => ['IDN', 'Indonesia', 'Asia/Jakarta', 'IDR', 'AS'],
    'IE' => ['IRL', 'Ireland', 'Europe/Dublin', 'EUR', 'EU'],
    'IL' => ['ISR', 'Israel', 'Asia/Jerusalem', 'ILS', 'AS'],
    'IN' => ['IND', 'India', 'Asia/Calcutta', 'INR', 'AS'],
    'IO' => ['IOT', 'British Indian Ocean Territory', 'Indian/Chagos', 'USD', 'AS'],
    'IQ' => ['IRQ', 'Iraq', 'Asia/Baghdad', 'IQD', 'AS'],
    'IR' => ['IRN', 'Islamic Republic Of Iran', 'Asia/Tehran', 'IRR', 'AS'],
    'IS' => ['ISL', 'Iceland', 'Atlantic/Reykjavik', 'ISK', 'EU'],
    'IT' => ['ITA', 'Italy', 'Europe/Rome', 'EUR', 'EU'],
    'JM' => ['JAM', 'Jamaica', 'America/Jamaica', 'JMD', 'NA'],
    'JO' => ['JOR', 'Jordan', 'Asia/Amman', 'JOD', 'AS'],
    'JP' => ['JPN', 'Japan', 'Asia/Tokyo', 'JPY', 'AS'],
    'KE' => ['KEN', 'Kenya', 'Africa/Nairobi', 'KES', 'AF'],
    'KG' => ['KGZ', 'Kyrgyzstan', 'Asia/Bishkek', 'KGS', 'AS'],
    'KH' => ['KHM', 'Cambodia', 'Asia/Phnom_Penh', 'KHR', 'AS'],
    'KI' => ['KIR', 'Kiribati', 'Pacific/Tarawa', 'AUD', 'OC'],
    'KM' => ['COM', 'Comoros', 'Indian/Comoro', 'KMF', 'AF'],
    'KN' => ['KNA', 'Saint Kitts And Nevis', 'America/St_Kitts', 'XCD', 'NA'],
    'KR' => ['KOR', 'Republic Of Korea', 'Asia/Seoul', 'KRW', 'AS'],
    'KW' => ['KWT', 'Kuwait', 'Asia/Kuwait', 'KWD', 'AS'],
    'KY' => ['CYM', 'Cayman Islands', 'America/Cayman', 'KYD', 'NA'],
    'KZ' => ['KAZ', 'Kazakhstan', 'Asia/Qyzylorda', 'KZT', 'AS'],
    'LA' => ['LAO', 'Lao People\'s Democratic Republic', 'Asia/Vientiane', 'LAK', 'AS'],
    'LB' => ['LBN', 'Lebanon', 'Asia/Beirut', 'LBP', 'AS'],
    'LC' => ['LCA', 'Saint Lucia', 'America/St_Lucia', 'XCD', 'NA'],
    'LI' => ['LIE', 'Liechtenstein', 'Europe/Vaduz', 'CHF', 'EU'],
    'LK' => ['LKA', 'Sri Lanka', 'Asia/Colombo', 'LKR', 'AS'],
    'LR' => ['LBR', 'Liberia', 'Africa/Monrovia', 'LRD', 'AF'],
    'LS' => ['LSO', 'Lesotho', 'Africa/Maseru', 'LSL', 'AF'],
    'LT' => ['LTU', 'Lithuania', 'Europe/Vilnius', 'EUR', 'EU'],
    'LU' => ['LUX', 'Luxembourg', 'Europe/Luxembourg', 'EUR', 'EU'],
    'LV' => ['LVA', 'Latvia', 'Europe/Riga', 'EUR', 'EU'],
    'LY' => ['LBY', 'Libyan Arab Jamahiriya', 'Africa/Tripoli', 'LYD', 'AF'],
    'MA' => ['MAR', 'Morocco', 'Africa/Casablanca', 'MAD', 'AF'],
    'MC' => ['MCO', 'Monaco', 'Europe/Monaco', 'EUR', 'EU'],
    'MD' => ['MDA', 'Republic Of Moldova', 'Europe/Chisinau', 'MDL', 'EU'],
    'MG' => ['MDG', 'Madagascar', 'Indian/Antananarivo', 'MGA', 'AF'],
    'MH' => ['MHL', 'Marshall Islands', 'Pacific/Majuro', 'USD', 'OC'],
    'MK' => ['MKD', 'The Former Yugoslav Republic Of Macedonia', 'Europe/Skopje', 'MKD', 'EU'],
    'ML' => ['MLI', 'Mali', 'Africa/Bamako', 'XOF', 'AF'],
    'MM' => ['MMR', 'Myanmar', 'Asia/Rangoon', 'MMK', 'AS'],
    'MN' => ['MNG', 'Mongolia', 'Asia/Ulaanbaatar', 'MNT', 'AS'],
    'MO' => ['MAC', 'Macao', 'Asia/Macau', 'MOP', 'AS'],
    'MP' => ['MNP', 'Northern Mariana Islands', 'Pacific/Saipan', 'USD', 'OC'],
    'MQ' => ['MTQ', 'Martinique', 'America/Martinique', 'EUR', 'NA'],
    'MR' => ['MRT', 'Mauritania', 'Africa/Nouakchott', 'MRO', 'AF'],
    'MT' => ['MLT', 'Malta', 'Europe/Malta', 'EUR', 'EU'],
    'MU' => ['MUS', 'Mauritius', 'Indian/Mauritius', 'MUR', 'AF'],
    'MV' => ['MDV', 'Maldives', 'Indian/Maldives', 'MVR', 'AS'],
    'MW' => ['MWI', 'Malawi', 'Africa/Blantyre', 'MWK', 'AF'],
    'MX' => ['MEX', 'Mexico', 'America/Mexico_City', 'MXN', 'NA'],
    'MY' => ['MYS', 'Malaysia', 'Asia/Kuala_Lumpur', 'MYR', 'AS'],
    'MZ' => ['MOZ', 'Mozambique', 'Africa/Maputo', 'MZN', 'AF'],
    'NA' => ['NAM', 'Namibia', 'Africa/Windhoek', 'NAD', 'AF'],
    'NC' => ['NCL', 'New Caledonia', 'Pacific/Noumea', 'XPF', 'OC'],
    'NE' => ['NER', 'Niger', 'Africa/Niamey', 'XOF', 'AF'],
    'NF' => ['NFK', 'Norfolk Island', 'Pacific/Norfolk', 'AUD', 'OC'],
    'NG' => ['NGA', 'Nigeria', 'Africa/Lagos', 'NGN', 'AF'],
    'NI' => ['NIC', 'Nicaragua', 'America/Managua', 'NIO', 'NA'],
    'NL' => ['NLD', 'Netherlands', 'Europe/Amsterdam', 'EUR', 'EU'],
    'NO' => ['NOR', 'Norway', 'Europe/Oslo', 'NOK', 'EU'],
    'NP' => ['NPL', 'Nepal', 'Asia/Katmandu', 'NPR', 'AS'],
    'NR' => ['NRU', 'Nauru', 'Pacific/Nauru', 'AUD', 'OC'],
    'NU' => ['NIU', 'Niue', 'Pacific/Niue', 'NZD', 'OC'],
    'NZ' => ['NZL', 'New Zealand', 'Pacific/Auckland', 'NZD', 'OC'],
    'OM' => ['OMN', 'Oman', 'Asia/Muscat', 'OMR', 'AS'],
    'PA' => ['PAN', 'Panama', 'America/Panama', 'USD', 'NA'],
    'PE' => ['PER', 'Peru', 'America/Lima', 'PEN', 'SA'],
    'PF' => ['PYF', 'French Polynesia', 'Pacific/Tahiti', 'XPF', 'OC'],
    'PG' => ['PNG', 'Papua New Guinea', 'Pacific/Port_Moresby', 'PGK', 'OC'],
    'PH' => ['PHL', 'Philippines', 'Asia/Manila', 'PHP', 'AS'],
    'PK' => ['PAK', 'Pakistan', 'Asia/Karachi', 'PKR', 'AS'],
    'PL' => ['POL', 'Poland', 'Europe/Warsaw', 'PLN', 'EU'],
    'PR' => ['PRI', 'Puerto Rico', 'America/Puerto_Rico', 'USD', 'NA'],
    'PS' => ['PSE', 'Palestinian Territory', 'Asia/Gaza', '', 'AS'],
    'PT' => ['PRT', 'Portugal', 'Europe/Lisbon', 'EUR', 'EU'],
    'PW' => ['PLW', 'Palau', 'Pacific/Palau', 'USD', 'OC'],
    'PY' => ['PRY', 'Paraguay', 'America/Asuncion', 'PYG', 'SA'],
    'QA' => ['QAT', 'Qatar', 'Asia/Qatar', 'QAR', 'AS'],
    'RE' => ['REU', 'Reunion', 'Indian/Reunion', '', 'AF'],
    'RO' => ['ROM', 'Romania', 'Europe/Bucharest', 'RON', 'EU'],
    'RU' => ['RUS', 'Russian Federation', 'Europe/Moscow', 'RUB', 'EU'],
    'RW' => ['RWA', 'Rwanda', 'Africa/Kigali', 'RWF', 'AF'],
    'SA' => ['SAU', 'Saudi Arabia', 'Asia/Riyadh', 'SAR', 'AS'],
    'SB' => ['SLB', 'Solomon Islands', 'Pacific/Guadalcanal', 'SBD', 'OC'],
    'SC' => ['SYC', 'Seychelles', 'Indian/Mahe', 'SCR', 'AF'],
    'SD' => ['SDN', 'Sudan', 'Africa/Khartoum', 'SDG', 'AF'],
    'SE' => ['SWE', 'Sweden', 'Europe/Stockholm', 'SEK', 'EU'],
    'SG' => ['SGP', 'Singapore', 'Asia/Singapore', 'SGD', 'AS'],
    'SI' => ['SVN', 'Slovenia', 'Europe/Ljubljana', 'EUR', 'EU'],
    'SK' => ['SVK', 'Slovakia (Slovak Republic)', 'Europe/Bratislava', 'EUR', 'EU'],
    'SL' => ['SLE', 'Sierra Leone', 'Africa/Freetown', 'SLL', 'AF'],
    'SM' => ['SMR', 'San Marino', 'Europe/San_Marino', 'EUR', 'EU'],
    'SN' => ['SEN', 'Senegal', 'Africa/Dakar', 'XOF', 'AF'],
    'SO' => ['SOM', 'Somalia', 'Africa/Mogadishu', 'SOS', 'AF'],
    'SR' => ['SUR', 'Suriname', 'America/Paramaribo', 'SRD', 'SA'],
    'ST' => ['STP', 'Sao Tome And Principe', 'Africa/Sao_Tome', 'STD', 'AF'],
    'SV' => ['SLV', 'El Salvador', 'America/El_Salvador', 'SVC', 'NA'],
    'SY' => ['SYR', 'Syrian Arab Republic', 'Asia/Damascus', 'SYP', 'AS'],
    'SZ' => ['SWZ', 'Swaziland', 'Africa/Mbabane', 'SZL', 'AF'],
    'TD' => ['TCD', 'Chad', 'Africa/Ndjamena', 'XAF', 'AF'],
    'TF' => ['ATF', 'French Southern Territories', 'Indian/Kerguelen', 'EUR', 'AN'],
    'TG' => ['TGO', 'Togo', 'Africa/Lome', 'XOF', 'AF'],
    'TH' => ['THA', 'Thailand', 'Asia/Bangkok', 'THB', 'AS'],
    'TJ' => ['TJK', 'Tajikistan', 'Asia/Dushanbe', 'TJS', 'AS'],
    'TK' => ['TKL', 'Tokelau', 'Pacific/Fakaofo', 'NZD', 'OC'],
    'TL' => ['TLS', 'Timor-Leste', 'Asia/Dili', 'USD', 'AS'],
    'TM' => ['TKM', 'Turkmenistan', 'Asia/Ashgabat', 'TMT', 'AS'],
    'TN' => ['TUN', 'Tunisia', 'Africa/Tunis', 'TND', 'AF'],
    'TO' => ['TON', 'Tonga', 'Pacific/Tongatapu', 'TOP', 'OC'],
    'TR' => ['TUR', 'Turkey', 'Europe/Istanbul', 'TRY', 'EU'],
    'TT' => ['TTO', 'Trinidad And Tobago', 'America/Port_of_Spain', 'TTD', 'NA'],
    'TV' => ['TUV', 'Tuvalu', 'Pacific/Funafuti', 'AUD', 'OC'],
    'TW' => ['TWN', 'Taiwan', 'Asia/Taipei', 'TWD', 'AS'],
    'TZ' => ['TZA', 'United Republic Of Tanzania', 'Africa/Dar_es_Salaam', 'TZS', 'AF'],
    'UA' => ['UKR', 'Ukraine', 'Europe/Kiev', 'UAH', 'EU'],
    'UG' => ['UGA', 'Uganda', 'Africa/Kampala', 'UGX', 'AF'],
    'US' => ['USA', 'United States', 'America/New_York', 'USD', 'NA'],
    'UY' => ['URY', 'Uruguay', 'America/Montevideo', 'UYU', 'SA'],
    'UZ' => ['UZB', 'Uzbekistan', 'Asia/Samarkand', 'UZS', 'AS'],
    'VA' => ['VAT', 'Holy See (Vatican City State)', 'Europe/Vatican', 'EUR', 'EU'],
    'VC' => ['VCT', 'Saint Vincent And The Grenadines', 'America/St_Vincent', 'XCD', 'NA'],
    'VE' => ['VEN', 'Venezuela', 'America/Caracas', 'VEF', 'SA'],
    'VG' => ['VGB', 'Virgin Islands', 'America/Tortola', 'USD', 'NA'],
    'VI' => ['VIR', 'Virgin Islands', 'America/St_Thomas', 'USD', 'NA'],
    'VN' => ['VNM', 'Viet Nam', 'Asia/Ho_Chi_Minh', 'VND', 'AS'],
    'VU' => ['VUT', 'Vanuatu', 'Pacific/Efate', 'VUV', 'OC'],
    'WS' => ['WSM', 'Samoa', 'Pacific/Apia', 'USD', 'OC'],
    'YE' => ['YEM', 'Yemen', 'Asia/Aden', 'YER', 'AS'],
    'YT' => ['MYT', 'Mayotte', 'Indian/Mayotte', 'EUR', 'AF'],
    'YU' => ['SAM', 'Serbia And Montenegro (Formally Yugoslavia)', 'Europe/Belgrade', 'RSD', ''],
    'ZA' => ['ZAF', 'South Africa', 'Africa/Johannesburg', 'ZAR', 'AF'],
    'ZM' => ['ZMB', 'Zambia', 'Africa/Lusaka', 'ZMW', 'AF'],
    'ZW' => ['ZWE', 'Zimbabwe', 'Africa/Harare', 'ZWL', 'AF'],
    'ZZ' => ['RES', 'Reserved', '', '', '']
];

function inet_to_bits($inet)
{
    $unpacked = unpack('A16', $inet);
    $unpacked = str_split($unpacked[1]);
    $binaryip = '';
    foreach ($unpacked as $char) {
        $binaryip .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
    }

    return $binaryip;
}

/**
 * nv_getCountry_from_file()
 *
 * @param string $ip
 * @return
 */
function nv_getCountry_from_file($ip)
{
    if (preg_match('/^([0-9]{1,3}+)\.([0-9]{1,3}+)\.([0-9]{1,3}+)\.([0-9]{1,3}+)$/', $ip, $numbers)) {
        $code = ($numbers[1] * 16777216) + ($numbers[2] * 65536) + ($numbers[3] * 256) + ($numbers[4]);

        $ranges = [];
        include NV_ROOTDIR . '/' . NV_IP_DIR . '/' . $numbers[1] . '.php';
        if (!empty($ranges)) {
            foreach ($ranges as $key => $value) {
                if ($key <= $code and $value[0] >= $code) {
                    return $value[1];
                }
            }
        }
    } else {
        $numbers = explode(':', $ip);
        if (file_exists(NV_ROOTDIR . '/' . NV_IP_DIR . '6/' . $numbers[0] . '.php')) {
            $ip = inet_pton($ip);
            $binaryip = inet_to_bits($ip);

            $ranges = [];
            include NV_ROOTDIR . '/' . NV_IP_DIR . '6/' . $numbers[0] . '.php';
            foreach ($ranges as $cidrnet => $country) {
                list($net, $maskbits) = explode('/', $cidrnet);
                $net = inet_pton($net);
                $binarynet = inet_to_bits($net);

                $ip_net_bits = substr($binaryip, 0, $maskbits);
                $net_bits = substr($binarynet, 0, $maskbits);

                if ($ip_net_bits === $net_bits) {
                    return $country;
                }
            }
        }
    }

    return 'ZZ';
}

/**
 * nv_getCountry_from_cookie()
 *
 * @param mixed $ip
 * @return
 */
function nv_getCountry_from_cookie($ip)
{
    global $global_config, $countries;
    $code = preg_replace('/[^a-z0-9]/', '_', $ip);
    if (isset($_COOKIE[$global_config['cookie_prefix'] . '_ctr'])) {
        $codecountry = base64_decode($_COOKIE[$global_config['cookie_prefix'] . '_ctr'], true);
        if (preg_match('/^' . $code . '\.([A-Z]{2})$/', $codecountry, $matches)) {
            if (isset($countries[$matches[1]])) {
                return $matches[1];
            }
        }
    }

    $country = nv_getCountry_from_file($ip);
    $codecountry = base64_encode($code . '.' . $country);
    $livecookietime = time() + 31536000;

    if (isset($_SERVER['SERVER_NAME']) and !empty($_SERVER['SERVER_NAME'])) {
        $cookie_domain = $_SERVER['SERVER_NAME'];
    } else {
        $cookie_domain = $_SERVER['HTTP_HOST'];
    }

    $cookie_domain = preg_replace(['/^[a-zA-Z]+\:\/\//', '/^([w]{3})\./'], ['', ''], $cookie_domain);
    $cookie_domain = preg_match('/^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/', $cookie_domain) ? '.' . $cookie_domain : '';
    $cookie_path = '/';
    if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
        $options = [
            'expires' => $livecookietime,
            'path' => $cookie_path,
            'domain' => $cookie_domain,
            'secure' => (bool) $global_config['cookie_secure'],
            'httponly' => (bool) $global_config['cookie_httponly']
        ];
        if (!empty($global_config['cookie_SameSite']) and ('Lax' == $global_config['cookie_SameSite'] or 'Strict' == $global_config['cookie_SameSite'] or ('None' == $global_config['cookie_SameSite'] and $global_config['cookie_secure']))) {
            $options['samesite'] = $global_config['cookie_SameSite'];
        }
        setcookie($global_config['cookie_prefix'] . '_ctr', $codecountry, $options);
    } else {
        setcookie($global_config['cookie_prefix'] . '_ctr', $codecountry, $livecookietime, $cookie_path, $cookie_domain, (bool) $global_config['cookie_secure'], (bool) $global_config['cookie_httponly']);
    }

    return $country;
}
