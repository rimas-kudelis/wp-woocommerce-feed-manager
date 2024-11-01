<?php

namespace RexTheme\RexShoppingVarleFeed;

use SimpleXMLElement;


class VarleFeed  extends \RexTheme\RexShoppingFeed\Feed
{
    /**
     * Adds items to feed
     */
    protected function addItemsToFeed()
    {
        foreach ($this->items as $item) {
            /** @var SimpleXMLElement $feedItemNode */
            if ( $this->channelName && !empty($this->channelName) ) {
                $feedItemNode = $this->feed->{$this->channelName}->addChild($this->itemlName);
            } else {
                $feedItemNode = $this->feed->addChild($this->itemlName);
            }

            foreach ($item->nodes() as $nodeName => $itemNode) {
                if (empty($itemNode)) {
                    continue;
                }

                if (is_array($itemNode)) {
                    $firstItem = $itemNode[0];
                    $groupNode = $feedItemNode->addChild($nodeName, null, $firstItem->get('_namespace'));

                    foreach ($itemNode as $realItemNode) {
                        $realItemNode->attachNodeTo($groupNode);
                    }

                    continue;
                }

                $itemNode->attachNodeTo($feedItemNode);
            }
        }
    }

    /**
     * [channel description]
     */
    private function channel()
    {
        if (! $this->wrapper) {
            $this->channelCreated = true;
            return;
        }
        if (! $this->channelCreated ) {
            $channel = $this->channelName ? $this->feed->addChild($this->channelName) : $this->feed;
            ! $this->title       ?: $channel->addChild('title', $this->title);
            ! $this->link        ?: $channel->addChild('link', $this->link);
            ! $this->description ?: $channel->addChild('description', $this->description);
            ! $this->datetime ?: $channel->addChild('datetime', $this->datetime);
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
