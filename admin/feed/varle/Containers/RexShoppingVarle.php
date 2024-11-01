<?php

namespace RexTheme\RexShoppingVarleFeed\Containers;

use RexTheme\RexShoppingVarleFeed\VarleFeed;

class RexShopping
{
    /**
     * Feed container
     * @var VarleFeed
     */
    public static $container = null;

    /**
     * Feed namespace
     * @var VarleFeed
     */
    public static $namespace = null;

    /**
     * Feed rss version
     * @var VarleFeed
     */
    public static $version = '';

    /**
     * Feed products wrapper
     * @var VarleFeed
     */
    public static $wrapper = 'products';

    /**
     * Feed product item wrapper
     * @var VarleFeed
     */
    public static $itemName = 'product';


    public static $rss = 'root';

    public static $stand_alone = false;

    public static $wrapperel = '';

    public static $namespace_prefix = '';

    /**
     * Return feed container
     * @return VarleFeed
     */
    public static function container()
    {
        if (is_null(static::$container)) {
            static::$container = new VarleFeed(static::$wrapper, static::$itemName, static::$namespace, static::$version, static::$rss, static::$stand_alone, static::$wrapperel, static::$namespace_prefix);
        }

        return static::$container;
    }

    /**
     * Init Feed Configuration
     * @return VarleFeed
     */
    public static function init($wrapper = true, $itemName = 'product', $namespace = null, $version = '', $rss = 'root', $stand_alone = false, $wrapperel = 'products', $namespace_prefix = '')
    {
        static::$namespace = $namespace;
        static::$version = $version;
        static::$wrapper = $wrapper;
        static::$itemName = $itemName;
        static::$rss = $rss;
        static::$stand_alone = $stand_alone;
        static::$wrapperel = $wrapperel;
        static::$namespace_prefix = $namespace_prefix;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(static::container(), $name), $arguments);
    }
}
