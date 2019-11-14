<?php

/**
 * The file that generates xml feed for any merchant with custom configuration.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Google
 * @subpackage Rex_Product_Feed_Google/includes
 * @author     RexTheme <info@rextheme.com>
 */


class Rex_Product_Feed_Pinterest extends Rex_Product_Feed_Other {

    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'item';
    }

    public function setItemsWrapper()
    {
        return 'items';
    }
}
