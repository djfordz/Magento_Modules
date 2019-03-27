<?php

class Filofax_Upsells_Block_Upsell extends Amasty_PromoBannersLite_Block_Banner
{

    protected $_itemCollection;

    public function isUpsellBanner($rule)
    {
        $upsellRule = Mage::getModel('filoupsell/upsells')
            ->loadByRuleId($rule->getId());
        if ($rule->getId() == $upsellRule->getRuleId() && $upsellRule->getFiloupsellEnable()) {
            return true;
        }
        return false;
    }

    public function getUpsellData($field, Mage_SalesRule_Model_Rule $validRule = null)
    {
        if ($validRule === null) {
            $validRule = $this->_getValidRule();
        }

        $upsellRule = Mage::getModel('filoupsell/upsells')
            ->loadByRuleId($validRule->getId());
        
        if ($validRule->getId() == $upsellRule->getRuleId()) {
            return $upsellRule->getData('filoupsell_product' . $field);
        }
    }

    public function getRelatedItems()
    {
        $upsellModel = Mage::getModel('filoupsell/upsells');
    }

    public function getImage(Mage_SalesRule_Model_Rule $validRule = null)
    {
        $bannerImg = $this->getUpsellData('_banner_img', $validRule);

        return Mage::helper("filoupsell/image")->getLink($bannerImg);
    }

    public function getBannerData($field, Mage_SalesRule_Model_Rule $validRule = null)
    {
        return $this->getLayout()->getBlockSingleton('ambannerslite/banner')->getBannerData($field, $validRule);
    }

    public function getLink(Mage_SalesRule_Model_Rule $validRule = null)
    {
        $link = $this->getUpsellData('_banner_link', $validRule);

        if ($link) {
            return $link;
        } else {
            return '#';
        }
    }
}
