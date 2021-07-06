<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Contracts;

interface PresenterInterface
{
    public function __construct(array $presentables);

    public function __get(string $key);
}
