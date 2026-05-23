<?php

namespace Lumera\GeoIp;

use Lumera\GeoIp\Exception\GeoIpException;

/**
 * GeoIP class for country and city lookups
 */
class GeoIp
{
    public $flags;
    public $filehandle;
    public $memory_buffer;
    public $databaseType;
    public $databaseSegments;
    public $record_length;
    public $shmid;

    public const GEOIP_COUNTRY_CODE_TO_NUMBER = [
        "" => 0, "AP" => 1, "EU" => 2, "AD" => 3, "AE" => 4, "AF" => 5,
        "AG" => 6, "AI" => 7, "AL" => 8, "AM" => 9, "CW" => 10, "AO" => 11,
        "AQ" => 12, "AR" => 13, "AS" => 14, "AT" => 15, "AU" => 16, "AW" => 17,
        "AZ" => 18, "BA" => 19, "BB" => 20, "BD" => 21, "BE" => 22, "BF" => 23,
        "BG" => 24, "BH" => 25, "BI" => 26, "BJ" => 27, "BM" => 28, "BN" => 29,
        "BO" => 30, "BR" => 31, "BS" => 32, "BT" => 33, "BV" => 34, "BW" => 35,
        "BY" => 36, "BZ" => 37, "CA" => 38, "CC" => 39, "CD" => 40, "CF" => 41,
        "CG" => 42, "CH" => 43, "CI" => 44, "CK" => 45, "CL" => 46, "CM" => 47,
        "CN" => 48, "CO" => 49, "CR" => 50, "CU" => 51, "CV" => 52, "CX" => 53,
        "CY" => 54, "CZ" => 55, "DE" => 56, "DJ" => 57, "DK" => 58, "DM" => 59,
        "DO" => 60, "DZ" => 61, "EC" => 62, "EE" => 63, "EG" => 64, "EH" => 65,
        "ER" => 66, "ES" => 67, "ET" => 68, "FI" => 69, "FJ" => 70, "FK" => 71,
        "FM" => 72, "FO" => 73, "FR" => 74, "SX" => 75, "GA" => 76, "GB" => 77,
        "GD" => 78, "GE" => 79, "GF" => 80, "GH" => 81, "GI" => 82, "GL" => 83,
        "GM" => 84, "GN" => 85, "GP" => 86, "GQ" => 87, "GR" => 88, "GS" => 89,
        "GT" => 90, "GU" => 91, "GW" => 92, "GY" => 93, "HK" => 94, "HM" => 95,
        "HN" => 96, "HR" => 97, "HT" => 98, "HU" => 99, "ID" => 100, "IE" => 101,
        "IL" => 102, "IN" => 103, "IO" => 104, "IQ" => 105, "IR" => 106, "IS" => 107,
        "IT" => 108, "JM" => 109, "JO" => 110, "JP" => 111, "KE" => 112, "KG" => 113,
        "KH" => 114, "KI" => 115, "KM" => 116, "KN" => 117, "KP" => 118, "KR" => 119,
        "KW" => 120, "KY" => 121, "KZ" => 122, "LA" => 123, "LB" => 124, "LC" => 125,
        "LI" => 126, "LK" => 127, "LR" => 128, "LS" => 129, "LT" => 130, "LU" => 131,
        "LV" => 132, "LY" => 133, "MA" => 134, "MC" => 135, "MD" => 136, "MG" => 137,
        "MH" => 138, "MK" => 139, "ML" => 140, "MM" => 141, "MN" => 142, "MO" => 143,
        "MP" => 144, "MQ" => 145, "MR" => 146, "MS" => 147, "MT" => 148, "MU" => 149,
        "MV" => 150, "MW" => 151, "MX" => 152, "MY" => 153, "MZ" => 154, "NA" => 155,
        "NC" => 156, "NE" => 157, "NF" => 158, "NG" => 159, "NI" => 160, "NL" => 161,
        "NO" => 162, "NP" => 163, "NR" => 164, "NU" => 165, "NZ" => 166, "OM" => 167,
        "PA" => 168, "PE" => 169, "PF" => 170, "PG" => 171, "PH" => 172, "PK" => 173,
        "PL" => 174, "PM" => 175, "PN" => 176, "PR" => 177, "PS" => 178, "PT" => 179,
        "PW" => 180, "PY" => 181, "QA" => 182, "RE" => 183, "RO" => 184, "RU" => 185,
        "RW" => 186, "SA" => 187, "SB" => 188, "SC" => 189, "SD" => 190, "SE" => 191,
        "SG" => 192, "SH" => 193, "SI" => 194, "SJ" => 195, "SK" => 196, "SL" => 197,
        "SM" => 198, "SN" => 199, "SO" => 200, "SR" => 201, "ST" => 202, "SV" => 203,
        "SY" => 204, "SZ" => 205, "TC" => 206, "TD" => 207, "TF" => 208, "TG" => 209,
        "TH" => 210, "TJ" => 211, "TK" => 212, "TM" => 213, "TN" => 214, "TO" => 215,
        "TL" => 216, "TR" => 217, "TT" => 218, "TV" => 219, "TW" => 220, "TZ" => 221,
        "UA" => 222, "UG" => 223, "UM" => 224, "US" => 225, "UY" => 226, "UZ" => 227,
        "VA" => 228, "VC" => 229, "VE" => 230, "VG" => 231, "VI" => 232, "VN" => 233,
        "VU" => 234, "WF" => 235, "WS" => 236, "YE" => 237, "YT" => 238, "RS" => 239,
        "ZA" => 240, "ZM" => 241, "ME" => 242, "ZW" => 243, "A1" => 244, "A2" => 245,
        "O1" => 246, "AX" => 247, "GG" => 248, "IM" => 249, "JE" => 250, "BL" => 251,
        "MF" => 252, "BQ" => 253,
    ];

