<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Commandment\Action;

use DecodeLabs\Commandment\Action;
use DecodeLabs\Commandment\Request;
use DecodeLabs\Terminus\Session;
use DecodeLabs\Typify\Catalogue\Apache\Generator;

class GenerateSource implements Action
{
    public function execute(
        Request $request,
    ): bool {
        $generator = new Generator();
        $generator->export();
        return true;
    }
}
