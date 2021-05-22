<?php

declare(strict_types=1);

namespace TPG\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TPG\IsPresentable\IsPresentableServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            IsPresentableServiceProvider::class,
        ];
    }
}
