<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 25/11/2011 5:27 GMT+7
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$countries = array(
	'AD' => array( 'AND', 'Andorra', 'Europe/Andorra' ),
	'AE' => array( 'ARE', 'United Arab Emirates', 'Asia/Dubai' ),
	'AF' => array( 'AFG', 'Afghanistan', 'Asia/Kabul' ),
	'AG' => array( 'ATG', 'Antigua And Barbuda', 'America/Antigua' ),
	'AI' => array( 'AIA', 'Anguilla', 'America/Anguilla' ),
	'AL' => array( 'ALB', 'Albania', 'Europe/Tirane' ),
	'AM' => array( 'ARM', 'Armenia', 'Asia/Yerevan' ),
	'AN' => array( 'ANT', 'Netherlands Antilles', 'America/Curacao' ),
	'AO' => array( 'AGO', 'Angola', 'Africa/Luanda' ),
	'AQ' => array( 'ATA', 'Antarctica', 'Antarctica/Rothera' ),
	'AR' => array( 'ARG', 'Argentina', 'America/Argentina/Buenos_Aires' ),
	'AS' => array( 'ASM', 'American Samoa', 'Pacific/Pago_Pago' ),
	'AT' => array( 'AUT', 'Austria', 'Europe/Vienna' ),
	'AU' => array( 'AUS', 'Australia', 'Australia/Sydney' ),
	'AW' => array( 'ABW', 'Aruba', 'America/Aruba' ),
	'AZ' => array( 'AZE', 'Azerbaijan', 'Asia/Baku' ),
	'BA' => array( 'BIH', 'Bosnia And Herzegovina', 'Europe/Sarajevo' ),
	'BB' => array( 'BRB', 'Barbados', 'America/Barbados' ),
	'BD' => array( 'BGD', 'Bangladesh', 'Asia/Dhaka' ),
	'BE' => array( 'BEL', 'Belgium', 'Europe/Brussels' ),
	'BF' => array( 'BUR', 'Burkina Faso', 'Africa/Ouagadougou' ),
	'BG' => array( 'BGR', 'Bulgaria', 'Europe/Sofia' ),
	'BH' => array( 'BHR', 'Bahrain', 'Asia/Bahrain' ),
	'BI' => array( 'BDI', 'Burundi', 'Africa/Bujumbura' ),
	'BJ' => array( 'BEN', 'Benin', 'Africa/Porto-Novo' ),
	'BM' => array( 'BMU', 'Bermuda', 'Atlantic/Bermuda' ),
	'BN' => array( 'BRN', 'Brunei Darussalam', 'Asia/Brunei' ),
	'BO' => array( 'BOL', 'Bolivia', 'America/La_Paz' ),
	'BR' => array( 'BRA', 'Brazil', 'America/Sao_Paulo' ),
	'BS' => array( 'BHS', 'Bahamas', 'America/Nassau' ),
	'BT' => array( 'BTN', 'Bhutan', 'Asia/Thimphu' ),
	'BW' => array( 'BWA', 'Botswana', 'Africa/Gaborone' ),
	'BY' => array( 'BLR', 'Belarus', 'Europe/Minsk' ),
	'BZ' => array( 'BLZ', 'Belize', 'America/Belize' ),
	'CA' => array( 'CAN', 'Canada', 'America/Toronto' ),
	'CD' => array( 'COD', 'The Democratic Republic Of The Congo', 'Africa/Kinshasa' ),
	'CF' => array( 'CAF', 'Central African Republic', 'Africa/Bangui' ),
	'CG' => array( 'COG', 'Congo', 'Africa/Brazzaville' ),
	'CH' => array( 'CHE', 'Switzerland', 'Europe/Zurich' ),
	'CI' => array( 'CIV', 'Cote D\'ivoire', 'Africa/Abidjan' ),
	'CK' => array( 'COK', 'Cook Islands', 'Pacific/Rarotonga' ),
	'CL' => array( 'CHL', 'Chile', 'America/Santiago' ),
	'CM' => array( 'CMR', 'Cameroon', 'Africa/Douala' ),
	'CN' => array( 'CHN', 'China', 'Asia/Shanghai' ),
	'CO' => array( 'COL', 'Colombia', 'America/Bogota' ),
	'CR' => array( 'CRI', 'Costa Rica', 'America/Costa_Rica' ),
	'CS' => array( 'SCG', 'Serbia And Montenegro', 'Europe/Belgrade' ),
	'CU' => array( 'CUB', 'Cuba', 'America/Havana' ),
	'CV' => array( 'CPV', 'Cape Verde', 'Atlantic/Cape_Verde' ),
	'CY' => array( 'CYP', 'Cyprus', 'Asia/Nicosia' ),
	'CZ' => array( 'CZE', 'Czech Republic', 'Europe/Prague' ),
	'DE' => array( 'DEU', 'Germany', 'Europe/Berlin' ),
	'DJ' => array( 'DJI', 'Djibouti', 'Africa/Djibouti' ),
	'DK' => array( 'DNK', 'Denmark', 'Europe/Copenhagen' ),
	'DM' => array( 'DMA', 'Dominica', 'America/Dominica' ),
	'DO' => array( 'DOM', 'Dominican Republic', 'America/Santo_Domingo' ),
	'DZ' => array( 'DZA', 'Algeria', 'Africa/Algiers' ),
	'EC' => array( 'ECU', 'Ecuador', 'America/Guayaquil' ),
	'EE' => array( 'EST', 'Estonia', 'Europe/Tallinn' ),
	'EG' => array( 'EGY', 'Egypt', 'Africa/Cairo' ),
	'ER' => array( 'ERI', 'Eritrea', 'Africa/Asmara' ),
	'ES' => array( 'ESP', 'Spain', 'Europe/Madrid' ),
	'ET' => array( 'ETH', 'Ethiopia', 'Africa/Addis_Ababa' ),
	'EU' => array( 'EUR', 'European Union', 'Europe/Brussels' ),
	'FI' => array( 'FIN', 'Finland', 'Europe/Helsinki' ),
	'FJ' => array( 'FJI', 'Fiji', 'Pacific/Fiji' ),
	'FK' => array( 'FLK', 'Falkland Islands (Malvinas)', 'Atlantic/Stanley' ),
	'FM' => array( 'FSM', 'Federated States Of Micronesia', 'Pacific/Ponape' ),
	'FO' => array( 'FRO', 'Faroe Islands', 'UTC' ),
	'FR' => array( 'FRA', 'France', 'Europe/Paris' ),
	'GA' => array( 'GAB', 'Gabon', 'Africa/Libreville' ),
	'GB' => array( 'GBR', 'United Kingdom', 'Europe/London' ),
	'GD' => array( 'GRD', 'Grenada', 'America/Grenada' ),
	'GE' => array( 'GEO', 'Georgia', 'Asia/Tbilisi' ),
	'GF' => array( 'GUF', 'French Guiana', 'America/Cayenne' ),
	'GH' => array( 'GHA', 'Ghana', 'Africa/Accra' ),
	'GI' => array( 'GIB', 'Gibraltar', 'Europe/Gibraltar' ),
	'GL' => array( 'GRL', 'Greenland', 'America/Godthab' ),
	'GM' => array( 'GMB', 'Gambia', 'Africa/Banjul' ),
	'GN' => array( 'GIN', 'Guinea', 'Africa/Conakry' ),
	'GP' => array( 'GLP', 'Guadeloupe', 'America/Guadeloupe' ),
	'GQ' => array( 'GNQ', 'Equatorial Guinea', 'Africa/Malabo' ),
	'GR' => array( 'GRC', 'Greece', 'Europe/Athens' ),
	'GS' => array( 'SGS', 'South Georgia And The South Sandwich Islands', 'Atlantic/South_Georgia' ),
	'GT' => array( 'GTM', 'Guatemala', 'America/Guatemala' ),
	'GU' => array( 'GUM', 'Guam', 'Pacific/Guam' ),
	'GW' => array( 'GNB', 'Guinea-Bissau', 'Africa/Bissau' ),
	'GY' => array( 'GUY', 'Guyana', 'America/Guyana' ),
	'HK' => array( 'HKG', 'Hong Kong', 'Asia/Hong_Kong' ),
	'HN' => array( 'HND', 'Honduras', 'America/Tegucigalpa' ),
	'HR' => array( 'HRV', 'Croatia', 'Europe/Zagreb' ),
	'HT' => array( 'HTI', 'Haiti', 'America/Port-au-Prince' ),
	'HU' => array( 'HUN', 'Hungary', 'Europe/Budapest' ),
	'ID' => array( 'IDN', 'Indonesia', 'Asia/Jakarta' ),
	'IE' => array( 'IRL', 'Ireland', 'Europe/Dublin' ),
	'IL' => array( 'ISR', 'Israel', 'Asia/Jerusalem' ),
	'IN' => array( 'IND', 'India', 'Asia/Calcutta' ),
	'IO' => array( 'IOT', 'British Indian Ocean Territory', 'Indian/Chagos' ),
	'IQ' => array( 'IRQ', 'Iraq', 'Asia/Baghdad' ),
	'IR' => array( 'IRN', 'Islamic Republic Of Iran', 'Asia/Tehran' ),
	'IS' => array( 'ISL', 'Iceland', 'Atlantic/Reykjavik' ),
	'IT' => array( 'ITA', 'Italy', 'Europe/Rome' ),
	'JM' => array( 'JAM', 'Jamaica', 'America/Jamaica' ),
	'JO' => array( 'JOR', 'Jordan', 'Asia/Amman' ),
	'JP' => array( 'JPN', 'Japan', 'Asia/Tokyo' ),
	'KE' => array( 'KEN', 'Kenya', 'Africa/Nairobi' ),
	'KG' => array( 'KGZ', 'Kyrgyzstan', 'Asia/Bishkek' ),
	'KH' => array( 'KHM', 'Cambodia', 'Asia/Phnom_Penh' ),
	'KI' => array( 'KIR', 'Kiribati', 'Pacific/Tarawa' ),
	'KM' => array( 'COM', 'Comoros', 'Indian/Comoro' ),
	'KN' => array( 'KNA', 'Saint Kitts And Nevis', 'America/St_Kitts' ),
	'KR' => array( 'KOR', 'Republic Of Korea', 'Asia/Seoul' ),
	'KW' => array( 'KWT', 'Kuwait', 'Asia/Kuwait' ),
	'KY' => array( 'CYM', 'Cayman Islands', 'America/Cayman' ),
	'KZ' => array( 'KAZ', 'Kazakhstan', 'Asia/Qyzylorda' ),
	'LA' => array( 'LAO', 'Lao People\'s Democratic Republic', 'Asia/Vientiane' ),
	'LB' => array( 'LBN', 'Lebanon', 'Asia/Beirut' ),
	'LC' => array( 'LCA', 'Saint Lucia', 'America/St_Lucia' ),
	'LI' => array( 'LIE', 'Liechtenstein', 'Europe/Vaduz' ),
	'LK' => array( 'LKA', 'Sri Lanka', 'Asia/Colombo' ),
	'LR' => array( 'LBR', 'Liberia', 'Africa/Monrovia' ),
	'LS' => array( 'LSO', 'Lesotho', 'Africa/Maseru' ),
	'LT' => array( 'LTU', 'Lithuania', 'Europe/Vilnius' ),
	'LU' => array( 'LUX', 'Luxembourg', 'Europe/Luxembourg' ),
	'LV' => array( 'LVA', 'Latvia', 'Europe/Riga' ),
	'LY' => array( 'LBY', 'Libyan Arab Jamahiriya', 'Africa/Tripoli' ),
	'MA' => array( 'MAR', 'Morocco', 'Africa/Casablanca' ),
	'MC' => array( 'MCO', 'Monaco', 'Europe/Monaco' ),
	'MD' => array( 'MDA', 'Republic Of Moldova', 'Europe/Chisinau' ),
	'MG' => array( 'MDG', 'Madagascar', 'Indian/Antananarivo' ),
	'MH' => array( 'MHL', 'Marshall Islands', 'Pacific/Majuro' ),
	'MK' => array( 'MKD', 'The Former Yugoslav Republic Of Macedonia', 'Europe/Skopje' ),
	'ML' => array( 'MLI', 'Mali', 'Africa/Bamako' ),
	'MM' => array( 'MMR', 'Myanmar', 'Asia/Rangoon' ),
	'MN' => array( 'MNG', 'Mongolia', 'Asia/Ulaanbaatar' ),
	'MO' => array( 'MAC', 'Macao', 'Asia/Macau' ),
	'MP' => array( 'MNP', 'Northern Mariana Islands', 'Pacific/Saipan' ),
	'MQ' => array( 'MTQ', 'Martinique', 'America/Martinique' ),
	'MR' => array( 'MRT', 'Mauritania', 'Africa/Nouakchott' ),
	'MT' => array( 'MLT', 'Malta', 'Europe/Malta' ),
	'MU' => array( 'MUS', 'Mauritius', 'Indian/Mauritius' ),
	'MV' => array( 'MDV', 'Maldives', 'Indian/Maldives' ),
	'MW' => array( 'MWI', 'Malawi', 'Africa/Blantyre' ),
	'MX' => array( 'MEX', 'Mexico', 'America/Mexico_City' ),
	'MY' => array( 'MYS', 'Malaysia', 'Asia/Kuala_Lumpur' ),
	'MZ' => array( 'MOZ', 'Mozambique', 'Africa/Maputo' ),
	'NA' => array( 'NAM', 'Namibia', 'Africa/Windhoek' ),
	'NC' => array( 'NCL', 'New Caledonia', 'Pacific/Noumea' ),
	'NE' => array( 'NER', 'Niger', 'Africa/Niamey' ),
	'NF' => array( 'NFK', 'Norfolk Island', 'Pacific/Norfolk' ),
	'NG' => array( 'NGA', 'Nigeria', 'Africa/Lagos' ),
	'NI' => array( 'NIC', 'Nicaragua', 'America/Managua' ),
	'NL' => array( 'NLD', 'Netherlands', 'Europe/Amsterdam' ),
	'NO' => array( 'NOR', 'Norway', 'Europe/Oslo' ),
	'NP' => array( 'NPL', 'Nepal', 'Asia/Katmandu' ),
	'NR' => array( 'NRU', 'Nauru', 'Pacific/Nauru' ),
	'NU' => array( 'NIU', 'Niue', 'Pacific/Niue' ),
	'NZ' => array( 'NZL', 'New Zealand', 'Pacific/Auckland' ),
	'OM' => array( 'OMN', 'Oman', 'Asia/Muscat' ),
	'PA' => array( 'PAN', 'Panama', 'America/Panama' ),
	'PE' => array( 'PER', 'Peru', 'America/Lima' ),
	'PF' => array( 'PYF', 'French Polynesia', 'Pacific/Tahiti' ),
	'PG' => array( 'PNG', 'Papua New Guinea', 'Pacific/Port_Moresby' ),
	'PH' => array( 'PHL', 'Philippines', 'Asia/Manila' ),
	'PK' => array( 'PAK', 'Pakistan', 'Asia/Karachi' ),
	'PL' => array( 'POL', 'Poland', 'Europe/Warsaw' ),
	'PR' => array( 'PRI', 'Puerto Rico', 'America/Puerto_Rico' ),
	'PS' => array( 'PSE', 'Palestinian Territory', 'Asia/Gaza' ),
	'PT' => array( 'PRT', 'Portugal', 'Europe/Lisbon' ),
	'PW' => array( 'PLW', 'Palau', 'Pacific/Palau' ),
	'PY' => array( 'PRY', 'Paraguay', 'America/Asuncion' ),
	'QA' => array( 'QAT', 'Qatar', 'Asia/Qatar' ),
	'RE' => array( 'REU', 'Reunion', 'Indian/Reunion' ),
	'RO' => array( 'ROM', 'Romania', 'Europe/Bucharest' ),
	'RU' => array( 'RUS', 'Russian Federation', 'Europe/Moscow' ),
	'RW' => array( 'RWA', 'Rwanda', 'Africa/Kigali' ),
	'SA' => array( 'SAU', 'Saudi Arabia', 'Asia/Riyadh' ),
	'SB' => array( 'SLB', 'Solomon Islands', 'Pacific/Guadalcanal' ),
	'SC' => array( 'SYC', 'Seychelles', 'Indian/Mahe' ),
	'SD' => array( 'SDN', 'Sudan', 'Africa/Khartoum' ),
	'SE' => array( 'SWE', 'Sweden', 'Europe/Stockholm' ),
	'SG' => array( 'SGP', 'Singapore', 'Asia/Singapore' ),
	'SI' => array( 'SVN', 'Slovenia', 'Europe/Ljubljana' ),
	'SK' => array( 'SVK', 'Slovakia (Slovak Republic)', 'Europe/Bratislava' ),
	'SL' => array( 'SLE', 'Sierra Leone', 'Africa/Freetown' ),
	'SM' => array( 'SMR', 'San Marino', 'Europe/San_Marino' ),
	'SN' => array( 'SEN', 'Senegal', 'Africa/Dakar' ),
	'SO' => array( 'SOM', 'Somalia', 'Africa/Mogadishu' ),
	'SR' => array( 'SUR', 'Suriname', 'America/Paramaribo' ),
	'ST' => array( 'STP', 'Sao Tome And Principe', 'Africa/Sao_Tome' ),
	'SV' => array( 'SLV', 'El Salvador', 'America/El_Salvador' ),
	'SY' => array( 'SYR', 'Syrian Arab Republic', 'Asia/Damascus' ),
	'SZ' => array( 'SWZ', 'Swaziland', 'Africa/Mbabane' ),
	'TD' => array( 'TCD', 'Chad', 'Africa/Ndjamena' ),
	'TF' => array( 'ATF', 'French Southern Territories', 'Indian/Kerguelen' ),
	'TG' => array( 'TGO', 'Togo', 'Africa/Lome' ),
	'TH' => array( 'THA', 'Thailand', 'Asia/Bangkok' ),
	'TJ' => array( 'TJK', 'Tajikistan', 'Asia/Dushanbe' ),
	'TK' => array( 'TKL', 'Tokelau', 'Pacific/Fakaofo' ),
	'TL' => array( 'TLS', 'Timor-Leste', 'Asia/Dili' ),
	'TM' => array( 'TKM', 'Turkmenistan', 'Asia/Ashgabat' ),
	'TN' => array( 'TUN', 'Tunisia', 'Africa/Tunis' ),
	'TO' => array( 'TON', 'Tonga', 'Pacific/Tongatapu' ),
	'TR' => array( 'TUR', 'Turkey', 'Europe/Istanbul' ),
	'TT' => array( 'TTO', 'Trinidad And Tobago', 'America/Port_of_Spain' ),
	'TV' => array( 'TUV', 'Tuvalu', 'Pacific/Funafuti' ),
	'TW' => array( 'TWN', 'Taiwan', 'Asia/Taipei' ),
	'TZ' => array( 'TZA', 'United Republic Of Tanzania', 'Africa/Dar_es_Salaam' ),
	'UA' => array( 'UKR', 'Ukraine', 'Europe/Kiev' ),
	'UG' => array( 'UGA', 'Uganda', 'Africa/Kampala' ),
	'US' => array( 'USA', 'United States', 'America/New_York' ),
	'UY' => array( 'URY', 'Uruguay', 'America/Montevideo' ),
	'UZ' => array( 'UZB', 'Uzbekistan', 'Asia/Samarkand' ),
	'VA' => array( 'VAT', 'Holy See (Vatican City State)', 'Europe/Vatican' ),
	'VC' => array( 'VCT', 'Saint Vincent And The Grenadines', 'America/St_Vincent' ),
	'VE' => array( 'VEN', 'Venezuela', 'America/Caracas' ),
	'VG' => array( 'VGB', 'Virgin Islands', 'America/Tortola' ),
	'VI' => array( 'VIR', 'Virgin Islands', 'America/St_Thomas' ),
	'VN' => array( 'VNM', 'Viet Nam', 'Asia/Saigon' ),
	'VU' => array( 'VUT', 'Vanuatu', 'Pacific/Efate' ),
	'WS' => array( 'WSM', 'Samoa', 'Pacific/Apia' ),
	'YE' => array( 'YEM', 'Yemen', 'Asia/Aden' ),
	'YT' => array( 'MYT', 'Mayotte', 'Indian/Mayotte' ),
	'YU' => array( 'SAM', 'Serbia And Montenegro (Formally Yugoslavia)', 'Europe/Belgrade' ),
	'ZA' => array( 'ZAF', 'South Africa', 'Africa/Johannesburg' ),
	'ZM' => array( 'ZMB', 'Zambia', 'Africa/Lusaka' ),
	'ZW' => array( 'ZWE', 'Zimbabwe', 'Africa/Harare' ),
	'ZZ' => array( 'RES', 'Reserved', '' )
);

