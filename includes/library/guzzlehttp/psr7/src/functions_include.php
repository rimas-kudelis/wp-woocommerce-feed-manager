<?php

namespace RexFeed;

// Don't redefine the functions if included multiple times.
if (!\function_exists('RexFeed\\GuzzleHttp\\Psr7\\str')) {
    require __DIR__ . '/functions.php';
}