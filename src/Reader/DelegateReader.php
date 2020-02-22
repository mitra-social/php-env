<?php

declare(strict_types=1);

namespace Mitra\Env\Reader;

final class DelegateReader implements ReaderInterface
{

    /**
     * @var array<ReaderInterface>
     */
    private $readers;

    /**
     * @param array<ReaderInterface> $readers
     */
    public function __construct(array $readers)
    {
        $this->readers = $readers;
    }


    public function read(string $varname): ?string
    {
        foreach ($this->readers as $reader) {
            if (null !== $value = $reader->get($varname)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public function readAll(): array
    {
        $all = [];

        foreach ($this->readers as $reader) {
            $all = array_merge($all, $reader->getAll());
        }

        return $all;
    }
}