    public const GEOIP_COUNTRY_CODES = [
        "", "AP", "EU", "AD", "AE", "AF", "AG", "AI", "AL", "AM", "CW",
        "AO", "AQ", "AR", "AS", "AT", "AU", "AW", "AZ", "BA", "BB",
        "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BM", "BN", "BO",
        "BR", "BS", "BT", "BV", "BW", "BY", "BZ", "CA", "CC", "CD",
        "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN", "CO", "CR",
        "CU", "CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO",
        "DZ", "EC", "EE", "EG", "EH", "ER", "ES", "ET", "FI", "FJ",
        "FK", "FM", "FO", "FR", "SX", "GA", "GB", "GD", "GE", "GF",
        "GH", "GI", "GL", "GM", "GN", "GP", "GQ", "GR", "GS", "GT",
        "GU", "GW", "GY", "HK", "HM", "HN", "HR", "HT", "HU", "ID",
        "IE", "IL", "IN", "IO", "IQ", "IR", "IS", "IT", "JM", "JO",
        "JP", "KE", "KG", "KH", "KI", "KM", "KN", "KP", "KR", "KW",
        "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR", "LS", "LT",
        "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MK", "ML",
        "MM", "MN", "MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MV",
        "MW", "MX", "MY", "MZ", "NA", "NC", "NE", "NF", "NG", "NI",
        "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "PA", "PE", "PF",
        "PG", "PH", "PK", "PL", "PM", "PN", "PR", "PS", "PT", "PW",
        "PY", "QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD",
        "SE", "SG", "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO",
        "SR", "ST", "SV", "SY", "SZ", "TC", "TD", "TF", "TG", "TH",
        "TJ", "TK", "TM", "TN", "TO", "TL", "TR", "TT", "TV", "TW",
        "TZ", "UA", "UG", "UM", "US", "UY", "UZ", "VA", "VC", "VE",
        "VG", "VI", "VN", "VU", "WF", "WS", "YE", "YT", "RS", "ZA",
        "ZM", "ME", "ZW", "A1", "A2", "O1", "AX", "GG", "IM", "JE",
        "BL", "MF", "BQ"
    ];

