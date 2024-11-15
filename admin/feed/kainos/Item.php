<?php

namespace RexTheme\RexShoppingKainosFeed;

use RexTheme\RexShoppingKainosFeed\Containers\RexShopping;

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

    /**
     * Product id
     * @var string
     */
    private $id = null;

    public function __construct( $namespace = null )
    {
        $this->namespace = $namespace;
    }

    public function id($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function title($title)
    {
        $node = new Node('title');
        $this->nodes['title'] = $node->value($title)->_namespace($this->namespace);
    }

    public function item_price($item_price)
    {
        $node = new Node('item_price');
        $this->nodes['item_price'] = $node->value($item_price)->_namespace($this->namespace);
    }

    public function loyalty_program_item_price($loyalty_program_item_price)
    {
        $node = new Node('loyalty_program_item_price');
        $this->nodes['loyalty_program_item_price'] = $node->value($loyalty_program_item_price)->_namespace($this->namespace);
    }

    public function manufacturer($manufacturer)
    {
        $node = new Node('manufacturer');
        $this->nodes['manufacturer'] = $node->value($manufacturer)->_namespace($this->namespace);
    }

    public function image_url($image_url)
    {
        $node = new Node('image_url');
        $this->nodes['image_url'] = $node->value($image_url)->_namespace($this->namespace);
    }

    public function product_url($product_url)
    {
        $node = new Node('product_url');
        $this->nodes['product_url'] = $node->value($product_url)->_namespace($this->namespace);
    }

    public function categories($categories)
    {
        $this->nodes['categories'] = [];

        foreach ((array) $categories as $category) {
            $node = new Node('category');
            $this->nodes['categories'][] = $node->value($category)->_namespace($this->namespace);
        }
    }

    public function stock($stock)
    {
        $node = new Node('stock');
        $this->nodes['stock'] = $node->value($stock)->_namespace($this->namespace);
    }

    public function ean_code($ean_code)
    {
        $node = new Node('ean_code');
        $this->nodes['ean_code'] = $node->value($ean_code)->_namespace($this->namespace);
    }

    public function eans($eans)
    {
        $this->nodes['eans'] = [];

        foreach ((array) $eans as $ean) {
            $node = new Node('ean');
            $this->nodes['eans'][] = $node->value($ean)->_namespace($this->namespace);
        }
    }

    public function manufacturer_code($manufacturer_code)
    {
        $node = new Node('manufacturer_code');
        $this->nodes['manufacturer_code'] = $node->value($manufacturer_code)->_namespace($this->namespace);
    }

    public function model($model)
    {
        $node = new Node('model');
        $this->nodes['model'] = $node->value($model)->_namespace($this->namespace);
    }

    public function additional_images($additional_images)
    {
        $this->nodes['additional_images'] = [];

        foreach ((array) $additional_images as $additional_image) {
            $node = new Node('image');
            $this->nodes['additional_images'][] = $node->value($additional_image)->_namespace($this->namespace);
        }
    }

    /* TODO
    public function specs($specs)
    {
        $node = new Node('specs');
        $this->nodes['specs'] = $node->value($specs)->_namespace($this->namespace);
    }
    */

    public function delivery_time($delivery_time)
    {
        $node = new Node('delivery_time');
        $this->nodes['delivery_time'] = $node->value($delivery_time)->_namespace($this->namespace);
    }

    public function delivery_text($delivery_text)
    {
        $node = new Node('delivery_text');
        $this->nodes['delivery_text'] = $node->value($delivery_text)->_namespace($this->namespace);
    }

    public function short_message($short_message)
    {
        $node = new Node('short_message');
        $this->nodes['short_message'] = $node->value($short_message)->_namespace($this->namespace);
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
