<?php

declare(strict_types=1);

namespace Mitra\Env\Writer;

final class DelegateWriter implements WriterInterface
{

    /**
     * @var array<WriterInterface>
     */
    private $writers;

    /**
     * @param array<WriterInterface> $writers
     */
    public function __construct(array $writers)
    {
        $this->writers = $writers;
    }

    public function write(string $varname, string $value): void
    {
        foreach ($this->writers as $writer) {
            $writer->write($varname, $value);
        }
    }
}
