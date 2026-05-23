<?php

if (!function_exists('geoip_open')) {
    /**
     * Open a GeoIP database file (procedural wrapper)
     *
     * @param string $filename Path to the GeoIP database file
     * @param int $flags Flags for opening the database
     * @return Lumera\GeoIp\GeoIp Returns the GeoIP object
     */
    function geoip_open(string $filename, int $flags): Lumera\GeoIp\GeoIp
    {
        return Lumera\GeoIp\GeoIp::geoip_open($filename, $flags);
    }
}

if (!function_exists('geoip_close')) {
    /**
     * Close a GeoIP database (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @return bool True on success, false on failure
     */
    function geoip_close(Lumera\GeoIp\GeoIp $gi): bool
    {
        return $gi->geoip_close();
    }
}

if (!function_exists('geoip_country_code_by_addr')) {
    /**
     * Get country code by IP address (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $addr IP address
     * @return string|false Country code or false on failure
     */
    function geoip_country_code_by_addr(Lumera\GeoIp\GeoIp $gi, string $addr)
    {
        return $gi->geoip_country_code_by_addr($addr);
    }
}

if (!function_exists('geoip_country_code_by_name')) {
    /**
     * Get country code by hostname (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $name Hostname
     * @return string|false Country code or false on failure
     */
    function geoip_country_code_by_name(Lumera\GeoIp\GeoIp $gi, string $name)
    {
        return $gi->geoip_country_code_by_name($name);
    }
}

if (!function_exists('geoip_country_name_by_addr')) {
    /**
     * Get country name by IP address (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $addr IP address
     * @return string|false Country name or false on failure
     */
    function geoip_country_name_by_addr(Lumera\GeoIp\GeoIp $gi, string $addr)
    {
        return $gi->geoip_country_name_by_addr($addr);
    }
}

if (!function_exists('geoip_country_name_by_name')) {
    /**
     * Get country name by hostname (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $name Hostname
     * @return string|false Country name or false on failure
     */
    function geoip_country_name_by_name(Lumera\GeoIp\GeoIp $gi, string $name)
    {
        return $gi->geoip_country_name_by_name($name);
    }
}

if (!function_exists('geoip_record_by_addr')) {
    /**
     * Get city record by IP address (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $addr IP address
     * @return Lumera\GeoIp\GeoIpCityRecord|false City record or false on failure
     */
    function geoip_record_by_addr(Lumera\GeoIp\GeoIp $gi, string $addr)
    {
        return $gi->geoip_record_by_addr($addr);
    }
}

if (!function_exists('geoip_region_by_addr')) {
    /**
     * Get region information by IP address (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $addr IP address
     * @return array|false Array with country_code and region, or false on failure
     */
    function geoip_region_by_addr(Lumera\GeoIp\GeoIp $gi, string $addr)
    {
        return $gi->geoip_region_by_addr($addr);
    }
}

if (!function_exists('geoip_org_by_addr')) {
    /**
     * Get organization by IP address (procedural wrapper)
     *
     * @param Lumera\GeoIp\GeoIp $gi GeoIP object
     * @param string $addr IP address
     * @return string|null Organization name or null on failure
     */
    function geoip_org_by_addr(Lumera\GeoIp\GeoIp $gi, string $addr): ?string
    {
        return $gi->geoip_org_by_addr($addr);
    }
}