    public const GEOIP_COUNTRY_CODES3 = [
        "", "AP", "EU", "AND", "ARE", "AFG", "ATG", "AIA", "ALB", "ARM", "CUW",
        "AGO", "ATA", "ARG", "ASM", "AUT", "AUS", "ABW", "AZE", "BIH", "BRB",
        "BGD", "BEL", "BFA", "BGR", "BHR", "BDI", "BEN", "BMU", "BRN", "BOL",
        "BRA", "BHS", "BTN", "BVT", "BWA", "BLR", "BLZ", "CAN", "CCK", "COD",
        "CAF", "COG", "CHE", "CIV", "COK", "CHL", "CMR", "CHN", "COL", "CRI",
        "CUB", "CPV", "CXR", "CYP", "CZE", "DEU", "DJI", "DNK", "DMA", "DOM",
        "DZA", "ECU", "EST", "EGY", "ESH", "ERI", "ESP", "ETH", "FIN", "FJI",
        "FLK", "FSM", "FRO", "FRA", "SXM", "GAB", "GBR", "GRD", "GEO", "GUF",
        "GHA", "GIB", "GRL", "GMB", "GIN", "GLP", "GNQ", "GRC", "SGS", "GTM",
        "GUM", "GNB", "GUY", "HKG", "HMD", "HND", "HRV", "HTI", "HUN", "IDN",
        "IRL", "ISR", "IND", "IOT", "IRQ", "IRN", "ISL", "ITA", "JAM", "JOR",
        "JPN", "KEN", "KGZ", "KHM", "KIR", "COM", "KNA", "PRK", "KOR", "KWT",
        "CYM", "KAZ", "LAO", "LBN", "LCA", "LIE", "LKA", "LBR", "LSO", "LTU",
        "LUX", "LVA", "LBY", "MAR", "MCO", "MDA", "MDG", "MHL", "MKD", "MLI",
        "MMR", "MNG", "MAC", "MNP", "MTQ", "MRT", "MSR", "MLT", "MUS", "MDV",
        "MWI", "MEX", "MYS", "MOZ", "NAM", "NCL", "NER", "NFK", "NGA", "NIC",
        "NLD", "NOR", "NPL", "NRU", "NIU", "NZL", "OMN", "PAN", "PER", "PYF",
        "PNG", "PHL", "PAK", "POL", "SPM", "PCN", "PRI", "PSE", "PRT", "PLW",
        "PRY", "QAT", "REU", "ROU", "RUS", "RWA", "SAU", "SLB", "SYC", "SDN",
        "SWE", "SGP", "SHN", "SVN", "SJM", "SVK", "SLE", "SMR", "SEN", "SOM",
        "SUR", "STP", "SLV", "SYR", "SWZ", "TCA", "TCD", "ATF", "TGO", "THA",
        "TJK", "TKL", "TKM", "TUN", "TON", "TLS", "TUR", "TTO", "TUV", "TWN",
        "TZA", "UKR", "UGA", "UMI", "USA", "URY", "UZB", "VAT", "VCT", "VEN",
        "VGB", "VIR", "VNM", "VUT", "WLF", "WSM", "YEM", "MYT", "SRB", "ZAF",
        "ZMB", "MNE", "ZWE", "A1", "A2", "O1", "ALA", "GGY", "IMN", "JEY",
        "BLM", "MAF", "BES"
    ];

