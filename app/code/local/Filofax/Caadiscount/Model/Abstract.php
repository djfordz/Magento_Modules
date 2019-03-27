<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 1/16/19
 * Time: 8:16 AM
 */

class Filofax_Caadiscount_Model_Abstract extends Filofax_Caadiscount_Model_Client
{

    /**
     * Coupon Code
     */
    const COUPON_CODE = 'AUTO_GENERATION_%s - CAA Membership Discount';

    /**
     * Application Type
     */
    const MEMBERSHIP = 'validation';

    /**
     * Application Type
     */
    const DISCOUNT = 'discount';


    /**
     * @param $membershipId
     */
    public function deleteCoupon($membershipId)
    {
        $model = Mage::getModel('salesrule/rule')
            ->getCollection()
            ->addFieldToFilter('name', array('eq' => sprintf(self::COUPON_CODE,
            $membershipId)))
            ->getFirstItem();

        $model->delete();
    }

    /**
     * @param $code
     * @return bool
     * @throws Mage_Core_Exception
     */
    protected function createSalesRule($code)
    {
        $couponCode = sprintf(self::COUPON_CODE, $code);

        $websiteId = Mage::app()->getWebsite()->getId();
        $customerGroups = Mage::getResourceModel('customer/group_collection');

        $groupIds = array();
        foreach($customerGroups as $group) {
            array_push($groupIds, $group->getId());
        }
        $data = array(
            'product_ids' => null,
            'name' => $couponCode ,
            'description' => null,
            'is_active' => 1,
            'website_ids' => array($websiteId),
            'customer_group_ids' => $groupIds,
            'coupon_type' => 2,
            'coupon_code' => $code,
            'uses_per_coupon' => 1,
            'uses_per_customer' => 1,
            'from_date' => null,
            'to_date' => null,
            'sort_order' => null,
            'is_rss' => 1,
            'rule' => array(
                'conditions' => array(
                    array(
                        'type' => 'salesrule/rule_condition_combine',
                        'aggregator' => 'all',
                        'value' => 1,
                        'new_child' => null
                    )
                )
            ),
            'simple_action' => 'by_percent',
            'discount_amount' => 10,
            'discount_qty' => 0,
            'discount_step' => null,
            'apply_to_shipping' => 0,
            'simple_free_shipping' => 0,
            'stop_rules_processing' => 0,
            'rule' => array(
                'actions' => array(
                    array('type' => 'salesrule/rule_condition_product_combine',
                        'aggregator' => 'all',
                        'value' => 1,
                        'new_child' => null),
                    array(
                        'type' => 'salesrule/rule_condition_product_combine',
                        'aggregator' => 'all',
                        'value' => 1,
                        'new_child' => null
                    )
                )
            ),
            'store_labels' => array('CAA Membership discount')
        );

        $model = Mage::getModel('salesrule/rule');
        $data = $this->_filterDates($data, array('from_date', 'to_date'));
        $validateResult = $model->validateData(new Varien_Object($data));

        if ($validateResult !== true) {
            foreach($validateResult as $errorMessage) {
                $this->_getSession()->addError($errorMessage);
            }
            return false;
        }

        if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent'
            && isset($data['discount_amount'])) {
            $data['discount_amount'] = min(100,$data['discount_amount']);
        }
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }
        if (isset($data['rule']['actions'])) {
            $data['actions'] = $data['rule']['actions'];
        }
        unset($data['rule']);

        $model->loadPost($data);
        $model->save();

        return true;
    }

    /**
     * Convert dates in array from localized to internal format
     *
     * @param   array $array
     * @param   array $dateFields
     * @return  array
     */
    protected function _filterDates($array, $dateFields)
    {
        if (empty($dateFields)) {
            return $array;
        }
        $filterInput = new Zend_Filter_LocalizedToNormalized(array(
            'date_format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));
        $filterInternal = new Zend_Filter_NormalizedToLocalized(array(
            'date_format' => Varien_Date::DATE_INTERNAL_FORMAT
        ));

        foreach ($dateFields as $dateField) {
            if (array_key_exists($dateField, $array) && !empty($dateField)) {
                $array[$dateField] = $filterInput->filter($array[$dateField]);
                $array[$dateField] = $filterInternal->filter($array[$dateField]);
            }
        }
        return $array;
    }

    /**
     * @return Filofax_Caadiscount_Helper_Data|Mage_Core_Helper_Abstract
     */
    protected function _helper()
    {
        return Mage::helper('filocaa');
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    /**
     * @return Mage_Checkout_Model_Cart|Mage_Core_Model_Abstract
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * @return Mage_Checkout_Model_Session|Mage_Core_Model_Abstract
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function getClient()
    {
        return Mage::getSingleton('filocaa/client');
    }
}
