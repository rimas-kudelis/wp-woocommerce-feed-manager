<?php

namespace RexGuzzleHttp\Promise\Tests;

use RexGuzzleHttp\Promise\AggregateException;
use PHPUnit\Framework\TestCase;

class AggregateExceptionTest extends TestCase
{
    public function testHasReason()
    {
        $e = new AggregateException('foo', ['baz', 'bar']);
        $this->assertStringContainsString('foo', $e->getMessage());
        $this->assertSame(['baz', 'bar'], $e->getReason());
    }
}
