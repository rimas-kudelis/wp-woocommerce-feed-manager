<?php

namespace RexGuzzleHttp\Tests\Psr7;

use RexGuzzleHttp\Psr7;

class HeaderTest extends BaseTest
{
    public function parseParamsProvider()
    {
        $res1 = [
            [
                '<http:/.../front.jpeg>',
                'rel' => 'front',
                'type' => 'image/jpeg',
            ],
            [
                '<http://.../back.jpeg>',
                'rel' => 'back',
                'type' => 'image/jpeg',
            ],
        ];
        return [
            [
                '<http:/.../front.jpeg>; rel="front"; type="image/jpeg", <http://.../back.jpeg>; rel=back; type="image/jpeg"',
                $res1,
            ],
            [
                '<http:/.../front.jpeg>; rel="front"; type="image/jpeg",<http://.../back.jpeg>; rel=back; type="image/jpeg"',
                $res1,
            ],
            [
                'foo="baz"; bar=123, boo, test="123", foobar="foo;bar"',
                [
                    ['foo' => 'baz', 'bar' => '123'],
                    ['boo'],
                    ['test' => '123'],
                    ['foobar' => 'foo;bar'],
                ],
            ],
            [
                '<http://.../side.jpeg?test=1>; rel="side"; type="image/jpeg",<http://.../side.jpeg?test=2>; rel=side; type="image/jpeg"',
                [
                    ['<http://.../side.jpeg?test=1>', 'rel' => 'side', 'type' => 'image/jpeg'],
                    ['<http://.../side.jpeg?test=2>', 'rel' => 'side', 'type' => 'image/jpeg'],
                ],
            ],
            [
                '',
                [],
            ],
        ];
    }

    /**
     * @dataProvider parseParamsProvider
     */
    public function testParseParams($header, $result)
    {
        $this->assertSame($result, Psr7\Header::parse($header));
    }

    public function testParsesArrayHeaders()
    {
        $header = ['a, b', 'c', 'd, e'];
        $this->assertSame(['a', 'b', 'c', 'd', 'e'], Psr7\Header::normalize($header));
    }
}
