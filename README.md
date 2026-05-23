# 🌍 GeoIP Lite — *Wizdam Edition*

**A modernized PHP 8.4+ library to look up IP address country, region, city, and ISP information using MaxMind’s classic GeoIP Lite binary database format.**

---

<p align="center">
  <a href="https://github.com/mokesano/geoip-legacy">
    <img src="https://img.shields.io/badge/PHP-^8.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  </a>
  <a href="https://github.com/mokesano/geoip-legacy/blob/main/LICENSE">
    <img src="https://img.shields.io/badge/license-GPL%203.0--only-blue?style=for-the-badge" alt="License">
  </a>
  <a href="https://packagist.org/packages/wizdam/geoip-legacy">
    <img src="https://img.shields.io/badge/packagist-wizdam%2Fgeoip--legacy-F28D1A?style=for-the-badge&logo=packagist&logoColor=white" alt="Packagist">
  </a>
  <a href="https://github.com/mokesano/geoip-legacy/actions">
    <img src="https://img.shields.io/badge/build-passing-brightgreen?style=for-the-badge&logo=github-actions&logoColor=white" alt="Build">
  </a>
  <a href="https://github.com/mokesano/geoip-legacy/security/advisories">
    <img src="https://img.shields.io/badge/security-policy-important?style=for-the-badge&logo=github" alt="Security Policy">
  </a>
</p>

<br>

<p align="center">
  <em>🌐 Resolve IP → Country · 🏙️ City & Region Lookup · 📡 ISP / Organization Detection · ⚡ Memory Caching</em>
</p>

---

## 📖 About the Project

GeoIP Lite is a **PHP port of the classic MaxMind GeoIP Lite C library** — the original tool for translating IP addresses into geographic and network-identifying information. This library works with MaxMind’s binary `.dat` database files, including the free **GeoLite City** and **GeoLite Country** databases.

This **Wizdam Edition** modernizes the original codebase for **PHP 8.4+**, adding **PSR‑4 autoloading** and **Composer packaging** so you can drop it straight into any modern PHP project.

