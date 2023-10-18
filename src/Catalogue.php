<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Typify;

interface Catalogue
{
    public function getTypeFor(string $extension): ?string;
    public function getExtensionFor(string $type): ?string;

    /**
     * @return array<string>
     */
    public function getExtensionsFor(string $type): array;
}
