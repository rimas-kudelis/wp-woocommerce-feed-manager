<?php

class RexPremium{
    const REX_REMAINING_FEED 	= "2000";

    private $total_product;

    private $remaining_feed;

    public static function rex_remaining_feed_option(){
        if(!get_option('rex_remaining_feed')){
            update_option('rex_remaining_feed', self::REX_REMAINING_FEED);
        }
    }

    public function rex_remaining_feed(){

        if(get_option('rex_remaining_feed')){
            $this->remaining_feed   =   get_option('rex_remaining_feed');
            $this->total_product    =   $this->rex_feed_total_products();
            if(($this->remaining_feed) > ($this->total_product)){
                $this->remaining_feed = $this->remaining_feed - $this->total_product;
                update_option('rex_remaining_feed', $this->remaining_feed);
                return $this->total_product;
            }else{
                update_option('rex_remaining_feed', -2);
                return $this->remaining_feed;
            }
        }
    }


    public function rex_feed_total_products(){
        $total_product = wp_count_posts('product')->publish;
        return $total_product;
    }

}