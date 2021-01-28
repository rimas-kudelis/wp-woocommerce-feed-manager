<?php

namespace rextheme\FacebookShoppingFeed;

use SimpleXMLElement;
use rextheme\FacebookShoppingFeed\Item;
use Gregwar\Cache\Cache;

class Feed
{

    /**
     * Define Google Namespace url
     * @var string
     */
    protected $namespace = 'http://base.google.com/ns/1.0';

    /**
     * @var string
     */
    protected $version = '2.0';

    /**
     * @var string
     */
    protected $iso4217CountryCode = 'GBP';

    /**
     * Stores the list of items for the feed
     * @var Item[]
     */
    private $items = array();

    /**
     * @var bool
     */
    private $channelCreated = false;

    /**
     * The base for the feed
     * @var SimpleXMLElement
     */
    private $feed = null;

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $cacheDir = 'cache';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $link = '';

    /**
     * Feed constructor
     */
    public function __construct()
    {
        $this->feed = new SimpleXMLElement('<rss xmlns:g="' . $this->namespace . '" version="' . $this->version . '"></rss>');
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
     * @param $code
     */
    public function setIso4217CountryCode( $code )
    {
        $this->iso4217CountryCode = $code;
    }

    /**
     * @return string
     */
    public function getIso4217CountryCode()
    {
        return $this->iso4217CountryCode;
    }

    /**
     * [channel description]
     */
    private function channel()
    {
        if (! $this->channelCreated) {
            $channel = $this->feed->addChild('channel');
            $channel->addChild('title', htmlspecialchars($this->title));
            $channel->addChild('link', htmlspecialchars($this->link));
            $channel->addChild('description', htmlspecialchars($this->description));
            $this->channelCreated = true;
        }
    }

    /**
     * @return Item
     */
    public function createItem()
    {
        $this->channel();
        $item = new Item($this);
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
            $feedItemNode = $this->feed->channel->addChild('item');
            foreach ($item->nodes() as $itemNode) {
                if (is_array($itemNode)) {
                    foreach ($itemNode as $node) {
                        $feedItemNode->addChild($node->get('name'), $node->get('value'), $node->get('_namespace'));
                    }
                } else {
                    $itemNode->attachNodeTo($feedItemNode);
                }
            }
        }
    }


    private function addItemsToFeedText() {
        $str = '';
        if(count($this->items)){
            $this->items_row[] = array_keys(end($this->items)->nodes());
            foreach ($this->items as $item) {
                $row = array();
                foreach ($item->nodes() as $itemNode) {
                    if (is_array($itemNode)) {
                        foreach ($itemNode as $node) {
                            $row[] = str_replace(array("\r\n", "\n", "\r"), ' ', $node->get('value'));
                        }
                    } else {
                        $row[] = str_replace(array("\r\n", "\n", "\r"), ' ', $itemNode->get('value'));
                    }
                }
                $this->items_row[] = $row;
            }
            foreach ($this->items_row as $fields) {
                $str .= implode("\t", $fields) . "\n";
            }
        }
        return $str;
    }

