<?php

declare(strict_types=1);

namespace Mitra\Env\Reader;

final class GetenvReader implements ReaderInterface
{

    public function read(string $varname): ?string
    {
        if (false !== $value = getenv($varname)) {
            return $value;
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public function readAll(): array
    {
        return getenv();
    }
}
