<?php

namespace RexTheme\RexShoppingFeedCustom\AmazonItCollaneFeed;

class AmazonItCollaneFeed extends \RexTheme\RexShoppingFeed\Feed
{
    protected $attributes;

    protected function init_atts() {
        $this->attributes = array(
            'feed_product_type' => 'Tipo di prodotto',
            'item_sku' => 'SKU venditore',
            'brand_name' => 'Marca',
            'manufacturer' => 'Produttore',
            'part_number' => 'Codice articolo del produttore',
            'item_name' => 'Titolo',
            'external_product_id_type' => 'Tipo codice prodotto',
            'external_product_id' => 'Codice prodotto standard',
            'recommended_browse_nodes' => 'Nodi di navigazione consigliati',
            'material_type1' => 'Materiale',
            'material_type2' => 'Materiale',
            'material_type3' => 'Materiale',
            'material_type4' => 'Materiale',
            'material_type5' => 'Materiale',
            'metal_type' => 'Tipo di metallo',
            'style_name' => 'Stile',
            'standard_price' => 'Prezzo',
            'quantity' => 'Quantità',
            'main_image_url' => 'URL immagine principale',
            'sale_price' => 'Prezzo scontato',
            'currency' => 'Valuta',
            'other_image_url1' => 'URL altre immagini1',
            'is_discontinued_by_manufacturer' => 'Fuori produzione',
            'offering_can_be_gift_messaged' => 'Disponibilità confezione regalo',
            'offering_can_be_giftwrapped' => 'Is Gift Wrap Available?',
            'merchant_release_date' => 'Data di rilascio',
            'number_of_items' => 'Numero di articoli',
            'item_package_quantity' => 'Numero di articoli inclusi nel collo',
            'max_aggregate_ship_quantity' => 'Numero massimo di articoli nella confezione',
            'restock_date' => 'Data di riassortimento',
            'fulfillment_latency' => 'Tempo di evasione ordine',
            'sale_end_date' => 'Data fine sconto',
            'sale_from_date' => 'Data inizio sconto',
            'condition_type' => 'Tipo condizioni offerta',
            'condition_note' => 'Nota sulla condizione del prodotto',
            'product_site_launch_date' => 'Data di lancio sul sito',
            'max_order_quantity' => 'Massima Quantità Ordinabile',
            'merchant_shipping_group_name' => 'Gruppo Spedizione venditore',
            'offering_start_date' => 'Data di inizio dell\'offerta',
            'delivery_schedule_group_id' => 'Elenco SKU  consegna programmata',
            'uvp_list_price' => 'Prezzo consigliato',
            'product_tax_code' => 'Codice fiscale prodotto',
            'parent_sku' => 'Parent_SKU'
        );
    }

    /**
     * Add Items to csv
     * @return array|\RexTheme\RexShoppingFeed\Item[]
     */
    private function addItemsToFeedCSV($batch, $type = 'seller'){

        if(count($this->items)){
            if($batch === 1) {
                $this->init_atts();
                if($type === 'seller') {
                    $first_row = array('TemplateType=fptcustom', 'Version=2020.0414', 'TemplateSignature=S0lUQ0hFTixPVVRET09SX1JFQ1JFQVRJT05fUFJPRFVDVCxXSVJFTEVTU19BQ0NFU1NPUlksT1VURVJXRUFSLEhPTUVfQkVEX0FORF9CQVRILFNXRUFURVIsU0hJUlQsU0hPUlRTLEZBU0hJT05ORUNLTEFDRUJSQUNFTEVUQU5LTEVULEZJTkVORUNLTEFDRUJSQUNFTEVUQU5LTEVULFNQT1JUSU5HX0dPT0RTLEhBTkRCQUcsU1dJTVdFQVIsQlJBLEFSVF9TVVBQTElFUyxTT0NLU0hPU0lFUlksQUNDRVNTT1JZLFNFUlZFV0FSRSxQQU5UUyxPVVRET09SX0xJVklORyxIT01F', 'The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.');

                }else {
                    $first_row = array('TemplateType=fptcustom', 'Version=2020.0907', 'TemplateSignature=S0lUQ0hFTixIT01FX0ZVUk5JVFVSRV9BTkRfREVDT1IsSE9NRQ', 'settings=contentLanguageTag=en_GB&feedType=113&timestamp=2020-09-07T11%3A54%3A24.482Z', 'The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.');

                }
                $second_row = array();
                $_third_row = array_keys(end($this->items)->nodes());
                $third_row = [];

                foreach ($_third_row as $key) {
                    if(array_key_exists($key, $this->attributes)) {
                        $second_row[] = $this->attributes[$key];
                    }else {
                        $second_row[] = ucfirst($key);
                    }
                    $third_row[] = strtolower(str_replace(' ', '_', $key));
                }
                $this->items_row[] = $first_row;
                $this->items_row[] = $second_row;
                $this->items_row[] = $third_row;
            }


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

            $str = '';
            foreach ($this->items_row as $fields) {
                $str .= implode("\t", $fields) . "\n";
            }
        }

        return $this->items_row;

    }


    /**
     * Generate CSV feed
     *
     * @param bool $batch
     * @param bool $output
     * @return array|\RexTheme\RexShoppingFeed\Item[]|string
     */
    public function asCSVFeeds($batch, $type = 'seller', $output = false)
    {
        ob_end_clean();
        $data = $this->addItemsToFeedCSV($batch, $type);
        if ($output) {
            die($data);
        }
        return $data;
    }


