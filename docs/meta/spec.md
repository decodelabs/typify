# Typify — Package Specification

> **Cluster:** `core`
> **Language:** `php`
> **Milestone:** `m4`
> **Repo:** `https://github.com/decodelabs/typify`
> **Role:** Mime types

## Overview

### Purpose

Typify provides MIME type detection tools for PHP. It allows identification and application of MIME type information to files and responses based on file extensions.

Key features:
- **MIME type detection**: Detect MIME types from file paths or extensions
- **Extension lookup**: Get extensions for a given MIME type
- **Extension suggestion**: Get a suggested extension for a MIME type
- **Apache-based catalogue**: Uses Apache HTTP Server's mime.types as the source of truth
- **Comprehensive coverage**: Supports hundreds of MIME types and extensions

### Non-Goals

- Typify does not perform actual file content analysis (magic byte detection).
- It does not handle file uploads or file system operations.
- It does not provide HTTP response header management.
- It does not validate MIME type formats or provide MIME type parsing.
- It does not handle MIME type negotiation or content negotiation.

## Role in the Ecosystem

### Cluster & Positioning

Typify belongs to the **core** cluster, providing foundational MIME type detection capabilities. It serves as a utility package for identifying content types based on file extensions, which is essential for HTTP responses, file handling, and content type management.

### Usage Contexts

- **HTTP responses**: Setting appropriate Content-Type headers based on file extensions
- **File handling**: Identifying file types from paths or extensions
- **Content type management**: Mapping between extensions and MIME types
- **File uploads**: Validating or identifying uploaded file types
- **Asset management**: Determining content types for static assets

## Public Surface

### Key Types

- **`Detector`** (class): Main detector class providing MIME type detection and extension lookup. Implements `Service` for Kingdom integration.

- **`Catalogue`** (interface): Interface for MIME type catalogues. Defines methods for type-to-extension and extension-to-type lookups.

- **`Catalogue\Apache`** (class): Apache-based catalogue implementation. Uses Apache HTTP Server's mime.types as the source. Implements `Catalogue` and `Catalogue\Apache\Source`.

- **`Catalogue\Apache\Source`** (interface): Source interface defining the `Types` constant array mapping extensions to MIME types.

- **`Catalogue\Apache\Generator`** (class): Generator class for updating the Apache catalogue from the official Apache mime.types file. Used for maintenance purposes.

### Main Entry Points

**Detector:**
- `new Detector(?Catalogue $catalogue = null)` — Constructor with optional catalogue
- `$detector->detect(string $path, string $default = 'application/octet-stream'): string` — Detect MIME type from file path
- `$detector->getTypeFor(string $extension, ?string $default = null): ?string` — Get MIME type for extension
- `$detector->getExtensionFor(string $type): ?string` — Get suggested extension for MIME type
- `$detector->getExtensionsFor(string $type): array` — Get all extensions for MIME type

**Catalogue Interface:**
- `Catalogue::getTypeFor(string $extension): ?string` — Get MIME type for extension
- `Catalogue::getExtensionFor(string $type): ?string` — Get suggested extension for MIME type
- `Catalogue::getExtensionsFor(string $type): array` — Get all extensions for MIME type

**Apache Catalogue:**
- `new Catalogue\Apache()` — Constructor
- `Catalogue\Apache::Types` — Constant array mapping extensions to MIME types

**Generator:**
- `new Generator()` — Constructor
- `$generator->export(): void` — Export updated catalogue to Source.php
- `$generator->exportInterface(): string` — Export interface code
- `$generator->exportTypesArray(): string` — Export types array code

## Dependencies

### Decode Labs

- **`decodelabs/exceptional`**: Required. Used for exception handling.
- **`decodelabs/kingdom`**: Required. Used for service container integration (`Service` interface).

### External

- **PHP**: See `composer.json` for supported PHP versions.

## Behaviour & Contracts

### Invariants

- Default catalogue is Apache catalogue if none provided.
- Extension lookups are case-insensitive (extensions normalized to lowercase).
- Extension detection from paths uses `pathinfo()` if path contains non-alphanumeric characters.
- If path contains only alphanumeric characters, entire path treated as extension.
- Empty extensions return default value (or `null` if no default).
- Extension strings trimmed of leading/trailing dots.
- `getExtensionFor()` returns first matching extension found in catalogue.
- `getExtensionsFor()` returns all matching extensions for a MIME type.
- Catalogue data sourced from Apache HTTP Server's mime.types file.

### Input & Output Contracts

**Detector Construction:**
- Constructor accepts optional `Catalogue` instance.
- If `null`, creates new `Catalogue\Apache` instance.
- Detector instance ready for use after construction.

