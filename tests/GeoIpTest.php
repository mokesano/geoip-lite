<?php

namespace Lumera\GeoIp\Tests;

use PHPUnit\Framework\TestCase;
use Lumera\GeoIp\GeoIp;

/**
 * Tests for the GeoIp class
 */
class GeoIpTest extends TestCase
{
    private string $databasePath;

    protected function setUp(): void
    {
        // Path to the test database
        $this->databasePath = __DIR__ . '/../../data/GeoLiteCity.dat';
        
        if (!file_exists($this->databasePath)) {
            $this->markTestSkipped('GeoLiteCity.dat database file not found');
        }
    }

    /**
     * Test that we can open a GeoIP database file
     */
    public function testGeoIpOpen(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        $this->assertInstanceOf(GeoIp::class, $gi);
        $this->assertNotNull($gi->filehandle);
        
        $gi->geoip_close();
    }

    /**
     * Test closing a GeoIP database
     */
    public function testGeoIpClose(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        $result = $gi->geoip_close();
        
        $this->assertTrue($result);
    }

    /**
     * Test country code lookup by IP address
     */
    public function testGeoipCountryCodeByAddr(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP (Google DNS)
        $countryCode = $gi->geoip_country_code_by_addr('8.8.8.8');
        
        $this->assertIsString($countryCode);
        $this->assertEquals(2, strlen($countryCode));
        
        $gi->geoip_close();
    }

    /**
     * Test country name lookup by IP address
     */
    public function testGeoipCountryNameByAddr(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP (Google DNS)
        $countryName = $gi->geoip_country_name_by_addr('8.8.8.8');
        
        $this->assertIsString($countryName);
        $this->assertNotEmpty($countryName);
        
        $gi->geoip_close();
    }

    /**
     * Test city record lookup by IP address
     */
    public function testGeoipRecordByAddr(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP
        $record = $gi->geoip_record_by_addr('8.8.8.8');
        
        if ($record !== false) {
            $this->assertInstanceOf(\Lumera\GeoIp\GeoIpCityRecord::class, $record);
        }
        
        $gi->geoip_close();
    }

    /**
     * Test region lookup by IP address
     */
    public function testGeoipRegionByAddr(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP
        $region = $gi->geoip_region_by_addr('8.8.8.8');
        
        if ($region !== false) {
            $this->assertIsArray($region);
            $this->assertArrayHasKey('country_code', $region);
            $this->assertArrayHasKey('region', $region);
        }
        
        $gi->geoip_close();
    }

    /**
     * Test invalid IP address handling
     */
    public function testInvalidIpAddress(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        $countryCode = $gi->geoip_country_code_by_addr('invalid.ip.address');
        
        $this->assertFalse($countryCode);
        
        $gi->geoip_close();
    }

    /**
     * Test database type detection
     */
    public function testDatabaseType(): void
    {
        $gi = GeoIp::geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        $this->assertIsString($gi->databaseType);
        $this->assertNotEmpty($gi->databaseType);
        
        $gi->geoip_close();
    }
}
