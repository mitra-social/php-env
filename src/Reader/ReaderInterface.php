<?php

declare(strict_types=1);

namespace Mitra\Env\Reader;

interface ReaderInterface
{
    public function read(string $varname): ?string;

    /**
     * @return array<string, string>
     */
    public function readAll(): array;
}
