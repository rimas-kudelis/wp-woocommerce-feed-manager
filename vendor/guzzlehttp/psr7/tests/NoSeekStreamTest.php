<?php

namespace RexGuzzleHttp\Tests\Psr7;

use RexGuzzleHttp\Psr7;
use RexGuzzleHttp\Psr7\NoSeekStream;

/**
 * @covers GuzzleHttp\Psr7\NoSeekStream
 * @covers GuzzleHttp\Psr7\StreamDecoratorTrait
 */
class NoSeekStreamTest extends BaseTest
{
    public function testCannotSeek()
    {
        $s = $this->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->setMethods(['isSeekable', 'seek'])
            ->getMockForAbstractClass();
        $s->expects($this->never())->method('seek');
        $s->expects($this->never())->method('isSeekable');
        $wrapped = new NoSeekStream($s);
        $this->assertFalse($wrapped->isSeekable());

        $this->expectExceptionGuzzle('RuntimeException', 'Cannot seek a NoSeekStream');

        $wrapped->seek(2);
    }

    public function testToStringDoesNotSeek()
    {
        $s = \RexGuzzleHttp\Psr7\Utils::streamFor('foo');
        $s->seek(1);
        $wrapped = new NoSeekStream($s);
        $this->assertSame('oo', (string) $wrapped);

        $wrapped->close();
    }
}