/**
 * nv_getCountry_from_file()
 *
 * @param string $ip
 * @return
 */
function nv_getCountry_from_file( $ip )
{
	global $countries;
	if( preg_match( '#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#', $ip ) )
	{
		$numbers = preg_split( '/\./', $ip );
		$code = ( $numbers[0] * 16777216 ) + ( $numbers[1] * 65536 ) + ( $numbers[2] * 256 ) + ( $numbers[3] );

		$ranges = array();
		include NV_ROOTDIR . '/' . NV_DATADIR . '/ip_files/' . $numbers[0] . '.php' ;

		if( ! empty( $ranges ) )
		{
			foreach( $ranges as $key => $value )
			{
				if( $key <= $code and $value[0] >= $code ) return $value[1];
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
function nv_getCountry_from_cookie( $ip )
{
	global $global_config, $countries;

	$numbers = preg_split( '/\./', $ip );
	$code = ( $numbers[0] * 16777216 ) + ( $numbers[1] * 65536 ) + ( $numbers[2] * 256 ) + ( $numbers[3] );

	if( isset( $_COOKIE[$global_config['cookie_prefix'] . '_ctr'] ) )
	{
		$codecountry = base64_decode( $_COOKIE[$global_config['cookie_prefix'] . '_ctr'] );
		if( preg_match( '/^' . $code . '\.([A-Z]{2})$/', $codecountry, $matches ) )
		{
			if( isset( $countries[$matches[1]] ) ) return $matches[1];
		}
	}

	$country = nv_getCountry_from_file( $ip );
	$codecountry = base64_encode( $code . '.' . $country );
	$livecookietime = time() + 31536000;

	if( isset( $_SERVER['SERVER_NAME'] ) and ! empty( $_SERVER['SERVER_NAME'] ) ) $cookie_domain = $_SERVER['SERVER_NAME'];
	else $cookie_domain = $_SERVER['HTTP_HOST'];

	$cookie_domain = preg_replace( array( '/^[a-zA-Z]+\:\/\//', '/^([w]{3})\./' ), array( '', '' ), $cookie_domain );
	$cookie_domain = preg_match( '/^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/', $cookie_domain ) ? '.' . $cookie_domain : '';

	setcookie( $global_config['cookie_prefix'] . '_ctr', $codecountry, $livecookietime, '/', $cookie_domain, ( bool )$global_config['cookie_secure'], ( bool )$global_config['cookie_httponly'] );

	return $country;
}