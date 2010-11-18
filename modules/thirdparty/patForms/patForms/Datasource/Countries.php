<?php
/**
 * Datasource for Country selectors
 *
 * This class is used to autmatically populate form elements like select boxes
 * with countries
 *
 * $Id: Countries.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @package		patForms
 * @subpackage	Datasource
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * Datasource for Country selectors
 *
 * This class is used to autmatically populate form elements like select boxes
 * with countries
 *
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @package		patForms
 * @subpackage	Datasource
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Datasource_Countries
{
    var $countryList = array(
                            'C' => array(
                                        array('value' => 'AF', 'label' => 'Afghanistan'),
                                        array('value' => 'AL', 'label' => 'Albania, People\'s Socialist Republic of'),
                                        array('value' => 'DZ', 'label' => 'Algeria, People\'s Democratic Republic of'),
                                        array('value' => 'AS', 'label' => 'American Samoa'),
                                        array('value' => 'AD', 'label' => 'Andorra, Principality of'),
                                        
                                        array('value' => 'AO', 'label' => 'Angola, Republic of'),
                                        array('value' => 'AI', 'label' => 'Anguilla'),
                                        array('value' => 'AQ', 'label' => 'Antarctica (the territory South of 60 deg S)'),
                                        array('value' => 'AG', 'label' => 'Antigua and Barbuda'),
                                        array('value' => 'AR', 'label' => 'Argentina, Argentine Republic'),
                                        array('value' => 'AM', 'label' => 'Armenia'),
                                        
                                        array('value' => 'AW', 'label' => 'Aruba'),
                                        array('value' => 'AU', 'label' => 'Australia, Commonwealth of'),
                                        array('value' => 'AT', 'label' => 'Austria, Republic of'),
                                        array('value' => 'AZ', 'label' => 'Azerbaijan, Republic of'),
                                        array('value' => 'BS', 'label' => 'Bahamas, Commonwealth of the'),
                                        array('value' => 'BH', 'label' => 'Bahrain, Kingdom of'),
                                        
                                        array('value' => 'BD', 'label' => 'Bangladesh, People\'s Republic of'),
                                        array('value' => 'BB', 'label' => 'Barbados'),
                                        array('value' => 'BY', 'label' => 'Belarus'),
                                        array('value' => 'BE', 'label' => 'Belgium, Kingdom of'),
                                        array('value' => 'BZ', 'label' => 'Belize'),
                                        array('value' => 'BJ', 'label' => 'Benin, People\'s Republic of'),
                                        
                                        array('value' => 'BM', 'label' => 'Bermuda'),
                                        array('value' => 'BT', 'label' => 'Bhutan, Kingdom of'),
                                        array('value' => 'BO', 'label' => 'Bolivia, Republic of'),
                                        array('value' => 'BA', 'label' => 'Bosnia and Herzegovina'),
                                        array('value' => 'BW', 'label' => 'Botswana, Republic of'),
                                        array('value' => 'BV', 'label' => 'Bouvet Island (Bouvetoya)'),
                                        
                                        array('value' => 'BR', 'label' => 'Brazil, Federative Republic of'),
                                        array('value' => 'IO', 'label' => 'British Indian Ocean Territory (Chagos Archipelago)'),
                                        array('value' => 'VG', 'label' => 'British Virgin Islands'),
                                        array('value' => 'BN', 'label' => 'Brunei Darussalam'),
                                        array('value' => 'BG', 'label' => 'Bulgaria, People\'s Republic of'),
                                        array('value' => 'BF', 'label' => 'Burkina Faso'),
                                        
                                        array('value' => 'BI', 'label' => 'Burundi, Republic of'),
                                        array('value' => 'KH', 'label' => 'Cambodia, Kingdom of'),
                                        array('value' => 'CM', 'label' => 'Cameroon, United Republic of'),
                                        array('value' => 'CA', 'label' => 'Canada'),
                                        array('value' => 'CV', 'label' => 'Cape Verde, Republic of'),
                                        array('value' => 'KY', 'label' => 'Cayman Islands'),
                                        
                                        array('value' => 'CF', 'label' => 'Central African Republic'),
                                        array('value' => 'TD', 'label' => 'Chad, Republic of'),
                                        array('value' => 'CL', 'label' => 'Chile, Republic of'),
                                        array('value' => 'CN', 'label' => 'China, People\'s Republic of'),
                                        array('value' => 'CX', 'label' => 'Christmas Island'),
                                        array('value' => 'CC', 'label' => 'Cocos (Keeling) Islands'),
                                        
                                        array('value' => 'CO', 'label' => 'Colombia, Republic of'),
                                        array('value' => 'KM', 'label' => 'Comoros, Federal and Islamic Republic of'),
                                        array('value' => 'CD', 'label' => 'Congo, Democratic Republic of'),
                                        array('value' => 'CG', 'label' => 'Congo, People\'s Republic of'),
                                        array('value' => 'CK', 'label' => 'Cook Islands'),
                                        array('value' => 'CR', 'label' => 'Costa Rica, Republic of'),
                                        
                                        array('value' => 'CI', 'label' => 'Cote D\'Ivoire, Ivory Coast, Republic of the'),
                                        array('value' => 'CU', 'label' => 'Cuba, Republic of'),
                                        array('value' => 'CY', 'label' => 'Cyprus, Republic of'),
                                        array('value' => 'CZ', 'label' => 'Czech Republic'),
                                        array('value' => 'DK', 'label' => 'Denmark, Kingdom of'),
                                        array('value' => 'DJ', 'label' => 'Djibouti, Republic of'),
                                        
                                        array('value' => 'DM', 'label' => 'Dominica, Commonwealth of'),
                                        array('value' => 'DO', 'label' => 'Dominican Republic'),
                                        array('value' => 'TL', 'label' => 'East Timor, Democratic Republic of'),
                                        array('value' => 'EC', 'label' => 'Ecuador, Republic of'),
                                        array('value' => 'EG', 'label' => 'Egypt, Arab Republic of'),
                                        array('value' => 'SV', 'label' => 'El Salvador, Republic of'),
                                        
                                        array('value' => 'GQ', 'label' => 'Equatorial Guinea, Republic of'),
                                        array('value' => 'ER', 'label' => 'Eritrea'),
                                        array('value' => 'EE', 'label' => 'Estonia'),
                                        array('value' => 'ET', 'label' => 'Ethiopia'),
                                        array('value' => 'FO', 'label' => 'Faeroe Islands'),
                                        array('value' => 'FK', 'label' => 'Falkland Islands (Malvinas)'),
                                        
                                        array('value' => 'FJ', 'label' => 'Fiji, Republic of the Fiji Islands'),
                                        array('value' => 'FI', 'label' => 'Finland, Republic of'),
                                        array('value' => 'FR', 'label' => 'France, French Republic'),
                                        array('value' => 'GF', 'label' => 'French Guiana'),
                                        array('value' => 'PF', 'label' => 'French Polynesia'),
                                        array('value' => 'TF', 'label' => 'French Southern Territories'),
                                        
                                        array('value' => 'GA', 'label' => 'Gabon, Gabonese Republic'),
                                        array('value' => 'GM', 'label' => 'Gambia, Republic of the'),
                                        array('value' => 'GE', 'label' => 'Georgia'),
                                        array('value' => 'DE', 'label' => 'Germany'),
                                        array('value' => 'GH', 'label' => 'Ghana, Republic of'),
                                        array('value' => 'GI', 'label' => 'Gibraltar'),
                                        
                                        array('value' => 'GR', 'label' => 'Greece, Hellenic Republic'),
                                        array('value' => 'GL', 'label' => 'Greenland'),
                                        array('value' => 'GD', 'label' => 'Grenada'),
                                        array('value' => 'GP', 'label' => 'Guadaloupe'),
                                        array('value' => 'GU', 'label' => 'Guam'),
                                        array('value' => 'GT', 'label' => 'Guatemala, Republic of'),
                                        
                                        array('value' => 'GN', 'label' => 'Guinea, Revolutionary People\'s Rep\'c of'),
                                        array('value' => 'GW', 'label' => 'Guinea-Bissau, Republic of'),
                                        array('value' => 'GY', 'label' => 'Guyana, Republic of'),
                                        array('value' => 'HT', 'label' => 'Haiti, Republic of'),
                                        array('value' => 'HM', 'label' => 'Heard and McDonald Islands'),
                                        array('value' => 'VA', 'label' => 'Holy See (Vatican City State)'),
                                        
                                        array('value' => 'HN', 'label' => 'Honduras, Republic of'),
                                        array('value' => 'HK', 'label' => 'Hong Kong, Special Administrative Region of China'),
                                        array('value' => 'HR', 'label' => 'Hrvatska (Croatia)'),
                                        array('value' => 'HU', 'label' => 'Hungary, Hungarian People\'s Republic'),
                                        array('value' => 'IS', 'label' => 'Iceland, Republic of'),
                                        array('value' => 'IN', 'label' => 'India, Republic of'),
                                        
                                        array('value' => 'ID', 'label' => 'Indonesia, Republic of'),
                                        array('value' => 'IR', 'label' => 'Iran, Islamic Republic of'),
                                        array('value' => 'IQ', 'label' => 'Iraq, Republic of'),
                                        array('value' => 'IE', 'label' => 'Ireland'),
                                        array('value' => 'IL', 'label' => 'Israel, State of'),
                                        array('value' => 'IT', 'label' => 'Italy, Italian Republic'),
                                        
                                        array('value' => 'JM', 'label' => 'Jamaica'),
                                        array('value' => 'JP', 'label' => 'Japan'),
                                        array('value' => 'JO', 'label' => 'Jordan, Hashemite Kingdom of'),
                                        array('value' => 'KZ', 'label' => 'Kazakhstan, Republic of'),
                                        array('value' => 'KE', 'label' => 'Kenya, Republic of'),
                                        array('value' => 'KI', 'label' => 'Kiribati, Republic of'),
                                        
                                        array('value' => 'KP', 'label' => 'Korea, Democratic People\'s Republic of'),
                                        array('value' => 'KR', 'label' => 'Korea, Republic of'),
                                        array('value' => 'KW', 'label' => 'Kuwait, State of'),
                                        array('value' => 'KG', 'label' => 'Kyrgyz Republic'),
                                        array('value' => 'LA', 'label' => 'Lao People\'s Democratic Republic'),
                                        array('value' => 'LV', 'label' => 'Latvia'),
                                        
                                        array('value' => 'LB', 'label' => 'Lebanon, Lebanese Republic'),
                                        array('value' => 'LS', 'label' => 'Lesotho, Kingdom of'),
                                        array('value' => 'LR', 'label' => 'Liberia, Republic of'),
                                        array('value' => 'LY', 'label' => 'Libyan Arab Jamahiriya'),
                                        array('value' => 'LI', 'label' => 'Liechtenstein, Principality of'),
                                        array('value' => 'LT', 'label' => 'Lithuania'),
                                        
                                        array('value' => 'LU', 'label' => 'Luxembourg, Grand Duchy of'),
                                        array('value' => 'MO', 'label' => 'Macao, Special Administrative Region of China'),
                                        array('value' => 'MK', 'label' => 'Macedonia, the former Yugoslav Republic of'),
                                        array('value' => 'MG', 'label' => 'Madagascar, Republic of'),
                                        array('value' => 'MW', 'label' => 'Malawi, Republic of'),
                                        array('value' => 'MY', 'label' => 'Malaysia'),
                                        
                                        array('value' => 'MV', 'label' => 'Maldives, Republic of'),
                                        array('value' => 'ML', 'label' => 'Mali, Republic of'),
                                        array('value' => 'MT', 'label' => 'Malta, Republic of'),
                                        array('value' => 'MH', 'label' => 'Marshall Islands'),
                                        array('value' => 'MQ', 'label' => 'Martinique'),
                                        array('value' => 'MR', 'label' => 'Mauritania, Islamic Republic of'),
                                        
                                        array('value' => 'MU', 'label' => 'Mauritius'),
                                        array('value' => 'YT', 'label' => 'Mayotte'),
                                        array('value' => 'MX', 'label' => 'Mexico, United Mexican States'),
                                        array('value' => 'FM', 'label' => 'Micronesia, Federated States of'),
                                        array('value' => 'MD', 'label' => 'Moldova, Republic of'),
                                        array('value' => 'MC', 'label' => 'Monaco, Principality of'),
                                        
                                        array('value' => 'MN', 'label' => 'Mongolia, Mongolian People\'s Republic'),
                                        array('value' => 'MS', 'label' => 'Montserrat'),
                                        array('value' => 'MA', 'label' => 'Morocco, Kingdom of'),
                                        array('value' => 'MZ', 'label' => 'Mozambique, People\'s Republic of'),
                                        array('value' => 'MM', 'label' => 'Myanmar'),
                                        array('value' => 'NA', 'label' => 'Namibia'),
                                        
                                        array('value' => 'NR', 'label' => 'Nauru, Republic of'),
                                        array('value' => 'NP', 'label' => 'Nepal, Kingdom of'),
                                        array('value' => 'AN', 'label' => 'Netherlands Antilles'),
                                        array('value' => 'NL', 'label' => 'Netherlands, Kingdom of the'),
                                        array('value' => 'NC', 'label' => 'New Caledonia'),
                                        array('value' => 'NZ', 'label' => 'New Zealand'),
                                        
                                        array('value' => 'NI', 'label' => 'Nicaragua, Republic of'),
                                        array('value' => 'NE', 'label' => 'Niger, Republic of the'),
                                        array('value' => 'NG', 'label' => 'Nigeria, Federal Republic of'),
                                        array('value' => 'NU', 'label' => 'Niue, Republic of'),
                                        array('value' => 'NF', 'label' => 'Norfolk Island'),
                                        array('value' => 'MP', 'label' => 'Northern Mariana Islands'),
                                        
                                        array('value' => 'NO', 'label' => 'Norway, Kingdom of'),
                                        array('value' => 'OM', 'label' => 'Oman, Sultanate of'),
                                        array('value' => 'PK', 'label' => 'Pakistan, Islamic Republic of'),
                                        array('value' => 'PW', 'label' => 'Palau'),
                                        array('value' => 'PS', 'label' => 'Palestinian Territory, Occupied'),
                                        array('value' => 'PA', 'label' => 'Panama, Republic of'),
                                        
                                        array('value' => 'PG', 'label' => 'Papua New Guinea'),
                                        array('value' => 'PY', 'label' => 'Paraguay, Republic of'),
                                        array('value' => 'PE', 'label' => 'Peru, Republic of'),
                                        array('value' => 'PH', 'label' => 'Philippines, Republic of the'),
                                        array('value' => 'PN', 'label' => 'Pitcairn Island'),
                                        array('value' => 'PL', 'label' => 'Poland, Polish People\'s Republic'),
                                        
                                        array('value' => 'PT', 'label' => 'Portugal, Portuguese Republic'),
                                        array('value' => 'PR', 'label' => 'Puerto Rico'),
                                        array('value' => 'QA', 'label' => 'Qatar, State of'),
                                        array('value' => 'RE', 'label' => 'Reunion'),
                                        array('value' => 'RO', 'label' => 'Romania, Socialist Republic of'),
                                        array('value' => 'RU', 'label' => 'Russian Federation'),
                                        
                                        array('value' => 'RW', 'label' => 'Rwanda, Rwandese Republic'),
                                        array('value' => 'SH', 'label' => 'St. Helena'),
                                        array('value' => 'KN', 'label' => 'St. Kitts and Nevis'),
                                        array('value' => 'LC', 'label' => 'St. Lucia'),
                                        array('value' => 'PM', 'label' => 'St. Pierre and Miquelon'),
                                        array('value' => 'VC', 'label' => 'St. Vincent and the Grenadines'),
                                        
                                        array('value' => 'WS', 'label' => 'Samoa, Independent State of'),
                                        array('value' => 'SM', 'label' => 'San Marino, Republic of'),
                                        array('value' => 'ST', 'label' => 'Sao Tome and Principe, Democratic Republic of'),
                                        array('value' => 'SA', 'label' => 'Saudi Arabia, Kingdom of'),
                                        array('value' => 'SN', 'label' => 'Senegal, Republic of'),
                                        array('value' => 'SC', 'label' => 'Seychelles, Republic of'),
                                        
                                        array('value' => 'SL', 'label' => 'Sierra Leone, Republic of'),
                                        array('value' => 'SG', 'label' => 'Singapore, Republic of'),
                                        array('value' => 'SK', 'label' => 'Slovakia (Slovak Republic)'),
                                        array('value' => 'SI', 'label' => 'Slovenia'),
                                        array('value' => 'SB', 'label' => 'Solomon Islands'),
                                        array('value' => 'SO', 'label' => 'Somalia, Somali Republic'),
                                        
                                        array('value' => 'ZA', 'label' => 'South Africa, Republic of'),
                                        array('value' => 'GS', 'label' => 'South Georgia and the South Sandwich Islands'),
                                        array('value' => 'ES', 'label' => 'Spain, Spanish State'),
                                        array('value' => 'LK', 'label' => 'Sri Lanka, Democratic Socialist Republic of'),
                                        array('value' => 'SD', 'label' => 'Sudan, Democratic Republic of the'),
                                        array('value' => 'SR', 'label' => 'Suriname, Republic of'),
                                        
                                        array('value' => 'SJ', 'label' => 'Svalbard & Jan Mayen Islands'),
                                        array('value' => 'SZ', 'label' => 'Swaziland, Kingdom of'),
                                        array('value' => 'SE', 'label' => 'Sweden, Kingdom of'),
                                        array('value' => 'CH', 'label' => 'Switzerland, Swiss Confederation'),
                                        array('value' => 'SY', 'label' => 'Syrian Arab Republic'),
                                        
                                        array('value' => 'TW', 'label' => 'Taiwan, Province of China'),
                                        array('value' => 'TJ', 'label' => 'Tajikistan'),
                                        array('value' => 'TZ', 'label' => 'Tanzania, United Republic of'),
                                        array('value' => 'TH', 'label' => 'Thailand, Kingdom of'),
                                        array('value' => 'TG', 'label' => 'Togo, Togolese Republic'),
                                        array('value' => 'TK', 'label' => 'Tokelau (Tokelau Islands)'),
                                        
                                        array('value' => 'TO', 'label' => 'Tonga, Kingdom of'),
                                        array('value' => 'TT', 'label' => 'Trinidad and Tobago, Republic of'),
                                        array('value' => 'TN', 'label' => 'Tunisia, Republic of'),
                                        array('value' => 'TR', 'label' => 'Turkey, Republic of'),
                                        array('value' => 'TM', 'label' => 'Turkmenistan'),
                                        array('value' => 'TC', 'label' => 'Turks and Caicos Islands'),
                                        
                                        array('value' => 'TV', 'label' => 'Tuvalu'),
                                        array('value' => 'VI', 'label' => 'US Virgin Islands'),
                                        array('value' => 'UG', 'label' => 'Uganda, Republic of'),
                                        array('value' => 'UA', 'label' => 'Ukraine'),
                                        array('value' => 'AE', 'label' => 'United Arab Emirates'),
                                        array('value' => 'GB', 'label' => 'United Kingdom of Great Britain & N. Ireland'),
                                        
                                        array('value' => 'UM', 'label' => 'United States Minor Outlying Islands'),
                                        array('value' => 'US', 'label' => 'United States of America'),
                                        array('value' => 'UY', 'label' => 'Uruguay, Eastern Republic of'),
                                        array('value' => 'UZ', 'label' => 'Uzbekistan'),
                                        array('value' => 'VU', 'label' => 'Vanuatu'),
                                        array('value' => 'VE', 'label' => 'Venezuela, Bolivarian Republic of'),
                                        
                                        array('value' => 'VN', 'label' => 'Viet Nam, Socialist Republic of'),
                                        array('value' => 'WF', 'label' => 'Wallis and Futuna Islands'),
                                        array('value' => 'EH', 'label' => 'Western Sahara'),
                                        array('value' => 'YE', 'label' => 'Yemen'),
                                        array('value' => 'YU', 'label' => 'Yugoslavia, Socialist Federal Republic of'),
                                        array('value' => 'ZM', 'label' => 'Zambia, Republic of'),
                                        
                                        array('value' => 'ZW', 'label' => 'Zimbabwe'),
                                    ),
                            'de' => array(
                                            array('value' => 'AD', 'label' => 'Andorra'),
                                            array('value' => 'AE', 'label' => 'Vereinigte Arabische Emirate'),
                                            array('value' => 'AF', 'label' => 'Afghanistan'),
                                            array('value' => 'AG', 'label' => 'Antigua und Barbuda'),
                                            array('value' => 'AI', 'label' => 'Anguilla'),
                                            
                                            array('value' => 'AL', 'label' => 'Albanien'),
                                            array('value' => 'AM', 'label' => 'Armenien'),
                                            array('value' => 'AN', 'label' => 'Niederländische Antillen'),
                                            array('value' => 'AO', 'label' => 'Angola'),
                                            array('value' => 'AQ', 'label' => 'Antarktis'),
                                            array('value' => 'AR', 'label' => 'Argentinien'),
                                            
                                            array('value' => 'AS', 'label' => 'Amerikanisch-Samoa'),
                                            array('value' => 'AT', 'label' => 'Österreich'),
                                            array('value' => 'AU', 'label' => 'Australien'),
                                            array('value' => 'AW', 'label' => 'Aruba'),
                                            array('value' => 'AZ', 'label' => 'Aserbeidschan'),
                                            array('value' => 'BA', 'label' => 'Bosnien-Herzegowina'),
                                            
                                            array('value' => 'BB', 'label' => 'Barbados'),
                                            array('value' => 'BD', 'label' => 'Bangladesh'),
                                            array('value' => 'BE', 'label' => 'Belgien'),
                                            array('value' => 'BF', 'label' => 'Burkina Faso'),
                                            array('value' => 'BG', 'label' => 'Bulgarien'),
                                            array('value' => 'BH', 'label' => 'Bahrain'),
                                            
                                            array('value' => 'BI', 'label' => 'Burundi'),
                                            array('value' => 'BJ', 'label' => 'Benin'),
                                            array('value' => 'BM', 'label' => 'Bermuda-Inseln'),
                                            array('value' => 'BN', 'label' => 'Brunei'),
                                            array('value' => 'BO', 'label' => 'Bolivien'),
                                            array('value' => 'BR', 'label' => 'Brasilien'),
                                            
                                            array('value' => 'BS', 'label' => 'Bahamas'),
                                            array('value' => 'BT', 'label' => 'Bhutan'),
                                            array('value' => 'BV', 'label' => 'Bouvet-Insel'),
                                            array('value' => 'BW', 'label' => 'Botswana'),
                                            array('value' => 'BY', 'label' => 'Belarus'),
                                            array('value' => 'BZ', 'label' => 'Belize'),
                                            
                                            array('value' => 'CA', 'label' => 'Kanada'),
                                            array('value' => 'CC', 'label' => 'Kokos-Inseln'),
                                            array('value' => 'CF', 'label' => 'Zentralafrikanische Republik'),
                                            array('value' => 'CG', 'label' => 'Kongo'),
                                            array('value' => 'CH', 'label' => 'Schweiz'),
                                            array('value' => 'CI', 'label' => 'Elfenbeinküste'),
                                            
                                            array('value' => 'CK', 'label' => 'Cook-Inseln'),
                                            array('value' => 'CL', 'label' => 'Chile'),
                                            array('value' => 'CM', 'label' => 'Kamerun'),
                                            array('value' => 'CN', 'label' => 'China'),
                                            array('value' => 'CO', 'label' => 'Kolumbien'),
                                            array('value' => 'CR', 'label' => 'Costa Rica'),
                                            
                                            array('value' => 'CU', 'label' => 'Kuba'),
                                            array('value' => 'CV', 'label' => 'Kapverden'),
                                            array('value' => 'CX', 'label' => 'Weihnachtsinsel (AUS)'),
                                            array('value' => 'CY', 'label' => 'Zypern'),
                                            array('value' => 'CZ', 'label' => 'Tschechei'),
                                            array('value' => 'DE', 'label' => 'Deutschland'),
                                            
                                            array('value' => 'DJ', 'label' => 'Djibouti'),
                                            array('value' => 'DK', 'label' => 'Dänemark'),
                                            array('value' => 'DM', 'label' => 'Dominica'),
                                            array('value' => 'DO', 'label' => 'Dominikanische Republik'),
                                            array('value' => 'DZ', 'label' => 'Algerien'),
                                            array('value' => 'EC', 'label' => 'Equador'),
                                            
                                            array('value' => 'EE', 'label' => 'Estland'),
                                            array('value' => 'EG', 'label' => 'Ägypten'),
                                            array('value' => 'EH', 'label' => 'Westsahara'),
                                            array('value' => 'ER', 'label' => 'Eritrea'),
                                            array('value' => 'ES', 'label' => 'Spanien'),
                                            array('value' => 'ET', 'label' => 'Äthiopien'),
                                            
                                            array('value' => 'FI', 'label' => 'Finnland'),
                                            array('value' => 'FJ', 'label' => 'Fidschi'),
                                            array('value' => 'FK', 'label' => 'Falkland-Inseln (Malvinen)'),
                                            array('value' => 'FM', 'label' => 'Mikronesien'),
                                            array('value' => 'FO', 'label' => 'Färoer-Inseln'),
                                            array('value' => 'FR', 'label' => 'Frankreich'),
                                            
                                            array('value' => 'FX', 'label' => 'Frankreich, Metropolitan'),
                                            array('value' => 'GA', 'label' => 'Gabun'),
                                            array('value' => 'GB', 'label' => 'Grossbritannien (UK)'),
                                            array('value' => 'GD', 'label' => 'Grenada'),
                                            array('value' => 'GE', 'label' => 'Georgien'),
                                            array('value' => 'GF', 'label' => 'Französisch-Guyana'),
                                            
                                            array('value' => 'GH', 'label' => 'Ghana'),
                                            array('value' => 'GI', 'label' => 'Gibraltar'),
                                            array('value' => 'GL', 'label' => 'Grönland'),
                                            array('value' => 'GM', 'label' => 'Gambia'),
                                            array('value' => 'GN', 'label' => 'Guinea'),
                                            array('value' => 'GP', 'label' => 'Guadeloupe'),
                                            
                                            array('value' => 'GQ', 'label' => 'Äquatorialguinea'),
                                            array('value' => 'GR', 'label' => 'Griechenland'),
                                            array('value' => 'GS', 'label' => 'Süd Georgia und die südlichen Sandwich Inseln'),
                                            array('value' => 'GT', 'label' => 'Guatemala'),
                                            array('value' => 'GU', 'label' => 'Guam'),
                                            array('value' => 'GW', 'label' => 'Guinea-Bissau'),
                                            
                                            array('value' => 'GY', 'label' => 'Guyana'),
                                            array('value' => 'HK', 'label' => 'Hongkong'),
                                            array('value' => 'HM', 'label' => 'Heard-, MacDonald-Inseln'),
                                            array('value' => 'HN', 'label' => 'Honduras'),
                                            array('value' => 'HR', 'label' => 'Kroatien'),
                                            array('value' => 'HT', 'label' => 'Haiti'),
                                            
                                            array('value' => 'HU', 'label' => 'Ungarn'),
                                            array('value' => 'ID', 'label' => 'Indonesien'),
                                            array('value' => 'IE', 'label' => 'Irland'),
                                            array('value' => 'IL', 'label' => 'Israel'),
                                            array('value' => 'IN', 'label' => 'Indien'),
                                            array('value' => 'IO', 'label' => 'Britische Territorien im Indischen Ozean'),
                                            
                                            array('value' => 'IQ', 'label' => 'Irak'),
                                            array('value' => 'IR', 'label' => 'Iran'),
                                            array('value' => 'IS', 'label' => 'Island'),
                                            array('value' => 'IT', 'label' => 'Italien'),
                                            array('value' => 'JM', 'label' => 'Jamaika'),
                                            array('value' => 'JO', 'label' => 'Jordanien'),
                                            
                                            array('value' => 'JP', 'label' => 'Japan'),
                                            array('value' => 'KE', 'label' => 'Kenia'),
                                            array('value' => 'KG', 'label' => 'Kirgistan'),
                                            array('value' => 'KH', 'label' => 'Kambodscha'),
                                            array('value' => 'KI', 'label' => 'Kiribati'),
                                            array('value' => 'KM', 'label' => 'Komoren'),
                                            
                                            array('value' => 'KN', 'label' => 'St. Kitts und Nevis'),
                                            array('value' => 'KP', 'label' => 'Korea (Demokratische Volksrepublik)'),
                                            array('value' => 'KR', 'label' => 'Korea (Republik)'),
                                            array('value' => 'KW', 'label' => 'Kuwait'),
                                            array('value' => 'KY', 'label' => 'Kaiman-Inseln'),
                                            array('value' => 'KZ', 'label' => 'Kasachstan'),
                                            
                                            array('value' => 'LA', 'label' => 'Laos'),
                                            array('value' => 'LB', 'label' => 'Libanon'),
                                            array('value' => 'LC', 'label' => 'St. Lucia'),
                                            array('value' => 'LI', 'label' => 'Liechtenstein'),
                                            array('value' => 'LK', 'label' => 'Sri Lanka'),
                                            array('value' => 'LR', 'label' => 'Liberia'),
                                            
                                            array('value' => 'LS', 'label' => 'Lesotho'),
                                            array('value' => 'LT', 'label' => 'Litauen'),
                                            array('value' => 'LU', 'label' => 'Luxemburg'),
                                            array('value' => 'LV', 'label' => 'Lettland'),
                                            array('value' => 'LY', 'label' => 'Libyen'),
                                            array('value' => 'MA', 'label' => 'Marokko'),
                                            
                                            array('value' => 'MC', 'label' => 'Monaco'),
                                            array('value' => 'MD', 'label' => 'Moldawien'),
                                            array('value' => 'MG', 'label' => 'Madagaskar'),
                                            array('value' => 'MH', 'label' => 'Marshall-Inseln'),
                                            array('value' => 'MK', 'label' => 'Mazedonien'),
                                            array('value' => 'ML', 'label' => 'Mali'),
                                            
                                            array('value' => 'MM', 'label' => 'Myanmar (Burma)'),
                                            array('value' => 'MN', 'label' => 'Mongolei'),
                                            array('value' => 'MO', 'label' => 'Macao'),
                                            array('value' => 'MP', 'label' => 'Nordliche Mariannen Insel'),
                                            array('value' => 'MQ', 'label' => 'Martinique'),
                                            array('value' => 'MR', 'label' => 'Mauretanien'),
                                            
                                            array('value' => 'MS', 'label' => 'Montserrat'),
                                            array('value' => 'MT', 'label' => 'Malta'),
                                            array('value' => 'MU', 'label' => 'Mauritius'),
                                            array('value' => 'MV', 'label' => 'Malediven'),
                                            array('value' => 'MW', 'label' => 'Malawi'),
                                            array('value' => 'MX', 'label' => 'Mexiko'),
                                            
                                            array('value' => 'MY', 'label' => 'Malaysia'),
                                            array('value' => 'MZ', 'label' => 'Mosambik'),
                                            array('value' => 'NA', 'label' => 'Namibia'),
                                            array('value' => 'NC', 'label' => 'Neukaledonien'),
                                            array('value' => 'NE', 'label' => 'Niger'),
                                            array('value' => 'NF', 'label' => 'Norfolk-Insel'),
                                            
                                            array('value' => 'NG', 'label' => 'Nigeria'),
                                            array('value' => 'NI', 'label' => 'Nicaragua'),
                                            array('value' => 'NL', 'label' => 'Niederlande'),
                                            array('value' => 'NO', 'label' => 'Norwegen'),
                                            array('value' => 'NP', 'label' => 'Nepal'),
                                            array('value' => 'NR', 'label' => 'Nauru'),
                                            
                                            array('value' => 'NU', 'label' => 'Niue'),
                                            array('value' => 'NZ', 'label' => 'Neuseeland'),
                                            array('value' => 'OM', 'label' => 'Oman'),
                                            array('value' => 'PA', 'label' => 'Panama'),
                                            array('value' => 'PE', 'label' => 'Peru'),
                                            array('value' => 'PF', 'label' => 'Französisch-Polynesien'),
                                            
                                            array('value' => 'PG', 'label' => 'Papua-Neuguinea'),
                                            array('value' => 'PH', 'label' => 'Philippinen'),
                                            array('value' => 'PK', 'label' => 'Pakistan'),
                                            array('value' => 'PL', 'label' => 'Polen'),
                                            array('value' => 'PM', 'label' => 'St. Pierre und Miquelon'),
                                            array('value' => 'PN', 'label' => 'Pitcairn-Inseln'),
                                            
                                            array('value' => 'PR', 'label' => 'Puerto Rico'),
                                            array('value' => 'PT', 'label' => 'Portugal'),
                                            array('value' => 'PW', 'label' => 'Palau'),
                                            array('value' => 'PY', 'label' => 'Paraguay'),
                                            array('value' => 'QA', 'label' => 'Katar'),
                                            array('value' => 'RE', 'label' => 'Réunion'),
                                            
                                            array('value' => 'RO', 'label' => 'Rumänien'),
                                            array('value' => 'RU', 'label' => 'Russland'),
                                            array('value' => 'RW', 'label' => 'Ruanda'),
                                            array('value' => 'SA', 'label' => 'Saudi-Arabien'),
                                            array('value' => 'SB', 'label' => 'Salomon-Inseln'),
                                            array('value' => 'SC', 'label' => 'Seyschellen'),
                                            
                                            array('value' => 'SD', 'label' => 'Sudan'),
                                            array('value' => 'SE', 'label' => 'Schweden'),
                                            array('value' => 'SG', 'label' => 'Singapur'),
                                            array('value' => 'SH', 'label' => 'St. Helena'),
                                            array('value' => 'SI', 'label' => 'Slowenien'),
                                            array('value' => 'SJ', 'label' => 'Svalbard und Jan Mayen'),
                                            
                                            array('value' => 'SK', 'label' => 'Slowakei'),
                                            array('value' => 'SL', 'label' => 'Sierra Leone'),
                                            array('value' => 'SM', 'label' => 'SanMarino'),
                                            array('value' => 'SN', 'label' => 'Senegal'),
                                            array('value' => 'SO', 'label' => 'Somalia'),
                                            array('value' => 'SR', 'label' => 'Surinam'),
                                            
                                            array('value' => 'ST', 'label' => 'SaoToma und Principe'),
                                            array('value' => 'SV', 'label' => 'El Salvador'),
                                            array('value' => 'SY', 'label' => 'Syrien'),
                                            array('value' => 'SZ', 'label' => 'Swasiland'),
                                            array('value' => 'TC', 'label' => 'Turks-, Caicos-Inseln'),
                                            array('value' => 'TD', 'label' => 'Tschad'),
                                            
                                            array('value' => 'TG', 'label' => 'Togo'),
                                            array('value' => 'TH', 'label' => 'Thailand'),
                                            array('value' => 'TJ', 'label' => 'Tadschikistan'),
                                            array('value' => 'TK', 'label' => 'Tokelau'),
                                            array('value' => 'TM', 'label' => 'Turkmenistan'),
                                            array('value' => 'TN', 'label' => 'Tunesien'),
                                            
                                            array('value' => 'TO', 'label' => 'Tonga'),
                                            array('value' => 'TP', 'label' => 'Ost-Timor'),
                                            array('value' => 'TR', 'label' => 'Türkei'),
                                            array('value' => 'TT', 'label' => 'Trinidad und Tobago'),
                                            array('value' => 'TV', 'label' => 'Tuvalu'),
                                            array('value' => 'TW', 'label' => 'Taiwan'),
                                            
                                            array('value' => 'TZ', 'label' => 'Tansania'),
                                            array('value' => 'UA', 'label' => 'Ukraine'),
                                            array('value' => 'UG', 'label' => 'Uganda'),
                                            array('value' => 'UM', 'label' => 'Übrige Inseln im Pazifik der USA'),
                                            array('value' => 'US', 'label' => 'Vereinigte Staaten von Amerika'),
                                            array('value' => 'UY', 'label' => 'Uruguay'),
                                            
                                            array('value' => 'UZ', 'label' => 'Usbekistan'),
                                            array('value' => 'VA', 'label' => 'Vatikanstadt'),
                                            array('value' => 'VC', 'label' => 'St. Vincent und die Grenadinen'),
                                            array('value' => 'VE', 'label' => 'Venezuela'),
                                            array('value' => 'VG', 'label' => 'Jungfern-Inseln (UK)'),
                                            array('value' => 'VI', 'label' => 'Jungfern-Inseln (USA)'),
                                            
                                            array('value' => 'VN', 'label' => 'Vietnam'),
                                            array('value' => 'VU', 'label' => 'Vanuatu'),
                                            array('value' => 'WF', 'label' => 'Wallis und Futuna'),
                                            array('value' => 'WS', 'label' => 'Samoa'),
                                            array('value' => 'YE', 'label' => 'Jemen'),
                                            array('value' => 'YT', 'label' => 'Mayotte'),
                                            
                                            array('value' => 'YU', 'label' => 'Jugoslawien'),
                                            array('value' => 'ZA', 'label' => 'Südafrika'),
                                            array('value' => 'ZM', 'label' => 'Sambia'),
                                            array('value' => 'ZR', 'label' => 'Zaire'),
                                            array('value' => 'ZW', 'label' => 'Simbabwe'),
                                        )
                            );
           
   /**
	* Returns a list of all countries
	*
	* @access public
	* @return array The values for the patForms element
	*/
	function getValues($element)
	{
		$language = $element->getLocale();
		
	    if (isset($this->countryList[$language])) {
	    	return $this->countryList[$language];
	    }
	    return $this->countryList['C'];
	}
}
?>