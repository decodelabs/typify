<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Clip\Task;

use DecodeLabs\Clip\Task;
use DecodeLabs\Terminus as Cli;
use DecodeLabs\Typify\Catalogue\Apache\Generator;

class GenerateSource implements Task
{
    public function execute(): bool
    {
        $generator = new Generator();
        $generator->export();
        return true;
    }
}
