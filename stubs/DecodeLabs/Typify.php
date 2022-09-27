<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Typify\Detector as Inst;

class Typify implements Proxy
{
    use ProxyTrait;

    const VENEER = 'DecodeLabs\Typify';
    const VENEER_TARGET = Inst::class;

    public static Inst $instance;

    public static function detect(string $path, string $default = 'application/octet-stream'): string {
        return static::$instance->detect(...func_get_args());
    }
    public static function getTypeFor(string $extension, ?string $default = NULL): ?string {
        return static::$instance->getTypeFor(...func_get_args());
    }
    public static function getExtensionFor(string $type): ?string {
        return static::$instance->getExtensionFor(...func_get_args());
    }
    public static function getExtensionsFor(string $type): array {
        return static::$instance->getExtensionsFor(...func_get_args());
    }
};