    public const GEOIP_COUNTRY_NAMES = [
        "", "Asia/Pacific Region", "Europe", "Andorra", "United Arab Emirates", "Afghanistan", "Antigua and Barbuda", "Anguilla", "Albania", "Armenia", "Curacao",
        "Angola", "Antarctica", "Argentina", "American Samoa", "Austria", "Australia", "Aruba", "Azerbaijan", "Bosnia and Herzegovina", "Barbados",
        "Bangladesh", "Belgium", "Burkina Faso", "Bulgaria", "Bahrain", "Burundi", "Benin", "Bermuda", "Brunei Darussalam", "Bolivia",
        "Brazil", "Bahamas", "Bhutan", "Bouvet Island", "Botswana", "Belarus", "Belize", "Canada", "Cocos (Keeling) Islands", "Congo, The Democratic Republic of the",
        "Central African Republic", "Congo", "Switzerland", "Cote D'Ivoire", "Cook Islands", "Chile", "Cameroon", "China", "Colombia", "Costa Rica",
        "Cuba", "Cape Verde", "Christmas Island", "Cyprus", "Czech Republic", "Germany", "Djibouti", "Denmark", "Dominica", "Dominican Republic",
        "Algeria", "Ecuador", "Estonia", "Egypt", "Western Sahara", "Eritrea", "Spain", "Ethiopia", "Finland", "Fiji",
        "Falkland Islands (Malvinas)", "Micronesia, Federated States of", "Faroe Islands", "France", "Sint Maarten (Dutch part)", "Gabon", "United Kingdom", "Grenada", "Georgia", "French Guiana",
        "Ghana", "Gibraltar", "Greenland", "Gambia", "Guinea", "Guadeloupe", "Equatorial Guinea", "Greece", "South Georgia and the South Sandwich Islands", "Guatemala",
        "Guam", "Guinea-Bissau", "Guyana", "Hong Kong", "Heard Island and McDonald Islands", "Honduras", "Croatia", "Haiti", "Hungary", "Indonesia",
        "Ireland", "Israel", "India", "British Indian Ocean Territory", "Iraq", "Iran, Islamic Republic of", "Iceland", "Italy", "Jamaica", "Jordan",
        "Japan", "Kenya", "Kyrgyzstan", "Cambodia", "Kiribati", "Comoros", "Saint Kitts and Nevis", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait",
        "Cayman Islands", "Kazakhstan", "Lao People's Democratic Republic", "Lebanon", "Saint Lucia", "Liechtenstein", "Sri Lanka", "Liberia", "Lesotho", "Lithuania",
        "Luxembourg", "Latvia", "Libya", "Morocco", "Monaco", "Moldova, Republic of", "Madagascar", "Marshall Islands", "Macedonia", "Mali",
        "Myanmar", "Mongolia", "Macau", "Northern Mariana Islands", "Martinique", "Mauritania", "Montserrat", "Malta", "Mauritius", "Maldives",
        "Malawi", "Mexico", "Malaysia", "Mozambique", "Namibia", "New Caledonia", "Niger", "Norfolk Island", "Nigeria", "Nicaragua",
        "Netherlands", "Norway", "Nepal", "Nauru", "Niue", "New Zealand", "Oman", "Panama", "Peru", "French Polynesia",
        "Papua New Guinea", "Philippines", "Pakistan", "Poland", "Saint Pierre and Miquelon", "Pitcairn Islands", "Puerto Rico", "Palestinian Territory", "Portugal", "Palau",
        "Paraguay", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saudi Arabia", "Solomon Islands", "Seychelles", "Sudan",
        "Sweden", "Singapore", "Saint Helena", "Slovenia", "Svalbard and Jan Mayen", "Slovakia", "Sierra Leone", "San Marino", "Senegal", "Somalia", "Suriname",
        "Sao Tome and Principe", "El Salvador", "Syrian Arab Republic", "Swaziland", "Turks and Caicos Islands", "Chad", "French Southern Territories", "Togo", "Thailand",
        "Tajikistan", "Tokelau", "Turkmenistan", "Tunisia", "Tonga", "Timor-Leste", "Turkey", "Trinidad and Tobago", "Tuvalu", "Taiwan",
        "Tanzania, United Republic of", "Ukraine", "Uganda", "United States Minor Outlying Islands", "United States", "Uruguay", "Uzbekistan", "Holy See (Vatican City State)", "Saint Vincent and the Grenadines", "Venezuela",
        "Virgin Islands, British", "Virgin Islands, U.S.", "Vietnam", "Vanuatu", "Wallis and Futuna", "Samoa", "Yemen", "Mayotte", "Serbia", "South Africa",
        "Zambia", "Montenegro", "Zimbabwe", "Anonymous Proxy", "Satellite Provider", "Other", "Aland Islands", "Guernsey", "Isle of Man", "Jersey",
        "Saint Barthelemy", "Saint Martin", "Bonaire, Saint Eustatius and Saba"
    ];

