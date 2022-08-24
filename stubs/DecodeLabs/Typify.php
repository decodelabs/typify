<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;
use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Typify\Detector as Inst;
class Typify implements Proxy { use ProxyTrait;
const VENEER = 'Typify';
const VENEER_TARGET = Inst::class;};
