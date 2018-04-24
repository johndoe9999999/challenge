<?php

class Monoqi_Availability_Model_Observer
{
    /**
     * Check products availability before it is added to the cart.
     *
     * @param Varien_Event_Observer $observer
     * @return Monoqi_Availability_Model_Observer
     */
    public function checkCartAdd(Varien_Event_Observer $observer)
    {
        $productId = Mage::app()->getRequest()->getParam('product');
        $this->validateAvailability($observer, $productId);
        return $this;
    }

    /**
     * Checks quote.
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function checkQuote(Varien_Event_Observer $observer)
    {
        $this->validateAvailability($observer, null);
        return $this;
    }

    /**
     * Checks checkout steps.
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function checkSteps(Varien_Event_Observer $observer)
    {
        if (!$this->validateAvailability($observer, null)) {
            Mage::app()->getResponse()
                ->setHeader('HTTP/1.1', '403 Session Expired')
                ->sendResponse();
        }
        return $this;
    }

    /**
     * Checks before order is placed.
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function checkFinal(Varien_Event_Observer $observer)
    {
        if (!$this->validateAvailability($observer, null)) {
            Mage::app()->getResponse()->setRedirect('checkout/cart');
        }
        return $this;
    }

    /**
     * Is given product / are quote items available or not? If not, let's raise errors and such.
     *
     * @param Varien_Event_Observer $observer
     * @param $productId
     * @return bool
     */
    public function validateAvailability(Varien_Event_Observer $observer, $productId)
    {
        if ($productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            if (!Mage::helper('monoqi_availability')->isAvailable($product)) {
                Mage::getSingleton('core/session')->addError(
                    Mage::helper('monoqi_availability')->__('The product "' . strtoupper($product->getName()) . '" is currently not available.')
                );
                $observer->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                Mage::app()->getResponse()->setRedirect(Mage::helper('core/http')->getHttpReferer())->sendResponse();
                return false;
            }
        } else {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $items = $quote->getAllItems();
            $unavailableItemsFound = false;

            foreach ($items as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                if (!Mage::helper('monoqi_availability')->isAvailable($product)) {
                    $unavailableItemsFound = true;
                    $item->addErrorInfo(
                        'monoqi_availability',
                        0,
                        Mage::helper('monoqi_availability')->__('This product is currently not available. Please remove the product in order to proceed to the checkout.')
                    );
                }
            }
            if ($unavailableItemsFound) {
                $quote->addErrorInfo(
                    'error',
                    'monoqi_availability',
                    0,
                    Mage::helper('monoqi_availability')->__('Some of the products are currently not available.')
                );
                return false;
            }
        }
        return true;
    }
}