> ⚠️ **Important notice** — MaxMind retired the GeoIP Lite product line on **May 31, 2022**. The downloadable `.dat` files are no longer officially updated. If you are starting a new project, we strongly recommend using [MaxMind’s GeoIP2 PHP API](https://dev.maxmind.com/geoip/geoip2/downloadable/) instead. This library remains useful for **maintaining legacy systems**, reading **archived databases**, or working within networks that still depend on the classic `.dat` format.

---

## ✨ Key Features

| 🔧 Feature | 📝 Description |
| :--- | :--- |
| **Country Lookup** | Get ISO country code, country name, or numeric ID from an IPv4 / IPv6 address |
| **City & Region** | Retrieve city, region, postal code, latitude, longitude, and metro code |
| **ISP / Organization** | Identify the ISP, organization, or domain associated with an IP |
| **DNS Reverse Lookup** | Resolve hostnames to IP addresses and look them up automatically |
| **Memory Caching** | Three database access modes: standard file, shared memory, and memory cache |
| **IPv4 + IPv6** | Full support for both IPv4 and IPv6 addresses |
| **Rich Database Support** | Handles Country, City, Region, ISP, Organization, Domain, and Proxy editions |
| **PHP 8.4+ Native** | Fully compatible with modern PHP — no deprecation warnings |
| **PSR‑4 Autoloading** | Namespaced under `Wizdam\GeoIp` for instant Composer autoloading |
| **Lightweight** | Zero external dependencies — only a `.dat` file is required |
| **Time‑Zone Mapping** | Includes timezone lookup array for inferred UTC offset detection |

---

## 🚀 Installation

### Via Composer (Recommended)

```bash
composer require wizdam/geoip-legacy
```

### Manual Installation

Clone the repository and include the autoloader:

```bash
git clone https://github.com/mokesano/geoip-legacy.git
cd geoip-legacy
composer install
```

### Requirements

- **PHP** ≥ 8.4
- A MaxMind binary database file (e.g. `GeoIP.dat`, `GeoLiteCity.dat`)

> 📦 A free **GeoLiteCity.dat** sample is included in the [`data/`](https://github.com/mokesano/geoip-legacy/tree/main/data) directory for testing.

---

## ⚡ Quick Start

### 🌐 Basic Country Lookup (IPv4)

```php
<?php

require_once 'vendor/autoload.php';

use Wizdam\GeoIp\GeoIp;

// Open the country database
$gi = GeoIp::open('/usr/local/share/GeoIP/GeoIP.dat', GEOIP_STANDARD);

// Look up country by IP address
$code = GeoIp::country_code_by_addr($gi, '24.24.24.24');
$name = GeoIp::country_name_by_addr($gi, '24.24.24.24');

echo "🌍 {$code} — {$name}\n";  // US — United States

GeoIp::close($gi);
```

### 🏙️ City & Region Lookup (IPv4)

```php
<?php

require_once 'vendor/autoload.php';

use Wizdam\GeoIp\GeoIpCity;

// Open the city database
$gi = GeoIp::open('/usr/local/share/GeoIP/GeoLiteCity.dat', GEOIP_STANDARD);

// Get full city record
$record = GeoIpCity::record_by_addr($gi, '24.24.24.24');

echo "🏙️  City:       {$record->city}\n";
echo "🏛️  Region:     {$record->region} ({$record->regionname})\n";
echo "🌍 Country:    {$record->country_name} ({$record->country_code})\n";
echo "📮 Postal:     {$record->postal_code}\n";
echo "📍 Lat / Lon:  {$record->latitude}, {$record->longitude}\n";
echo "📡 ISP:        {$record->isp}\n";

GeoIp::close($gi);
```

### ⚡ Memory Caching for High Performance

```php
<?php

require_once 'vendor/autoload.php';

use Wizdam\GeoIp\GeoIp;

// Use memory cache for repeated lookups
$gi = GeoIp::open('/usr/local/share/GeoIP/GeoLiteCity.dat', GEOIP_MEMORY_CACHE);

// Perform thousands of lookups without touching the disk
foreach ($ipAddresses as $ip) {
    $country = GeoIp::country_code_by_addr($gi, $ip);
    // process …
}

GeoIp::close($gi);
```

### 🖧 Hostname Lookup (DNS Resolution)

```php
<?php

require_once 'vendor/autoload.php';

use Wizdam\GeoIp\GeoIp;

$gi = GeoIp::open('/usr/local/share/GeoIP/GeoIP.dat', GEOIP_STANDARD);

// The library resolves the hostname before lookup
echo GeoIp::country_code_by_name($gi, 'github.com');   // US
echo GeoIp::country_name_by_name($gi, 'github.com');   // United States

GeoIp::close($gi);
```

---

## 📚 API Reference

### Opening & Closing the Database

| Function | Description |
| :--- | :--- |
| `GeoIp::open($filename, $flags)` | Open a `.dat` database. Returns a `GeoIP` resource. |
| `GeoIp::close($gi)` | Close the database and free resources. |

**Available flags:**

| Constant | Mode |
| :--- | :--- |
| `GEOIP_STANDARD` | Read from disk (default) |
| `GEOIP_MEMORY_CACHE` | Load the entire database into PHP memory |
| `GEOIP_SHARED_MEMORY` | Use shared memory segment (requires `shmop` extension) |

### Country Lookup Functions (`GeoIp`)

| Function | Returns |
| :--- | :--- |
| `country_code_by_addr($gi, $addr)` | Two‑letter ISO country code (e.g. `'US'`) |
| `country_name_by_addr($gi, $addr)` | Full country name (e.g. `'United States'`) |
| `country_id_by_addr($gi, $addr)` | Numeric country ID |
| `country_code_by_name($gi, $name)` | Country code from hostname (auto‑resolves DNS) |
| `country_name_by_name($gi, $name)` | Country name from hostname |
| `country_id_by_name($gi, $name)` | Country ID from hostname |

Append `_v6` to any function for IPv6 support (e.g. `country_code_by_addr_v6`).

### City / Region / ISP Functions (`GeoIpCity`)

| Function | Returns |
| :--- | :--- |
| `record_by_addr($gi, $addr)` | Full `geoiprecord` object with all fields |
| `record_by_addr_v6($gi, $addr)` | Same as above for IPv6 |

**`geoiprecord` object fields:**

| Field | Description |
| :--- | :--- |
| `country_code` | Two‑letter country code |
| `country_code3` | Three‑letter country code |
| `country_name` | Full country name |
| `continent_code` | Continent abbreviation |
| `region` | Region code (e.g. `'CA'`) |
| `regionname` | Region name (e.g. `'California'`) |
| `city` | City name |
| `postal_code` | ZIP / postal code |
| `latitude` | Decimal latitude |
| `longitude` | Decimal longitude |
| `metro_code` / `dmacode` | DMA / metro code |
| `areacode` | Telephone area code |
| `isp` | Internet Service Provider |
| `org` | Organization name |

### Organization Functions (`GeoIp`)

| Function | Description |
| :--- | :--- |
| `name_by_addr($gi, $addr)` | Organization or ISP name |
| `org_by_addr($gi, $addr)` | Alias for `name_by_addr` |
| `region_by_addr($gi, $addr)` | Country code and region as `[$country, $region]` |

---

## 📦 Supported Database Editions

| Edition | Constant | Lookup Capability |
| :--- | :--- | :--- |
| Country | `GEOIP_COUNTRY_EDITION` | Country only |
| City | `GEOIP_CITY_EDITION_REV1` | City, region, postal, coordinates |
| Region | `GEOIP_REGION_EDITION_REV1` | Country + region |
| ISP | `GEOIP_ISP_EDITION` | ISP name |
| Organization | `GEOIP_ORG_EDITION` | Organization name |
| Domain | `GEOIP_DOMAIN_EDITION` | Second‑level domain |
| Netspeed | `GEOIP_NETSPEED_EDITION` | Connection speed |
| Proxy | `GEOIP_PROXY_EDITION` | Proxy detection |

The library **auto‑detects** the database edition when opened — you do not need to specify it manually.

---

## 🏗️ Project Structure

```
geoip-legacy/
├── data/
│   └── GeoLiteCity.dat          # Sample city database (for testing)
├── src/
│   ├── GeoIp.inc                # Core library – country & organization lookup
│   ├── GeoIpCity.inc            # City, region, ISP record parsing
│   ├── GeoIpRegionVars.php      # Region name maps (FIPS & ISO)
│   ├── Benchmark.php            # Performance benchmark script
│   ├── functions.php            # Autoloaded helper functions
│   ├── ChangeLog                # Historical release notes
│   └── timezone/                # Timezone offset lookup table
├── composer.json                # PSR‑4 autoloading, namespace Wizdam\GeoIp
├── LICENSE                      # GPL‑3.0‑only
├── CODE_OF_CONDUCT.md
├── CONTRIBUTING.md
├── SECURITY.md
└── README.md
```

---

## ⚠️ GeoIP Lite vs. GeoIP2

| | **GeoIP Lite** (this library) | **GeoIP2** |
| :--- | :--- | :--- |
| **Database format** | Binary `.dat` | Binary `.mmdb` |
| **Status** | Retired (May 2022) | Actively maintained |
| **PHP library** | `wizdam/geoip-legacy` | `geoip2/geoip2` |
| **Updates** | ❌ No longer published | ✅ Weekly updates |
| **Accuracy** | Varies by edition | Improved precision |
| **IPv6 support** | Limited | Full native |
| **Anonymous IP** | Proxy edition | Built‑in traits |

If you are currently using GeoIP Lite in production, consider **migrating to GeoIP2**. MaxMind provides a [detailed upgrade guide](https://dev.maxmind.com/geoip/geoip2/whats-new-in-geoip2/).

---

## 🤝 Contributing

We welcome contributions! Please review our [Contributing Guidelines](https://github.com/mokesano/geoip-legacy/blob/main/CONTRIBUTING.md) before submitting a pull request.

**Coding Standards:**
- PHP code must follow **PSR‑1** guidelines
- JavaScript follows [Crockford's conventions](http://javascript.crockford.com/code.html)
- All new features should include updated documentation

This project adheres to the [Contributor Covenant Code of Conduct](https://github.com/mokesano/geoip-legacy/blob/main/CODE_OF_CONDUCT.md). By participating, you agree to uphold these standards.

---

## 🔒 Security

Security is taken seriously. **Please do not publicly disclose any vulnerabilities.**

- **Reporting:** Send vulnerability reports to [security@sangia.org](mailto:security@sangia.org)
- **Acknowledgment:** The lead maintainer will respond within 48 hours
- **Advisories:** Published at [GitHub Security Advisories](https://github.com/mokesano/geoip-legacy/security/advisories)

Full details are in our [Security Policy](https://github.com/mokesano/geoip-legacy/blob/main/SECURITY.md).

---

## 📄 License

This project is distributed under the **GNU General Public License v3.0 (GPL‑3.0‑only)**. See [LICENSE](https://github.com/mokesano/geoip-legacy/blob/main/LICENSE) for the full text.

---

## 🙏 Acknowledgments

| 🏷️ Attribution | 🔗 Reference |
| :--- | :--- |
| **Original Author** | MaxMind, Inc. — original C library and database format |
| **Original PHP Port** | Jim Winstead & MaxMind contributors |
| **Wizdam Edition Maintainer** | [Rochmady (mokesano)](https://github.com/mokesano) |
| **MaxMind GeoIP Lite** | [maxmind.com](https://www.maxmind.com/) |
| **GeoIP2 Migration** | [dev.maxmind.com/geoip/geoip2/](https://dev.maxmind.com/geoip/geoip2/) |
| **Database Source** | Free GeoLite City database — included for testing |

---

<p align="center">
  <br>
  <sub>Made with ❤️ for the PHP and networking community</sub>
  <br><br>
  <a href="https://github.com/mokesano/geoip-legacy/stargazers">
    <img src="https://img.shields.io/github/stars/mokesano/geoip-legacy?style=social" alt="GitHub Stars">
  </a>
  <a href="https://github.com/mokesano/geoip-legacy/network/members">
    <img src="https://img.shields.io/github/forks/mokesano/geoip-legacy?style=social" alt="GitHub Forks">
  </a>
  <br><br>
  <sub>© 2026 Rochmady. Licensed under GPL‑3.0‑only.</sub>
</p>