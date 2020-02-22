<?php

declare(strict_types=1);

namespace Mitra\Env;

use Mitra\Env\Reader\ReaderInterface;
use Mitra\Env\Writer\WriterInterface;
use Psr\SimpleCache\CacheInterface;

final class Env
{

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var bool
     */
    private $immutable;

    /**
     * @param CacheInterface $cache
     * @param ReaderInterface $reader
     * @param WriterInterface $writer
     * @param bool $immutable
     */
    private function __construct(
        ReaderInterface $reader,
        WriterInterface $writer,
        CacheInterface $cache,
        bool $immutable
    ) {
        $this->cache = $cache;
        $this->reader = $reader;
        $this->writer = $writer;
        $this->immutable = $immutable;
    }

    public static function immutable(ReaderInterface $reader, WriterInterface $writer, CacheInterface $cache): self
    {
        return new self($reader, $writer, $cache, true);
    }

    public static function mutable(ReaderInterface $reader, WriterInterface $writer, CacheInterface $cache): self
    {
        return new self($reader, $writer, $cache, false);
    }

    public function get(string $varname): ?string
    {
        if (null !== ($value = $this->cache->get($varname))) {
            return $value;
        }

        $value = $this->reader->read($varname);
        $this->cache->set($varname, $value);

        return $value;
    }

    /**
     * @param string $varname
     * @param string $value
     * @return self
     */
    public function set(string $varname, string $value): self
    {
        if ($this->immutable && null !== $this->get($varname)) {
            throw new EnvException(sprintf('Environment variable with name `%s` is already set', $varname));
        }

        $this->writer->write($varname, $value);
        $this->cache->set($varname, $value);

        return $this;
    }

    public function isImmutable(): bool
    {
        return $this->immutable;
    }
}
