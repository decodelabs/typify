<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

/**
 * global helpers
 */
namespace DecodeLabs\Typify
{
    use DecodeLabs\Typify;
    use DecodeLabs\Veneer;

    // Register the Veneer proxy
    Veneer::register(Detector::class, Typify::class);
}