    public const GEOIP_CONTINENT_CODES = [
        "--", "AS", "EU", "EU", "AS", "AS", "NA", "NA", "EU", "AS", "NA",
        "AF", "AN", "SA", "OC", "EU", "OC", "NA", "AS", "EU", "NA",
        "AS", "EU", "AF", "EU", "AS", "AF", "AF", "NA", "AS", "SA",
        "SA", "NA", "AS", "AN", "AF", "EU", "NA", "NA", "AS", "AF",
        "AF", "AF", "EU", "AF", "OC", "SA", "AF", "AS", "SA", "NA",
        "NA", "AF", "AS", "AS", "EU", "EU", "AF", "EU", "NA", "NA",
        "AF", "SA", "EU", "AF", "AF", "AF", "EU", "AF", "EU", "OC",
        "SA", "OC", "EU", "EU", "NA", "AF", "EU", "NA", "AS", "SA",
        "AF", "EU", "NA", "AF", "AF", "NA", "AF", "EU", "AN", "NA",
        "OC", "AF", "SA", "AS", "AN", "NA", "EU", "NA", "EU", "AS",
        "EU", "AS", "AS", "AS", "AS", "AS", "EU", "EU", "NA", "AS",
        "AS", "AF", "AS", "AS", "OC", "AF", "NA", "AS", "AS", "AS",
        "NA", "AS", "AS", "AS", "NA", "EU", "AS", "AF", "AF", "EU",
        "EU", "EU", "AF", "AF", "EU", "EU", "AF", "OC", "EU", "AF",
        "AS", "AS", "AS", "OC", "NA", "AF", "NA", "EU", "AF", "AS",
        "AF", "NA", "AS", "AF", "AF", "OC", "AF", "OC", "AF", "NA",
        "EU", "EU", "AS", "OC", "OC", "OC", "AS", "NA", "SA", "OC",
        "OC", "AS", "AS", "EU", "NA", "OC", "NA", "AS", "EU", "OC",
        "SA", "AS", "AF", "EU", "EU", "AF", "AS", "OC", "AF", "AF",
        "EU", "AS", "AF", "EU", "EU", "EU", "AF", "EU", "AF", "AF",
        "SA", "AF", "NA", "AS", "AF", "NA", "AF", "AN", "AF", "AS",
        "AS", "OC", "AS", "AF", "OC", "AS", "EU", "NA", "OC", "AS",
        "AF", "EU", "AF", "OC", "NA", "SA", "AS", "EU", "NA", "SA",
        "NA", "NA", "AS", "OC", "OC", "OC", "AS", "AF", "EU", "AF",
        "AF", "EU", "AF", "--", "--", "--", "EU", "EU", "EU", "EU",
        "NA", "NA", "NA"
    ];

    // Database types
    public const GEOIP_COUNTRY_EDITION = 1;
    public const GEOIP_REGION_EDITION_REV0 = 7;
    public const GEOIP_REGION_EDITION_REV1 = 3;
    public const GEOIP_CITY_EDITION_REV0 = 6;
    public const GEOIP_CITY_EDITION_REV1 = 2;
    public const GEOIP_ORG_EDITION = 5;
    public const GEOIP_ISP_EDITION = 4;
    public const GEOIP_DOMAIN_EDITION = 7;
    public const GEOIP_NETSPEED_EDITION = 8;
    public const GEOIP_ACCURACYRADIUS_EDITION = 12;
    public const GEOIP_ASNUM_EDITION = 9;
    public const GEOIP_COUNTRY_EDITION_V6 = 14;

    // Flags
    public const GEOIP_STANDARD = 0;
    public const GEOIP_MEMORY_CACHE = 1;
    public const GEOIP_SHARED_MEMORY = 2;
    public const GEOIP_INDEX_CACHE = 4;
    public const GEOIP_CHECK_CONSISTENCY = 8;
    public const GEOIP_SILENCE_WARNINGS = 16;

    private static $instances = [];