    private function addItemsToFeedCSV(){
        $header_one = [];
        $header_two = [];
        if(count($this->items)){
            $header_two = array_keys(end($this->items)->nodes());


            foreach ($header_two as $headerone){
                if($headerone==='id'){
                    $header_one[] = '# Required | A unique ID for the item. Use the SKU if you can. Enter each ID only once or the item won&t upload.
                                For dynamic ads; this ID must exactly match the content ID for the same item in your Facebook pixel. Character limit: 100.';
                }elseif ($headerone==='title'){
                    $header_one[] = '# Required | A specific; relevant title for the item. Include keywords like brand; attributes or condition. Character limit: 150.';
                }elseif ($headerone==='description'){
                    $header_one[] = '# Required | A short; relevant description of the item. Include specific or unique product features like material or color.
                Use plain text and don&t enter text in all capital letters. Character limit: 5;000.';
                }elseif ($headerone ==='availability'){
                    $header_one[] = '# Required | The current availability of the item in your store. | Supported values: in stock; available for order; preorder; out of stock; discontinued';
                }elseif ($headerone==='condition'){
                    $header_one[] = '# Required | The condition of the item. | Supported values: new; refurbished; used';
                }elseif ($headerone==='price'){
                    $header_one[] = '# Required | The cost and currency of the item. The price is a number followed by the 3-digit currency code (ISO 4217 standards). Use a period (".") as the decimal point.';
                }elseif ($headerone==='link'){
                    $header_one[] = '# Required | The URL of the specific product page where people can buy the item. If you don&t have a URL; provide a fallback; like a link to your Facebook business Page.';
                }elseif ($headerone==='image_link'){
                    $header_one[] = '# Required | The URL for the main image of your item. Use a square (1:1) format image with a resolution of 1024 x 1024 pixels or higher.';
                }elseif ($headerone==='brand'){
                    $header_one[] = '# Required | The brand name; unique manufacturer part number (MPN) or Global Trade Item Number (GTIN) of the item. You only need to enter one of these; not all of them.
                For GTIN; enter the item&s UPC; EAN; JAN or ISBN. Character limit: 100.';
                }elseif ($headerone==='inventory'){
                    $header_one[] = '# Optional | The quantity of this item in your inventory. People can&t buy this item unless the inventory is 1 or higher. Note: In a Page shop; an item will show as out of stock if inventory is 0;
               even if its availability is in stock.';
                }elseif ($headerone==='google_product_category'){
                    $header_one[] = '# Optional | The Google product category for the item. Learn more about product categories: https://www.facebook.com/business/help/526764014610932.
                    Provide a category in either the fb_product_category field or google_product_category field; or both.';
                }elseif ($headerone==='sale_price'){
                    $header_one[] = '# Optional | The discounted price and currency of the item if its on sale. The price is a number followed by the currency code (ISO 4217 standards). Use "." as the decimal point. A sale price is required if you want to use an overlay for discounted prices.';
                }elseif ($headerone==='sale_price_effective_date'){
                    $header_one[] = '# Optional | The time range for your sale period; including the date; time and time zone when your sale starts and ends. If you don not
                     enter sale dates; any items with a sale_price will remain on sale until you remove their sale price. Use this format: YYYY-MM-DDT23:59+00:00/YYYY-MM-DDT23:59+00:00.
                      Enter the start date as YYYY-MM-DD. Enter a "T". Then; enter the start time in 24-hour format (00:00 to 23:59) followed by the UTC time zone (-12:00 to +14:00). Enter a '/' and then repeat the same format for your end date and time. The example row below uses the PST time zone (-08:00).';
                }elseif ($headerone==='item_group_id'){
                    $header_one[] = '# Optional | If the item is a variant; use this column to enter the same group ID for all variants within the same product group. For example; Blue Facebook T-Shirt is a variant of Facebook T-Shirt.
                     Facebook will select one variant to show from each product group based on relevance or popularity. Character limit: 100.';
                }elseif ($headerone==='gender'){
                    $header_one[] = '# Optional | The gender of a person that the item is targeted towards. | Supported values: female; male; unisex';
                }elseif ($headerone==='gender'){
                    $header_one[] = '# Optional | The gender of a person that the item is targeted towards. | Supported values: female; male; unisex';
                }elseif ($headerone==='color'){
                    $header_one[] = '# Optional | The color of the item. Use one or more words to describe the color; not a hex code. Character limit: 200.';
                }elseif ($headerone==='size'){
                    $header_one[] = '# Optional | The size of the item written as a word; abbreviation or number; such as small; XL or 12. Character limit: 200.';
                }elseif ($headerone==='age_group'){
                    $header_one[] = '# Optional | The age group that the item is targeted towards. | Supported values: adult; all ages; infant; kids; newborn; teen; toddler';
                }elseif ($headerone==='material'){
                    $header_one[] = '# Optional | The material the item is made from; such as cotton; denim or leather. Character limit: 200.';
                }elseif ($headerone==='pattern'){
                    $header_one[] = '# Optional | The pattern or graphic print on the item. Character limit: 100.';
                }elseif ($headerone==='product_type'){
                    $header_one[] = '# Optional | The category the item belongs to according to your business&s product categorization system; if you have one. You can also enter a Google product category. Character limit: 1;000.';
                }elseif ($headerone==='shipping_weight'){
                    $header_one[] = '# Optional | The shipping weight of the item in lb; oz; g or kg.';
                }else{
                    $header_one[] = ' ';
                }
            }

            $this->items_row[] = $header_one;
            $this->items_row[] = $header_two;


            if(!in_array('item_group_id', $this->items_row[0])){
                $this->items_row[0][] = '# Optional | If the item is a variant; use this column to enter the same group ID for all variants within the same product group. For example; Blue Facebook T-Shirt is a variant of Facebook T-Shirt.
                     Facebook will select one variant to show from each product group based on relevance or popularity. Character limit: 100.';
                $this->items_row[1][] = 'item_group_id';

            }
            $length = count($this->items_row[0]);

            foreach ($this->items as $item) {
                $row = array();
                foreach ($item->nodes() as $itemNode) {
                    if (is_array($itemNode)) {
                        foreach ($itemNode as $node) {
                            $row[] = str_replace(array("\r\n", "\n", "\r"), ' ', $node->get('value'));
                        }
                    } else {
                        $row[] = str_replace(array("\r\n", "\n", "\r"), ' ', $itemNode->get('value'));
                    }
                }
                if((count($row)+1) == $length) {
                    $row[$length-1] = '';
                }
                $this->items_row[] = $row;
            }

            $str = '';
            foreach ($this->items_row as $fields) {
                if(!$fields[$length-1]) {
                    $str .= implode("\t", $fields) . ",\n";
                }else {
                    $str .= implode("\t", $fields) . "\n";
                }

            }
        }

        return $this->items_row;


    }

