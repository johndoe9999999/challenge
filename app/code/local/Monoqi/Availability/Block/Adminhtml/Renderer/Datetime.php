<?php

class Monoqi_Availability_Block_Adminhtml_Renderer_Datetime extends Varien_Data_Form_Element_Date
{
    /**
     * Construct
     *
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setTime(true);
    }

    /**
     * Retrieve date format
     *
     * @return string
     */
    public function getFormat()
    {
        return Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }
}
