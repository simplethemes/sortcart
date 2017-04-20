<?php
/**
 * sortcartitems plugin for Craft CMS
 *
 * @author    Casey Lee
 * @copyright Copyright (c) 2017 Casey Lee
 * @link      github.com/simplethemes
 * @package   sortcartitems
 * @since     1.0.0
 */

namespace Craft;

class SortCartService extends BaseApplicationComponent
{

    public $types;
    public $order;

    public function displayItems($order,$types,$bykey)
    {
        $this->order = $order;
        $this->types = $types;
        $out = array();
        $i = 0;

        if (!empty($this->order->lineitems)) {

            $lineitems = $this->getLineItems($bykey);
            foreach ($lineitems as $key => $lineitem) {
                if (!empty($lineitem))
                $out[$i]['type'] = $key;
                foreach ($lineitem as $item) {
                    $out[$i]['items'][] = $item;
                }
                $i++;
            }
            $other_products = $this->displayUnsortedItems($bykey);
            $out = array_merge($out,$other_products);
            return $out;
        }

    }


    public function displayUnsortedItems($bykey)
    {
        $unsorted = $this->getUnsortedItems($bykey);
        $this->types = $unsorted;
        $other_lineitems = $this->getLineItems($bykey);
        $out = array();
        $i=0;
        $out['type'] = 'unsorted';
        foreach ($other_lineitems as $lineitem) {
            foreach ($lineitem as $item) {
                $out['items'][] = $item;
            }
            $i++;
        }
        return array($out);

    }

    /**
     * Get the initial lineitems by variable key
     * @param  string $bykey [name,id]
     * @return array Craft\Commerce_LineItemModel
     */
    public function getLineItems($bykey)
    {
        if ($bykey == 'name') {
            $lineitems = $this->getLineItemsbyTypeName();
        } else {
            $lineitems = $this->getLineItemsbyTypeId();
        }
        return $lineitems;
    }


    /**
     * Fetch an associative array of lineitems by type->name
     * @return array Craft\Commerce_LineItemModel
     */

    public function getLineItemsbyTypeName()
    {
        $lineitems = array();
        foreach ($this->types as $type) {
            $lineitems[$type] = array_filter($this->order->lineitems, function($product) use($type) {
                return $product->purchasable->product->getType()->name == $type;
            });
        }
        return $lineitems;
    }


    /**
     * Fetch an associative array of lineitems by type->id
     * @return array Craft\Commerce_LineItemModel
     */

    public function getLineItemsbyTypeId()
    {
        $lineitems = array();
        $types = $this->types;
        foreach ($types as $type) {
            $type_name = craft()->commerce_productTypes->getProductTypeById($type)->name;
            $lineitems[$type_name] = array_filter($this->order->lineitems, function($product) use($type) {
                return $product->purchasable->product->getType()->id == $type;
            });
        }
        return $lineitems;
    }

    /**
     * Gets an associative array of line items excluding the specified $types
     * @param  string $bykey
     * @return array types
     */

    public function getUnsortedItems($bykey)
    {
        $productTypes = craft()->commerce_productTypes->getAllProductTypes();
        $type_ids = array();
        foreach ($productTypes as $productType) {
            if ($bykey == 'name') {
                $type_ids[] = $productType->name;
            } else {
                $type_ids[] = $productType->id;
            }
        }
        return array_diff($type_ids, $this->types);
    }


}