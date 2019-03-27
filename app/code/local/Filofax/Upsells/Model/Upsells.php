<?php

class Filofax_Upsells_Model_Upsells extends Amasty_PromoBannersLite_Model_Banners 
{
    public function _construct()
    {
        $this->_init('filoupsell/upsells');
    }

    public function loadByRuleId($id)
    {
        return $this->load($id, 'rule_id');
    }

    public function getProducts($ruleId)
    {
        $ids = $this->getResource()->getProducts($ruleId);

        return $ids;
    }

    public function getRulesCollection()
    {
        $websiteId = Mage::app()->getWebsite()->getId();
        $storeId = Mage::app()->getStore()->getId();
        $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();

        $rulesCollection = Mage::getModel('salesrule/rule')->getCollection();
        /**
         *validate by store id and group id
         */
        $rulesCollection = $rulesCollection->addWebsiteGroupDateFilter($websiteId, $groupId);

        $rulesCollection->getSelect()->joinInner(
            array(
            'banners' => $rulesCollection->getTable('ambannerslite/banners')),
            'main_table.rule_id = banners.rule_id',
            '*'
        )->joinInner(
            array(
                'upsell' => $rulesCollection->getTable('filoupsell/upsells')),
            'main_table.rule_id = upsell.rule_id',
            '*'
        );

        if (Mage::helper('filoupsell')->isEnabled()) {
            /**
             * check stores filter
             * if rule don't have selected stores, they should be available too
             * current store matched, stores filter not initialized or all stores options selected (0 value)
             */
            /*$rulesCollection->getSelect()->where(
                "FIND_IN_SET ('{$storeId}', amstore_ids) or amstore_ids = '' or FIND_IN_SET (0, amstore_ids)"
            ); */
        }

        return $rulesCollection;
    }
}
