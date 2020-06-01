<?php

namespace RexTheme\RexShoppingVivinoFeed\VivinoFeed;

use SimpleXMLElement;


class VivinoFeed  extends \RexTheme\RexShoppingFeed\Feed
{
    protected $attributes;

    protected function init_atts() {
        $this->attributes = array(
            'products' => array(
                'price' => 'Price',
                'product-name' => 'Product name',
                'inventory-count' => 'Inventory count',
                'link' => 'Link',
                'bottles-size' => 'Bottles size',
                'price-discounted-from' => 'price-discounted-from',
                'price-discounted-until' => 'price-discounted-until',
            ),
            'extras' => array(
                'wine-name' => 'Wine name',
                'bottles-quantity' => 'Bottles quantity',
                'acidity' => 'acidity',
                'ageing' => 'ageing',
                'alcohol' => 'alcohol',
                'appellation' => 'appellation',
                'certified-biodynamic' => 'certified-biodynamic',
                'certified-organic' => 'certified-organic',
                'closure' => 'closure',
                'color' => 'color',
                'contains-added-sulfites' => 'contains-added-sulfites',
                'contains-egg-allergens' => 'contains-egg-allergens',
                'contains-milk-allergens' => 'contains-milk-allergens',
                'country' => 'country',
                'decant-for' => 'decant-for',
                'description' => 'description',
                'drinking-temperature' => 'drinking-temperature',
                'drinking-years-from' => 'drinking-years-from',
                'drinking-years-to' => 'drinking-years-to',
                'importer-address' => 'importer-address',
                'kosher' => 'kosher',
                'meshuval' => 'meshuval',
                'non-alcoholic' => 'non-alcoholic',
                'ph' => 'ph',
                'producer' => 'producer',
                'producer-address' => 'producer-address',
                'product-id' => 'product-id',
                'production-size' => 'production-size',
                'residual-sugar' => 'residual-sugar',
                'sweetness' => 'sweetness',
                'varietal' => 'varietal',
                'vegan-friendly' => 'vegan-friendly',
                'vintage' => 'vintage',
                'winemaker' => 'winemaker',
            ),
        );
    }

    /**
     * Adds items to feed
     */
    protected function addItemsToFeed()
    {
        $this->init_atts();
        foreach ($this->items as $item) {

            /** @var SimpleXMLElement $feedItemNode */
            if ( $this->channelName && !empty($this->channelName) ) {
                $feedItemNode = $this->feed->{$this->channelName}->addChild($this->itemlName);
            }else{
                $feedItemNode = $this->feed->addChild($this->itemlName);
            }
            $i=0;
            foreach ($item->nodes() as $itemNode) {

                if (is_array($itemNode)) {
                    foreach ($itemNode as $node) {
                        $feedItemNode->addChild(str_replace(' ', '_', $node->get('name')), $node->get('value'), $node->get('_namespace'));
                    }
                } else {
                    if(array_key_exists($itemNode->get('name'), $this->attributes['products'])) {
                        if($itemNode->get('name') === 'bottles-size') {
                            $bottle_size = $feedItemNode->addChild($itemNode->get('name'));
                            $bottle_size->addAttribute('size', $itemNode->get('value'));
                        }else {
                            $feedItemNode->addChild($itemNode->get('name'));
                        }
                    }
                    if(array_key_exists($itemNode->get('name'), $this->attributes['extras'])) {
                        if( !empty($feedItemNode->extras)){
                            $feedItemNode->extras->addChild($itemNode->get('name'), $itemNode->get('value'));
                        }else {
                            $feedItemNode->addChild('extras');
                            $feedItemNode->extras->addChild($itemNode->get('name'), $itemNode->get('value'));
                        }
                    }
                }


            }
        }
    }



    /**
     * Generate CSV feed
     *
     * @param bool $batch
     * @param bool $output
     * @return array|\RexTheme\RexShoppingFeed\Item[]|string
     */
    public function asRss($output = false)
    {

        if (ob_get_contents()) ob_end_clean();
        $this->addItemsToFeed();
        $data = $this->feed->asXml();
        if ($output) {
            header('Content-Type: application/xml; charset=utf-8');
            die($data);
        }

        return $data;
    }
}
