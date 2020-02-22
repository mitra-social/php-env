<?php

declare(strict_types=1);

namespace Mitra\Env\Reader;

final class EnvVarReader implements ReaderInterface
{

    public function read(string $varname): ?string
    {
        return $_ENV[$varname] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function readAll(): array
    {
        return $_ENV;
    }
}
