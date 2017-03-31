<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 25/11/2011 5:27 GMT+7
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$countries = array(
    'AD' => array( 'AND', 'Andorra', 'Europe/Andorra', 'EUR' ),
    'AE' => array( 'ARE', 'United Arab Emirates', 'Asia/Dubai', 'AED' ),
    'AF' => array( 'AFG', 'Afghanistan', 'Asia/Kabul', 'AFN' ),
    'AG' => array( 'ATG', 'Antigua And Barbuda', 'America/Antigua', 'XCD' ),
    'AI' => array( 'AIA', 'Anguilla', 'America/Anguilla', 'XCD' ),
    'AL' => array( 'ALB', 'Albania', 'Europe/Tirane', 'ALL' ),
    'AM' => array( 'ARM', 'Armenia', 'Asia/Yerevan', 'AMD' ),
    'AN' => array( 'ANT', 'Netherlands Antilles', 'America/Curacao', 'ANG' ),
    'AO' => array( 'AGO', 'Angola', 'Africa/Luanda', 'AOA' ),
    'AQ' => array( 'ATA', 'Antarctica', 'Antarctica/Rothera', '' ),
    'AR' => array( 'ARG', 'Argentina', 'America/Argentina/Buenos_Aires', 'ARS' ),
    'AS' => array( 'ASM', 'American Samoa', 'Pacific/Pago_Pago', 'USD' ),
    'AT' => array( 'AUT', 'Austria', 'Europe/Vienna', 'EUR' ),
    'AU' => array( 'AUS', 'Australia', 'Australia/Sydney', 'AUD' ),
    'AW' => array( 'ABW', 'Aruba', 'America/Aruba', 'AWG' ),
    'AZ' => array( 'AZE', 'Azerbaijan', 'Asia/Baku', 'AZN' ),
    'BA' => array( 'BIH', 'Bosnia And Herzegovina', 'Europe/Sarajevo', 'BAM' ),
    'BB' => array( 'BRB', 'Barbados', 'America/Barbados', 'BBD' ),
    'BD' => array( 'BGD', 'Bangladesh', 'Asia/Dhaka', 'BDT' ),
    'BE' => array( 'BEL', 'Belgium', 'Europe/Brussels', 'EUR' ),
    'BF' => array( 'BUR', 'Burkina Faso', 'Africa/Ouagadougou', 'XOF' ),
    'BG' => array( 'BGR', 'Bulgaria', 'Europe/Sofia', 'BGN' ),
    'BH' => array( 'BHR', 'Bahrain', 'Asia/Bahrain', 'BHD' ),
    'BI' => array( 'BDI', 'Burundi', 'Africa/Bujumbura', 'BIF' ),
    'BJ' => array( 'BEN', 'Benin', 'Africa/Porto-Novo', 'XOF' ),
    'BM' => array( 'BMU', 'Bermuda', 'Atlantic/Bermuda', 'BMD' ),
    'BN' => array( 'BRN', 'Brunei Darussalam', 'Asia/Brunei', 'BND' ),
    'BO' => array( 'BOL', 'Bolivia', 'America/La_Paz', 'BOB' ),
    'BR' => array( 'BRA', 'Brazil', 'America/Sao_Paulo', 'BRL' ),
    'BS' => array( 'BHS', 'Bahamas', 'America/Nassau', 'BSD' ),
    'BT' => array( 'BTN', 'Bhutan', 'Asia/Thimphu', 'BTN' ),
    'BW' => array( 'BWA', 'Botswana', 'Africa/Gaborone', 'BWP' ),
    'BY' => array( 'BLR', 'Belarus', 'Europe/Minsk', 'BYR' ),
    'BZ' => array( 'BLZ', 'Belize', 'America/Belize', 'BZD' ),
    'CA' => array( 'CAN', 'Canada', 'America/Toronto', 'CAD' ),
    'CD' => array( 'COD', 'The Democratic Republic Of The Congo', 'Africa/Kinshasa', 'CDF' ),
    'CF' => array( 'CAF', 'Central African Republic', 'Africa/Bangui', 'XAF' ),
    'CG' => array( 'COG', 'Congo', 'Africa/Brazzaville', 'XAF' ),
    'CH' => array( 'CHE', 'Switzerland', 'Europe/Zurich', 'CHE' ),
    'CI' => array( 'CIV', 'Cote D\'ivoire', 'Africa/Abidjan', 'XOF' ),
    'CK' => array( 'COK', 'Cook Islands', 'Pacific/Rarotonga', 'NZD' ),
    'CL' => array( 'CHL', 'Chile', 'America/Santiago', 'CLF' ),
    'CM' => array( 'CMR', 'Cameroon', 'Africa/Douala', 'XAF' ),
    'CN' => array( 'CHN', 'China', 'Asia/Shanghai', 'CNY' ),
    'CO' => array( 'COL', 'Colombia', 'America/Bogota', 'COP' ),
    'CR' => array( 'CRI', 'Costa Rica', 'America/Costa_Rica', 'CRC' ),
    'CS' => array( 'SCG', 'Serbia And Montenegro', 'Europe/Belgrade', 'RSD' ),
    'CU' => array( 'CUB', 'Cuba', 'America/Havana', 'CUC' ),
    'CV' => array( 'CPV', 'Cape Verde', 'Atlantic/Cape_Verde', 'CVE' ),
    'CY' => array( 'CYP', 'Cyprus', 'Asia/Nicosia', 'EUR' ),
    'CZ' => array( 'CZE', 'Czech Republic', 'Europe/Prague', 'CZK' ),
    'DE' => array( 'DEU', 'Germany', 'Europe/Berlin', 'EUR' ),
    'DJ' => array( 'DJI', 'Djibouti', 'Africa/Djibouti', 'DJF' ),
    'DK' => array( 'DNK', 'Denmark', 'Europe/Copenhagen', 'DKK' ),
    'DM' => array( 'DMA', 'Dominica', 'America/Dominica', 'XCD' ),
    'DO' => array( 'DOM', 'Dominican Republic', 'America/Santo_Domingo', 'DOP' ),
    'DZ' => array( 'DZA', 'Algeria', 'Africa/Algiers', 'DZD' ),
    'EC' => array( 'ECU', 'Ecuador', 'America/Guayaquil', 'USD' ),
    'EE' => array( 'EST', 'Estonia', 'Europe/Tallinn', 'EUR' ),
    'EG' => array( 'EGY', 'Egypt', 'Africa/Cairo', 'EGP' ),
    'ER' => array( 'ERI', 'Eritrea', 'Africa/Asmara', 'ERN' ),
    'ES' => array( 'ESP', 'Spain', 'Europe/Madrid', 'EUR' ),
    'ET' => array( 'ETH', 'Ethiopia', 'Africa/Addis_Ababa', 'ETB' ),
    'EU' => array( 'EUR', 'European Union', 'Europe/Brussels' , 'EUR'),
    'FI' => array( 'FIN', 'Finland', 'Europe/Helsinki', 'EUR' ),
    'FJ' => array( 'FJI', 'Fiji', 'Pacific/Fiji', 'FJD' ),
    'FK' => array( 'FLK', 'Falkland Islands (Malvinas)', 'Atlantic/Stanley', 'FKP' ),
    'FM' => array( 'FSM', 'Federated States Of Micronesia', 'Pacific/Ponape', 'USD' ),
    'FO' => array( 'FRO', 'Faroe Islands', 'UTC', 'DKK' ),
    'FR' => array( 'FRA', 'France', 'Europe/Paris', 'EUR' ),
    'GA' => array( 'GAB', 'Gabon', 'Africa/Libreville', 'XAF' ),
    'GB' => array( 'GBR', 'United Kingdom', 'Europe/London', 'GBP' ),
    'GD' => array( 'GRD', 'Grenada', 'America/Grenada', 'XCD' ),
    'GE' => array( 'GEO', 'Georgia', 'Asia/Tbilisi', 'GEL' ),
    'GF' => array( 'GUF', 'French Guiana', 'America/Cayenne', 'EUR' ),
    'GH' => array( 'GHA', 'Ghana', 'Africa/Accra', 'GHS' ),
    'GI' => array( 'GIB', 'Gibraltar', 'Europe/Gibraltar', 'GIP' ),
    'GL' => array( 'GRL', 'Greenland', 'America/Godthab', 'DKK' ),
    'GM' => array( 'GMB', 'Gambia', 'Africa/Banjul', 'GMD' ),
    'GN' => array( 'GIN', 'Guinea', 'Africa/Conakry', 'GNF' ),
    'GP' => array( 'GLP', 'Guadeloupe', 'America/Guadeloupe', 'EUR' ),
    'GQ' => array( 'GNQ', 'Equatorial Guinea', 'Africa/Malabo', 'XAF' ),
    'GR' => array( 'GRC', 'Greece', 'Europe/Athens', 'EUR' ),
    'GS' => array( 'SGS', 'South Georgia And The South Sandwich Islands', 'Atlantic/South_Georgia', '' ),
    'GT' => array( 'GTM', 'Guatemala', 'America/Guatemala', 'GTQ' ),
    'GU' => array( 'GUM', 'Guam', 'Pacific/Guam', 'USD' ),
    'GW' => array( 'GNB', 'Guinea-Bissau', 'Africa/Bissau', 'XOF' ),
    'GY' => array( 'GUY', 'Guyana', 'America/Guyana', 'GYD' ),
    'HK' => array( 'HKG', 'Hong Kong', 'Asia/Hong_Kong', 'HKD' ),
    'HN' => array( 'HND', 'Honduras', 'America/Tegucigalpa', 'HNL' ),
    'HR' => array( 'HRV', 'Croatia', 'Europe/Zagreb', 'HRK' ),
    'HT' => array( 'HTI', 'Haiti', 'America/Port-au-Prince', 'HTG' ),
    'HU' => array( 'HUN', 'Hungary', 'Europe/Budapest', 'HUF' ),
    'ID' => array( 'IDN', 'Indonesia', 'Asia/Jakarta', 'IDR' ),
    'IE' => array( 'IRL', 'Ireland', 'Europe/Dublin', 'EUR' ),
    'IL' => array( 'ISR', 'Israel', 'Asia/Jerusalem', 'ILS' ),
    'IN' => array( 'IND', 'India', 'Asia/Calcutta', 'INR' ),
    'IO' => array( 'IOT', 'British Indian Ocean Territory', 'Indian/Chagos', 'USD' ),
    'IQ' => array( 'IRQ', 'Iraq', 'Asia/Baghdad', 'IQD' ),
    'IR' => array( 'IRN', 'Islamic Republic Of Iran', 'Asia/Tehran', 'IRR' ),
    'IS' => array( 'ISL', 'Iceland', 'Atlantic/Reykjavik', 'ISK' ),
    'IT' => array( 'ITA', 'Italy', 'Europe/Rome', 'EUR' ),
    'JM' => array( 'JAM', 'Jamaica', 'America/Jamaica', 'JMD' ),
    'JO' => array( 'JOR', 'Jordan', 'Asia/Amman', 'JOD' ),
    'JP' => array( 'JPN', 'Japan', 'Asia/Tokyo', 'JPY' ),
    'KE' => array( 'KEN', 'Kenya', 'Africa/Nairobi', 'KES' ),
    'KG' => array( 'KGZ', 'Kyrgyzstan', 'Asia/Bishkek', 'KGS' ),
    'KH' => array( 'KHM', 'Cambodia', 'Asia/Phnom_Penh', 'KHR' ),
    'KI' => array( 'KIR', 'Kiribati', 'Pacific/Tarawa', 'AUD' ),
    'KM' => array( 'COM', 'Comoros', 'Indian/Comoro', 'KMF' ),
    'KN' => array( 'KNA', 'Saint Kitts And Nevis', 'America/St_Kitts', 'XCD' ),
    'KR' => array( 'KOR', 'Republic Of Korea', 'Asia/Seoul', 'KRW' ),
    'KW' => array( 'KWT', 'Kuwait', 'Asia/Kuwait', 'KWD' ),
    'KY' => array( 'CYM', 'Cayman Islands', 'America/Cayman', 'KYD' ),
    'KZ' => array( 'KAZ', 'Kazakhstan', 'Asia/Qyzylorda', 'KZT' ),
    'LA' => array( 'LAO', 'Lao People\'s Democratic Republic', 'Asia/Vientiane', 'LAK' ),
    'LB' => array( 'LBN', 'Lebanon', 'Asia/Beirut', 'LBP' ),
    'LC' => array( 'LCA', 'Saint Lucia', 'America/St_Lucia', 'XCD' ),
    'LI' => array( 'LIE', 'Liechtenstein', 'Europe/Vaduz', 'CHF' ),
    'LK' => array( 'LKA', 'Sri Lanka', 'Asia/Colombo', 'LKR' ),
    'LR' => array( 'LBR', 'Liberia', 'Africa/Monrovia', 'LRD' ),
    'LS' => array( 'LSO', 'Lesotho', 'Africa/Maseru', 'LSL' ),
    'LT' => array( 'LTU', 'Lithuania', 'Europe/Vilnius', 'EUR' ),
    'LU' => array( 'LUX', 'Luxembourg', 'Europe/Luxembourg', 'EUR' ),
    'LV' => array( 'LVA', 'Latvia', 'Europe/Riga', 'EUR' ),
    'LY' => array( 'LBY', 'Libyan Arab Jamahiriya', 'Africa/Tripoli', 'LYD' ),
    'MA' => array( 'MAR', 'Morocco', 'Africa/Casablanca', 'MAD' ),
    'MC' => array( 'MCO', 'Monaco', 'Europe/Monaco', 'EUR' ),
    'MD' => array( 'MDA', 'Republic Of Moldova', 'Europe/Chisinau', 'MDL' ),
    'MG' => array( 'MDG', 'Madagascar', 'Indian/Antananarivo', 'MGA' ),
    'MH' => array( 'MHL', 'Marshall Islands', 'Pacific/Majuro', 'USD' ),
    'MK' => array( 'MKD', 'The Former Yugoslav Republic Of Macedonia', 'Europe/Skopje', 'MKD' ),
    'ML' => array( 'MLI', 'Mali', 'Africa/Bamako', 'XOF' ),
    'MM' => array( 'MMR', 'Myanmar', 'Asia/Rangoon', 'MMK' ),
    'MN' => array( 'MNG', 'Mongolia', 'Asia/Ulaanbaatar', 'MNT' ),
    'MO' => array( 'MAC', 'Macao', 'Asia/Macau', 'MOP' ),
    'MP' => array( 'MNP', 'Northern Mariana Islands', 'Pacific/Saipan', 'USD' ),
    'MQ' => array( 'MTQ', 'Martinique', 'America/Martinique', 'EUR' ),
    'MR' => array( 'MRT', 'Mauritania', 'Africa/Nouakchott', 'MRO' ),
    'MT' => array( 'MLT', 'Malta', 'Europe/Malta', 'EUR' ),
    'MU' => array( 'MUS', 'Mauritius', 'Indian/Mauritius', 'MUR' ),
    'MV' => array( 'MDV', 'Maldives', 'Indian/Maldives', 'MVR' ),
    'MW' => array( 'MWI', 'Malawi', 'Africa/Blantyre', 'MWK' ),
    'MX' => array( 'MEX', 'Mexico', 'America/Mexico_City', 'MXN' ),
    'MY' => array( 'MYS', 'Malaysia', 'Asia/Kuala_Lumpur', 'MYR' ),
    'MZ' => array( 'MOZ', 'Mozambique', 'Africa/Maputo', 'MZN' ),
    'NA' => array( 'NAM', 'Namibia', 'Africa/Windhoek', 'NAD' ),
    'NC' => array( 'NCL', 'New Caledonia', 'Pacific/Noumea', 'XPF' ),
    'NE' => array( 'NER', 'Niger', 'Africa/Niamey', 'XOF' ),
    'NF' => array( 'NFK', 'Norfolk Island', 'Pacific/Norfolk', 'AUD' ),
    'NG' => array( 'NGA', 'Nigeria', 'Africa/Lagos', 'NGN' ),
    'NI' => array( 'NIC', 'Nicaragua', 'America/Managua', 'NIO' ),
    'NL' => array( 'NLD', 'Netherlands', 'Europe/Amsterdam', 'EUR' ),
    'NO' => array( 'NOR', 'Norway', 'Europe/Oslo', 'NOK' ),
    'NP' => array( 'NPL', 'Nepal', 'Asia/Katmandu', 'NPR' ),
    'NR' => array( 'NRU', 'Nauru', 'Pacific/Nauru', 'AUD' ),
    'NU' => array( 'NIU', 'Niue', 'Pacific/Niue', 'NZD' ),
    'NZ' => array( 'NZL', 'New Zealand', 'Pacific/Auckland', 'NZD' ),
    'OM' => array( 'OMN', 'Oman', 'Asia/Muscat', 'OMR' ),
    'PA' => array( 'PAN', 'Panama', 'America/Panama', 'USD' ),
    'PE' => array( 'PER', 'Peru', 'America/Lima', 'PEN' ),
    'PF' => array( 'PYF', 'French Polynesia', 'Pacific/Tahiti', 'XPF' ),
    'PG' => array( 'PNG', 'Papua New Guinea', 'Pacific/Port_Moresby', 'PGK' ),
    'PH' => array( 'PHL', 'Philippines', 'Asia/Manila', 'PHP' ),
    'PK' => array( 'PAK', 'Pakistan', 'Asia/Karachi', 'PKR' ),
    'PL' => array( 'POL', 'Poland', 'Europe/Warsaw', 'PLN' ),
    'PR' => array( 'PRI', 'Puerto Rico', 'America/Puerto_Rico', 'USD' ),
    'PS' => array( 'PSE', 'Palestinian Territory', 'Asia/Gaza', '' ),
    'PT' => array( 'PRT', 'Portugal', 'Europe/Lisbon', 'EUR' ),
    'PW' => array( 'PLW', 'Palau', 'Pacific/Palau', 'USD' ),
    'PY' => array( 'PRY', 'Paraguay', 'America/Asuncion', 'PYG' ),
    'QA' => array( 'QAT', 'Qatar', 'Asia/Qatar', 'QAR' ),
    'RE' => array( 'REU', 'Reunion', 'Indian/Reunion', '' ),
    'RO' => array( 'ROM', 'Romania', 'Europe/Bucharest', 'RON' ),
    'RU' => array( 'RUS', 'Russian Federation', 'Europe/Moscow', "RUB" ),
    'RW' => array( 'RWA', 'Rwanda', 'Africa/Kigali', 'RWF' ),
    'SA' => array( 'SAU', 'Saudi Arabia', 'Asia/Riyadh', 'SAR' ),
    'SB' => array( 'SLB', 'Solomon Islands', 'Pacific/Guadalcanal', 'SBD' ),
    'SC' => array( 'SYC', 'Seychelles', 'Indian/Mahe', 'SCR' ),
    'SD' => array( 'SDN', 'Sudan', 'Africa/Khartoum', 'SDG' ),
    'SE' => array( 'SWE', 'Sweden', 'Europe/Stockholm', 'SEK' ),
    'SG' => array( 'SGP', 'Singapore', 'Asia/Singapore', 'SGD' ),
    'SI' => array( 'SVN', 'Slovenia', 'Europe/Ljubljana', 'EUR' ),
    'SK' => array( 'SVK', 'Slovakia (Slovak Republic)', 'Europe/Bratislava', 'EUR' ),
    'SL' => array( 'SLE', 'Sierra Leone', 'Africa/Freetown', 'SLL' ),
    'SM' => array( 'SMR', 'San Marino', 'Europe/San_Marino', 'EUR' ),
    'SN' => array( 'SEN', 'Senegal', 'Africa/Dakar', 'XOF' ),
    'SO' => array( 'SOM', 'Somalia', 'Africa/Mogadishu', 'SOS' ),
    'SR' => array( 'SUR', 'Suriname', 'America/Paramaribo', 'SRD' ),
    'ST' => array( 'STP', 'Sao Tome And Principe', 'Africa/Sao_Tome', 'STD' ),
    'SV' => array( 'SLV', 'El Salvador', 'America/El_Salvador', 'SVC' ),
    'SY' => array( 'SYR', 'Syrian Arab Republic', 'Asia/Damascus', 'SYP' ),
    'SZ' => array( 'SWZ', 'Swaziland', 'Africa/Mbabane', 'SZL' ),
    'TD' => array( 'TCD', 'Chad', 'Africa/Ndjamena', 'XAF' ),
    'TF' => array( 'ATF', 'French Southern Territories', 'Indian/Kerguelen', 'EUR' ),
    'TG' => array( 'TGO', 'Togo', 'Africa/Lome', 'XOF' ),
    'TH' => array( 'THA', 'Thailand', 'Asia/Bangkok', 'THB' ),
    'TJ' => array( 'TJK', 'Tajikistan', 'Asia/Dushanbe', 'TJS' ),
    'TK' => array( 'TKL', 'Tokelau', 'Pacific/Fakaofo', 'NZD' ),
    'TL' => array( 'TLS', 'Timor-Leste', 'Asia/Dili', 'USD' ),
    'TM' => array( 'TKM', 'Turkmenistan', 'Asia/Ashgabat', 'TMT' ),
    'TN' => array( 'TUN', 'Tunisia', 'Africa/Tunis', 'TND' ),
    'TO' => array( 'TON', 'Tonga', 'Pacific/Tongatapu', 'TOP' ),
    'TR' => array( 'TUR', 'Turkey', 'Europe/Istanbul', 'TRY' ),
    'TT' => array( 'TTO', 'Trinidad And Tobago', 'America/Port_of_Spain', 'TTD' ),
    'TV' => array( 'TUV', 'Tuvalu', 'Pacific/Funafuti', 'AUD' ),
    'TW' => array( 'TWN', 'Taiwan', 'Asia/Taipei', 'TWD' ),
    'TZ' => array( 'TZA', 'United Republic Of Tanzania', 'Africa/Dar_es_Salaam', 'TZS' ),
    'UA' => array( 'UKR', 'Ukraine', 'Europe/Kiev', 'UAH' ),
    'UG' => array( 'UGA', 'Uganda', 'Africa/Kampala', 'UGX' ),
    'US' => array( 'USA', 'United States', 'America/New_York', 'USD' ),
    'UY' => array( 'URY', 'Uruguay', 'America/Montevideo', 'UYU' ),
    'UZ' => array( 'UZB', 'Uzbekistan', 'Asia/Samarkand', 'UZS' ),
    'VA' => array( 'VAT', 'Holy See (Vatican City State)', 'Europe/Vatican', 'EUR' ),
    'VC' => array( 'VCT', 'Saint Vincent And The Grenadines', 'America/St_Vincent', 'XCD' ),
    'VE' => array( 'VEN', 'Venezuela', 'America/Caracas', 'VEF' ),
    'VG' => array( 'VGB', 'Virgin Islands', 'America/Tortola', 'USD' ),
    'VI' => array( 'VIR', 'Virgin Islands', 'America/St_Thomas', 'USD' ),
    'VN' => array( 'VNM', 'Viet Nam', 'Asia/Saigon', 'VND' ),
    'VU' => array( 'VUT', 'Vanuatu', 'Pacific/Efate', 'VUV' ),
    'WS' => array( 'WSM', 'Samoa', 'Pacific/Apia', 'USD' ),
    'YE' => array( 'YEM', 'Yemen', 'Asia/Aden', 'YER' ),
    'YT' => array( 'MYT', 'Mayotte', 'Indian/Mayotte', 'EUR' ),
    'YU' => array( 'SAM', 'Serbia And Montenegro (Formally Yugoslavia)', 'Europe/Belgrade', 'RSD' ),
    'ZA' => array( 'ZAF', 'South Africa', 'Africa/Johannesburg', 'ZAR' ),
    'ZM' => array( 'ZMB', 'Zambia', 'Africa/Lusaka', 'ZMW' ),
    'ZW' => array( 'ZWE', 'Zimbabwe', 'Africa/Harare', 'ZWL' ),
    'ZZ' => array( 'RES', 'Reserved', '', '' )
);

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
    global $countries;
    if (preg_match('/^([0-9]{1,3}+)\.([0-9]{1,3}+)\.([0-9]{1,3}+)\.([0-9]{1,3}+)$/', $ip, $numbers)) {
        $code = ($numbers[1] * 16777216) + ($numbers[2] * 65536) + ($numbers[3] * 256) + ($numbers[4]);
        
        $ranges = array();
        include NV_ROOTDIR . '/' . NV_IP_DIR . '/' . $numbers[1] . '.php' ;
        if (! empty($ranges)) {
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

            $ranges = array();
            include NV_ROOTDIR . '/' . NV_IP_DIR . '6/' . $numbers[0] . '.php' ;
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
        $codecountry = base64_decode($_COOKIE[$global_config['cookie_prefix'] . '_ctr']);
        if (preg_match('/^' . $code . '\.([A-Z]{2})$/', $codecountry, $matches)) {
            if (isset($countries[$matches[1]])) {
                return $matches[1];
            }
        }
    }

    $country = nv_getCountry_from_file($ip);
    $codecountry = base64_encode($code . '.' . $country);
    $livecookietime = time() + 31536000;

    if (isset($_SERVER['SERVER_NAME']) and ! empty($_SERVER['SERVER_NAME'])) {
        $cookie_domain = $_SERVER['SERVER_NAME'];
    } else {
        $cookie_domain = $_SERVER['HTTP_HOST'];
    }

    $cookie_domain = preg_replace(array( '/^[a-zA-Z]+\:\/\//', '/^([w]{3})\./' ), array( '', '' ), $cookie_domain);
    $cookie_domain = preg_match('/^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/', $cookie_domain) ? '.' . $cookie_domain : '';

    setcookie($global_config['cookie_prefix'] . '_ctr', $codecountry, $livecookietime, '/', $cookie_domain, ( bool )$global_config['cookie_secure'], ( bool )$global_config['cookie_httponly']);

    return $country;
}
