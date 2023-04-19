<?php

namespace JscDev\LaravelTable\Exceptions;

use Exception;
use JscDev\LaravelTable\Abstracts\AbstractTableConfiguration;

class InvalidTableConfiguration extends Exception
{
    public function __construct(string $config)
    {
        parent::__construct('The given ' . $config
            . ' table config should extend ' . AbstractTableConfiguration::class . '.');
    }
}
