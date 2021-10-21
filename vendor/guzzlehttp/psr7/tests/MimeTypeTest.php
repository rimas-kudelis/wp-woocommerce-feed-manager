<?php

namespace RexGuzzleHttp\Tests\Psr7;

use RexGuzzleHttp\Psr7;

class MimeTypeTestTest extends BaseTest
{
    public function testDetermineFromExtension()
    {
        $this->assertNull(Psr7\MimeType::fromExtension('not-a-real-extension'));
        $this->assertSame('application/json', Psr7\MimeType::fromExtension('json'));
    }

    public function testDetermineFromFilename()
    {
        $this->assertSame(
            'image/jpeg',
            Psr7\MimeType::fromFilename('/tmp/images/IMG034821.JPEG')
        );
    }
}
