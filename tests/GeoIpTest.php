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
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        $this->assertInstanceOf(GeoIp::class, $geoIpInstance);
        $this->assertNotNull($geoIpInstance->filehandle);
        
        $geoIpInstance->geoip_close();
    }

    /**
     * Test closing a GeoIP database
     */
    public function testGeoIpClose(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        $result = $geoIpInstance->geoip_close();
        
        $this->assertTrue($result);
    }

    /**
     * Test country code lookup by IP address
     */
    public function testGeoipCountryCodeByAddr(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP (Google DNS)
        $countryCode = $geoIpInstance->geoip_country_code_by_addr('8.8.8.8');
        
        $this->assertIsString($countryCode);
        $this->assertEquals(2, strlen($countryCode));
        
        $geoIpInstance->geoip_close();
    }

    /**
     * Test country name lookup by IP address
     */
    public function testGeoipCountryNameByAddr(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP (Google DNS)
        $countryName = $geoIpInstance->geoip_country_name_by_addr('8.8.8.8');
        
        $this->assertIsString($countryName);
        $this->assertNotEmpty($countryName);
        
        $geoIpInstance->geoip_close();
    }

    /**
     * Test city record lookup by IP address
     */
    public function testGeoipRecordByAddr(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP
        $record = $geoIpInstance->geoip_record_by_addr('8.8.8.8');
        
        if ($record !== false) {
            $this->assertInstanceOf(\Lumera\GeoIp\GeoIpCityRecord::class, $record);
        }
        
        $geoIpInstance->geoip_close();
    }

    /**
     * Test region lookup by IP address
     */
    public function testGeoipRegionByAddr(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        // Test with a known IP
        $region = $geoIpInstance->geoip_region_by_addr('8.8.8.8');
        
        if ($region !== false) {
            $this->assertIsArray($region);
            $this->assertArrayHasKey('country_code', $region);
            $this->assertArrayHasKey('region', $region);
        }
        
        $geoIpInstance->geoip_close();
    }

    /**
     * Test invalid IP address handling
     */
    public function testInvalidIpAddress(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        $countryCode = $geoIpInstance->geoip_country_code_by_addr('invalid.ip.address');
        
        $this->assertFalse($countryCode);
        
        $geoIpInstance->geoip_close();
    }

    /**
     * Test database type detection
     */
    public function testDatabaseType(): void
    {
        $geoIpInstance = new GeoIp();
        $geoIpInstance = $geoIpInstance->geoip_open($this->databasePath, GeoIp::GEOIP_STANDARD);
        
        $this->assertIsString($geoIpInstance->databaseType);
        $this->assertNotEmpty($geoIpInstance->databaseType);
        
        $geoIpInstance->geoip_close();
    }
}