    /**
     * Open a GeoIP database file
     *
     * @param string $filename Path to the database file
     * @param int $flags Flags for opening the database
     * @return GeoIp Returns the GeoIp object
     * @throws GeoIpException
     */
    public static function geoip_open(string $filename, int $flags = self::GEOIP_STANDARD): self
    {
        if (!file_exists($filename)) {
            throw new GeoIpException("Database file not found: {$filename}");
        }

        $gi = new self();
        $gi->flags = $flags;
        $gi->filehandle = fopen($filename, 'rb');

        if ($gi->filehandle === false) {
            throw new GeoIpException("Cannot open database file: {$filename}");
        }

        // Read database metadata
        fseek($gi->filehandle, -3, SEEK_END);
        $buf = fread($gi->filehandle, 3);
        
        if (strlen($buf) !== 3) {
            fclose($gi->filehandle);
            throw new GeoIpException("Invalid database format");
        }

        $edition = unpack('C*', $buf);
        $gi->databaseType = $edition[1] + ($edition[2] ?? 0) * 256 + ($edition[3] ?? 0) * 65536;

        // Find database segments
        if ($gi->databaseType === self::GEOIP_COUNTRY_EDITION || 
            $gi->databaseType === self::GEOIP_COUNTRY_EDITION_V6) {
            $gi->databaseSegments = self::_find_segment($gi, $gi->databaseType);
            $gi->record_length = 3;
        } elseif ($gi->databaseType === self::GEOIP_CITY_EDITION_REV0 || 
                  $gi->databaseType === self::GEOIP_CITY_EDITION_REV1) {
            $gi->databaseSegments = self::_find_segment($gi, $gi->databaseType);
            $gi->record_length = 4;
        } else {
            $gi->databaseSegments = self::_find_segment($gi, $gi->databaseType);
            $gi->record_length = 3;
        }

        // Load into memory if requested
        if ($flags & self::GEOIP_MEMORY_CACHE) {
            fseek($gi->filehandle, 0, SEEK_SET);
            $gi->memory_buffer = fread($gi->filehandle, filesize($filename));
            fclose($gi->filehandle);
            $gi->filehandle = null;
        }

        return $gi;
    }

    /**
     * Close a GeoIP database
     *
     * @param GeoIp|null $gi The GeoIp object to close
     * @return bool True on success
     */
    public static function geoip_close(?self $gi): bool
    {
        if ($gi === null) {
            return false;
        }

        if ($gi->filehandle !== null && !($gi->flags & self::GEOIP_MEMORY_CACHE)) {
            fclose($gi->filehandle);
        }

        $gi->filehandle = null;
        $gi->memory_buffer = null;

        return true;
    }

    /**
     * Look up country code by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address to look up
     * @return string Country code (2 letters) or empty string if not found
     */
    public static function geoip_country_code_by_addr(self $gi, string $addr): string
    {
        $countryId = self::_get_country_id($gi, $addr);
        if ($countryId < 1 || $countryId > 253) {
            return '';
        }
        return self::GEOIP_COUNTRY_CODES[$countryId];
    }

    /**
     * Look up country name by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address to look up
     * @return string Country name or empty string if not found
     */
    public static function geoip_country_name_by_addr(self $gi, string $addr): string
    {
        $countryId = self::_get_country_id($gi, $addr);
        if ($countryId < 1 || $countryId > 253) {
            return '';
        }
        return self::GEOIP_COUNTRY_NAMES[$countryId];
    }

    /**
     * Look up country ID by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address to look up
     * @return int Country ID (1-253) or 0 if not found
     */
    public static function geoip_id_by_addr(self $gi, string $addr): int
    {
        return self::_get_country_id($gi, $addr);
    }

    /**
     * Look up city record by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address to look up
     * @return GeoIpCityRecord|null City record object or null if not found
     */
    public static function geoip_record_by_addr(self $gi, string $addr): ?GeoIpCityRecord
    {
        if ($gi->databaseType !== self::GEOIP_CITY_EDITION_REV0 && 
            $gi->databaseType !== self::GEOIP_CITY_EDITION_REV1) {
            return null;
        }

        $ipnum = self::_ip_to_number($addr);
        if ($ipnum === null) {
            return null;
        }

        $offset = self::_seek_country_v6($gi, $ipnum);
        if ($offset === 0) {
            return null;
        }

        $record = self::_get_city_record($gi, $offset);
        if ($record === null) {
            return null;
        }

        return new GeoIpCityRecord($record);
    }

    /**
     * Get organization by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address to look up
     * @return string|null Organization name or null if not found
     */
    public static function geoip_org_by_addr(self $gi, string $addr): ?string
    {
        return self::_get_org_or_isp($gi, $addr);
    }

    /**
     * Get ISP by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address to look up
     * @return string|null ISP name or null if not found
     */
    public static function geoip_isp_by_addr(self $gi, string $addr): ?string
    {
        return self::_get_org_or_isp($gi, $addr);
    }

