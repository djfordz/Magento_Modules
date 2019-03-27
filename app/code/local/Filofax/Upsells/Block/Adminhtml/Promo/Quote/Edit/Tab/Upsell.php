<?php

class Filofax_Upsells_Block_Adminhtml_Promo_Quote_Edit_Tab_Upsell
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    public function canShowTab()
    {
        return Mage::helper('filoupsell')->isEnabled();
    }

    public function getTabLabel()
    {
        return $this->__('Yearly Upsell');
    }

    public function getTabTitle()
    {
        return $this->__('Yearly Upsell');
    }

    public function isHidden()
    {
        return false;
    }

    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();
        return $return;
    }

    protected function _prepareForm()
    {
        $parent = parent::_prepareForm();
        $model = Mage::getModel('filoupsell/upsells');

        $form = new Varien_Data_Form();
        $layout = $this->getLayout();

        $this->_initFieldSets($form, $model);

        

        $rule = Mage::registry('current_promo_quote_rule');
        if ($rule) {
            $ruleId = $rule->getRuleId();
            $model->loadByRuleId($ruleId);
        }

        $values = $model->getData();
        $form->setValues($values);

        $this->setForm($form);
        $this->_injectDependencies($layout, $model);

        return $parent;
    }

    protected function _initFieldSets($form, $model)
    {
        $this->_addYearlyUpsellsBanner($form, $model);
    }

    protected function _addYearlyUpsellsBanner($form, $model)
    {
        $fldSet = $form->addFieldset(
            'filoupsell_product_banner',
            array(
                'legend' => $this->__('Filofax Yearly Upsell Product Banner'),
            )
        );

        $type = $fldSet->addField('filoupsell_enable', 'select', array(
                'label' => $this->__('Make This Rule A Yearly Upsell Rule'),
                'title' => $this->__('Make This Rule A Yearly Upesll Rule'),
                'name' => 'filoupsell_enable',
                'required' => false,
                'options' => array(
                    '0' => $this->__('No'),
                    '1' => $this->__('Yes'),
                ),
            )
        );

        $this->setFiloupsellUpsellType($type);
    }

    protected function _injectDependencies($layout, $model)
    {
        $type = $this->getFiloupsellUpsellType();
        $this->setChild(
            'form_after',
            $layout
                ->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($type->getHtmlId(), $type->getName())
        );
    }
}
