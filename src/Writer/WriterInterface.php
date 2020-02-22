<?php

declare(strict_types=1);

namespace Mitra\Env\Writer;

interface WriterInterface
{
    public function write(string $varname, string $value): void;
}
