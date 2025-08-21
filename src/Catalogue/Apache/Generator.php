<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Typify\Catalogue\Apache;

use DecodeLabs\Exceptional;

class Generator
{
    protected const MimeList = 'http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types';

    protected const ExtraTypes = [
        'heic' => 'image/heic',
        'php' => 'application/x-php',
        'sass' => 'text/x-sass',
        'scss' => 'text/x-scss',
    ];

    public function export(): void
    {
        $interface = $this->exportInterface();
        file_put_contents(__DIR__ . '/Source.php', $interface);
    }

    public function exportInterface(): string
    {
        $output = <<<PHP
<?php

/**
 * @package Typify
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Typify\Catalogue\Apache;

interface Source
{
    public const Types =
PHP;

        $output .= ' ' . str_replace("\n", "\n    ", $this->exportTypesArray()) . ';' . "\n" . '}' . "\n";
        return $output;
    }

    public function exportTypesArray(): string
    {
        if (!$data = file_get_contents(self::MimeList)) {
            throw Exceptional::Runtime(
                message: 'Unable to fetch Apache mime list'
            );
        }

        $output = [];

        foreach (explode("\n", $data) as $line) {
            if (
                !isset($line[0]) ||
                $line[0] === '#'
            ) {
                continue;
            }

            if (
                !preg_match_all('#([^\s]+)#', $line, $matches) ||
                (($count = count($matches[1])) <= 1)
            ) {
                continue;
            }

            for ($i = 1; $i < $count; $i++) {
                $key = $matches[1][$i];

                if (isset($output[$key])) {
                    continue;
                }

                $output[$key] = '    \'' . $key . '\' => \'' . $matches[1][0] . '\'';
            }
        }

        foreach (self::ExtraTypes as $key => $value) {
            $output[$key] = '    \'' . $key . '\' => \'' . $value . '\'';
        }

        return '[' . "\n" . implode(",\n", $output) . "\n" . ']';
    }
}
