<?php

namespace RexGuzzleHttp\Promise\Tests;

class Thing2 implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return '{}';
    }
}
