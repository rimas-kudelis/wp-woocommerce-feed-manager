<?php

namespace RexTheme\RexShoppingFeed;

use SimpleXMLElement;
use RexTheme\RexShoppingFeed\Item;
use Gregwar\Cache\Cache;

class Feed
{

    /**
     * Define Google Namespace url
     * @var string
     */
    protected $namespace;

    /**
     * [$version description]
     * @var string
     */
    protected $version;

    /**
     * Stores the list of items for the feed
     * @var Item[]
     */
    private $items = array();

    /**
     * Stores the list of items for the feed
     * @var Item[]
     */
    private $items_row = array();

    /**
     * [$channelCreated description]
     * @var boolean
     */
    private $channelName;


    /**
     * [$channelCreated description]
     * @var boolean
     */
    private $itemlName;

    /**
     * [$channelCreated description]
     * @var boolean
     */
    private $channelCreated = false;

    /**
     * The base for the feed
     * @var SimpleXMLElement
     */
    private $feed = null;

    /**
     * [$title description]
     * @var string
     */
    private $title = '';

    /**
     * [$cacheDir description]
     * @var string
     */
    private $cacheDir = 'cache';

    /**
     * [$description description]
     * @var string
     */
    private $description = '';

    /**
     * [$link description]
     * @var string
     */
    private $link = '';


    private $rss = 'rss';

    /**
     * Feed constructor
     */
    public function __construct($wrapper = false, $itemlName = 'item', $namespace = null, $version = '', $rss = 'rss')
    {
        $this->namespace   = $namespace;
        $this->version     = $version;
        $this->channelName = $wrapper;
        $this->itemlName   = $itemlName;
        $this->rss          = $rss;

        $namespace = $this->namespace && !empty($this->namespace) ? " xmlns:f='$this->namespace'" : '';
        $version   = $this->version && !empty($this->version) ? " version='$this->version'" : '';

        $this->feed = new SimpleXMLElement("<$rss $namespace $version ></$rss>");
    }

    /**
     * @param string $title
     */
    public function title($title)
    {
        $this->title = (string)$title;
    }

    /**
     * @param string $description
     */
    public function description($description)
    {
        $this->description = (string)$description;
    }

    /**
     * @param string $link
     */
    public function link($link)
    {
        $this->link = (string)$link;
    }

    /**
     * [channel description]
     */
    private function channel()
    {
        if (! $this->channelName) {
            $this->channelCreated = true;
            return;
        }
        if (! $this->channelCreated ) {
            $channel = $this->feed->addChild($this->channelName);
            ! $this->title       ?: $channel->addChild('title', $this->title);
            ! $this->link        ?: $channel->addChild('link', $this->link);
            ! $this->description ?: $channel->addChild('description', $this->description);
            $this->channelCreated = true;
        }
    }

    /**
     * @return Item
     */
    public function createItem()
    {
        $this->channel();
        $item = new Item($this->namespace);
        $index = 'index_' . md5(microtime());
        $this->items[$index] = $item;
        $item->setIndex($index);
        return $item;
    }

    /**
     * @param int $index
     */
    public function removeItemByIndex($index)
    {
        unset($this->items[$index]);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function standardiseSizeVarient($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function standardiseColourVarient($value)
    {
        return $value;
    }

    /**
     * @param string $group
     * @return bool|string
     */
    public function isVariant($group)
    {
        if (preg_match("#^\s*colou?rs?\s*$#is", trim($group))) {
            return 'color';
        }
        if (preg_match("#^\s*sizes?\s*$#is", trim($group))) {
            return 'size';
        }
        if (preg_match("#^\s*materials?\s*$#is", trim($group))) {
            return 'material';
        }
        return false;
    }

    /**
     * Adds items to feed
     */
    private function addItemsToFeed()
    {
        foreach ($this->items as $item) {

            /** @var SimpleXMLElement $feedItemNode */
            if ( $this->channelName && !empty($this->channelName) ) {
                $feedItemNode = $this->feed->{$this->channelName}->addChild($this->itemlName);
            }else{
                $feedItemNode = $this->feed->addChild($this->itemlName);
            }
            foreach ($item->nodes() as $itemNode) {

                if (is_array($itemNode)) {
                    foreach ($itemNode as $node) {
                        $feedItemNode->addChild(str_replace(' ', '_', $node->get('name')), $node->get('value'), $node->get('_namespace'));
                    }
                } else {

                    $itemNode->attachNodeTo($feedItemNode);
                }
            }
        }
    }

    private function addItemsToFeedText()
    {

        if(count($this->items)){
            $this->items_row[] = array_keys(array_pop($this->items)->nodes());
            foreach ($this->items as $item) {
                $row = array();
                foreach ($item->nodes() as $itemNode) {
                    if (is_array($itemNode)) {
                        foreach ($itemNode as $node) {
//                            $row[] = $node->get('value');
                            $row[] = str_replace(array("\r\n", "\n", "\r"), ' ', $node->get('value'));
                        }
                    } else {
//                        $row[] = $itemNode->get('value');
                        $row[] = str_replace(array("\r\n", "\n", "\r"), ' ', $itemNode->get('value'));
                    }
                }
                $this->items_row[] = $row;
            }
            $str = '';
            foreach ($this->items_row as $fields) {
                $str .= implode("\t", $fields) . "\n";
            }
        }
        return $str;
    }

    /**
     * Retrieve Google product categories from internet and cache the result
     * @return array
     */
    public function categories()
    {
        $cache = new Cache;
        $cache->setCacheDirectory($this->cacheDir);
        $data = $cache->getOrCreate('google-feed-taxonomy.txt', array( 'max-age' => '86400' ), function () {
            return file_get_contents("http://www.google.com/basepages/producttype/taxonomy.en-GB.txt");
        });
        return explode("\n", trim($data));
    }

    /**
     * Build an HTML select containing Google taxonomy categories
     * @param string $selected
     * @return string
     */
    public function categoriesAsSelect($selected = '')
    {
        $categories = $this->categories();
        unset($categories[0]);
        $select = '<select name="google_category">';
        $select .= '<option value="">Please select a Google Category</option>';
        foreach ($categories as $category) {
            $select .= '<option ' . ($category == $selected ? 'selected' : '') . ' name="' . $category . '">' . $category . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    /**
     * Generate RSS feed
     * @param bool $output
     * @return string
     */
    public function asRss($output = false)
    {
        ob_end_clean();
        $this->addItemsToFeed();
        $data = html_entity_decode($this->feed->asXml());
        if ($output) {
            header('Content-Type: application/xml; charset=utf-8');
            die($data);
        }
        return $data;
    }

    /**
     * Generate Txt feed
     * @param bool $output
     * @return string
     */
    public function asTxt($output = false)
    {
        ob_end_clean();
        $data = $this->addItemsToFeedText();
        if ($output) {
            die($data);
        }
        return $data;
    }

    /**
     * Remove last inserted item
     */
    public function removeLastItem()
    {
        array_pop($this->items);
    }
}