    /**
     * Add Items to TSV
     * @return array|\RexTheme\RexShoppingFeed\Item[]
     */
    private function addItemsToFeedTSV($batch, $type = 'seller'){
        $str = '';
        if(count($this->items)){
            if($batch === 1) {
                $this->init_atts();
                if($type === 'seller') {
                    $first_row = array('TemplateType=fptcustom', 'Version=2020.0414', 'TemplateSignature=S0lUQ0hFTixPVVRET09SX1JFQ1JFQVRJT05fUFJPRFVDVCxXSVJFTEVTU19BQ0NFU1NPUlksT1VURVJXRUFSLEhPTUVfQkVEX0FORF9CQVRILFNXRUFURVIsU0hJUlQsU0hPUlRTLEZBU0hJT05ORUNLTEFDRUJSQUNFTEVUQU5LTEVULEZJTkVORUNLTEFDRUJSQUNFTEVUQU5LTEVULFNQT1JUSU5HX0dPT0RTLEhBTkRCQUcsU1dJTVdFQVIsQlJBLEFSVF9TVVBQTElFUyxTT0NLU0hPU0lFUlksQUNDRVNTT1JZLFNFUlZFV0FSRSxQQU5UUyxPVVRET09SX0xJVklORyxIT01F', 'The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.');

                }else {
                    $first_row = array('TemplateType=fptcustom', 'Version=2020.0907', 'TemplateSignature=S0lUQ0hFTixIT01FX0ZVUk5JVFVSRV9BTkRfREVDT1IsSE9NRQ', 'settings=contentLanguageTag=en_GB&feedType=113&timestamp=2020-09-07T11%3A54%3A24.482Z', 'The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.');

                }
                $second_row = array();
                $_third_row = array_keys(end($this->items)->nodes());
                $third_row = [];

                foreach ($_third_row as $key) {
                    if(array_key_exists($key, $this->attributes)) {
                        $second_row[] = $this->attributes[$key];
                    }else {
                        $second_row[] = ucfirst($key);
                    }
                    $third_row[] = strtolower(str_replace(' ', '_', $key));
                }
                $this->items_row[] = $first_row;
                $this->items_row[] = $second_row;
                $this->items_row[] = $third_row;
            }

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


    /**
     * Generate TSV feed
     *
     * @param bool $batch
     * @param bool $output
     * @return array|\RexTheme\RexShoppingFeed\Item[]|string
     */
    public function asTSVFeeds($batch, $type = 'seller', $output = false)
    {

        ob_end_clean();
        $data = $this->addItemsToFeedTSV($batch, $type);
        if ($output) {
            die($data);
        }
        return $data;
    }




    /**
     * Add Items to Text
     * @return array|\RexTheme\RexShoppingFeed\Item[]
     */
    private function addItemsToFeedText($batch, $type = 'seller'){
        $str = '';
        if(count($this->items)){
            if($batch === 1) {
                $this->init_atts();
                if($type === 'seller') {
                    $first_row = array('TemplateType=fptcustom', 'Version=2020.0414', 'TemplateSignature=S0lUQ0hFTixPVVRET09SX1JFQ1JFQVRJT05fUFJPRFVDVCxXSVJFTEVTU19BQ0NFU1NPUlksT1VURVJXRUFSLEhPTUVfQkVEX0FORF9CQVRILFNXRUFURVIsU0hJUlQsU0hPUlRTLEZBU0hJT05ORUNLTEFDRUJSQUNFTEVUQU5LTEVULEZJTkVORUNLTEFDRUJSQUNFTEVUQU5LTEVULFNQT1JUSU5HX0dPT0RTLEhBTkRCQUcsU1dJTVdFQVIsQlJBLEFSVF9TVVBQTElFUyxTT0NLU0hPU0lFUlksQUNDRVNTT1JZLFNFUlZFV0FSRSxQQU5UUyxPVVRET09SX0xJVklORyxIT01F', 'The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.');

                }else {
                    $first_row = array('TemplateType=fptcustom', 'Version=2020.0907', 'TemplateSignature=S0lUQ0hFTixIT01FX0ZVUk5JVFVSRV9BTkRfREVDT1IsSE9NRQ', 'settings=contentLanguageTag=en_GB&feedType=113&timestamp=2020-09-07T11%3A54%3A24.482Z', 'The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows.');

                }
                $second_row = array();
                $_third_row = array_keys(end($this->items)->nodes());
                $third_row = [];

                foreach ($_third_row as $key) {
                    if(array_key_exists($key, $this->attributes)) {
                        $second_row[] = $this->attributes[$key];
                    }else {
                        $second_row[] = ucfirst($key);
                    }
                    $third_row[] = strtolower(str_replace(' ', '_', $key));
                }
                $this->items_row[] = $first_row;
                $this->items_row[] = $second_row;
                $this->items_row[] = $third_row;
            }

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


    /**
     * Generate Text feed
     *
     * @param bool $batch
     * @param bool $output
     * @return array|\RexTheme\RexShoppingFeed\Item[]|string
     */
    public function asTextFeeds($batch, $type = 'seller', $output = false)
    {
        ob_end_clean();
        $data = $this->addItemsToFeedText($batch, $type);
        if ($output) {
            die($data);
        }
        return $data;
    }


}
