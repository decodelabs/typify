<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy as Proxy;
use DecodeLabs\Veneer\ProxyTrait as ProxyTrait;
use DecodeLabs\Typify\Detector as Inst;

class Typify implements Proxy
{
    use ProxyTrait;

    public const Veneer = 'DecodeLabs\\Typify';
    public const VeneerTarget = Inst::class;

    protected static Inst $_veneerInstance;

    public static function detect(string $path, string $default = 'application/octet-stream'): string {
        return static::$_veneerInstance->detect(...func_get_args());
    }
    public static function getTypeFor(string $extension, ?string $default = NULL): ?string {
        return static::$_veneerInstance->getTypeFor(...func_get_args());
    }
    public static function getExtensionFor(string $type): ?string {
        return static::$_veneerInstance->getExtensionFor(...func_get_args());
    }
    public static function getExtensionsFor(string $type): array {
        return static::$_veneerInstance->getExtensionsFor(...func_get_args());
    }
};
