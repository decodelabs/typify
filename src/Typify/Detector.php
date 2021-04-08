<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Typify;

use DecodeLabs\Typify\Catalogue\Apache as ApacheCatalogue;

class Detector
{
    /**
     * @var Catalogue
     */
    protected $catalogue;

    /**
     * Init with catalogue
     */
    public function __construct(?Catalogue $catalogue = null)
    {
        if ($catalogue === null) {
            $catalogue = new ApacheCatalogue();
        }

        $this->catalogue = $catalogue;
    }


    /**
     * Detect mime type from path
     */
    public function detect(string $path, string $default = 'application/octet-stream'): string
    {
        if (preg_match('#[^a-zA-Z0-9]#', $path)) {
            $extension = pathinfo($path, \PATHINFO_EXTENSION);
        } else {
            $extension = $path;
        }

        $extension = strtolower($extension);
        return (string)$this->getTypeFor($extension, $default);
    }


    /**
     * Get type for extension
     */
    public function getTypeFor(string $extension, ?string $default = null): ?string
    {
        if (empty($extension)) {
            return $default;
        }

        $extension = trim($extension, '.');

        if (null === ($output = $this->catalogue->getTypeFor($extension))) {
            return $default;
        }

        return $output;
    }

    /**
     * Suggest extension for type
     */
    public function getExtensionFor(string $type): ?string
    {
        return $this->catalogue->getExtensionFor($type);
    }

    /**
     * Get list of extensions for type
     *
     * @return array<string>
     */
    public function getExtensionsFor(string $type): array
    {
        return $this->catalogue->getExtensionsFor($type);
    }
}
