<?php

namespace RexTheme\RexShoppingVarleFeed;

use RexTheme\RexShoppingVarleFeed\Containers\RexShopping;

class Item
{
    /**
     * Stores all of the product nodes
     * @var Node[]
     */
    private $nodes = array();

    /**
     * Item index
     * @var string
     */
    private $index = null;

    /**
     * [$namespace - (g:) namespace definition]
     * @var string
     */
    protected $namespace;

    public function __construct( $namespace = null )
    {
        $this->namespace = $namespace;
    }

    public function title($title)
    {
        $node = new Node('title');
        $this->nodes['title'] = $node->value($title)->_namespace($this->namespace);
    }

    public function description($description)
    {
        $node = new Node('description');
        $this->nodes['description'] = $node->value($description)->_namespace($this->namespace);
    }

    public function id($id)
    {
        $node = new Node('id');
        $this->nodes['id'] = $node->value($id)->_namespace($this->namespace);
    }

    public function price($price)
    {
        $node = new Node('price');
        $this->nodes['price'] = $node->value($price)->_namespace($this->namespace);
    }

    public function categories($categories)
    {
        $this->nodes['categories'] = [];

        foreach ((array) $categories as $category) {
            $node = new Node('category');
            $this->nodes['categories'][] = $node->value($category)->_namespace($this->namespace);
        }
    }

    public function delivery_text($delivery_text)
    {
        $node = new Node('delivery_text');
        $this->nodes['delivery_text'] = $node->value($delivery_text)->_namespace($this->namespace);
    }

    public function images($images)
    {
        $this->nodes['images'] = [];

        foreach ((array) $images as $image) {
            $node = new Node('image');
            $this->nodes['images'][] = $node->value($image)->_namespace($this->namespace);
        }
    }

    public function quantity($quantity)
    {
        $node = new Node('quantity');
        $this->nodes['quantity'] = $node->value($quantity)->_namespace($this->namespace);
    }

    public function barcode_format($barcode_format)
    {
        $node = new Node('barcode_format');
        $this->nodes['barcode_format'] = $node->value($barcode_format)->_namespace($this->namespace);
    }

    public function barcode($barcode)
    {
        $node = new Node('barcode');
        $this->nodes['barcode'] = $node->value($barcode)->_namespace($this->namespace);
    }

    public function model($model)
    {
        $node = new Node('model');
        $this->nodes['model'] = $node->value($model)->_namespace($this->namespace);
    }

    public function weight($weight)
    {
        $node = new Node('weight');
        $this->nodes['weight'] = $node->value($weight)->_namespace($this->namespace);
    }

    public function manufacturer($manufacturer)
    {
        $node = new Node('manufacturer');
        $this->nodes['manufacturer'] = $node->value($manufacturer)->_namespace($this->namespace);
    }

    public function videos($videos)
    {
        $this->nodes['videos'] = [];

        foreach ((array) $videos as $video) {
            $node = new Node('video');
            $this->nodes['videos'][] = $node->value($video)->_namespace($this->namespace);
        }
    }

    /* TODO
    public function attributes($attributes)
    {
        $node = new Node('attributes');
        $this->nodes['attributes'] = $node->value($attributes)->_namespace($this->namespace);
    }
    */

    public function group($group)
    {
        $node = new Node('group');
        $this->nodes['group'] = $node->value($group)->_namespace($this->namespace);
    }

    public function price_old($price_old)
    {
        $node = new Node('price_old');
        $this->nodes['price_old'] = $node->value($price_old)->_namespace($this->namespace);
    }

    public function url($url)
    {
        $node = new Node('url');
        $this->nodes['url'] = $node->value($url)->_namespace($this->namespace);
    }

    public function warranty($warranty)
    {
        $node = new Node('warranty');
        $this->nodes['warranty'] = $node->value($warranty)->_namespace($this->namespace);
    }

    public function product_with_gift($product_with_gift)
    {
        $node = new Node('product_with_gift');
        $this->nodes['product_with_gift'] = $node->value($product_with_gift)->_namespace($this->namespace);
    }

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $node = new Node($name);
        $this->nodes[$name] = $node->value($arguments[0])->_namespace($this->namespace);
    }

    /**
     * Returns item nodes
     * @return array
     */
    public function nodes()
    {
        return $this->nodes;
    }

    /**
     * Sets item index
     * @param $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Delete an item
     */
    public function delete()
    {
        RexShopping::removeItemByIndex($this->index);
    }
}
