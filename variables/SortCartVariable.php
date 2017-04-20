<?php
/**
 * @author    Casey Lee
 * @copyright Copyright (c) 2017 Casey Lee
 * @link      github.com/simplethemes
 * @package   sortcart
 * @since     1.0.0
 */

namespace Craft;

class SortCartVariable
{
    /**
     * Sort cart/order lineitems by product type
     * @param  Commerce_OrderModel $order
     * @param  array  $types types to filter on
     * @return string output
     */
    public function sorted($order,$types=array(),$bykey='name')
    {
        if (!$order instanceof Commerce_OrderModel)
            return 'Error: The first argument should be an Commerce_OrderModel';
        return craft()->sortCart->displayItems($order,$types,$bykey);
    }
}