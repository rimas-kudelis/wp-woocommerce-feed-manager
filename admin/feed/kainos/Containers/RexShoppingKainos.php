<?php

namespace RexTheme\RexShoppingKainosFeed\Containers;

use RexTheme\RexShoppingKainosFeed\KainosFeed;

class RexShopping
{
    /**
     * Feed container
     * @var KainosFeed
     */
    public static $container = null;

    /**
     * Feed namespace
     * @var KainosFeed
     */
    public static $namespace = null;

    /**
     * Feed rss version
     * @var KainosFeed
     */
    public static $version = '';

    /**
     * Feed products wrapper
     * @var KainosFeed
     */
    public static $wrapper = false;

    /**
     * Feed product item wrapper
     * @var KainosFeed
     */
    public static $itemName = 'product';


    public static $rss = 'products';

    public static $stand_alone = false;

    public static $wrapperel = '';

    public static $namespace_prefix = '';

    /**
     * Return feed container
     * @return KainosFeed
     */
    public static function container()
    {
        if (is_null(static::$container)) {
            static::$container = new KainosFeed(static::$wrapper, static::$itemName, static::$namespace, static::$version, static::$rss, static::$stand_alone, static::$wrapperel, static::$namespace_prefix);
        }

        return static::$container;
    }

    /**
     * Init Feed Configuration
     * @return KainosFeed
     */
    public static function init($wrapper = false, $itemName = 'product', $namespace = null, $version = '', $rss = 'products', $stand_alone = false, $wrapperel = '', $namespace_prefix = '')
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
