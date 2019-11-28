<?php

/**
 * The file that generates xml feed for any merchant with custom configuration.
 *
 * A class definition that includes functions used for generating xml feed.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed_Trovaprezzi
 * @subpackage Rex_Product_Feed_Trovaprezzi/includes
 * @author     RexTheme <info@rextheme.com>
 */


class Rex_Product_Feed_Trovaprezzi extends Rex_Product_Feed_Other {

    /**
     * @return string
     */
    public function setItemWrapper()
    {
        return 'Offer';
    }

    public function setItemsWrapper()
    {
        return 'Products';
    }
}