<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Exceptions;

use Exception;
use Throwable;

class InvalidPresentableClass extends Exception
{
    public function __construct(string $className, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Invalid presentable class '.$className, $code, $previous);
    }
}