    /**
     * Convert IP address to number
     *
     * @param string $addr IP address
     * @return int|null IP as number or null if invalid
     */
    private static function _ip_to_number(string $addr): ?int
    {
        $packed = inet_pton($addr);
        if ($packed === false) {
            return null;
        }

        $hex = bin2hex($packed);
        return hexdec($hex);
    }

    /**
     * Find segment in database
     *
     * @param GeoIp $gi The GeoIp object
     * @param int $databaseType Database type
     * @return int Segment offset
     */
    private static function _find_segment(self $gi, int $databaseType): int
    {
        $filepos = ftell($gi->filehandle);
        
        if ($databaseType === self::GEOIP_COUNTRY_EDITION) {
            fseek($gi->filehandle, -3, SEEK_END);
            $buf = fread($gi->filehandle, 3);
            $segment = unpack('C*', $buf);
            $result = ($segment[1] ?? 0) + (($segment[2] ?? 0) << 8) + (($segment[3] ?? 0) << 16);
        } else {
            fseek($gi->filehandle, -3, SEEK_END);
            $buf = fread($gi->filehandle, 3);
            $segment = unpack('C*', $buf);
            $result = ($segment[1] ?? 0) + (($segment[2] ?? 0) << 8) + (($segment[3] ?? 0) << 16);
        }

        fseek($gi->filehandle, $filepos, SEEK_SET);
        return $result;
    }

    /**
     * Get country ID by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address
     * @return int Country ID
     */
    private static function _get_country_id(self $gi, string $addr): int
    {
        $ipnum = self::_ip_to_number($addr);
        if ($ipnum === null) {
            return 0;
        }

        if ($gi->databaseType === self::GEOIP_COUNTRY_EDITION_V6) {
            return self::_seek_country_v6($gi, $ipnum);
        }

        return self::_seek_country($gi, $ipnum);
    }

    /**
     * Seek country in IPv4 database
     *
     * @param GeoIp $gi The GeoIp object
     * @param int $ipnum IP as number
     * @return int Country ID
     */
    private static function _seek_country(self $gi, int $ipnum): int
    {
        $offset = 0;
        
        for ($depth = 31; $depth >= 0; $depth--) {
            $byte = ($ipnum >> $depth) & 1;
            
            if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
                $buffer = $gi->memory_buffer;
                $index = $offset * 6 + $byte * 3;
                $data = substr($buffer, $index, 3);
                $value = unpack('C*', $data);
                $offset = $value[1] + ($value[2] << 8) + ($value[3] << 16);
            } else {
                fseek($gi->filehandle, $offset * 6 + $byte * 3, SEEK_SET);
                $data = fread($gi->filehandle, 3);
                $value = unpack('C*', $data);
                $offset = $value[1] + ($value[2] << 8) + ($value[3] << 16);
            }

            if ($offset >= $gi->databaseSegments) {
                return $offset - $gi->databaseSegments;
            }
        }

