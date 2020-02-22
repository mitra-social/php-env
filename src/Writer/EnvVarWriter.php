<?php

declare(strict_types=1);

namespace Mitra\Env\Writer;

final class EnvVarWriter implements WriterInterface
{

    public function write(string $varname, string $value): void
    {
        $_ENV[$varname] = $value;
    }
}
