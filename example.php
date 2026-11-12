<?php

/**
 * Simple example of the solvebeam/twinfield SDK.
 *
 * Log in once with `vendor/bin/twf login`, then run `php example.php`.
 */

declare(strict_types=1);

use SolveBeam\Twinfield\Cli\Credentials;
use SolveBeam\Twinfield\Twinfield;

require __DIR__ . '/vendor/autoload.php';

$twinfield = Twinfield::create(Credentials::default());

$offices = $twinfield->offices()->list();

echo 'Offices (', count($offices), '):', PHP_EOL;

foreach ($offices as $office) {
    echo '- ', $office->code, ' ', $office->name, PHP_EOL;
}