    /**
     * Retrieve Google product categories from internet and cache the result
     * @param string $languageISO639
     * @return array
     */
    public function categories($languageISO639 = 'gb')
    {
        //map two letter language to culture
        $languageMap = array(
            'au' => 'en-AU',
            'br' => 'pt-BR',
            'cn' => 'zh-CN',
            'cz' => 'cs-CZ',
            'de' => 'de-DE',
            'dk' => 'da-DK',
            'es' => 'es-ES',
            'fr' => 'fr-FR',
            'gb' => 'en-GB',
            'it' => 'it-IT',
            'jp' => 'ja-JP',
            'nl' => 'nl-NL',
            'no' => 'no-NO',
            'pl' => 'pl-PL',
            'ru' => 'ru-RU',
            'sw' => 'sv-SE',
            'tr' => 'tr-TR',
            'us' => 'en-US'
        );
        //set default language to gb for backward compatibility
        $languageCulture = $languageMap['gb'];
        if (array_key_exists($languageISO639, $languageMap)) {
            $languageCulture = $languageMap[$languageISO639];
        }

        $cache = new Cache;
        $cache->setCacheDirectory($this->cacheDir);
        $data = $cache->getOrCreate('google-feed-taxonomy.'.$languageISO639.'.txt', array('max-age' => '86400'),
            function () use ($languageCulture) {
                return file_get_contents("http://www.google.com/basepages/producttype/taxonomy." . $languageCulture . ".txt");
            }
        );

        return explode("\n", trim($data));
    }

    /**
     * Build an HTML select containing Google taxonomy categories
     * @param string $selected
     * @param string $languageISO639
     * @return string
     */
    public function categoriesAsSelect($selected = '', $languageISO639 = 'gb')
    {
        $categories = $this->categories($languageISO639);
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
     * @param string $languageISO639
     * @return array
     */
    public function categoriesAsNameAssociativeArray( $languageISO639 = 'gb' )
    {
        $categories = $this->categories($languageISO639);
        unset($categories[0]);
        $return = [];
        foreach( $categories as $key => $value ) {
            $return[$value] = $value;
        }
        return $return;
    }

    /**
     * Generate RSS feed
     * @param bool $output
     * @return string
     */
    public function asRss($output = false)
    {
        if (ob_get_contents()) ob_end_clean();
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
     * Generate CSV feed
     * @param bool $output
     * @return string
     */
    public function asCsv($output = false)
    {
        ob_end_clean();
        $data = $this->addItemsToFeedCSV();
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
