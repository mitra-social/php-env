<?php

namespace spec\Mitra\Env;

use Mitra\Env\Env;
use Mitra\Env\EnvException;
use Mitra\Env\Reader\ReaderInterface;
use Mitra\Env\Writer\WriterInterface;
use PhpSpec\ObjectBehavior;
use Psr\SimpleCache\CacheInterface;

final class EnvSpec extends ObjectBehavior
{

    public function it_is_immutable(ReaderInterface $reader, WriterInterface $writer, CacheInterface $cache): void
    {
        $this->beConstructedThrough('immutable', [$reader, $writer, $cache]);

        $this->isImmutable()->shouldReturn(true);
    }

    public function it_is_mutable(ReaderInterface $reader, WriterInterface $writer, CacheInterface $cache): void
    {
        $this->beConstructedThrough('mutable', [$reader, $writer, $cache]);

        $this->isImmutable()->shouldReturn(false);
    }

    public function it_resolves_from_cache(
        ReaderInterface $reader,
        WriterInterface $writer,
        CacheInterface $cache
    ): void {
        $varname = 'foo';
        $cachedValue = 'cachedValue';

        $this->beConstructedThrough('immutable', [$reader, $writer, $cache]);

        $cache->get($varname)->shouldBeCalled()->willReturn($cachedValue);
        $reader->read()->shouldNotBeCalled();

        $this->get($varname)->shouldReturn($cachedValue);
    }

    public function it_reads_env_variable_and_caches_it(
        ReaderInterface $reader,
        WriterInterface $writer,
        CacheInterface $cache
    ): void {
        $varname = 'foo';
        $value = 'someValue';

        $this->beConstructedThrough('immutable', [$reader, $writer, $cache]);

        $cache->get($varname)->shouldBeCalled()->willReturn(null);
        $cache->set($varname, $value)->shouldBeCalled()->willReturn(true);
        $reader->read($varname)->shouldBeCalled()->willReturn($value);

        $this->get($varname)->shouldReturn($value);
    }

    public function it_writes_env_if_not_existing_and_adds_to_cache(
        ReaderInterface $reader,
        WriterInterface $writer,
        CacheInterface $cache
    ): void {
        $varname = 'foo';
        $value = 'someValue';

        $this->beConstructedThrough('immutable', [$reader, $writer, $cache]);

        $cache->get($varname)->shouldBeCalled()->willReturn(null);
        $cache->set($varname, null)->shouldBeCalled()->willReturn(true);
        $cache->set($varname, $value)->shouldBeCalled()->willReturn(true);
        $reader->read($varname)->shouldBeCalled()->willReturn(null);
        $writer->write($varname, $value)->shouldBeCalled();

        $this->set($varname, $value);
    }

    public function it_writes_env_if_existing_and_adds_to_cache_if_mutable(
        ReaderInterface $reader,
        WriterInterface $writer,
        CacheInterface $cache
    ): void {
        $varname = 'foo';
        $value = 'someValue';

        $this->beConstructedThrough('mutable', [$reader, $writer, $cache]);

        $cache->get()->shouldNotBeCalled();
        $cache->set($varname, $value)->shouldBeCalled();
        $reader->read()->shouldNotBeCalled();
        $writer->write($varname, $value)->shouldBeCalled();

        $this->set($varname, $value);
    }

    public function it_throws_exception_on_immutable_write_and_existing_env(
        ReaderInterface $reader,
        WriterInterface $writer,
        CacheInterface $cache
    ): void {
        $varname = 'foo';
        $cachedValue = 'someValue';

        $this->beConstructedThrough('immutable', [$reader, $writer, $cache]);

        $cache->get($varname)->shouldBeCalled()->willReturn($cachedValue);
        $cache->set()->shouldNotBeCalled();
        $reader->read()->shouldNotBeCalled();
        $writer->write()->shouldNotBeCalled();

        $this->shouldThrow(
            new EnvException(sprintf('Environment variable with name `%s` is already set', $varname))
        )->during('set', [$varname, $cachedValue]);
    }
}