**Path Detection:**
- `detect()` accepts file path string.
- Path parsed to extract extension:
  - If path contains non-alphanumeric characters: uses `pathinfo($path, PATHINFO_EXTENSION)`.
  - If path contains only alphanumeric characters: entire path treated as extension.
- Extension normalized to lowercase.
- Returns MIME type string or default value.
- Default value: `'application/octet-stream'` if not specified.

**Extension to Type:**
- `getTypeFor()` accepts extension string.
- Empty extension returns default (or `null` if no default).
- Extension trimmed of leading/trailing dots.
- Extension normalized to lowercase.
- Returns MIME type string or default value.
- Returns `null` if extension not found and no default provided.

**Type to Extension:**
- `getExtensionFor()` accepts MIME type string.
- Searches catalogue for first matching extension.
- Returns extension string or `null` if not found.
- Extension returned without leading dot.

**Type to Extensions:**
- `getExtensionsFor()` accepts MIME type string.
- Searches catalogue for all matching extensions.
- Returns array of extension strings.
- Returns empty array if no matches found.
- Extensions returned without leading dots.

**Catalogue Interface:**
- `getTypeFor()`: Returns MIME type for extension or `null`.
- `getExtensionFor()`: Returns first extension for MIME type or `null`.
- `getExtensionsFor()`: Returns all extensions for MIME type (empty array if none).

**Apache Catalogue:**
- Uses `Source::Types` constant array for lookups.
- `getTypeFor()`: Direct array lookup by extension key.
- `getExtensionFor()`: Iterates array to find first matching type.
- `getExtensionsFor()`: Iterates array to find all matching types.

**Generator:**
- `export()`: Fetches Apache mime.types, processes, and writes to Source.php.
- `exportInterface()`: Generates interface code with Types constant.
- `exportTypesArray()`: Fetches and processes Apache mime.types file.
- Processes lines: skips comments (lines starting with `#`), extracts MIME type and extensions.
- Adds extra types (heic, php, sass, scss) to output.
- Throws exception if unable to fetch Apache mime.types file.

## Error Handling

- **Generator fetch failure**: `exportTypesArray()` throws `Runtime` exception if unable to fetch Apache mime.types file.
- **Invalid extension**: Empty or invalid extensions return default value or `null`.
- **Unknown MIME type**: `getExtensionFor()` and `getExtensionsFor()` return `null` or empty array if MIME type not found.
- **Unknown extension**: `getTypeFor()` returns default value or `null` if extension not found.

## Configuration & Extensibility

### Custom Catalogue

Create custom catalogue by implementing `Catalogue` interface:

```php
use DecodeLabs\Typify\Catalogue;

class MyCatalogue implements Catalogue
{
    public function getTypeFor(string $extension): ?string
    {
        // Custom lookup logic
    }

    public function getExtensionFor(string $type): ?string
    {
        // Custom lookup logic
    }

    public function getExtensionsFor(string $type): array
    {
        // Custom lookup logic
    }
}
```

Use custom catalogue:

```php
use DecodeLabs\Typify\Detector;

$detector = new Detector(new MyCatalogue());
```

### Updating Apache Catalogue

Use generator to update catalogue from Apache:

```php
use DecodeLabs\Typify\Catalogue\Apache\Generator;

$generator = new Generator();
$generator->export(); // Updates Source.php
```

## Interactions with Other Packages

- **Kingdom**: Used for service container integration. Detector implements `Service` interface.
- **Exceptional**: Used for exception handling throughout the package.

## Usage Examples

### Basic Detection

```php
use DecodeLabs\Typify\Detector;

$detector = new Detector();
echo $detector->detect(__FILE__);
// application/x-php
```

### Detection with Default

```php
$type = $detector->detect('unknown.xyz', 'application/octet-stream');
// application/octet-stream
```

### Extension to Type

```php
$type = $detector->getTypeFor('txt');
// text/plain

$type = $detector->getTypeFor('.txt');
// text/plain

$type = $detector->getTypeFor('unknown', 'application/octet-stream');
// application/octet-stream
```

### Type to Extension

```php
$ext = $detector->getExtensionFor('text/plain');
// txt
```

### Type to Extensions

```php
$exts = $detector->getExtensionsFor('text/plain');
// ['txt', 'text', 'conf', 'def', 'list', 'log', 'in']
```

### Custom Catalogue

```php
use DecodeLabs\Typify\Detector;
use DecodeLabs\Typify\Catalogue;

class CustomCatalogue implements Catalogue
{
    public function getTypeFor(string $extension): ?string
    {
        return match($extension) {
            'custom' => 'application/x-custom',
            default => null,
        };
    }

    public function getExtensionFor(string $type): ?string
    {
        return match($type) {
            'application/x-custom' => 'custom',
            default => null,
        };
    }

    public function getExtensionsFor(string $type): array
    {
        return match($type) {
            'application/x-custom' => ['custom'],
            default => [],
        };
    }
}

$detector = new Detector(new CustomCatalogue());
```

