<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Typify\Catalogue;

use DecodeLabs\Typify\Catalogue;
use DecodeLabs\Typify\Catalogue\Apache\Source;

class Apache implements
    Catalogue,
    Source
{
    /**
     * Lookup mime type for extension
     */
    public function getTypeFor(
        string $extension
    ): ?string {
        return self::Types[$extension] ?? null;
    }

    /**
     * Lookup first extension for mime type
     */
    public function getExtensionFor(
        string $type
    ): ?string {
        foreach (self::Types as $ext => $testType) {
            if ($type === $testType) {
                return (string)$ext;
            }
        }

        return null;
    }

    /**
     * Look up all extensions for mime type
     */
    public function getExtensionsFor(
        string $type
    ): array {
        $output = [];

        foreach (self::Types as $ext => $testType) {
            if ($type === $testType) {
                $output[] = (string)$ext;
            }
        }

        return $output;
    }
}