        return 0;
    }

    /**
     * Seek country in IPv6 database
     *
     * @param GeoIp $gi The GeoIp object
     * @param int $ipnum IP as number
     * @return int Country ID
     */
    private static function _seek_country_v6(self $gi, int $ipnum): int
    {
        $offset = 0;
        
        for ($depth = 127; $depth >= 0; $depth--) {
            $byte = ($ipnum >> $depth) & 1;
            
            if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
                $buffer = $gi->memory_buffer;
                $index = $offset * 6 + $byte * 3;
                $data = substr($buffer, $index, 3);
                $value = unpack('C*', $data);
                $offset = $value[1] + ($value[2] << 8) + ($value[3] << 16);
            } else {
                fseek($gi->filehandle, $offset * 6 + $byte * 3, SEEK_SET);
                $data = fread($gi->filehandle, 3);
                $value = unpack('C*', $data);
                $offset = $value[1] + ($value[2] << 8) + ($value[3] << 16);
            }

            if ($offset >= $gi->databaseSegments) {
                return $offset - $gi->databaseSegments;
            }
        }

        return 0;
    }

    /**
     * Get city record from database
     *
     * @param GeoIp $gi The GeoIp object
     * @param int $offset Offset in database
     * @return array|null City record data or null if not found
     */
    private static function _get_city_record(self $gi, int $offset): ?array
    {
        $record = [
            'country_code' => '',
            'country_name' => '',
            'region' => '',
            'city' => '',
            'postal_code' => '',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'metro_code' => 0,
            'area_code' => 0,
        ];

        // Read country code
        if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
            $countryId = ord(substr($gi->memory_buffer, $offset, 1));
            $offset++;
        } else {
            fseek($gi->filehandle, $offset, SEEK_SET);
            $data = fread($gi->filehandle, 1);
            $countryId = ord($data);
            $offset++;
        }

        if ($countryId > 0 && $countryId <= 253) {
            $record['country_code'] = self::GEOIP_COUNTRY_CODES[$countryId];
            $record['country_name'] = self::GEOIP_COUNTRY_NAMES[$countryId];
        }

        // Read region
        $region = self::_read_string($gi, $offset);
        $record['region'] = $region['value'];
        $offset = $region['offset'];

        // Read city
        $city = self::_read_string($gi, $offset);
        $record['city'] = $city['value'];
        $offset = $city['offset'];

        // Read postal code
        if ($gi->databaseType === self::GEOIP_CITY_EDITION_REV1) {
            $postal = self::_read_string($gi, $offset);
            $record['postal_code'] = $postal['value'];
            $offset = $postal['offset'];
        }

        // Read latitude and longitude
        if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
            $latBuf = substr($gi->memory_buffer, $offset, 4);
            $lonBuf = substr($gi->memory_buffer, $offset + 4, 4);
        } else {
            fseek($gi->filehandle, $offset, SEEK_SET);
            $latBuf = fread($gi->filehandle, 4);
            $lonBuf = fread($gi->filehandle, 4);
        }

        $latVal = unpack('f', $latBuf);
        $lonVal = unpack('f', $lonBuf);

        $record['latitude'] = $latVal[1] ?? 0.0;
        $record['longitude'] = $lonVal[1] ?? 0.0;
        $offset += 8;

        // Read metro and area codes
        if ($record['country_code'] === 'US') {
            if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
                $metroBuf = substr($gi->memory_buffer, $offset, 2);
            } else {
                fseek($gi->filehandle, $offset, SEEK_SET);
                $metroBuf = fread($gi->filehandle, 2);
            }
            $metroVal = unpack('v', $metroBuf);
            $record['metro_code'] = $metroVal[1] ?? 0;
            $offset += 2;

            if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
                $areaBuf = substr($gi->memory_buffer, $offset, 2);
            } else {
                fseek($gi->filehandle, $offset, SEEK_SET);
                $areaBuf = fread($gi->filehandle, 2);
            }
            $areaVal = unpack('v', $areaBuf);
            $record['area_code'] = $areaVal[1] ?? 0;
        }

        return $record;
    }

    /**
     * Read string from database
     *
     * @param GeoIp $gi The GeoIp object
     * @param int $offset Starting offset
     * @return array Array with 'value' and 'offset' keys
     */
    private static function _read_string(self $gi, int $offset): array
    {
        $length = 0;
        $c = 0;
        
        do {
            if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
                $c = ord(substr($gi->memory_buffer, $offset, 1));
            } else {
                fseek($gi->filehandle, $offset, SEEK_SET);
                $c = ord(fread($gi->filehandle, 1));
            }
            $offset++;
            $length += $c;
        } while ($c !== 0);

        if ($gi->flags & self::GEOIP_MEMORY_CACHE) {
            $value = substr($gi->memory_buffer, $offset, $length);
        } else {
            fseek($gi->filehandle, $offset, SEEK_SET);
            $value = fread($gi->filehandle, $length);
        }

        return [
            'value' => $value,
            'offset' => $offset + $length,
        ];
    }

    /**
     * Get organization or ISP by IP address
     *
     * @param GeoIp $gi The GeoIp object
     * @param string $addr IP address
     * @return string|null Organization/ISP name or null if not found
     */
    private static function _get_org_or_isp(self $gi, string $addr): ?string
    {
        $ipnum = self::_ip_to_number($addr);
        if ($ipnum === null) {
            return null;
        }

        $offset = self::_seek_country($gi, $ipnum);
        if ($offset === 0) {
            return null;
        }

        $result = self::_read_string($gi, $offset);
        return $result['value'] ?: null;
    }
}
