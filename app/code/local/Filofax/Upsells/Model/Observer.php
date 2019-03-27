<?php

class Filofax_Upsells_Model_Observer
{
    public function saveAfter($observer)
    {
        if (Mage::helper('filoupsell')->isEnabled()) {
            $this->_saveUpsellInfo($observer->getRule());
        }
    }

    protected function _saveUpsellInfo($rule)
    {
        $data         = $rule->getData();
        $upsellsModel = Mage::getModel('filoupsell/upsells');
        $upsellsModel = $upsellsModel->loadByRuleId($data['rule_id']);

        unset($data['upsell_id']);

        $upsellsModel->addData($data);
        try {
            $upsellsModel->save();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function addEnctypeToForm($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Promo_Quote_Edit_Form) {
            $form = $observer->getBlock()->getForm();
            $form->setData('enctype', 'multipart/form-data');
            $form->setUseContainer(true);
        }
    }
}
