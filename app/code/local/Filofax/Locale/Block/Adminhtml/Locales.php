<?php

class Filofax_Locale_Block_Adminhtml_Locales extends Mage_Adminhtml_Block_System_Config_Form_Field
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_addRowButtonHtml = array();
    protected $_removeRowButtonHtml = array();

   /**
    * Returns html part of the setting
    *
    * @param Varien_Data_Form_Element_Abstract $element
    * @return string
    */
   protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
   {
      return $this->getRowTableHtml($element); 
   }

    protected function getRowTableHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '<div id="locale_country_url_template" style="display:none">';
        $html .= $this->_getRowTemplateHtml($element);
        $html .= '</div>';

        $html .= '<ul id="locale_country_url_container">';
        if ($element->getValue('locales')) {
           foreach ($element->getValue('locales') as $i => $f) {
               if ($i) {
                   $html .= $this->_getRowTemplateHtml($element, $i);
               }
           }
        }
        $html .= '</ul>';
        $html .= $this->_getAddRowButtonHtml('locale_country_url_container',
           'locale_country_url_template', $this->__('Add Locale Title/Url'));

        return $html;
    }

   /**
    * Retrieve html template for setting
    *
    * @param int $rowIndex
    * @return string
    */
   protected function _getRowTemplateHtml(Varien_Data_Form_Element_Abstract $element, $rowIndex = 0)
   {
       $this->setElement($element);
       
       $html = '<li>';
       $html .= '<select style="width:120px;" name="'
           . $element->getName() . '[locales][]"' . 'id="' . $element->getId() . '"' . '>';

       $html .= '<optgroup label="locales">';
       foreach($this->_getLocalesList() as $locale) {
           $html .= '<option value="' . $locale['value'] . '" '
            . $this->_getSelected('locales/' . $rowIndex, $locale['value']) . '>' . $locale['label'] . '</option>';
       }
       $html .= '</optgroup></select>';

       $html .= '<input style="width:120px;" type="url" name="'
           . $element->getName() . '[urls][]" value="'
           . $this->_getValue('urls/' . $rowIndex) . '" ' . 'placeholder="Url"' . '/> ';
       $html .= $this->_getRemoveRowButtonHtml();
       $html .= '</div>';
       $html .= '</li>';

       return $html;
   }

   protected function _getDisabled()
   {
       return $this->getElement()->getDisabled() ? ' disabled' : '';
   }

   protected function _getValue($key)
   {
       return $this->getElement()->getData('value/' . $key);
   }

   protected function _getSelected($key, $value)
   {
       return $this->getElement()->getData('value/' . $key) == $value ? 'selected' : '';
   }

   protected function _getAddRowButtonHtml($container, $template, $title='Add')
   {
       if (!isset($this->_addRowButtonHtml[$container])) {
           $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
               ->setType('button')
               ->setClass('add ')
               ->setLabel($this->__($title))
               ->setOnClick("Element.insert($('" . $container . "'), {bottom: $('" . $template . "').innerHTML})")
               ->toHtml();
       }
       return $this->_addRowButtonHtml[$container];
   }

   protected function _getRemoveRowButtonHtml($selector = 'li', $title = '')
   {
       if (!$this->_removeRowButtonHtml) {
           $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
               ->setType('button')
               ->setClass('delete v-middle ')
               ->setLabel($this->__($title))
               ->setOnClick("Element.remove($(this).up('" . $selector . "'))")
               ->toHtml();
       }
       return $this->_removeRowButtonHtml;
   }

   protected function _getLocalesList() {
       return Mage::getModel('filolocale/adminhtml_system_config_source_language')->toOptionArray(false); 
   }
}