### Path Detection

```php
// File path
$type = $detector->detect('/path/to/file.txt');
// text/plain

// Extension only
$type = $detector->detect('txt');
// text/plain

// Unknown extension
$type = $detector->detect('unknown.xyz');
// application/octet-stream
```

## Implementation Notes (for Contributors)

### Detector Implementation

- Detector wraps a `Catalogue` instance.
- Default catalogue is `Catalogue\Apache` if none provided.
- Path detection extracts extension using `pathinfo()` if path contains non-alphanumeric characters.
- If path contains only alphanumeric characters, entire path treated as extension.
- Extensions normalized to lowercase before lookup.
- Extension strings trimmed of leading/trailing dots.

### Apache Catalogue Implementation

- Apache catalogue uses `Source::Types` constant array.
- Array maps extension keys to MIME type values.
- `getTypeFor()` performs direct array lookup.
- `getExtensionFor()` iterates array to find first matching type.
- `getExtensionsFor()` iterates array to find all matching types.
- Extensions returned without leading dots.

### Generator Implementation

- Generator fetches Apache mime.types from SVN repository.
- Processes lines: skips comments, extracts MIME type and extensions.
- First token on line is MIME type, remaining tokens are extensions.
- Extensions mapped to MIME type in output array.
- Extra types added: heic, php, sass, scss.
- Output formatted as PHP array constant.

### Catalogue Data Source

- Primary source: Apache HTTP Server's mime.types file.
- Location: `http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types`
- Format: Space-separated values, first value is MIME type, remaining are extensions.
- Comments: Lines starting with `#` are ignored.
- Extra types: Added manually for types not in Apache list (heic, php, sass, scss).

### Extension Normalization

- Extensions normalized to lowercase for lookups.
- Leading/trailing dots trimmed from extension strings.
- Path detection uses `pathinfo()` for proper extension extraction.

### MIME Type Lookup

- Extension-to-type: Direct array lookup by extension key.
- Type-to-extension: Iterate array to find matching type value.
- First match returned for `getExtensionFor()`.
- All matches returned for `getExtensionsFor()`.

## Testing & Quality

**Current Status:**
- Code quality: 4/5
- README quality: 3/5
- Documentation: 0/5 (no formal docs yet)
- Tests: 0/5 (no test suite yet)

**Testing Considerations:**
- Detector should be tested for:
  - Path detection (with and without file separators)
  - Extension-only detection
  - Default value handling
  - Unknown extension handling
  - Extension normalization (case, dots)

- Extension to type should be tested for:
  - Known extensions
  - Unknown extensions
  - Empty extensions
  - Extensions with leading/trailing dots
  - Case sensitivity

- Type to extension should be tested for:
  - Known MIME types
  - Unknown MIME types
  - Multiple extensions per type

- Type to extensions should be tested for:
  - Known MIME types with multiple extensions
  - Unknown MIME types
  - Empty results

- Catalogue interface should be tested for:
  - Custom catalogue implementations
  - Null returns for unknown values
  - Empty array returns

- Generator should be tested for:
  - Apache mime.types fetching
  - Line processing (comments, empty lines)
  - Extra types addition
  - Output formatting

- Edge cases should be tested for:
  - Empty strings
  - Special characters in paths
  - Very long paths
  - Invalid MIME type formats
  - Duplicate extensions in catalogue

## Roadmap & Future Ideas

- **Magic byte detection**: Add file content analysis for more accurate type detection
- **More catalogues**: Support for additional MIME type sources (IANA, etc.)
- **MIME type validation**: Validate MIME type format and structure
- **Content negotiation**: Support for MIME type negotiation
- **Performance optimization**: Caching and optimization for large catalogues
- **Better error messages**: More detailed error messages for debugging
- **MIME type parsing**: Parse and validate MIME type strings (type/subtype; parameters)

## References

- Package repository: https://github.com/decodelabs/typify
- Composer package: https://packagist.org/packages/decodelabs/typify
- Apache mime.types: http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
- Related packages:
  - `decodelabs/kingdom` — Service container integration
  - `decodelabs/exceptional` — Exception handling
- MIME type standards:
  - RFC 2045: Multipurpose Internet Mail Extensions (MIME) Part One
  - RFC 2046: Multipurpose Internet Mail Extensions (MIME) Part Two
  - IANA Media Types: https://www.iana.org/assignments/media-types/

