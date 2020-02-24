<?php

declare(strict_types=1);

namespace Mitra\Env\Reader;

final class ArrayReader implements ReaderInterface
{

    /**
     * @var array<string, string>
     */
    private $envData = [];

    /**
     * @param array<string, string> $envData
     */
    public function __construct(array $envData)
    {
        $this->envData = $envData;
    }

    public function read(string $varname): ?string
    {
        return $this->envData[$varname] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function readAll(): array
    {
        return $this->envData;
    }
}
