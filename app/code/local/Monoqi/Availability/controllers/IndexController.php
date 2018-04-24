<?php

class Monoqi_Availability_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Since our frontend ajax call is polling every 10 seconds, let's at least try to have an action with a lower footprint .
     *
     * @return $this
     */
    public function preDispatch()
    {
        $this->setFlag('', self::FLAG_NO_START_SESSION, 1);
        $this->setFlag('', self::FLAG_NO_PRE_DISPATCH, 1);
        $this->setFlag('', self::FLAG_NO_POST_DISPATCH, 1);
        return $this;
    }

    /**
     * Ajax wants to find out, if we have a status mismatch between frontend and backend. If yes, we do a reload.
     * Only works actually on detail page of a product. For list/checkout/etc you can copy the solution, but
     * having an AJAX would be a bad approach anyway.
     *
     * !!!Honestly ... it's just the crappy 5-minute-solution.
     *
     * 1. We shouldn't poll the shop. Imagine 10K visitors idling around on some list or detail catalog page.
     * Each AJAX boots at least some parts of the shop, is asking the database ... just doesn't make sense this way. Using sockets should be the way to go.
     *
     * 2. Of course is a full location reload a poor mans data update. It is always way more elegant to just update the data, that has changed (preferably just return JSON
     * and let the clients do some work.
     *
     *
     * @return Monoqi_Availability_IndexController
     */
    public function IndexAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $input    = $this->getRequest()->getPost();
            $reload   = false;
            $product  = Mage::getModel('catalog/product')->load($input['productId']);
            if ($product->getId()) {
                if ((bool) $input['status'] != Mage::helper('monoqi_availability')->isAvailable($product)) {
                    $reload = true;
                }
            }
            return $this->_prepareResponse($reload);
        }
    }

    /**
     * Prepare response
     *
     * @param   string    $output
     * @return  Monoqi_Availability_IndexController
     */
    protected function _prepareResponse($output)
    {
        $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody($output);
        return $this;
    }
}