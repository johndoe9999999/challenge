<?php

class Monoqi_Availability_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Check if availability dates of product are within range or not.
     *
     * @param $product
     * @return bool
     */
    public function isAvailable($product)
    {
        $storeTimeZone = new DateTimeZone(Mage::getStoreConfig('general/locale/timezone'));
        $dateFrom      = new DateTime($product->getAvailableFrom(), $storeTimeZone);
        $date          = new DateTime('now', $storeTimeZone);
        $dateTo        = new DateTime($product->getAvailableTo(), $storeTimeZone);
        if ($date >= $dateFrom && $date <= $dateTo) {
            return true;
        }
        return false;
    }
}
