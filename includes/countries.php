<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 25/11/2011 5:27 GMT+7
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$countries = array(
    'AD' => array( 'AND', 'Andorra', 'Europe/Andorra', 'EUR', 'EU' ),
    'AE' => array( 'ARE', 'United Arab Emirates', 'Asia/Dubai', 'AED', 'AS' ),
    'AF' => array( 'AFG', 'Afghanistan', 'Asia/Kabul', 'AFN', 'AS' ),
    'AG' => array( 'ATG', 'Antigua And Barbuda', 'America/Antigua', 'XCD', 'NA' ),
    'AI' => array( 'AIA', 'Anguilla', 'America/Anguilla', 'XCD', 'NA' ),
    'AL' => array( 'ALB', 'Albania', 'Europe/Tirane', 'ALL', 'EU' ),
    'AM' => array( 'ARM', 'Armenia', 'Asia/Yerevan', 'AMD', 'AS' ),
    'AN' => array( 'ANT', 'Netherlands Antilles', 'America/Curacao', 'ANG', 'NA' ),
    'AO' => array( 'AGO', 'Angola', 'Africa/Luanda', 'AOA', 'AF' ),
    'AQ' => array( 'ATA', 'Antarctica', 'Antarctica/Rothera', '', 'AN' ),
    'AR' => array( 'ARG', 'Argentina', 'America/Argentina/Buenos_Aires', 'ARS', 'SA' ),
    'AS' => array( 'ASM', 'American Samoa', 'Pacific/Pago_Pago', 'USD', 'OC' ),
    'AT' => array( 'AUT', 'Austria', 'Europe/Vienna', 'EUR', 'EU' ),
    'AU' => array( 'AUS', 'Australia', 'Australia/Sydney', 'AUD', 'OC' ),
    'AW' => array( 'ABW', 'Aruba', 'America/Aruba', 'AWG', 'NA' ),
    'AZ' => array( 'AZE', 'Azerbaijan', 'Asia/Baku', 'AZN', 'AS' ),
    'BA' => array( 'BIH', 'Bosnia And Herzegovina', 'Europe/Sarajevo', 'BAM', 'EU' ),
    'BB' => array( 'BRB', 'Barbados', 'America/Barbados', 'BBD', 'NA' ),
    'BD' => array( 'BGD', 'Bangladesh', 'Asia/Dhaka', 'BDT', 'AS' ),
    'BE' => array( 'BEL', 'Belgium', 'Europe/Brussels', 'EUR', 'EU' ),
    'BF' => array( 'BUR', 'Burkina Faso', 'Africa/Ouagadougou', 'XOF', 'AF' ),
    'BG' => array( 'BGR', 'Bulgaria', 'Europe/Sofia', 'BGN', 'EU' ),
    'BH' => array( 'BHR', 'Bahrain', 'Asia/Bahrain', 'BHD', 'AS' ),
    'BI' => array( 'BDI', 'Burundi', 'Africa/Bujumbura', 'BIF', 'AF' ),
    'BJ' => array( 'BEN', 'Benin', 'Africa/Porto-Novo', 'XOF', 'AF' ),
    'BM' => array( 'BMU', 'Bermuda', 'Atlantic/Bermuda', 'BMD', 'NA' ),
    'BN' => array( 'BRN', 'Brunei Darussalam', 'Asia/Brunei', 'BND', 'AS' ),
    'BO' => array( 'BOL', 'Bolivia', 'America/La_Paz', 'BOB', 'SA' ),
    'BR' => array( 'BRA', 'Brazil', 'America/Sao_Paulo', 'BRL', 'SA' ),
    'BS' => array( 'BHS', 'Bahamas', 'America/Nassau', 'BSD', 'NA' ),
    'BT' => array( 'BTN', 'Bhutan', 'Asia/Thimphu', 'BTN', 'AS' ),
    'BW' => array( 'BWA', 'Botswana', 'Africa/Gaborone', 'BWP', 'AF' ),
    'BY' => array( 'BLR', 'Belarus', 'Europe/Minsk', 'BYR', 'EU' ),
    'BZ' => array( 'BLZ', 'Belize', 'America/Belize', 'BZD', 'NA' ),
    'CA' => array( 'CAN', 'Canada', 'America/Toronto', 'CAD', 'NA' ),
    'CD' => array( 'COD', 'The Democratic Republic Of The Congo', 'Africa/Kinshasa', 'CDF', 'AF' ),
    'CF' => array( 'CAF', 'Central African Republic', 'Africa/Bangui', 'XAF', 'AF' ),
    'CG' => array( 'COG', 'Congo', 'Africa/Brazzaville', 'XAF', 'AF' ),
    'CH' => array( 'CHE', 'Switzerland', 'Europe/Zurich', 'CHE', 'EU' ),
    'CI' => array( 'CIV', 'Cote D\'ivoire', 'Africa/Abidjan', 'XOF', 'AF' ),
    'CK' => array( 'COK', 'Cook Islands', 'Pacific/Rarotonga', 'NZD', 'OC' ),
    'CL' => array( 'CHL', 'Chile', 'America/Santiago', 'CLF', 'SA' ),
    'CM' => array( 'CMR', 'Cameroon', 'Africa/Douala', 'XAF', 'AF' ),
    'CN' => array( 'CHN', 'China', 'Asia/Shanghai', 'CNY', 'AS' ),
    'CO' => array( 'COL', 'Colombia', 'America/Bogota', 'COP', 'SA' ),
    'CR' => array( 'CRI', 'Costa Rica', 'America/Costa_Rica', 'CRC', 'NA' ),
    'CS' => array( 'SCG', 'Serbia And Montenegro', 'Europe/Belgrade', 'RSD', '' ),
    'CU' => array( 'CUB', 'Cuba', 'America/Havana', 'CUC', 'NA' ),
    'CV' => array( 'CPV', 'Cape Verde', 'Atlantic/Cape_Verde', 'CVE', 'AF' ),
    'CY' => array( 'CYP', 'Cyprus', 'Asia/Nicosia', 'EUR', 'AS' ),
    'CZ' => array( 'CZE', 'Czech Republic', 'Europe/Prague', 'CZK', 'EU' ),
    'DE' => array( 'DEU', 'Germany', 'Europe/Berlin', 'EUR', 'EU' ),
    'DJ' => array( 'DJI', 'Djibouti', 'Africa/Djibouti', 'DJF', 'AF' ),
    'DK' => array( 'DNK', 'Denmark', 'Europe/Copenhagen', 'DKK', 'EU' ),
    'DM' => array( 'DMA', 'Dominica', 'America/Dominica', 'XCD', 'NA' ),
    'DO' => array( 'DOM', 'Dominican Republic', 'America/Santo_Domingo', 'DOP', 'NA' ),
    'DZ' => array( 'DZA', 'Algeria', 'Africa/Algiers', 'DZD', 'AF' ),
    'EC' => array( 'ECU', 'Ecuador', 'America/Guayaquil', 'USD', 'SA' ),
    'EE' => array( 'EST', 'Estonia', 'Europe/Tallinn', 'EUR', 'EU' ),
    'EG' => array( 'EGY', 'Egypt', 'Africa/Cairo', 'EGP', 'AF' ),
    'ER' => array( 'ERI', 'Eritrea', 'Africa/Asmara', 'ERN', 'AF' ),
    'ES' => array( 'ESP', 'Spain', 'Europe/Madrid', 'EUR', 'EU' ),
    'ET' => array( 'ETH', 'Ethiopia', 'Africa/Addis_Ababa', 'ETB', 'AF' ),
    'EU' => array( 'EUR', 'European Union', 'Europe/Brussels', 'EUR', 'EU' ),
    'FI' => array( 'FIN', 'Finland', 'Europe/Helsinki', 'EUR', 'EU' ),
    'FJ' => array( 'FJI', 'Fiji', 'Pacific/Fiji', 'FJD', 'OC' ),
    'FK' => array( 'FLK', 'Falkland Islands (Malvinas)', 'Atlantic/Stanley', 'FKP', 'SA' ),
    'FM' => array( 'FSM', 'Federated States Of Micronesia', 'Pacific/Ponape', 'USD', 'OC' ),
    'FO' => array( 'FRO', 'Faroe Islands', 'UTC', 'DKK', 'EU' ),
    'FR' => array( 'FRA', 'France', 'Europe/Paris', 'EUR', 'EU' ),
    'GA' => array( 'GAB', 'Gabon', 'Africa/Libreville', 'XAF', 'AF' ),
    'GB' => array( 'GBR', 'United Kingdom', 'Europe/London', 'GBP', 'EU' ),
    'GD' => array( 'GRD', 'Grenada', 'America/Grenada', 'XCD', 'NA' ),
    'GE' => array( 'GEO', 'Georgia', 'Asia/Tbilisi', 'GEL', 'AS' ),
    'GF' => array( 'GUF', 'French Guiana', 'America/Cayenne', 'EUR', 'SA' ),
    'GH' => array( 'GHA', 'Ghana', 'Africa/Accra', 'GHS', 'AF' ),
    'GI' => array( 'GIB', 'Gibraltar', 'Europe/Gibraltar', 'GIP', 'EU' ),
    'GL' => array( 'GRL', 'Greenland', 'America/Godthab', 'DKK', 'NA' ),
    'GM' => array( 'GMB', 'Gambia', 'Africa/Banjul', 'GMD', 'AF' ),
    'GN' => array( 'GIN', 'Guinea', 'Africa/Conakry', 'GNF', 'AF' ),
    'GP' => array( 'GLP', 'Guadeloupe', 'America/Guadeloupe', 'EUR', 'NA' ),
    'GQ' => array( 'GNQ', 'Equatorial Guinea', 'Africa/Malabo', 'XAF', 'AF' ),
    'GR' => array( 'GRC', 'Greece', 'Europe/Athens', 'EUR', 'EU' ),
    'GS' => array( 'SGS', 'South Georgia And The South Sandwich Islands', 'Atlantic/South_Georgia', '', 'AN' ),
    'GT' => array( 'GTM', 'Guatemala', 'America/Guatemala', 'GTQ', 'NA' ),
    'GU' => array( 'GUM', 'Guam', 'Pacific/Guam', 'USD', 'OC' ),
    'GW' => array( 'GNB', 'Guinea-Bissau', 'Africa/Bissau', 'XOF', 'AF' ),
    'GY' => array( 'GUY', 'Guyana', 'America/Guyana', 'GYD', 'SA' ),
    'HK' => array( 'HKG', 'Hong Kong', 'Asia/Hong_Kong', 'HKD', 'AS' ),
    'HN' => array( 'HND', 'Honduras', 'America/Tegucigalpa', 'HNL', 'NA' ),
    'HR' => array( 'HRV', 'Croatia', 'Europe/Zagreb', 'HRK', 'EU' ),
    'HT' => array( 'HTI', 'Haiti', 'America/Port-au-Prince', 'HTG', 'NA' ),
    'HU' => array( 'HUN', 'Hungary', 'Europe/Budapest', 'HUF', 'EU' ),
    'ID' => array( 'IDN', 'Indonesia', 'Asia/Jakarta', 'IDR', 'AS' ),
    'IE' => array( 'IRL', 'Ireland', 'Europe/Dublin', 'EUR', 'EU' ),
    'IL' => array( 'ISR', 'Israel', 'Asia/Jerusalem', 'ILS', 'AS' ),
    'IN' => array( 'IND', 'India', 'Asia/Calcutta', 'INR', 'AS' ),
    'IO' => array( 'IOT', 'British Indian Ocean Territory', 'Indian/Chagos', 'USD', 'AS' ),
    'IQ' => array( 'IRQ', 'Iraq', 'Asia/Baghdad', 'IQD', 'AS' ),
    'IR' => array( 'IRN', 'Islamic Republic Of Iran', 'Asia/Tehran', 'IRR', 'AS' ),
    'IS' => array( 'ISL', 'Iceland', 'Atlantic/Reykjavik', 'ISK', 'EU' ),
    'IT' => array( 'ITA', 'Italy', 'Europe/Rome', 'EUR', 'EU' ),
    'JM' => array( 'JAM', 'Jamaica', 'America/Jamaica', 'JMD', 'NA' ),
    'JO' => array( 'JOR', 'Jordan', 'Asia/Amman', 'JOD', 'AS' ),
    'JP' => array( 'JPN', 'Japan', 'Asia/Tokyo', 'JPY', 'AS' ),
    'KE' => array( 'KEN', 'Kenya', 'Africa/Nairobi', 'KES', 'AF' ),
    'KG' => array( 'KGZ', 'Kyrgyzstan', 'Asia/Bishkek', 'KGS', 'AS' ),
    'KH' => array( 'KHM', 'Cambodia', 'Asia/Phnom_Penh', 'KHR', 'AS' ),
    'KI' => array( 'KIR', 'Kiribati', 'Pacific/Tarawa', 'AUD', 'OC' ),
    'KM' => array( 'COM', 'Comoros', 'Indian/Comoro', 'KMF', 'AF' ),
    'KN' => array( 'KNA', 'Saint Kitts And Nevis', 'America/St_Kitts', 'XCD', 'NA' ),
    'KR' => array( 'KOR', 'Republic Of Korea', 'Asia/Seoul', 'KRW', 'AS' ),
    'KW' => array( 'KWT', 'Kuwait', 'Asia/Kuwait', 'KWD', 'AS' ),
    'KY' => array( 'CYM', 'Cayman Islands', 'America/Cayman', 'KYD', 'NA' ),
    'KZ' => array( 'KAZ', 'Kazakhstan', 'Asia/Qyzylorda', 'KZT', 'AS' ),
    'LA' => array( 'LAO', 'Lao People\'s Democratic Republic', 'Asia/Vientiane', 'LAK', 'AS' ),
    'LB' => array( 'LBN', 'Lebanon', 'Asia/Beirut', 'LBP', 'AS' ),
    'LC' => array( 'LCA', 'Saint Lucia', 'America/St_Lucia', 'XCD', 'NA' ),
    'LI' => array( 'LIE', 'Liechtenstein', 'Europe/Vaduz', 'CHF', 'EU' ),
    'LK' => array( 'LKA', 'Sri Lanka', 'Asia/Colombo', 'LKR', 'AS' ),
    'LR' => array( 'LBR', 'Liberia', 'Africa/Monrovia', 'LRD', 'AF' ),
    'LS' => array( 'LSO', 'Lesotho', 'Africa/Maseru', 'LSL', 'AF' ),
    'LT' => array( 'LTU', 'Lithuania', 'Europe/Vilnius', 'EUR', 'EU' ),
    'LU' => array( 'LUX', 'Luxembourg', 'Europe/Luxembourg', 'EUR', 'EU' ),
    'LV' => array( 'LVA', 'Latvia', 'Europe/Riga', 'EUR', 'EU' ),
    'LY' => array( 'LBY', 'Libyan Arab Jamahiriya', 'Africa/Tripoli', 'LYD', 'AF' ),
    'MA' => array( 'MAR', 'Morocco', 'Africa/Casablanca', 'MAD', 'AF' ),
    'MC' => array( 'MCO', 'Monaco', 'Europe/Monaco', 'EUR', 'EU' ),
    'MD' => array( 'MDA', 'Republic Of Moldova', 'Europe/Chisinau', 'MDL', 'EU' ),
    'MG' => array( 'MDG', 'Madagascar', 'Indian/Antananarivo', 'MGA', 'AF' ),
    'MH' => array( 'MHL', 'Marshall Islands', 'Pacific/Majuro', 'USD', 'OC' ),
    'MK' => array( 'MKD', 'The Former Yugoslav Republic Of Macedonia', 'Europe/Skopje', 'MKD', 'EU' ),
    'ML' => array( 'MLI', 'Mali', 'Africa/Bamako', 'XOF', 'AF' ),
    'MM' => array( 'MMR', 'Myanmar', 'Asia/Rangoon', 'MMK', 'AS' ),
    'MN' => array( 'MNG', 'Mongolia', 'Asia/Ulaanbaatar', 'MNT', 'AS' ),
    'MO' => array( 'MAC', 'Macao', 'Asia/Macau', 'MOP', 'AS' ),
    'MP' => array( 'MNP', 'Northern Mariana Islands', 'Pacific/Saipan', 'USD', 'OC' ),
    'MQ' => array( 'MTQ', 'Martinique', 'America/Martinique', 'EUR', 'NA' ),
    'MR' => array( 'MRT', 'Mauritania', 'Africa/Nouakchott', 'MRO', 'AF' ),
    'MT' => array( 'MLT', 'Malta', 'Europe/Malta', 'EUR', 'EU' ),
    'MU' => array( 'MUS', 'Mauritius', 'Indian/Mauritius', 'MUR', 'AF' ),
    'MV' => array( 'MDV', 'Maldives', 'Indian/Maldives', 'MVR', 'AS' ),
    'MW' => array( 'MWI', 'Malawi', 'Africa/Blantyre', 'MWK', 'AF' ),
    'MX' => array( 'MEX', 'Mexico', 'America/Mexico_City', 'MXN', 'NA' ),
    'MY' => array( 'MYS', 'Malaysia', 'Asia/Kuala_Lumpur', 'MYR', 'AS' ),
    'MZ' => array( 'MOZ', 'Mozambique', 'Africa/Maputo', 'MZN', 'AF' ),
    'NA' => array( 'NAM', 'Namibia', 'Africa/Windhoek', 'NAD', 'AF' ),
    'NC' => array( 'NCL', 'New Caledonia', 'Pacific/Noumea', 'XPF', 'OC' ),
    'NE' => array( 'NER', 'Niger', 'Africa/Niamey', 'XOF', 'AF' ),
    'NF' => array( 'NFK', 'Norfolk Island', 'Pacific/Norfolk', 'AUD', 'OC' ),
    'NG' => array( 'NGA', 'Nigeria', 'Africa/Lagos', 'NGN', 'AF' ),
    'NI' => array( 'NIC', 'Nicaragua', 'America/Managua', 'NIO', 'NA' ),
    'NL' => array( 'NLD', 'Netherlands', 'Europe/Amsterdam', 'EUR', 'EU' ),
    'NO' => array( 'NOR', 'Norway', 'Europe/Oslo', 'NOK', 'EU' ),
    'NP' => array( 'NPL', 'Nepal', 'Asia/Katmandu', 'NPR', 'AS' ),
    'NR' => array( 'NRU', 'Nauru', 'Pacific/Nauru', 'AUD', 'OC' ),
    'NU' => array( 'NIU', 'Niue', 'Pacific/Niue', 'NZD', 'OC' ),
    'NZ' => array( 'NZL', 'New Zealand', 'Pacific/Auckland', 'NZD', 'OC' ),
    'OM' => array( 'OMN', 'Oman', 'Asia/Muscat', 'OMR', 'AS' ),
    'PA' => array( 'PAN', 'Panama', 'America/Panama', 'USD', 'NA' ),
    'PE' => array( 'PER', 'Peru', 'America/Lima', 'PEN', 'SA' ),
    'PF' => array( 'PYF', 'French Polynesia', 'Pacific/Tahiti', 'XPF', 'OC' ),
    'PG' => array( 'PNG', 'Papua New Guinea', 'Pacific/Port_Moresby', 'PGK', 'OC' ),
    'PH' => array( 'PHL', 'Philippines', 'Asia/Manila', 'PHP', 'AS' ),
    'PK' => array( 'PAK', 'Pakistan', 'Asia/Karachi', 'PKR', 'AS' ),
    'PL' => array( 'POL', 'Poland', 'Europe/Warsaw', 'PLN', 'EU' ),
    'PR' => array( 'PRI', 'Puerto Rico', 'America/Puerto_Rico', 'USD', 'NA' ),
    'PS' => array( 'PSE', 'Palestinian Territory', 'Asia/Gaza', '', 'AS' ),
    'PT' => array( 'PRT', 'Portugal', 'Europe/Lisbon', 'EUR', 'EU' ),
    'PW' => array( 'PLW', 'Palau', 'Pacific/Palau', 'USD', 'OC' ),
    'PY' => array( 'PRY', 'Paraguay', 'America/Asuncion', 'PYG', 'SA' ),
    'QA' => array( 'QAT', 'Qatar', 'Asia/Qatar', 'QAR', 'AS' ),
    'RE' => array( 'REU', 'Reunion', 'Indian/Reunion', '', 'AF' ),
    'RO' => array( 'ROM', 'Romania', 'Europe/Bucharest', 'RON', 'EU' ),
    'RU' => array( 'RUS', 'Russian Federation', 'Europe/Moscow', 'RUB', 'EU' ),
    'RW' => array( 'RWA', 'Rwanda', 'Africa/Kigali', 'RWF', 'AF' ),
    'SA' => array( 'SAU', 'Saudi Arabia', 'Asia/Riyadh', 'SAR', 'AS' ),
    'SB' => array( 'SLB', 'Solomon Islands', 'Pacific/Guadalcanal', 'SBD', 'OC' ),
    'SC' => array( 'SYC', 'Seychelles', 'Indian/Mahe', 'SCR', 'AF' ),
    'SD' => array( 'SDN', 'Sudan', 'Africa/Khartoum', 'SDG', 'AF' ),
    'SE' => array( 'SWE', 'Sweden', 'Europe/Stockholm', 'SEK', 'EU' ),
    'SG' => array( 'SGP', 'Singapore', 'Asia/Singapore', 'SGD', 'AS' ),
    'SI' => array( 'SVN', 'Slovenia', 'Europe/Ljubljana', 'EUR', 'EU' ),
    'SK' => array( 'SVK', 'Slovakia (Slovak Republic)', 'Europe/Bratislava', 'EUR', 'EU' ),
    'SL' => array( 'SLE', 'Sierra Leone', 'Africa/Freetown', 'SLL', 'AF' ),
    'SM' => array( 'SMR', 'San Marino', 'Europe/San_Marino', 'EUR', 'EU' ),
    'SN' => array( 'SEN', 'Senegal', 'Africa/Dakar', 'XOF', 'AF' ),
    'SO' => array( 'SOM', 'Somalia', 'Africa/Mogadishu', 'SOS', 'AF' ),
    'SR' => array( 'SUR', 'Suriname', 'America/Paramaribo', 'SRD', 'SA' ),
    'ST' => array( 'STP', 'Sao Tome And Principe', 'Africa/Sao_Tome', 'STD', 'AF' ),
    'SV' => array( 'SLV', 'El Salvador', 'America/El_Salvador', 'SVC', 'NA' ),
    'SY' => array( 'SYR', 'Syrian Arab Republic', 'Asia/Damascus', 'SYP', 'AS' ),
    'SZ' => array( 'SWZ', 'Swaziland', 'Africa/Mbabane', 'SZL', 'AF' ),
    'TD' => array( 'TCD', 'Chad', 'Africa/Ndjamena', 'XAF', 'AF' ),
    'TF' => array( 'ATF', 'French Southern Territories', 'Indian/Kerguelen', 'EUR', 'AN' ),
    'TG' => array( 'TGO', 'Togo', 'Africa/Lome', 'XOF', 'AF' ),
    'TH' => array( 'THA', 'Thailand', 'Asia/Bangkok', 'THB', 'AS' ),
    'TJ' => array( 'TJK', 'Tajikistan', 'Asia/Dushanbe', 'TJS', 'AS' ),
    'TK' => array( 'TKL', 'Tokelau', 'Pacific/Fakaofo', 'NZD', 'OC' ),
    'TL' => array( 'TLS', 'Timor-Leste', 'Asia/Dili', 'USD', 'AS' ),
    'TM' => array( 'TKM', 'Turkmenistan', 'Asia/Ashgabat', 'TMT', 'AS' ),
    'TN' => array( 'TUN', 'Tunisia', 'Africa/Tunis', 'TND', 'AF' ),
    'TO' => array( 'TON', 'Tonga', 'Pacific/Tongatapu', 'TOP', 'OC' ),
    'TR' => array( 'TUR', 'Turkey', 'Europe/Istanbul', 'TRY', 'EU' ),
    'TT' => array( 'TTO', 'Trinidad And Tobago', 'America/Port_of_Spain', 'TTD', 'NA' ),
    'TV' => array( 'TUV', 'Tuvalu', 'Pacific/Funafuti', 'AUD', 'OC' ),
    'TW' => array( 'TWN', 'Taiwan', 'Asia/Taipei', 'TWD', 'AS' ),
    'TZ' => array( 'TZA', 'United Republic Of Tanzania', 'Africa/Dar_es_Salaam', 'TZS', 'AF' ),
    'UA' => array( 'UKR', 'Ukraine', 'Europe/Kiev', 'UAH', 'EU' ),
    'UG' => array( 'UGA', 'Uganda', 'Africa/Kampala', 'UGX', 'AF' ),
    'US' => array( 'USA', 'United States', 'America/New_York', 'USD', 'NA' ),
    'UY' => array( 'URY', 'Uruguay', 'America/Montevideo', 'UYU', 'SA' ),
    'UZ' => array( 'UZB', 'Uzbekistan', 'Asia/Samarkand', 'UZS', 'AS' ),
    'VA' => array( 'VAT', 'Holy See (Vatican City State)', 'Europe/Vatican', 'EUR', 'EU' ),
    'VC' => array( 'VCT', 'Saint Vincent And The Grenadines', 'America/St_Vincent', 'XCD', 'NA' ),
    'VE' => array( 'VEN', 'Venezuela', 'America/Caracas', 'VEF', 'SA' ),
    'VG' => array( 'VGB', 'Virgin Islands', 'America/Tortola', 'USD', 'NA' ),
    'VI' => array( 'VIR', 'Virgin Islands', 'America/St_Thomas', 'USD', 'NA' ),
    'VN' => array( 'VNM', 'Viet Nam', 'Asia/Saigon', 'VND', 'AS' ),
    'VU' => array( 'VUT', 'Vanuatu', 'Pacific/Efate', 'VUV', 'OC' ),
    'WS' => array( 'WSM', 'Samoa', 'Pacific/Apia', 'USD', 'OC' ),
    'YE' => array( 'YEM', 'Yemen', 'Asia/Aden', 'YER', 'AS' ),
    'YT' => array( 'MYT', 'Mayotte', 'Indian/Mayotte', 'EUR', 'AF' ),
    'YU' => array( 'SAM', 'Serbia And Montenegro (Formally Yugoslavia)', 'Europe/Belgrade', 'RSD', '' ),
    'ZA' => array( 'ZAF', 'South Africa', 'Africa/Johannesburg', 'ZAR', 'AF' ),
    'ZM' => array( 'ZMB', 'Zambia', 'Africa/Lusaka', 'ZMW', 'AF' ),
    'ZW' => array( 'ZWE', 'Zimbabwe', 'Africa/Harare', 'ZWL', 'AF' ),
    'ZZ' => array( 'RES', 'Reserved', '', '', '' )
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
